<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $tableNum = str_pad($i, 2, '0', STR_PAD_LEFT);
            Table::updateOrCreate(
                ['table_number' => $tableNum],
                [
                    'status' => 'available',
                    'current_customer_name' => null,
                    'current_order_code' => null,
                ]
            );
        }
    }
}
