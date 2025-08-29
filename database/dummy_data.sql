-- =====================================================
-- DUMMY DATA FOR LARAVEL E-COMMERCE BACKEND
-- =====================================================
-- This file contains dummy data for all tables
-- Execute in order to maintain foreign key relationships
-- =====================================================

-- Clear existing data (optional - uncomment if needed)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE invoice_products;
-- TRUNCATE TABLE product_reviews;
-- TRUNCATE TABLE product_carts;
-- TRUNCATE TABLE product_wishes;
-- TRUNCATE TABLE product_sliders;
-- TRUNCATE TABLE product_details;
-- TRUNCATE TABLE products;
-- TRUNCATE TABLE invoices;
-- TRUNCATE TABLE customer_profiles;
-- TRUNCATE TABLE users;
-- TRUNCATE TABLE categories;
-- TRUNCATE TABLE brands;
-- TRUNCATE TABLE policies;
-- TRUNCATE TABLE sslcommerz_accounts;
-- SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- 1. USERS TABLE
-- =====================================================
INSERT INTO users (id, email, otp, created_at, updated_at) VALUES
(1, 'john.doe@example.com', '123456', NOW(), NOW()),
(2, 'jane.smith@example.com', '234567', NOW(), NOW()),
(3, 'mike.johnson@example.com', '345678', NOW(), NOW()),
(4, 'sarah.wilson@example.com', '456789', NOW(), NOW()),
(5, 'david.brown@example.com', '567890', NOW(), NOW()),
(6, 'lisa.davis@example.com', '678901', NOW(), NOW()),
(7, 'tom.miller@example.com', '789012', NOW(), NOW()),
(8, 'emma.garcia@example.com', '890123', NOW(), NOW()),
(9, 'alex.martinez@example.com', '901234', NOW(), NOW()),
(10, 'olivia.anderson@example.com', '012345', NOW(), NOW());

-- =====================================================
-- 2. CUSTOMER PROFILES TABLE
-- =====================================================
INSERT INTO customer_profiles (id, cus_name, cus_state, cus_city, cus_postcode, cus_country, cus_phone, cus_address, ship_name, ship_state, ship_address, ship_city, ship_postcode, ship_country, ship_phone, user_id, created_at, updated_at) VALUES
(1, 'John Doe', 'California', 'Los Angeles', '90210', 'USA', '+1-555-0101', '123 Main St, Apt 4B', 'John Doe', 'California', '123 Main St, Apt 4B', 'Los Angeles', '90210', 'USA', '+1-555-0101', 1, NOW(), NOW()),
(2, 'Jane Smith', 'New York', 'New York City', '10001', 'USA', '+1-555-0102', '456 Broadway Ave', 'Jane Smith', 'New York', '456 Broadway Ave', 'New York City', '10001', 'USA', '+1-555-0102', 2, NOW(), NOW()),
(3, 'Mike Johnson', 'Texas', 'Houston', '77001', 'USA', '+1-555-0103', '789 Oak Street', 'Mike Johnson', 'Texas', '789 Oak Street', 'Houston', '77001', 'USA', '+1-555-0103', 3, NOW(), NOW()),
(4, 'Sarah Wilson', 'Florida', 'Miami', '33101', 'USA', '+1-555-0104', '321 Palm Avenue', 'Sarah Wilson', 'Florida', '321 Palm Avenue', 'Miami', '33101', 'USA', '+1-555-0104', 4, NOW(), NOW()),
(5, 'David Brown', 'Illinois', 'Chicago', '60601', 'USA', '+1-555-0105', '654 Lake Shore Dr', 'David Brown', 'Illinois', '654 Lake Shore Dr', 'Chicago', '60601', 'USA', '+1-555-0105', 5, NOW(), NOW()),
(6, 'Lisa Davis', 'Washington', 'Seattle', '98101', 'USA', '+1-555-0106', '987 Pine Street', 'Lisa Davis', 'Washington', '987 Pine Street', 'Seattle', '98101', 'USA', '+1-555-0106', 6, NOW(), NOW()),
(7, 'Tom Miller', 'Colorado', 'Denver', '80201', 'USA', '+1-555-0107', '147 Mountain View', 'Tom Miller', 'Colorado', '147 Mountain View', 'Denver', '80201', 'USA', '+1-555-0107', 7, NOW(), NOW()),
(8, 'Emma Garcia', 'Arizona', 'Phoenix', '85001', 'USA', '+1-555-0108', '258 Desert Road', 'Emma Garcia', 'Arizona', '258 Desert Road', 'Phoenix', '85001', 'USA', '+1-555-0108', 8, NOW(), NOW()),
(9, 'Alex Martinez', 'Nevada', 'Las Vegas', '89101', 'USA', '+1-555-0109', '369 Strip Boulevard', 'Alex Martinez', 'Nevada', '369 Strip Boulevard', 'Las Vegas', '89101', 'USA', '+1-555-0109', 9, NOW(), NOW()),
(10, 'Olivia Anderson', 'Oregon', 'Portland', '97201', 'USA', '+1-555-0110', '741 River Street', 'Olivia Anderson', 'Oregon', '741 River Street', 'Portland', '97201', 'USA', '+1-555-0110', 10, NOW(), NOW());

