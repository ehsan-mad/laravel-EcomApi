<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel E-Commerce Backend API

A robust Laravel-based e-commerce backend API that provides comprehensive endpoints for managing products, categories, brands, user authentication, shopping cart, wishlists, reviews, and payment processing through SSLCommerz.

## Features

- **Product Management**: Complete CRUD operations for products with categories and brands
- **User Authentication**: Secure API authentication using Laravel Sanctum and JWT
- **Shopping Cart**: Add, update, remove items from cart
- **Wishlist**: Save products for later purchase
- **Product Reviews**: User reviews and ratings system
- **Payment Processing**: Integration with SSLCommerz payment gateway
- **Order Management**: Track and manage customer orders
- **Featured Products**: Highlight products in sliders and by remarks

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

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
