<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SslcommerzAccount;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed demo user (avoid factory to prevent Faker dependency in production image)
        if (!User::where('email', 'test@example.com')->exists()) {
            User::create([
                'email' => 'test@example.com',
                'otp' => '000000',
            ]);
        }

        // Seed SSLCommerz sandbox credentials if table empty
        if (SslcommerzAccount::count() === 0) {
            SslcommerzAccount::create([
                'store_id' => env('SSLC_STORE_ID', 'testbox'),
                'store_password' => env('SSLC_STORE_PASSWORD', 'qwerty'),
                'currency' => 'BDT',
                'init_url' => env('SSLC_INIT_URL', 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'),
                'success_url' => env('SSLC_SUCCESS_URL', 'https://laravel-ecomapi.onrender.com/PaymentSuccess'),
                'fail_url' => env('SSLC_FAIL_URL', 'https://laravel-ecomapi.onrender.com/PaymentFail'),
                'cancel_url' => env('SSLC_CANCEL_URL', 'https://laravel-ecomapi.onrender.com/PaymentCancel'),
                'ipn_url' => env('SSLC_IPN_URL', 'https://laravel-ecomapi.onrender.com/PaymentIPN'),
            ]);
        }
    }
}
