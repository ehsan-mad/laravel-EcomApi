<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CockroachMigrate extends Command
{
    /**
     * Signature now supports an optional --bootstrap flag to insert sample data only when tables are empty.
     */
    protected $signature = 'cockroach:migrate {--bootstrap : Insert sample seed data if tables are empty}';
    protected $description = 'Ensure CockroachDB tables exist (idempotent) and optionally bootstrap sample data';

    public function handle()
    {
        $bootstrap = (bool)$this->option('bootstrap');
        $this->info('Ensuring tables for CockroachDB (idempotent)...');

        try {
            // Use IF NOT EXISTS for idempotency to silence duplicate errors on redeploys.
            $statements = [
                'users' => 'CREATE TABLE IF NOT EXISTS users (\n                    id SERIAL PRIMARY KEY,\n                    email VARCHAR(255) NOT NULL,\n                    otp VARCHAR(255) NOT NULL,\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'customer_profiles' => 'CREATE TABLE IF NOT EXISTS customer_profiles (\n                    id SERIAL PRIMARY KEY,\n                    cus_name VARCHAR(255),\n                    cus_state VARCHAR(255),\n                    cus_city VARCHAR(255),\n                    cus_postcode VARCHAR(255),\n                    cus_country VARCHAR(255),\n                    cus_phone VARCHAR(255),\n                    cus_address TEXT,\n                    ship_name VARCHAR(255),\n                    ship_state VARCHAR(255),\n                    ship_address TEXT,\n                    ship_city VARCHAR(255),\n                    ship_postcode VARCHAR(255),\n                    ship_country VARCHAR(255),\n                    ship_phone VARCHAR(255),\n                    user_id BIGINT REFERENCES users(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'categories' => 'CREATE TABLE IF NOT EXISTS categories (\n                    id SERIAL PRIMARY KEY,\n                    category_name VARCHAR(255) NOT NULL,\n                    category_img VARCHAR(255),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'brands' => 'CREATE TABLE IF NOT EXISTS brands (\n                    id SERIAL PRIMARY KEY,\n                    brand_name VARCHAR(255) NOT NULL,\n                    brand_img VARCHAR(255),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'products' => 'CREATE TABLE IF NOT EXISTS products (\n                    id SERIAL PRIMARY KEY,\n                    name VARCHAR(255) NOT NULL,\n                    short_des TEXT,\n                    price DECIMAL(10,2) NOT NULL,\n                    discount DECIMAL(5,2) DEFAULT 0,\n                    discount_price DECIMAL(10,2),\n                    image VARCHAR(255),\n                    star INTEGER DEFAULT 0,\n                    stock INTEGER DEFAULT 0,\n                    remark VARCHAR(255),\n                    category_id BIGINT REFERENCES categories(id),\n                    brand_id BIGINT REFERENCES brands(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'product_details' => 'CREATE TABLE IF NOT EXISTS product_details (\n                    id SERIAL PRIMARY KEY,\n                    img1 VARCHAR(255),\n                    img2 VARCHAR(255),\n                    img3 VARCHAR(255),\n                    img4 VARCHAR(255),\n                    des TEXT,\n                    color VARCHAR(255),\n                    size VARCHAR(255),\n                    product_id BIGINT REFERENCES products(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'product_sliders' => 'CREATE TABLE IF NOT EXISTS product_sliders (\n                    id SERIAL PRIMARY KEY,\n                    title VARCHAR(255),\n                    short_des TEXT,\n                    price DECIMAL(10,2),\n                    img VARCHAR(255),\n                    product_id BIGINT REFERENCES products(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'product_reviews' => 'CREATE TABLE IF NOT EXISTS product_reviews (\n                    id SERIAL PRIMARY KEY,\n                    description TEXT,\n                    rating INTEGER,\n                    product_id BIGINT REFERENCES products(id),\n                    customer_id BIGINT REFERENCES customer_profiles(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'product_wishes' => 'CREATE TABLE IF NOT EXISTS product_wishes (\n                    id SERIAL PRIMARY KEY,\n                    product_id BIGINT REFERENCES products(id),\n                    user_id BIGINT REFERENCES users(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'product_carts' => 'CREATE TABLE IF NOT EXISTS product_carts (\n                    id SERIAL PRIMARY KEY,\n                    product_id BIGINT REFERENCES products(id),\n                    user_id BIGINT REFERENCES users(id),\n                    color VARCHAR(255),\n                    size VARCHAR(255),\n                    qty INTEGER,\n                    price DECIMAL(10,2),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'sslcommerz_accounts' => 'CREATE TABLE IF NOT EXISTS sslcommerz_accounts (\n                    id SERIAL PRIMARY KEY,\n                    store_id VARCHAR(255),\n                    store_password VARCHAR(255),\n                    currency VARCHAR(255),\n                    success_url VARCHAR(255),\n                    fail_url VARCHAR(255),\n                    cancel_url VARCHAR(255),\n                    ipn_url VARCHAR(255),\n                    init_url VARCHAR(255),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'invoices' => 'CREATE TABLE IF NOT EXISTS invoices (\n                    id SERIAL PRIMARY KEY,\n                    total DECIMAL(10,2),\n                    vat DECIMAL(10,2),\n                    payable DECIMAL(10,2),\n                    cus_details TEXT,\n                    ship_details TEXT,\n                    tran_id VARCHAR(255),\n                    val_id VARCHAR(255) DEFAULT 0,\n                    delivery_status VARCHAR(255),\n                    payment_status VARCHAR(255),\n                    user_id BIGINT REFERENCES users(id),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'invoice_products' => 'CREATE TABLE IF NOT EXISTS invoice_products (\n                    id SERIAL PRIMARY KEY,\n                    invoice_id BIGINT REFERENCES invoices(id),\n                    product_id BIGINT REFERENCES products(id),\n                    user_id BIGINT REFERENCES users(id),\n                    qty INTEGER,\n                    sale_price DECIMAL(10,2),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
                'policies' => 'CREATE TABLE IF NOT EXISTS policies (\n                    id SERIAL PRIMARY KEY,\n                    title VARCHAR(255),\n                    description TEXT,\n                    type VARCHAR(255),\n                    status VARCHAR(255),\n                    created_at TIMESTAMP DEFAULT NOW(),\n                    updated_at TIMESTAMP DEFAULT NOW()\n                )',
            ];

            foreach ($statements as $name => $sql) {
                DB::statement($sql);
                $this->info("✓ {$name}");
            }

            $this->info('🎉 Tables ensured successfully.');

            if ($bootstrap) {
                $this->bootstrapSampleData();
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Insert lightweight sample data only if core tables appear empty.
     * This will NOT delete existing data. Uses explicit IDs for predictability
     * but skips any insert that would conflict.
     */
    protected function bootstrapSampleData(): void
    {
        $this->info('📋 Bootstrapping sample data (only if empty)...');

        // If users table already has rows, assume already bootstrapped.
        $userCount = DB::table('users')->count();
        if ($userCount > 0) {
            $this->info("Skipped sample inserts (users table already has {$userCount} rows).");
            return;
        }

        try {
            DB::beginTransaction();

            // Users
            DB::table('users')->insert([
                ['id' => 1, 'email' => 'test@example.com', 'otp' => '0'],
                ['id' => 2, 'email' => 'lal@g.com', 'otp' => '0'],
            ]);

            // Categories
            DB::table('categories')->insert([
                ['id' => 1, 'category_name' => 'Electronics', 'category_img' => 'electronics.jpg'],
                ['id' => 2, 'category_name' => 'Clothing', 'category_img' => 'clothing.jpg'],
                ['id' => 3, 'category_name' => 'Books', 'category_img' => 'books.jpg'],
            ]);

            // Brands
            DB::table('brands')->insert([
                ['id' => 1, 'brand_name' => 'Samsung', 'brand_img' => 'samsung.jpg'],
                ['id' => 2, 'brand_name' => 'Apple', 'brand_img' => 'apple.jpg'],
                ['id' => 3, 'brand_name' => 'Nike', 'brand_img' => 'nike.jpg'],
            ]);

            // Products
            DB::table('products')->insert([
                ['id' => 1, 'name' => 'iPhone 15', 'short_des' => 'Latest iPhone', 'price' => 999.99, 'discount' => 10, 'discount_price' => 899.99, 'image' => 'iphone15.jpg', 'star' => 5, 'stock' => 50, 'remark' => 'hot', 'category_id' => 1, 'brand_id' => 2],
                ['id' => 2, 'name' => 'Samsung Galaxy S24', 'short_des' => 'Latest Samsung phone', 'price' => 899.99, 'discount' => 5, 'discount_price' => 854.99, 'image' => 'galaxy-s24.jpg', 'star' => 4, 'stock' => 30, 'remark' => 'new', 'category_id' => 1, 'brand_id' => 1],
                ['id' => 3, 'name' => 'iPad Pro', 'short_des' => 'Latest iPad', 'price' => 1099.99, 'discount' => 8, 'discount_price' => 1011.99, 'image' => 'ipad-pro.jpg', 'star' => 5, 'stock' => 25, 'remark' => 'hot', 'category_id' => 1, 'brand_id' => 2],
                ['id' => 4, 'name' => 'Nike Air Max', 'short_des' => 'Comfortable sneakers', 'price' => 150.00, 'discount' => 15, 'discount_price' => 127.50, 'image' => 'nike-air-max.jpg', 'star' => 4, 'stock' => 100, 'remark' => 'popular', 'category_id' => 2, 'brand_id' => 3],
                ['id' => 5, 'name' => 'Programming Book', 'short_des' => 'Learn to code', 'price' => 29.99, 'discount' => 20, 'discount_price' => 23.99, 'image' => 'code-book.jpg', 'star' => 4, 'stock' => 200, 'remark' => 'education', 'category_id' => 3, 'brand_id' => 1],
            ]);

            // SSLCommerz account placeholder
            DB::table('sslcommerz_accounts')->insert([
                ['id' => 1, 'store_id' => 'placeholder_store', 'store_password' => 'placeholder_pass', 'currency' => 'BDT', 'success_url' => 'http://localhost/PaymentSuccess', 'fail_url' => 'http://localhost/PaymentFail', 'cancel_url' => 'http://localhost/PaymentCancel', 'ipn_url' => 'http://localhost/PaymentIPN', 'init_url' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'],
            ]);

            DB::commit();

            $this->info('🎉 Sample data inserted. Summary:');
            $this->line('  Users: 2');
            $this->line('  Categories: 3');
            $this->line('  Brands: 3');
            $this->line('  Products: 5');
        } catch (\Throwable $t) {
            DB::rollBack();
            $this->error('Sample bootstrap failed: ' . $t->getMessage());
        }
    }
}
