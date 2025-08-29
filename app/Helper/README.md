# Helper Classes

This folder contains helper classes and utility functions for the Laravel E-commerce Backend application.

## ResponseHelper

The `ResponseHelper` class provides a standardized way to return JSON responses from your API endpoints.

### Features

- Consistent response format across all API endpoints
- Pre-defined methods for common HTTP status codes
- Support for pagination, collections, and meta data
- Global helper functions for cleaner code

### Available Methods

#### Class Methods (ResponseHelper::)

- `success($data, $message, $statusCode)` - Success response
- `error($message, $errors, $statusCode)` - Error response
- `validationError($errors, $message)` - Validation error (422)
- `unauthorized($message)` - Unauthorized response (401)
- `forbidden($message)` - Forbidden response (403)
- `notFound($message)` - Not found response (404)
- `serverError($message)` - Server error response (500)
- `created($data, $message)` - Created response (201)
- `noContent()` - No content response (204)
- `paginated($data, $pagination, $message)` - Paginated response
- `collection($collection, $message, $meta)` - Collection with meta
- `resource($resource, $message)` - Single resource response
- `custom($data, $statusCode)` - Custom response

#### Helper Functions

- `responseSuccess($data, $message, $statusCode)`
- `responseError($message, $errors, $statusCode)`
- `responseValidationError($errors, $message)`
- `responseUnauthorized($message)`
- `responseForbidden($message)`
- `responseNotFound($message)`
- `responseCreated($data, $message)`
- `responsePaginated($data, $pagination, $message)`
- `responseCollection($collection, $message, $meta)`
- `responseResource($resource, $message)`

### Usage Examples

#### In Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        // Using class method
        return ResponseHelper::success($products, 'Products retrieved successfully');
        
        // Or using helper function (recommended)
        return responseSuccess($products, 'Products retrieved successfully');
    }
    
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            return responseValidationError($validator->errors());
        }
        
        $product = Product::create($request->all());
        
        return responseCreated($product, 'Product created successfully');
    }
    
    public function show($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return responseNotFound('Product not found');
        }
        
        return responseResource($product, 'Product retrieved successfully');
    }
}
```

#### Response Format

All responses follow this consistent structure:

```json
{
    "status": "success|error",
    "message": "Your message here",
    "data": {...}, // Only in success responses
    "errors": {...}, // Only in error responses
    "pagination": {...}, // Only in paginated responses
    "meta": {...} // Only when meta data is provided
}
```

### Installation

The helper functions are automatically loaded via Composer's autoload files configuration. No additional setup is required.

### Files

- `ResponseHelper.php` - Main helper class
- `helpers.php` - Global helper functions
- `ResponseHelperExample.php` - Usage examples (can be deleted after review)
- `README.md` - This documentation file

### Best Practices

1. Use helper functions instead of class methods for cleaner code
2. Always provide meaningful messages in responses
3. Use appropriate HTTP status codes
4. Include validation errors in validation error responses
5. Use pagination for large datasets
6. Include meta data when additional context is helpful

### Contributing

When adding new response types:

1. Add the method to `ResponseHelper.php`
2. Add corresponding helper function to `helpers.php`
3. Update this README with usage examples
4. Add examples to `ResponseHelperExample.php`