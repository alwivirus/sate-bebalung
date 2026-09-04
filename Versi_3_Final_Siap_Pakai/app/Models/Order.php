<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'table_number',
        'payment_method',
        'payment_status',
        'order_status',
        'total_amount',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public static function generateOrderCode(): string
    {
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 3));
        $num = rand(1000, 9999);
        return "ORD-{$num}-{$random}";
    }
}