-- =====================================================
-- 3. CATEGORIES TABLE
-- =====================================================
INSERT INTO categories (id, category_name, category_img, created_at, updated_at) VALUES
(1, 'Electronics', 'https://example.com/images/categories/electronics.jpg', NOW(), NOW()),
(2, 'Clothing & Fashion', 'https://example.com/images/categories/clothing.jpg', NOW(), NOW()),
(3, 'Home & Garden', 'https://example.com/images/categories/home-garden.jpg', NOW(), NOW()),
(4, 'Sports & Outdoors', 'https://example.com/images/categories/sports.jpg', NOW(), NOW()),
(5, 'Books & Media', 'https://example.com/images/categories/books.jpg', NOW(), NOW()),
(6, 'Health & Beauty', 'https://example.com/images/categories/health-beauty.jpg', NOW(), NOW()),
(7, 'Toys & Games', 'https://example.com/images/categories/toys.jpg', NOW(), NOW()),
(8, 'Automotive', 'https://example.com/images/categories/automotive.jpg', NOW(), NOW()),
(9, 'Food & Beverages', 'https://example.com/images/categories/food.jpg', NOW(), NOW()),
(10, 'Office Supplies', 'https://example.com/images/categories/office.jpg', NOW(), NOW());

-- =====================================================
-- 4. BRANDS TABLE
-- =====================================================
INSERT INTO brands (id, brand_name, brand_img, created_at, updated_at) VALUES
(1, 'Apple', 'https://example.com/images/brands/apple.jpg', NOW(), NOW()),
(2, 'Samsung', 'https://example.com/images/brands/samsung.jpg', NOW(), NOW()),
(3, 'Nike', 'https://example.com/images/brands/nike.jpg', NOW(), NOW()),
(4, 'Adidas', 'https://example.com/images/brands/adidas.jpg', NOW(), NOW()),
(5, 'Sony', 'https://example.com/images/brands/sony.jpg', NOW(), NOW()),
(6, 'LG', 'https://example.com/images/brands/lg.jpg', NOW(), NOW()),
(7, 'Dell', 'https://example.com/images/brands/dell.jpg', NOW(), NOW()),
(8, 'HP', 'https://example.com/images/brands/hp.jpg', NOW(), NOW()),
(9, 'Canon', 'https://example.com/images/brands/canon.jpg', NOW(), NOW()),
(10, 'Nikon', 'https://example.com/images/brands/nikon.jpg', NOW(), NOW()),
(11, 'Zara', 'https://example.com/images/brands/zara.jpg', NOW(), NOW()),
(12, 'H&M', 'https://example.com/images/brands/hm.jpg', NOW(), NOW()),
(13, 'IKEA', 'https://example.com/images/brands/ikea.jpg', NOW(), NOW()),
(14, 'Lego', 'https://example.com/images/brands/lego.jpg', NOW(), NOW()),
(15, 'Toyota', 'https://example.com/images/brands/toyota.jpg', NOW(), NOW());

