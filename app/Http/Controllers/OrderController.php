<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Tampilan utama Menu (Scan Meja / Table QR Menu).
     */
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja', '01');
        $customerName = $request->query('nama', 'Pelanggan');

        $categories = Category::with(['menus' => function ($query) {
            $query->where('is_available', true)->orderBy('sort_order', 'asc');
        }])->orderBy('sort_order', 'asc')->get();

        return view('customer.menu', compact('categories', 'tableNumber', 'customerName'));
    }

    /**
     * Halaman Ringkasan Pesanan & Pilih Pembayaran (Screenshot 3, Screen 1).
     */
    public function checkout(Request $request)
    {
        $tableNumber = $request->input('table_number', $request->query('meja', session('table_number', '01')));
        $customerName = $request->input('customer_name', $request->query('nama', session('customer_name', 'Pelanggan')));

        $rawCart = $request->input('cart');
        $cartData = [];

        if ($rawCart) {
            $parsed = is_array($rawCart) ? $rawCart : json_decode($rawCart, true);
            if (is_array($parsed) && !empty($parsed)) {
                $cartData = $parsed;
                session(['cart' => $cartData, 'table_number' => $tableNumber, 'customer_name' => $customerName]);
            }
        }

        if (empty($cartData)) {
            $cartData = session('cart', []);
        }

        $items = [];
        $totalAmount = 0;

        if (is_array($cartData)) {
            foreach ($cartData as $menuId => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $menu = Menu::find($menuId);
                    if ($menu) {
                        $subtotal = $menu->price * $qty;
                        $totalAmount += $subtotal;
                        $items[] = [
                            'menu' => $menu,
                            'quantity' => $qty,
                            'subtotal' => $subtotal,
                        ];
                    }
                }
            }
        }

        return view('customer.checkout', compact('items', 'totalAmount', 'tableNumber', 'customerName', 'cartData'));
    }

    /**
     * Konfirmasi dan Simpan Pesanan ke Database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'payment_method' => 'required|in:online,kasir',
            'cart_items' => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $orderCode = Order::generateOrderCode();

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $request->input('customer_name') ?: ('Pelanggan Meja ' . $request->input('table_number')),
                'table_number' => $request->input('table_number', '01'),
                'payment_method' => $request->input('payment_method', 'online'),
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'total_amount' => 0,
                'notes' => $request->input('notes'),
            ]);

            $totalAmount = 0;

            foreach ($request->input('cart_items') as $item) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    $qty = max(1, (int)$item['quantity']);
                    $subtotal = $menu->price * $qty;
                    $totalAmount += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'price' => $menu->price,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            $order->update(['total_amount' => $totalAmount]);

            // Bersihkan cart di session & simpan kode pesanan terakhir
            session()->forget('cart');
            session(['last_order_code' => $order->order_code, 'last_table_number' => $order->table_number]);

            if ($order->payment_method === 'online') {
                return redirect()->route('order.payment', ['order_code' => $order->order_code]);
            } else {
                return redirect()->route('order.success', ['order_code' => $order->order_code]);
            }
        });
    }

    /**
     * Redirect ke halaman pembayaran terakhir jika akses /payment atau /pembayaran langsung.
     */
    public function latestPayment(Request $request)
    {
        $orderCode = session('last_order_code') ?? $request->query('order_code');

        if (!$orderCode) {
            $latestOrder = Order::latest()->first();
            if ($latestOrder) {
                $orderCode = $latestOrder->order_code;
            }
        }

        if ($orderCode) {
            return redirect()->route('order.payment', ['order_code' => $orderCode]);
        }

        return redirect()->route('customer.menu')->with('error', 'Belum ada transaksi aktif untuk pembayaran.');
    }

    /**
     * Redirect ke halaman sukses terakhir jika akses /success langsung.
     */
    public function latestSuccess(Request $request)
    {
        $orderCode = session('last_order_code') ?? $request->query('order_code');

        if (!$orderCode) {
            $latestOrder = Order::latest()->first();
            if ($latestOrder) {
                $orderCode = $latestOrder->order_code;
            }
        }

        if ($orderCode) {
            return redirect()->route('order.success', ['order_code' => $orderCode]);
        }

        return redirect()->route('customer.menu')->with('error', 'Belum ada pesanan aktif.');
    }

    /**
     * Halaman Menunggu Pembayaran QRIS (Screenshot 3, Screen 2).
     */
    public function payment($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        return view('customer.payment', compact('order'));
    }

    /**
     * Selesai Bayar (Konfirmasi pembayaran online/QRIS).
     */
    public function confirmPayment($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);

        return redirect()->route('order.success', ['order_code' => $order->order_code])
            ->with('success', 'Pembayaran QRIS berhasil dikonfirmasi!');
    }

    /**
     * Halaman Pesanan Siap & Barcode Kasir (Screenshot 3, Screen 3).
     */
    public function success($order_code)
    {
        $order = Order::with('items.menu')->where('order_code', $order_code)->firstOrFail();

        return view('customer.success', compact('order'));
    }

    /**
     * Cek status pesanan via AJAX (untuk auto refresh).
     */
    public function status($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        return response()->json([
            'order_code' => $order->order_code,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
        ]);
    }
}
