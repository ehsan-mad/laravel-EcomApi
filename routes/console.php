<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cockroach:migrate', function () {
    $this->info('Creating tables for CockroachDB with simple IDs...');

    try {
        // Drop all tables first
        DB::statement('DROP TABLE IF EXISTS invoice_products CASCADE');
        DB::statement('DROP TABLE IF EXISTS invoices CASCADE');
        DB::statement('DROP TABLE IF EXISTS product_reviews CASCADE');
        DB::statement('DROP TABLE IF EXISTS product_wishes CASCADE');
        DB::statement('DROP TABLE IF EXISTS product_carts CASCADE');
        DB::statement('DROP TABLE IF EXISTS product_sliders CASCADE');
        DB::statement('DROP TABLE IF EXISTS product_details CASCADE');
        DB::statement('DROP TABLE IF EXISTS products CASCADE');
        DB::statement('DROP TABLE IF EXISTS customer_profiles CASCADE');
        DB::statement('DROP TABLE IF EXISTS policies CASCADE');
        DB::statement('DROP TABLE IF EXISTS sslcommerz_accounts CASCADE');
        DB::statement('DROP TABLE IF EXISTS brands CASCADE');
        DB::statement('DROP TABLE IF EXISTS categories CASCADE');
        DB::statement('DROP TABLE IF EXISTS users CASCADE');

        // Create users table with simple IDs
        DB::statement('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                otp VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created users table');

        // Create customer_profiles table
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
                user_id INTEGER REFERENCES users(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created customer_profiles table');

        // Create categories table
        DB::statement('
            CREATE TABLE categories (
                id INTEGER PRIMARY KEY,
                category_name VARCHAR(255) NOT NULL,
                category_img VARCHAR(255),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created categories table');

        // Create brands table
        DB::statement('
            CREATE TABLE brands (
                id INTEGER PRIMARY KEY,
                brand_name VARCHAR(255) NOT NULL,
                brand_img VARCHAR(255),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created brands table');

        // Create products table
        DB::statement('
            CREATE TABLE products (
                id INTEGER PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                short_des TEXT,
                price DECIMAL(10,2) NOT NULL,
                discount DECIMAL(5,2) DEFAULT 0,
                discount_price DECIMAL(10,2),
                image VARCHAR(255),
                star INTEGER DEFAULT 0,
                stock INTEGER DEFAULT 0,
                remark VARCHAR(255),
                category_id INTEGER REFERENCES categories(id),
                brand_id INTEGER REFERENCES brands(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created products table');

        // Create product_details table
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
                product_id INTEGER REFERENCES products(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created product_details table');

        // Create product_sliders table
        DB::statement('
            CREATE TABLE product_sliders (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255),
                short_des TEXT,
                price DECIMAL(10,2),
                img VARCHAR(255),
                product_id INTEGER REFERENCES products(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created product_sliders table');

        // Create product_reviews table
        DB::statement('
            CREATE TABLE product_reviews (
                id SERIAL PRIMARY KEY,
                description TEXT,
                rating INTEGER,
                product_id INTEGER REFERENCES products(id),
                customer_id INTEGER REFERENCES customer_profiles(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created product_reviews table');

        // Create product_wishes table
        DB::statement('
            CREATE TABLE product_wishes (
                id SERIAL PRIMARY KEY,
                product_id INTEGER REFERENCES products(id),
                user_id INTEGER REFERENCES users(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created product_wishes table');

        // Create product_carts table
        DB::statement('
            CREATE TABLE product_carts (
                id SERIAL PRIMARY KEY,
                product_id INTEGER REFERENCES products(id),
                user_id INTEGER REFERENCES users(id),
                color VARCHAR(255),
                size VARCHAR(255),
                qty INTEGER,
                price DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created product_carts table');

        // Create sslcommerz_accounts table
        DB::statement('
            CREATE TABLE sslcommerz_accounts (
                id INTEGER PRIMARY KEY,
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

        // Create invoices table
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
                user_id INTEGER REFERENCES users(id),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created invoices table');

        // Create invoice_products table
        DB::statement('
            CREATE TABLE invoice_products (
                id SERIAL PRIMARY KEY,
                invoice_id INTEGER REFERENCES invoices(id),
                product_id INTEGER REFERENCES products(id),
                user_id INTEGER REFERENCES users(id),
                qty INTEGER,
                sale_price DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created invoice_products table');

        // Create policies table
        DB::statement('
            CREATE TABLE policies (
                id INTEGER PRIMARY KEY,
                title VARCHAR(255),
                description TEXT,
                type VARCHAR(255),
                status VARCHAR(255),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )
        ');
        $this->info('✓ Created policies table');

        // Insert sample data with simple IDs
        $this->info('📋 Inserting sample data...');

        // Insert users
        DB::statement("INSERT INTO users (id, email, otp) VALUES (1, 'test@example.com', '0')");
        DB::statement("INSERT INTO users (id, email, otp) VALUES (2, 'lal@g.com', '0')");

        // Insert customer profiles
        DB::statement("INSERT INTO customer_profiles (id, cus_name, cus_state, cus_city, cus_postcode, cus_country, cus_phone, cus_address, ship_name, ship_state, ship_address, ship_city, ship_postcode, ship_country, ship_phone, user_id) VALUES (1, 'John Doe', 'Dhaka', 'Dhaka', '1000', 'Bangladesh', '+8801234567890', '123 Main Street, Dhaka', 'John Doe', 'Dhaka', '123 Main Street, Dhaka', 'Dhaka', '1000', 'Bangladesh', '+8801234567890', 1)");
        DB::statement("INSERT INTO customer_profiles (id, cus_name, cus_state, cus_city, cus_postcode, cus_country, cus_phone, cus_address, ship_name, ship_state, ship_address, ship_city, ship_postcode, ship_country, ship_phone, user_id) VALUES (2, 'Lal Ahmed', 'Chittagong', 'Chittagong', '4000', 'Bangladesh', '+8801987654321', '456 Second Street, Chittagong', 'Lal Ahmed', 'Chittagong', '456 Second Street, Chittagong', 'Chittagong', '4000', 'Bangladesh', '+8801987654321', 2)");

        // Insert categories
        DB::statement("INSERT INTO categories (id, category_name, category_img) VALUES (1, 'Electronics', 'electronics.jpg')");
        DB::statement("INSERT INTO categories (id, category_name, category_img) VALUES (2, 'Clothing', 'clothing.jpg')");
        DB::statement("INSERT INTO categories (id, category_name, category_img) VALUES (3, 'Books', 'books.jpg')");

        // Insert brands
        DB::statement("INSERT INTO brands (id, brand_name, brand_img) VALUES (1, 'Samsung', 'samsung.jpg')");
        DB::statement("INSERT INTO brands (id, brand_name, brand_img) VALUES (2, 'Apple', 'apple.jpg')");
        DB::statement("INSERT INTO brands (id, brand_name, brand_img) VALUES (3, 'Nike', 'nike.jpg')");

        // Insert products
        DB::statement("INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id) VALUES (1, 'iPhone 15', 'Latest iPhone', 999.99, 10, 899.99, 'iphone15.jpg', 5, 50, 'hot', 1, 2)");
        DB::statement("INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id) VALUES (2, 'Samsung Galaxy S24', 'Latest Samsung phone', 899.99, 5, 854.99, 'galaxy-s24.jpg', 4, 30, 'new', 1, 1)");
        DB::statement("INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id) VALUES (3, 'iPad Pro', 'Latest iPad', 1099.99, 8, 1011.99, 'ipad-pro.jpg', 5, 25, 'hot', 1, 2)");
        DB::statement("INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id) VALUES (4, 'Nike Air Max', 'Comfortable sneakers', 150.00, 15, 127.50, 'nike-air-max.jpg', 4, 100, 'popular', 2, 3)");
        DB::statement("INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id) VALUES (5, 'Programming Book', 'Learn to code', 29.99, 20, 23.99, 'code-book.jpg', 4, 200, 'education', 3, 1)");

        // Insert SSLCommerz account
        DB::statement("INSERT INTO sslcommerz_accounts (id, store_id, store_password, currency, success_url, fail_url, cancel_url, ipn_url, init_url) VALUES (1, 'laravel66a6c7f1e3fcd', 'laravel66a6c7f1e3fcd@ssl', 'BDT', 'http://127.0.0.1:8000/PaymentSuccess', 'http://127.0.0.1:8000/PaymentFail', 'http://127.0.0.1:8000/PaymentCancel', 'http://127.0.0.1:8000/PaymentIPN', 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php')");

        $this->info('🎉 All tables created and sample data inserted with simple IDs!');
        $this->info('');
        $this->info('📊 Sample Data Summary:');
        $this->info('Users: 1, 2');
        $this->info('Categories: 1 (Electronics), 2 (Clothing), 3 (Books)');
        $this->info('Brands: 1 (Samsung), 2 (Apple), 3 (Nike)');
        $this->info('Products: 1 (iPhone 15), 2 (Galaxy S24), 3 (iPad Pro), 4 (Nike Air Max), 5 (Programming Book)');

    } catch (\Exception $e) {
        $this->error('Error: ' . $e->getMessage());
        return 1;
    }

    return 0;
})->purpose('Run migrations for CockroachDB with simple IDs');