-- =====================================================
-- 5. PRODUCTS TABLE
-- =====================================================
INSERT INTO products (id, name, short_des, price, discount, discount_price, image, star, stock, remark, category_id, brand_id, created_at, updated_at) VALUES
(1, 'iPhone 15 Pro', 'Latest iPhone with advanced camera system', 999.99, 10, 899.99, 'https://example.com/images/products/iphone15pro.jpg', 4.8, 50, 'new', 1, 1, NOW(), NOW()),
(2, 'Samsung Galaxy S24', 'Flagship Android smartphone with AI features', 899.99, 15, 764.99, 'https://example.com/images/products/galaxys24.jpg', 4.7, 75, 'popular', 1, 2, NOW(), NOW()),
(3, 'Nike Air Max 270', 'Comfortable running shoes with air cushioning', 150.00, 20, 120.00, 'https://example.com/images/products/airmax270.jpg', 4.5, 100, 'popular', 4, 3, NOW(), NOW()),
(4, 'Adidas Ultraboost 22', 'Premium running shoes with boost technology', 180.00, 25, 135.00, 'https://example.com/images/products/ultraboost22.jpg', 4.6, 80, 'sale', 4, 4, NOW(), NOW()),
(5, 'Sony WH-1000XM5', 'Wireless noise-canceling headphones', 399.99, 12, 351.99, 'https://example.com/images/products/sonywh1000xm5.jpg', 4.9, 30, 'new', 1, 5, NOW(), NOW()),
(6, 'LG OLED C3 55"', '55-inch OLED 4K Smart TV', 1499.99, 18, 1229.99, 'https://example.com/images/products/lgoled55.jpg', 4.8, 25, 'popular', 1, 6, NOW(), NOW()),
(7, 'Dell XPS 13', 'Ultra-portable laptop with Intel Core i7', 1299.99, 8, 1195.99, 'https://example.com/images/products/dellxps13.jpg', 4.7, 40, 'new', 1, 7, NOW(), NOW()),
(8, 'HP Spectre x360', '2-in-1 convertible laptop with touchscreen', 1199.99, 10, 1079.99, 'https://example.com/images/products/hpspectre.jpg', 4.6, 35, 'popular', 1, 8, NOW(), NOW()),
(9, 'Canon EOS R6 Mark II', 'Full-frame mirrorless camera', 2499.99, 5, 2374.99, 'https://example.com/images/products/canonr6.jpg', 4.9, 15, 'new', 1, 9, NOW(), NOW()),
(10, 'Nikon Z9', 'Professional mirrorless camera', 5499.99, 3, 5334.99, 'https://example.com/images/products/nikonz9.jpg', 4.9, 10, 'regular', 1, 10, NOW(), NOW()),
(11, 'Zara Wool Coat', 'Premium wool winter coat', 199.99, 30, 139.99, 'https://example.com/images/products/zaracoat.jpg', 4.4, 60, 'sale', 2, 11, NOW(), NOW()),
(12, 'H&M Cotton T-Shirt', 'Basic cotton t-shirt in various colors', 19.99, 0, 19.99, 'https://example.com/images/products/hmtshirt.jpg', 4.2, 200, 'regular', 2, 12, NOW(), NOW()),
(13, 'IKEA MALM Bed Frame', 'Modern bed frame with storage', 299.99, 15, 254.99, 'https://example.com/images/products/ikeamalm.jpg', 4.3, 45, 'popular', 3, 13, NOW(), NOW()),
(14, 'LEGO Creator Expert', 'Advanced building set for adults', 249.99, 20, 199.99, 'https://example.com/images/products/legocreator.jpg', 4.8, 25, 'new', 7, 14, NOW(), NOW()),
(15, 'Toyota Camry Floor Mats', 'All-weather floor mats for Toyota Camry', 89.99, 10, 80.99, 'https://example.com/images/products/toyotamats.jpg', 4.5, 150, 'regular', 8, 15, NOW(), NOW());

