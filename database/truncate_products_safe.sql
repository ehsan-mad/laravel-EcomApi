-- =====================================================
-- SAFE TRUNCATE PRODUCTS TABLE
-- =====================================================
-- This script safely truncates the products table and all related tables
-- Execute this in your MySQL database

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all related tables first (in correct order)
TRUNCATE TABLE invoice_products;
TRUNCATE TABLE product_reviews;
TRUNCATE TABLE product_carts;
TRUNCATE TABLE product_wishes;
TRUNCATE TABLE product_sliders;
TRUNCATE TABLE product_details;

-- Now truncate the main products table
TRUNCATE TABLE products;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- VERIFICATION QUERIES (Optional - run to verify)
-- =====================================================
-- SELECT COUNT(*) as products_count FROM products;
-- SELECT COUNT(*) as product_details_count FROM product_details;
-- SELECT COUNT(*) as product_sliders_count FROM product_sliders;
-- SELECT COUNT(*) as product_reviews_count FROM product_reviews;
-- SELECT COUNT(*) as product_carts_count FROM product_carts;
-- SELECT COUNT(*) as product_wishes_count FROM product_wishes;
-- SELECT COUNT(*) as invoice_products_count FROM invoice_products;