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
        $this->info('Creating tables for CockroachDB...');

        try {
            // Create users table
            if (!Schema::hasTable('users')) {
                DB::statement('
                    CREATE TABLE users (
                        id SERIAL PRIMARY KEY,
                        email VARCHAR(255) NOT NULL,
                        otp VARCHAR(255) NOT NULL,
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created users table');
            }

            // Create customer_profiles table
            if (!Schema::hasTable('customer_profiles')) {
                DB::statement('
                    CREATE TABLE customer_profiles (
                        id SERIAL PRIMARY KEY,
                        cus_name VARCHAR(255),
                        cus_state VARCHAR(255),
                        cus_city VARCHAR(255),
                        cus_postcode VARCHAR(255),
                        cus_country VARCHAR(255),
                        cus_phone VARCHAR(255),
                        cus_address TEXT,
                        ship_name VARCHAR(255),
                        ship_state VARCHAR(255),
                        ship_address TEXT,
                        ship_city VARCHAR(255),
                        ship_postcode VARCHAR(255),
                        ship_country VARCHAR(255),
                        ship_phone VARCHAR(255),
                        user_id BIGINT REFERENCES users(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created customer_profiles table');
            }

            // Create categories table
            if (!Schema::hasTable('categories')) {
                DB::statement('
                    CREATE TABLE categories (
                        id SERIAL PRIMARY KEY,
                        category_name VARCHAR(255) NOT NULL,
                        category_img VARCHAR(255),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created categories table');
            }

            // Create brands table
            if (!Schema::hasTable('brands')) {
                DB::statement('
                    CREATE TABLE brands (
                        id SERIAL PRIMARY KEY,
                        brand_name VARCHAR(255) NOT NULL,
                        brand_img VARCHAR(255),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created brands table');
            }

            // Create products table
            if (!Schema::hasTable('products')) {
                DB::statement('
                    CREATE TABLE products (
                        id SERIAL PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        short_des TEXT,
                        price DECIMAL(10,2) NOT NULL,
                        discount DECIMAL(5,2) DEFAULT 0,
                        discount_price DECIMAL(10,2),
                        image VARCHAR(255),
                        star INTEGER DEFAULT 0,
                        stock INTEGER DEFAULT 0,
                        remark VARCHAR(255),
                        category_id BIGINT REFERENCES categories(id),
                        brand_id BIGINT REFERENCES brands(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created products table');
            }

            // Create product_details table
            if (!Schema::hasTable('product_details')) {
                DB::statement('
                    CREATE TABLE product_details (
                        id SERIAL PRIMARY KEY,
                        img1 VARCHAR(255),
                        img2 VARCHAR(255),
                        img3 VARCHAR(255),
                        img4 VARCHAR(255),
                        des TEXT,
                        color VARCHAR(255),
                        size VARCHAR(255),
                        product_id BIGINT REFERENCES products(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created product_details table');
            }

            // Create product_sliders table
            if (!Schema::hasTable('product_sliders')) {
                DB::statement('
                    CREATE TABLE product_sliders (
                        id SERIAL PRIMARY KEY,
                        title VARCHAR(255),
                        short_des TEXT,
                        price DECIMAL(10,2),
                        img VARCHAR(255),
                        product_id BIGINT REFERENCES products(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created product_sliders table');
            }

            // Create product_reviews table
            if (!Schema::hasTable('product_reviews')) {
                DB::statement('
                    CREATE TABLE product_reviews (
                        id SERIAL PRIMARY KEY,
                        description TEXT,
                        rating INTEGER,
                        product_id BIGINT REFERENCES products(id),
                        customer_id BIGINT REFERENCES customer_profiles(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created product_reviews table');
            }

            // Create product_wishes table
            if (!Schema::hasTable('product_wishes')) {
                DB::statement('
                    CREATE TABLE product_wishes (
                        id SERIAL PRIMARY KEY,
                        product_id BIGINT REFERENCES products(id),
                        user_id BIGINT REFERENCES users(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created product_wishes table');
            }

            // Create product_carts table
            if (!Schema::hasTable('product_carts')) {
                DB::statement('
                    CREATE TABLE product_carts (
                        id SERIAL PRIMARY KEY,
                        product_id BIGINT REFERENCES products(id),
                        user_id BIGINT REFERENCES users(id),
                        color VARCHAR(255),
                        size VARCHAR(255),
                        qty INTEGER,
                        price DECIMAL(10,2),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created product_carts table');
            }

            // Create sslcommerz_accounts table
            if (!Schema::hasTable('sslcommerz_accounts')) {
                DB::statement('
                    CREATE TABLE sslcommerz_accounts (
                        id SERIAL PRIMARY KEY,
                        store_id VARCHAR(255),
                        store_password VARCHAR(255),
                        currency VARCHAR(255),
                        success_url VARCHAR(255),
                        fail_url VARCHAR(255),
                        cancel_url VARCHAR(255),
                        ipn_url VARCHAR(255),
                        init_url VARCHAR(255),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created sslcommerz_accounts table');
            }

            // Create invoices table
            if (!Schema::hasTable('invoices')) {
                DB::statement('
                    CREATE TABLE invoices (
                        id SERIAL PRIMARY KEY,
                        total DECIMAL(10,2),
                        vat DECIMAL(10,2),
                        payable DECIMAL(10,2),
                        cus_details TEXT,
                        ship_details TEXT,
                        tran_id VARCHAR(255),
                        val_id VARCHAR(255) DEFAULT 0,
                        delivery_status VARCHAR(255),
                        payment_status VARCHAR(255),
                        user_id BIGINT REFERENCES users(id),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created invoices table');
            }

            // Create invoice_products table
            if (!Schema::hasTable('invoice_products')) {
                DB::statement('
                    CREATE TABLE invoice_products (
                        id SERIAL PRIMARY KEY,
                        invoice_id BIGINT REFERENCES invoices(id),
                        product_id BIGINT REFERENCES products(id),
                        user_id BIGINT REFERENCES users(id),
                        qty INTEGER,
                        sale_price DECIMAL(10,2),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created invoice_products table');
            }

            // Create policies table
            if (!Schema::hasTable('policies')) {
                DB::statement('
                    CREATE TABLE policies (
                        id SERIAL PRIMARY KEY,
                        title VARCHAR(255),
                        description TEXT,
                        type VARCHAR(255),
                        status VARCHAR(255),
                        created_at TIMESTAMP DEFAULT NOW(),
                        updated_at TIMESTAMP DEFAULT NOW()
                    )
                ');
                $this->info('✓ Created policies table');
            }

            $this->info('🎉 All tables created successfully for CockroachDB!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
