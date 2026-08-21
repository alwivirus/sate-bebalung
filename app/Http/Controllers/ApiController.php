<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * GET /api/categories
     */
    public function getCategories()
    {
        $categories = Category::with(['menus' => function ($query) {
            $query->where('is_available', true);
        }])->orderBy('sort_order', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * GET /api/menus
     */
    public function getMenus(Request $request)
    {
        $query = Menu::with('category')->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->orderBy('sort_order', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $menus,
        ]);
    }

    /**
     * POST /api/orders
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'customer_name' => 'nullable|string',
            'payment_method' => 'required|in:online,kasir',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $orderCode = Order::generateOrderCode();

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $request->input('customer_name', 'Pelanggan Meja ' . $request->input('table_number')),
                'table_number' => $request->input('table_number'),
                'payment_method' => $request->input('payment_method'),
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'total_amount' => 0,
                'notes' => $request->input('notes'),
            ]);

            $totalAmount = 0;

            foreach ($request->input('items') as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $qty = (int)$item['quantity'];
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

            $order->update(['total_amount' => $totalAmount]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $order->load('items'),
            ], 201);
        });
    }

    /**
     * GET /api/orders/{order_code}
     */
    public function getOrder($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * POST /api/orders/{order_code}/pay
     */
    public function payOrder($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil dikonfirmasi.',
            'data' => $order,
        ]);
    }
}
