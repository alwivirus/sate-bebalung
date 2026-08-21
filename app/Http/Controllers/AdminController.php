<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Dashboard Kasir / Dapur (Monitoring Pesanan Realtime).
     */
    public function dashboard(Request $request)
    {
        $statusFilter = $request->query('status');

        $query = Order::with('items')->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('order_status', $statusFilter);
        }

        $orders = $query->paginate(15);

        $stats = [
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_today' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_amount'),
            'pending_count' => Order::where('order_status', 'pending')->count(),
            'processing_count' => Order::where('order_status', 'processing')->count(),
            'total_menus' => Menu::count(),
        ];

        return view('admin.dashboard', compact('orders', 'stats', 'statusFilter'));
    }

    /**
     * Halaman Khusus Kasir: Scan Barcode & POS.
     */
    public function scanIndex(Request $request)
    {
        $code = $request->query('code');
        $selectedOrder = null;

        if ($code) {
            $selectedOrder = Order::with('items')
                ->where('order_code', $code)
                ->orWhere('order_code', 'LIKE', "%{$code}%")
                ->latest()
                ->first();
        }

        // Ambil pesanan hari ini untuk antrean kasir
        $recentOrders = Order::with('items')
            ->whereDate('created_at', today())
            ->latest()
            ->take(15)
            ->get();

        return view('admin.scan', compact('selectedOrder', 'recentOrders', 'code'));
    }

    /**
     * AJAX Search pesanan berdasarkan barcode / kode order / nomor meja.
     */
    public function searchOrder(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (empty($query)) {
            return response()->json(['success' => false, 'message' => 'Kode pencarian tidak boleh kosong.']);
        }

        $order = Order::with('items')
            ->where('order_code', $query)
            ->orWhere('order_code', 'LIKE', "%{$query}%")
            ->orWhere('table_number', $query)
            ->latest()
            ->first();

        if ($order) {
            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'order_status' => $order->order_status,
                    'total_amount' => $order->total_amount,
                    'formatted_total' => $order->formatted_total,
                    'notes' => $order->notes,
                    'created_at_formatted' => $order->created_at->format('d M Y, H:i:s'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'subtotal' => $item->subtotal,
                            'formatted_subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                            'notes' => $item->notes,
                        ];
                    }),
                ],
            ]);
        }

        return response()->json(['success' => false, 'message' => "Pesanan dengan kode '{$query}' tidak ditemukan."]);
    }

    /**
     * Quick Pay: Bayar Lunas langsung dari scanner kasir.
     */
    public function quickPay(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'paid',
            'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pesanan {$order->order_code} (Meja #{$order->table_number}) berhasil DIBAYAR LUNAS!",
                'order' => $order,
            ]);
        }

        return redirect()->route('admin.scan', ['code' => $order->order_code])
            ->with('success', "Pesanan {$order->order_code} (Meja #{$order->table_number}) berhasil DIBAYAR LUNAS!");
    }

    /**
     * Cetak Struk Kasir (Thermal Receipt 58mm / 80mm).
     */
    public function receipt($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        return view('admin.receipt', compact('order'));
    }

    /**
     * Update status pesanan oleh Kasir / Dapur.
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'nullable|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
        ]);

        $order = Order::findOrFail($id);

        if ($request->has('order_status')) {
            $order->order_status = $request->order_status;
        }

        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return redirect()->back()->with('success', "Status pesanan {$order->order_code} berhasil diperbarui!");
    }

    /**
     * Halaman Manajemen Menu (CRUD).
     */
    public function menusIndex()
    {
        $categories = Category::with('menus')->orderBy('sort_order', 'asc')->get();
        $allCategories = Category::orderBy('name', 'asc')->get();

        return view('admin.menus.index', compact('categories', 'allCategories'));
    }

    /**
     * Simpan menu baru.
     */
    public function storeMenu(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $imageName);
        }

        Menu::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
            'is_available' => true,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Update menu yang sudah ada.
     */
    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        $imageName = $menu->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $imageName);
        }

        $menu->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
            'is_available' => $request->has('is_available') ? true : false,
        ]);

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$menu->name}' berhasil diperbarui!");
    }

    /**
     * Hapus menu.
     */
    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $name = $menu->name;
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$name}' berhasil dihapus!");
    }

    /**
     * Toggle ketersediaan menu (stok ada / habis).
     */
    public function toggleMenuAvailability($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->is_available = !$menu->is_available;
        $menu->save();

        $status = $menu->is_available ? 'Tersedia' : 'Habis';
        return redirect()->back()->with('success', "Status menu '{$menu->name}' diubah menjadi {$status}.");
    }
}
