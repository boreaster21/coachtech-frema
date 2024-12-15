<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            ['method_name' => 'コンビニ払い'],
            ['method_name' => 'クレジットカード'],
            ['method_name' => '銀行振込'],
            ['method_name' => 'Stripe'],
        ];

        foreach ($paymentMethods as $method) {
            Payment::updateOrCreate(['method_name' => $method['method_name']], $method);
        }
    }
}