-- =====================================================
-- 6. PRODUCT DETAILS TABLE
-- =====================================================
INSERT INTO product_details (id, img1, img2, img3, img4, des, color, size, product_id, created_at, updated_at) VALUES
(1, 'https://example.com/images/products/iphone15pro_1.jpg', 'https://example.com/images/products/iphone15pro_2.jpg', 'https://example.com/images/products/iphone15pro_3.jpg', 'https://example.com/images/products/iphone15pro_4.jpg', 'The iPhone 15 Pro features a titanium design, advanced camera system with 5x telephoto zoom, and the powerful A17 Pro chip. Perfect for photography enthusiasts and power users.', 'Natural Titanium,Blue Titanium,White Titanium,Black Titanium', '128GB,256GB,512GB,1TB', 1, NOW(), NOW()),
(2, 'https://example.com/images/products/galaxys24_1.jpg', 'https://example.com/images/products/galaxys24_2.jpg', 'https://example.com/images/products/galaxys24_3.jpg', 'https://example.com/images/products/galaxys24_4.jpg', 'Samsung Galaxy S24 with AI-powered features, improved camera capabilities, and long-lasting battery. Experience the future of mobile technology.', 'Phantom Black,Cream,Violet,Amber Yellow', '128GB,256GB,512GB', 2, NOW(), NOW()),
(3, 'https://example.com/images/products/airmax270_1.jpg', 'https://example.com/images/products/airmax270_2.jpg', 'https://example.com/images/products/airmax270_3.jpg', 'https://example.com/images/products/airmax270_4.jpg', 'Nike Air Max 270 delivers exceptional comfort with its large Air unit and lightweight design. Perfect for running and everyday wear.', 'Black,White,Red,Blue,Grey', '7,7.5,8,8.5,9,9.5,10,10.5,11,11.5,12', 3, NOW(), NOW()),
(4, 'https://example.com/images/products/ultraboost22_1.jpg', 'https://example.com/images/products/ultraboost22_2.jpg', 'https://example.com/images/products/ultraboost22_3.jpg', 'https://example.com/images/products/ultraboost22_4.jpg', 'Adidas Ultraboost 22 with responsive BOOST midsole and Primeknit upper. Engineered for runners who demand the best performance.', 'Core Black,Cloud White,Solar Red,Navy', '7,7.5,8,8.5,9,9.5,10,10.5,11,11.5,12', 4, NOW(), NOW()),
(5, 'https://example.com/images/products/sonywh1000xm5_1.jpg', 'https://example.com/images/products/sonywh1000xm5_2.jpg', 'https://example.com/images/products/sonywh1000xm5_3.jpg', 'https://example.com/images/products/sonywh1000xm5_4.jpg', 'Sony WH-1000XM5 headphones with industry-leading noise cancellation, exceptional sound quality, and 30-hour battery life.', 'Black,Silver', 'One Size', 5, NOW(), NOW()),
(6, 'https://example.com/images/products/lgoled55_1.jpg', 'https://example.com/images/products/lgoled55_2.jpg', 'https://example.com/images/products/lgoled55_3.jpg', 'https://example.com/images/products/lgoled55_4.jpg', 'LG OLED C3 55" TV with perfect blacks, vibrant colors, and smart webOS platform. Ideal for movies, gaming, and streaming.', 'Black', '55 inch', 6, NOW(), NOW()),
(7, 'https://example.com/images/products/dellxps13_1.jpg', 'https://example.com/images/products/dellxps13_2.jpg', 'https://example.com/images/products/dellxps13_3.jpg', 'https://example.com/images/products/dellxps13_4.jpg', 'Dell XPS 13 with Intel Core i7, 16GB RAM, and 512GB SSD. Ultra-portable design with stunning InfinityEdge display.', 'Platinum Silver,Frost White', '13.4 inch', 7, NOW(), NOW()),
(8, 'https://example.com/images/products/hpspectre_1.jpg', 'https://example.com/images/products/hpspectre_2.jpg', 'https://example.com/images/products/hpspectre_3.jpg', 'https://example.com/images/products/hpspectre_4.jpg', 'HP Spectre x360 2-in-1 laptop with 360-degree hinge, touchscreen display, and premium build quality. Perfect for work and creativity.', 'Nightfall Black,Natural Silver', '13.5 inch', 8, NOW(), NOW()),
(9, 'https://example.com/images/products/canonr6_1.jpg', 'https://example.com/images/products/canonr6_2.jpg', 'https://example.com/images/products/canonr6_3.jpg', 'https://example.com/images/products/canonr6_4.jpg', 'Canon EOS R6 Mark II with 24.2MP full-frame sensor, advanced autofocus, and 4K video recording. Professional photography made accessible.', 'Black', 'Body Only,With 24-70mm Lens', 9, NOW(), NOW()),
(10, 'https://example.com/images/products/nikonz9_1.jpg', 'https://example.com/images/products/nikonz9_2.jpg', 'https://example.com/images/products/nikonz9_3.jpg', 'https://example.com/images/products/nikonz9_4.jpg', 'Nikon Z9 flagship mirrorless camera with 45.7MP sensor, 8K video, and professional-grade performance for demanding photographers.', 'Black', 'Body Only,With 24-70mm Lens', 10, NOW(), NOW()),
(11, 'https://example.com/images/products/zaracoat_1.jpg', 'https://example.com/images/products/zaracoat_2.jpg', 'https://example.com/images/products/zaracoat_3.jpg', 'https://example.com/images/products/zaracoat_4.jpg', 'Premium wool winter coat with elegant design and superior warmth. Perfect for cold weather and formal occasions.', 'Black,Navy,Camel,Grey', 'XS,S,M,L,XL', 11, NOW(), NOW()),
(12, 'https://example.com/images/products/hmtshirt_1.jpg', 'https://example.com/images/products/hmtshirt_2.jpg', 'https://example.com/images/products/hmtshirt_3.jpg', 'https://example.com/images/products/hmtshirt_4.jpg', 'Basic cotton t-shirt made from 100% organic cotton. Comfortable fit and available in multiple colors.', 'White,Black,Navy,Grey,Red', 'XS,S,M,L,XL,XXL', 12, NOW(), NOW()),
(13, 'https://example.com/images/products/ikeamalm_1.jpg', 'https://example.com/images/products/ikeamalm_2.jpg', 'https://example.com/images/products/ikeamalm_3.jpg', 'https://example.com/images/products/ikeamalm_4.jpg', 'Modern bed frame with built-in storage drawers. Clean design that fits any bedroom decor.', 'White,Black-Brown,Oak Veneer', 'Queen,King', 13, NOW(), NOW()),
(14, 'https://example.com/images/products/legocreator_1.jpg', 'https://example.com/images/products/legocreator_2.jpg', 'https://example.com/images/products/legocreator_3.jpg', 'https://example.com/images/products/legocreator_4.jpg', 'Advanced LEGO Creator Expert set with detailed instructions and premium pieces. Perfect for adult builders and collectors.', 'Multicolor', '2000+ pieces', 14, NOW(), NOW()),
(15, 'https://example.com/images/products/toyotamats_1.jpg', 'https://example.com/images/products/toyotamats_2.jpg', 'https://example.com/images/products/toyotamats_3.jpg', 'https://example.com/images/products/toyotamats_4.jpg', 'All-weather floor mats designed specifically for Toyota Camry. Durable, easy to clean, and perfect fit guaranteed.', 'Black,Grey,Beige', 'Front & Rear Set', 15, NOW(), NOW());

