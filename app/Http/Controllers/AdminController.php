<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Dashboard Kasir / Dapur (Monitoring Pesanan Realtime & Live Status Meja).
     */
    public function dashboard(Request $request)
    {
        // Auto-heal database schema & categories
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'order_status')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders ADD COLUMN order_status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending' AFTER payment_status");
            }
            if (Category::where('slug', 'makanan')->doesntExist() || Menu::count() < 15) {
                (new \Database\Seeders\CategorySeeder())->run();
                (new \Database\Seeders\MenuSeeder())->run();
            }
        } catch (\Throwable $e) {}

        $statusFilter = $request->query('status');
        $paymentFilter = $request->query('payment');

        $query = Order::with('items')->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('order_status', $statusFilter);
        }

        if ($paymentFilter && in_array($paymentFilter, ['unpaid', 'paid', 'cash', 'online'])) {
            if ($paymentFilter === 'cash') {
                $query->where('payment_method', 'kasir');
            } elseif ($paymentFilter === 'online') {
                $query->where('payment_method', 'online');
            } else {
                $query->where('payment_status', $paymentFilter);
            }
        }

        $orders = $query->paginate(15);

        // Daily, Weekly, Monthly Analytics
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $stats = [
            'total_orders_today' => Order::whereDate('created_at', $today)->count(),
            'revenue_today' => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount'),
            'revenue_week' => Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('payment_status', 'paid')->sum('total_amount'),
            'revenue_month' => Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('payment_status', 'paid')->sum('total_amount'),
            
            'cash_revenue_today' => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->where('payment_method', 'kasir')->sum('total_amount'),
            'qris_revenue_today' => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->where('payment_method', 'online')->sum('total_amount'),
            
            'pending_count' => Order::where('order_status', 'pending')->count(),
            'processing_count' => Order::where('order_status', 'processing')->count(),
            'unpaid_count' => Order::where('payment_status', 'unpaid')->count(),
            'total_menus' => Menu::count(),
        ];

        // Live Monitoring Status Meja Terhubung (Terpakai vs Kosong)
        $liveTables = Table::orderBy('table_number', 'asc')->get();
        $occupiedTablesCount = Table::where('status', 'occupied')->count();

        return view('admin.dashboard', compact('orders', 'stats', 'statusFilter', 'paymentFilter', 'liveTables', 'occupiedTablesCount'));
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

    /**
     * Konfirmasi Penerimaan Pembayaran Tunai di Kasir (Cash POS).
     */
    public function confirmCashPay(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'paid',
            'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
        ]);

        return redirect()->back()->with('success', "Pembayaran Kasir Pesanan {$order->order_code} (Meja #{$order->table_number} a.n {$order->customer_name}) Rp " . number_format($order->total_amount, 0, ',', '.') . " berhasil DITERIMA LUNAS & tercatat di omset!");
    }

    /**
     * Halaman Menu Catatan Aktivitas & Riwayat Uang Masuk (Cash & QRIS).
     */
    public function activityLogs(Request $request)
    {
        $period = $request->query('period', 'all');
        $method = $request->query('method');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $search = $request->query('search');

        $query = Order::with('items')->where('payment_status', 'paid')->latest();

        // Filter periode
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        }

        // Filter metode pembayaran
        if ($method && in_array($method, ['kasir', 'online'])) {
            $query->where('payment_method', $method);
        }

        // Search kode / nama / meja
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('table_number', 'LIKE', "%{$search}%");
            });
        }

        // Hitung total ringkasan dari query yang difilter
        $filteredQuery = clone $query;
        $totalIncome = (clone $filteredQuery)->sum('total_amount');
        $cashIncome = (clone $filteredQuery)->where('payment_method', 'kasir')->sum('total_amount');
        $qrisIncome = (clone $filteredQuery)->where('payment_method', 'online')->sum('total_amount');
        $totalCount = (clone $filteredQuery)->count();

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.activity_logs', compact(
            'logs',
            'totalIncome',
            'cashIncome',
            'qrisIncome',
            'totalCount',
            'period',
            'method',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Halaman Kelola & Cetak QR Code Meja (Meja 1, Meja 2, dst).
     */
    public function tablesIndex(Request $request)
    {
        $tableCount = (int) $request->query('count', 20);
        if ($tableCount < 1) $tableCount = 1;
        if ($tableCount > 50) $tableCount = 50;

        $baseUrl = url('/');
        $tables = [];

        for ($i = 1; $i <= $tableCount; $i++) {
            $tableNum = str_pad($i, 2, '0', STR_PAD_LEFT);
            $scanUrl = $baseUrl . '/?meja=' . $tableNum;
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($scanUrl) . '&margin=0';

            $dbTable = Table::where('table_number', $tableNum)->first();

            $tables[] = [
                'number' => $tableNum,
                'scan_url' => $scanUrl,
                'qr_image' => $qrApiUrl,
                'status' => $dbTable ? $dbTable->status : 'available',
                'customer_name' => $dbTable ? $dbTable->current_customer_name : null,
                'order_code' => $dbTable ? $dbTable->current_order_code : null,
                'last_scanned_at' => $dbTable ? $dbTable->last_scanned_at : null,
                'active_orders_count' => Order::whereIn('table_number', [$tableNum, (string)(int)$tableNum])->whereIn('order_status', ['pending', 'processing'])->count(),
            ];
        }

        $occupiedCount = Table::where('status', 'occupied')->count();

        return view('admin.tables', compact('tables', 'tableCount', 'baseUrl', 'occupiedCount'));
    }

    /**
     * Kosongkan / Reset Status Meja (Setelah Pelanggan Selesai Makan).
     */
    public function releaseTable(Request $request, $table_number)
    {
        Table::markAvailable($table_number);
        Table::markAvailable((string)(int)$table_number);
        return redirect()->back()->with('success', "Meja #{$table_number} berhasil dikosongkan & siap untuk pelanggan berikutnya.");
    }

    /**
     * Halaman Pengaturan QRIS Pembayaran Toko.
     */
    public function qrisIndex()
    {
        $qrisImage = Setting::get('qris_image');
        $merchantName = Setting::get('qris_merchant_name', 'SATE KAMBING BE BA LUNG');
        $nmid = Setting::get('qris_nmid', 'ID1025428876474');

        return view('admin.settings.qris', compact('qrisImage', 'merchantName', 'nmid'));
    }

    /**
     * Update / Ganti Gambar QRIS Toko.
     */
    public function updateQris(Request $request)
    {
        $request->validate([
            'merchant_name' => 'nullable|string|max:255',
            'nmid' => 'nullable|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
        ]);

        if ($request->has('merchant_name')) {
            Setting::set('qris_merchant_name', $request->input('merchant_name'));
        }

        if ($request->has('nmid')) {
            Setting::set('qris_nmid', $request->input('nmid'));
        }

        if ($request->hasFile('qris_image')) {
            $file = $request->file('qris_image');
            $fileName = 'qris_merchant_' . time() . '.' . $file->getClientOriginalExtension();
            
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $file->move($targetDir, $fileName);
            Setting::set('qris_image', 'uploads/settings/' . $fileName);
        }

        return redirect()->route('admin.settings.qris')->with('success', 'Gambar dan Pengaturan QRIS berhasil diperbarui!');
    }

    /**
     * Halaman Edit Profil Akun Admin / Kasir.
     */
    public function profileIndex()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Simpan Perubahan Profil & Password Akun Admin / Kasir.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:4|confirmed',
        ], [
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.min' => 'Password minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $userData = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }

        $user->update($userData);

        return redirect()->route('admin.profile')->with('success', 'Profil dan kredensial akun berhasil diperbarui! Silakan gunakan data baru untuk login berikutnya.');
    }
}
