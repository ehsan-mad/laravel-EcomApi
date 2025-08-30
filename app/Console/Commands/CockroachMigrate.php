<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CockroachMigrate extends Command
{
    protected $signature = 'cockroach:migrate';
    protected $description = 'Run migrations for CockroachDB without transactions';

    public function handle()
    {
        $this->info('Creating tables for CockroachDB (idempotent)...');

        try {
            // Use IF NOT EXISTS for idempotency to silence duplicate errors on redeploys
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
                $this->info("✓ Ensured {$name} table");
            }

            $this->info('🎉 All tables ensured successfully for CockroachDB!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