-- =====================================================
-- 7. PRODUCT SLIDERS TABLE
-- =====================================================
INSERT INTO product_sliders (id, title, short_des, price, img, product_id, created_at, updated_at) VALUES
(1, 'iPhone 15 Pro - Now Available', 'Experience the power of titanium', 999.99, 'https://example.com/images/sliders/iphone15pro_slider.jpg', 1, NOW(), NOW()),
(2, 'Galaxy S24 - AI Revolution', 'Smartphone redefined with AI', 899.99, 'https://example.com/images/sliders/galaxys24_slider.jpg', 2, NOW(), NOW()),
(3, 'Nike Air Max 270 - Comfort Redefined', 'Step into ultimate comfort', 150.00, 'https://example.com/images/sliders/airmax270_slider.jpg', 3, NOW(), NOW()),
(4, 'Sony WH-1000XM5 - Silence the World', 'Premium noise cancellation', 399.99, 'https://example.com/images/sliders/sonywh1000xm5_slider.jpg', 5, NOW(), NOW()),
(5, 'LG OLED C3 - Picture Perfect', 'OLED technology at its finest', 1499.99, 'https://example.com/images/sliders/lgoled55_slider.jpg', 6, NOW(), NOW());

-- =====================================================
-- 8. PRODUCT REVIEWS TABLE
-- =====================================================
INSERT INTO product_reviews (id, description, rating, product_id, customer_id, created_at, updated_at) VALUES
(1, 'Amazing phone! The camera quality is outstanding and the titanium build feels premium.', 5, 1, 1, NOW(), NOW()),
(2, 'Great value for money. The AI features are impressive and battery life is excellent.', 4, 2, 2, NOW(), NOW()),
(3, 'Very comfortable shoes. Perfect for daily running and the air cushioning is noticeable.', 5, 3, 3, NOW(), NOW()),
(4, 'Best running shoes I have ever owned. The boost technology really makes a difference.', 5, 4, 4, NOW(), NOW()),
(5, 'Incredible noise cancellation. Perfect for flights and commuting. Sound quality is top-notch.', 5, 5, 5, NOW(), NOW()),
(6, 'Beautiful OLED display with perfect blacks. Great for movies and gaming.', 5, 6, 6, NOW(), NOW()),
(7, 'Excellent laptop for productivity. Lightweight and powerful with great battery life.', 4, 7, 7, NOW(), NOW()),
(8, 'Love the 2-in-1 design. Great for both work and creative tasks.', 4, 8, 8, NOW(), NOW()),
(9, 'Professional camera with amazing image quality. Worth every penny for serious photographers.', 5, 9, 9, NOW(), NOW()),
(10, 'Top-tier camera for professionals. The 8K video capability is incredible.', 5, 10, 10, NOW(), NOW()),
(11, 'Beautiful coat with excellent quality. Keeps me warm in harsh winters.', 4, 11, 1, NOW(), NOW()),
(12, 'Basic t-shirt but good quality for the price. Comfortable and fits well.', 4, 12, 2, NOW(), NOW()),
(13, 'Sturdy bed frame with useful storage. Easy to assemble and looks great.', 4, 13, 3, NOW(), NOW()),
(14, 'Challenging and fun build. Great quality pieces and detailed instructions.', 5, 14, 4, NOW(), NOW()),
(15, 'Perfect fit for my Camry. Durable and easy to clean.', 4, 15, 5, NOW(), NOW());

