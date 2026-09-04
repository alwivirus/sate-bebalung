<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Table extends Model
{
    protected $fillable = [
        'table_number',
        'status',
        'current_customer_name',
        'current_order_code',
        'last_scanned_at',
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime',
    ];

    /**
     * Mark a table as scanned / in-use by customer.
     */
    public static function markScanned(string $tableNumber, ?string $customerName = null)
    {
        $table = static::firstOrCreate(
            ['table_number' => str_pad($tableNumber, 2, '0', STR_PAD_LEFT)],
            ['status' => 'occupied']
        );

        $table->update([
            'status' => 'occupied',
            'last_scanned_at' => Carbon::now(),
            'current_customer_name' => $customerName ?: ($table->current_customer_name ?: 'Pelanggan (Scan HP)'),
        ]);

        return $table;
    }

    /**
     * Mark a table as having placed an active order.
     */
    public static function markOrdering(string $tableNumber, string $customerName, string $orderCode)
    {
        $table = static::firstOrCreate(
            ['table_number' => str_pad($tableNumber, 2, '0', STR_PAD_LEFT)],
            ['status' => 'occupied']
        );

        $table->update([
            'status' => 'occupied',
            'current_customer_name' => $customerName,
            'current_order_code' => $orderCode,
            'last_scanned_at' => Carbon::now(),
        ]);

        return $table;
    }

    /**
     * Mark table as available again (Reset/Kosongkan Meja).
     */
    public static function markAvailable(string $tableNumber)
    {
        $table = static::where('table_number', str_pad($tableNumber, 2, '0', STR_PAD_LEFT))->first();
        if ($table) {
            $table->update([
                'status' => 'available',
                'current_customer_name' => null,
                'current_order_code' => null,
            ]);
        }
        return $table;
    }
}
