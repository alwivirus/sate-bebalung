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

    /**
     * Secret key for cryptographic table verification.
     */
    protected static function getSecretSalt(): string
    {
        $key = config('app.key', 'base64:UY2OVc2xqJBlQDrWGCMXH52rgKkPNwf/reeLBpo8IX8=');
        return !empty($key) ? (string)$key : 'bebalung_depot_secure_salt_2026';
    }

    /**
     * Generate 8-character cryptographic hash token for a table.
     */
    public static function getSecureToken(string $tableNumber): string
    {
        $cleanNum = str_pad((string)(int)$tableNumber, 2, '0', STR_PAD_LEFT);
        return substr(hash_hmac('sha256', 'bebalung_table_token_' . $cleanNum, static::getSecretSalt()), 0, 8);
    }

    /**
     * Generate secure table code (e.g. M01-e94a8b7c).
     */
    public static function getSecureCode(string $tableNumber): string
    {
        $cleanNum = str_pad((string)(int)$tableNumber, 2, '0', STR_PAD_LEFT);
        $token = static::getSecureToken($cleanNum);
        return "M{$cleanNum}-{$token}";
    }

    /**
     * Generate full official scan URL for a table.
     */
    public static function getSecureScanUrl(string $tableNumber): string
    {
        return url('/?meja=' . static::getSecureCode($tableNumber));
    }

    /**
     * Validate incoming table request and resolve to actual table number.
     * Rejects direct/unauthenticated guesses like ?meja=01 or ?meja=2.
     * Returns string '01' if valid, or null if fake/invalid.
     */
    public static function validateAndResolveTable(?string $input, ?string $tokenParam = null): ?string
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        // Pattern 1: M01-e94a8b7c or M1-e94a8b7c or M01_e94a8b7c
        if (preg_match('/^M?0*([1-9][0-9]*)[-_]([a-f0-9]{8})$/i', $input, $matches)) {
            $tableNum = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $providedToken = strtolower($matches[2]);
            $expectedToken = static::getSecureToken($tableNum);

            if (hash_equals($expectedToken, $providedToken)) {
                return $tableNum;
            }
        }

        // Pattern 2: Separate table number and token query parameter (?meja=01&token=e94a8b7c)
        if (!empty($tokenParam) && is_numeric(preg_replace('/[^0-9]/', '', $input))) {
            $tableNum = str_pad((string)(int)preg_replace('/[^0-9]/', '', $input), 2, '0', STR_PAD_LEFT);
            $expectedToken = static::getSecureToken($tableNum);

            if (hash_equals($expectedToken, strtolower(trim($tokenParam)))) {
                return $tableNum;
            }
        }

        // Unauthenticated plain numbers like "?meja=01" or "?meja=2" are strictly rejected for security
        return null;
    }
}