-- =====================================================
-- 9. PRODUCT WISHES TABLE
-- =====================================================
INSERT INTO product_wishes (id, product_id, user_id, created_at, updated_at) VALUES
(1, 1, 2, NOW(), NOW()),
(2, 2, 3, NOW(), NOW()),
(3, 3, 4, NOW(), NOW()),
(4, 5, 1, NOW(), NOW()),
(5, 6, 5, NOW(), NOW()),
(6, 7, 6, NOW(), NOW()),
(7, 9, 7, NOW(), NOW()),
(8, 10, 8, NOW(), NOW()),
(9, 11, 9, NOW(), NOW()),
(10, 14, 10, NOW(), NOW()),
(11, 1, 6, NOW(), NOW()),
(12, 4, 2, NOW(), NOW()),
(13, 8, 3, NOW(), NOW()),
(14, 12, 7, NOW(), NOW()),
(15, 15, 4, NOW(), NOW());

-- =====================================================
-- 10. PRODUCT CARTS TABLE
-- =====================================================
INSERT INTO product_carts (id, product_id, user_id, color, size, qty, price, created_at, updated_at) VALUES
(1, 1, 1, 'Natural Titanium', '256GB', 1, 899.99, NOW(), NOW()),
(2, 3, 2, 'Black', '9', 2, 120.00, NOW(), NOW()),
(3, 5, 3, 'Black', 'One Size', 1, 351.99, NOW(), NOW()),
(4, 12, 4, 'White', 'M', 3, 19.99, NOW(), NOW()),
(5, 11, 5, 'Navy', 'L', 1, 139.99, NOW(), NOW()),
(6, 2, 6, 'Phantom Black', '256GB', 1, 764.99, NOW(), NOW()),
(7, 4, 7, 'Core Black', '10', 1, 135.00, NOW(), NOW()),
(8, 7, 8, 'Platinum Silver', '13.4 inch', 1, 1195.99, NOW(), NOW()),
(9, 13, 9, 'White', 'Queen', 1, 254.99, NOW(), NOW()),
(10, 15, 10, 'Black', 'Front & Rear Set', 1, 80.99, NOW(), NOW());

