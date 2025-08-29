<?php

namespace App\Helper;

use App\Helper\ResponseHelper;
use Illuminate\Http\Request;

/**
 * This is an example class showing how to use the ResponseHelper
 * You can use this as a reference and delete this file when you're comfortable with the usage
 */
class ResponseHelperExample
{
    /**
     * Example: Using ResponseHelper class directly
     */
    public function exampleUsingClass()
    {
        // Success response with data
        $users = ['user1', 'user2', 'user3'];
        return ResponseHelper::success($users, 'Users retrieved successfully');

        // Error response
        return ResponseHelper::error('Something went wrong', null, 500);

        // Validation error
        $validationErrors = ['email' => ['Email is required']];
        return ResponseHelper::validationError($validationErrors);

        // Not found response
        return ResponseHelper::notFound('User not found');

        // Created response
        $newUser = ['id' => 1, 'name' => 'John Doe'];
        return ResponseHelper::created($newUser, 'User created successfully');

        // Unauthorized response
        return ResponseHelper::unauthorized('Please login first');
    }

    /**
     * Example: Using helper functions (recommended for cleaner code)
     */
    public function exampleUsingHelperFunctions()
    {
        // Success response with data
        $products = ['product1', 'product2', 'product3'];
        return responseSuccess($products, 'Products retrieved successfully');

        // Error response
        return responseError('Product not available', null, 400);

        // Validation error
        $errors = ['name' => ['Product name is required']];
        return responseValidationError($errors);

        // Not found response
        return responseNotFound('Product not found');

        // Created response
        $newProduct = ['id' => 1, 'name' => 'New Product'];
        return responseCreated($newProduct);

        // Unauthorized response
        return responseUnauthorized();
    }

    /**
     * Example: Real-world usage in a controller method
     */
    public function getProducts(Request $request)
    {
        try {
            // Simulate fetching products from database
            $products = [
                ['id' => 1, 'name' => 'Product 1', 'price' => 100],
                ['id' => 2, 'name' => 'Product 2', 'price' => 200],
            ];

            // Return success response
            return responseSuccess($products, 'Products retrieved successfully');

        } catch (\Exception $e) {
            // Return error response
            return responseError('Failed to retrieve products', null, 500);
        }
    }

    /**
     * Example: Creating a product with validation
     */
    public function createProduct(Request $request)
    {
        // Simulate validation
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return responseValidationError($validator->errors());
        }

        try {
            // Simulate creating product
            $product = [
                'id' => rand(1, 1000),
                'name' => $request->name,
                'price' => $request->price,
                'created_at' => now()
            ];

            return responseCreated($product, 'Product created successfully');

        } catch (\Exception $e) {
            return responseError('Failed to create product', null, 500);
        }
    }

    /**
     * Example: Paginated response
     */
    public function getPaginatedProducts()
    {
        // Simulate paginated data
        $products = [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ];

        $pagination = [
            'current_page' => 1,
            'per_page' => 10,
            'total' => 25,
            'last_page' => 3,
            'from' => 1,
            'to' => 10
        ];

        return responsePaginated($products, $pagination, 'Products retrieved successfully');
    }

    /**
     * Example: Collection response with meta data
     */
    public function getProductsWithMeta()
    {
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'category' => 'Electronics'],
            ['id' => 2, 'name' => 'Product 2', 'category' => 'Clothing'],
        ];

        $meta = [
            'total_categories' => 5,
            'featured_count' => 2,
            'last_updated' => now()->toDateTimeString()
        ];

        return responseCollection($products, 'Products with metadata', $meta);
    }
}

/*
USAGE EXAMPLES IN YOUR CONTROLLERS:

1. Basic Success Response:
   return responseSuccess($data, 'Operation successful');

2. Error Response:
   return responseError('Something went wrong');

3. Validation Error:
   return responseValidationError($validator->errors());

4. Not Found:
   return responseNotFound('Resource not found');

5. Created:
   return responseCreated($newResource, 'Resource created');

6. Unauthorized:
   return responseUnauthorized('Please login');

7. Forbidden:
   return responseForbidden('Access denied');

8. Paginated Data:
   return responsePaginated($data, $paginationInfo);

9. Collection with Meta:
   return responseCollection($collection, 'Success', $metaData);

10. Single Resource:
    return responseResource($resource, 'Resource retrieved');

RESPONSE FORMAT:
All responses follow this consistent format:
{
    "status": "success|error",
    "message": "Your message here",
    "data": {...}, // Only in success responses
    "errors": {...}, // Only in error responses
    "pagination": {...}, // Only in paginated responses
    "meta": {...} // Only when meta data is provided
}
*/