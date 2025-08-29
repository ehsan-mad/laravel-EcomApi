---
description: Repository Information Overview
alwaysApply: true
---

# Laravel E-Commerce Backend Information

## Summary
This is a Laravel-based e-commerce backend API that provides endpoints for managing products, categories, brands, user authentication, shopping cart, wishlists, reviews, and payment processing through SSLCommerz.

## Structure
- **app**: Contains the core application code (controllers, models, middleware)
- **database**: Contains migrations, seeders, and factories
- **routes**: API and web route definitions
- **config**: Application configuration files
- **resources**: Frontend assets and views
- **storage**: Application storage (logs, uploads)
- **tests**: Unit and feature tests

## Language & Runtime
**Language**: PHP
**Version**: ^8.2
**Framework**: Laravel ^12.0
**Package Manager**: Composer

## Dependencies
**Main Dependencies**:
- laravel/framework: ^12.0
- laravel/sanctum: ^4.0 (API authentication)
- firebase/php-jwt: ^6.11 (JWT authentication)
- laravel/tinker: ^2.10.1

**Development Dependencies**:
- fakerphp/faker: ^1.23
- laravel/pint: ^1.13 (PHP code style fixer)
- pestphp/pest: ^3.8 (Testing framework)
- laravel/sail: ^1.41 (Docker development environment)

## Database Structure
- **Users**: Authentication and user management
- **CustomerProfiles**: Extended user information
- **Products**: Core product information
- **ProductDetails**: Detailed product specifications
- **Categories**: Product categorization
- **Brands**: Product brand information
- **ProductSliders**: Featured products for sliders
- **ProductReviews**: User reviews for products
- **ProductWishes**: User wishlist functionality
- **ProductCarts**: Shopping cart functionality
- **Invoices**: Order information
- **InvoiceProducts**: Products in each order
- **SslcommerzAccounts**: Payment gateway configuration

## API Endpoints
- **/api/brandList**: Get all brands
- **/api/categoryList**: Get all categories
- **/api/listProductByCategory/{id}**: Get products by category
- **/api/listProductByBrand/{id}**: Get products by brand
- **/api/listProductByRemarks/{remark}**: Get products by remark
- **/api/listProductSlider**: Get slider products
- **/api/productDetails/{id}**: Get product details
- **/api/listReviewByProduct/{id}**: Get product reviews
- **/api/PaymentIPN**: Payment gateway callback

## Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js and npm (for frontend assets)

### Installation Steps
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd laravel-Ecom-Backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Create environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure database in .env file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_ecom_backend
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run database migrations:
   ```bash
   php artisan migrate
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

9. For frontend asset compilation:
   ```bash
   npm run dev
   ```

### All-in-one Development Command
```bash
composer dev
```
This runs the server, queue worker, and Vite development server concurrently.

## Key Features
- **Product Management**: Complete CRUD for products with categories and brands
- **User Authentication**: Secure API authentication using Laravel Sanctum and JWT
- **Shopping Cart**: Add, update, remove items from cart
- **Wishlist**: Save products for later
- **Product Reviews**: User reviews and ratings
- **Payment Processing**: Integration with SSLCommerz payment gateway
- **Order Management**: Track and manage customer orders
- **Featured Products**: Highlight products in sliders and by remarks