-- =====================================================
-- 11. INVOICES TABLE
-- =====================================================
INSERT INTO invoices (id, total, vat, payable, cus_details, ship_details, tran_id, val_id, delivery_status, payment_status, user_id, created_at, updated_at) VALUES
(1, 899.99, 89.99, 989.98, '{"name":"John Doe","email":"john.doe@example.com","phone":"+1-555-0101","address":"123 Main St, Apt 4B, Los Angeles, CA 90210"}', '{"name":"John Doe","address":"123 Main St, Apt 4B, Los Angeles, CA 90210","phone":"+1-555-0101"}', 'TXN001234567890', 'VAL001234567890', 'Delivered', 'Paid', 1, NOW(), NOW()),
(2, 240.00, 24.00, 264.00, '{"name":"Jane Smith","email":"jane.smith@example.com","phone":"+1-555-0102","address":"456 Broadway Ave, New York City, NY 10001"}', '{"name":"Jane Smith","address":"456 Broadway Ave, New York City, NY 10001","phone":"+1-555-0102"}', 'TXN001234567891', 'VAL001234567891', 'Shipped', 'Paid', 2, NOW(), NOW()),
(3, 351.99, 35.19, 387.18, '{"name":"Mike Johnson","email":"mike.johnson@example.com","phone":"+1-555-0103","address":"789 Oak Street, Houston, TX 77001"}', '{"name":"Mike Johnson","address":"789 Oak Street, Houston, TX 77001","phone":"+1-555-0103"}', 'TXN001234567892', 'VAL001234567892', 'Processing', 'Paid', 3, NOW(), NOW()),
(4, 59.97, 5.99, 65.96, '{"name":"Sarah Wilson","email":"sarah.wilson@example.com","phone":"+1-555-0104","address":"321 Palm Avenue, Miami, FL 33101"}', '{"name":"Sarah Wilson","address":"321 Palm Avenue, Miami, FL 33101","phone":"+1-555-0104"}', 'TXN001234567893', 'VAL001234567893', 'Delivered', 'Paid', 4, NOW(), NOW()),
(5, 139.99, 13.99, 153.98, '{"name":"David Brown","email":"david.brown@example.com","phone":"+1-555-0105","address":"654 Lake Shore Dr, Chicago, IL 60601"}', '{"name":"David Brown","address":"654 Lake Shore Dr, Chicago, IL 60601","phone":"+1-555-0105"}', 'TXN001234567894', 'VAL001234567894', 'Pending', 'Pending', 5, NOW(), NOW());

-- =====================================================
-- 12. INVOICE PRODUCTS TABLE
-- =====================================================
INSERT INTO invoice_products (id, invoice_id, product_id, user_id, qty, sale_price, created_at, updated_at) VALUES
(1, 1, 1, 1, 1, 899.99, NOW(), NOW()),
(2, 2, 3, 2, 2, 120.00, NOW(), NOW()),
(3, 3, 5, 3, 1, 351.99, NOW(), NOW()),
(4, 4, 12, 4, 3, 19.99, NOW(), NOW()),
(5, 5, 11, 5, 1, 139.99, NOW(), NOW());

-- =====================================================
-- 13. POLICIES TABLE
-- =====================================================
INSERT INTO policies (id, title, description, type, status, created_at, updated_at) VALUES
(1, 'Privacy Policy', 'This privacy policy explains how we collect, use, and protect your personal information when you use our e-commerce platform.', 'privacy', 'active', NOW(), NOW()),
(2, 'Terms of Service', 'These terms of service govern your use of our website and services. By using our platform, you agree to these terms.', 'terms', 'active', NOW(), NOW()),
(3, 'Return Policy', 'Our return policy allows you to return items within 30 days of purchase for a full refund, provided they are in original condition.', 'return', 'active', NOW(), NOW()),
(4, 'Shipping Policy', 'We offer various shipping options including standard, expedited, and overnight delivery. Shipping costs vary by location and method.', 'shipping', 'active', NOW(), NOW()),
(5, 'Cookie Policy', 'This cookie policy explains how we use cookies and similar technologies to improve your browsing experience on our website.', 'cookie', 'active', NOW(), NOW()),
(6, 'Refund Policy', 'Refunds are processed within 5-7 business days after we receive your returned item. Original shipping costs are non-refundable.', 'refund', 'active', NOW(), NOW()),
(7, 'Warranty Policy', 'All products come with manufacturer warranty. Extended warranty options are available for select items at checkout.', 'warranty', 'active', NOW(), NOW()),
(8, 'Data Protection Policy', 'We are committed to protecting your data in accordance with GDPR and other applicable data protection regulations.', 'data_protection', 'active', NOW(), NOW());

-- =====================================================
-- 14. SSLCOMMERZ ACCOUNTS TABLE
-- =====================================================
INSERT INTO sslcommerz_accounts (id, store_id, store_password, currency, success_url, fail_url, cancel_url, ipn_url, init_url, created_at, updated_at) VALUES
(1, 'testbox', 'qwerty', 'BDT', 'https://yoursite.com/success', 'https://yoursite.com/fail', 'https://yoursite.com/cancel', 'https://yoursite.com/ipn', 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php', NOW(), NOW());

-- =====================================================
-- SUMMARY OF INSERTED DATA
-- =====================================================
-- Users: 10 records
-- Customer Profiles: 10 records
-- Categories: 10 records
-- Brands: 15 records
-- Products: 15 records
-- Product Details: 15 records
-- Product Sliders: 5 records
-- Product Reviews: 15 records
-- Product Wishes: 15 records
-- Product Carts: 10 records
-- Invoices: 5 records
-- Invoice Products: 5 records
-- Policies: 8 records
-- SSLCommerz Accounts: 1 record
-- =====================================================
-- Total Records: 143
-- =====================================================