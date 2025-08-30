<?php
namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use App\Models\ProductWish;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function ListProductByCategory(Request $request)
    {
        // Logic to retrieve products by category
        $data = Product::where('category_id', $request->id)->with('category', 'brand')->get();
        return ResponseHelper::success($data, 'Product list retrieved successfully');
    }
    public function ListProductByBrand(Request $request)
    {
        // Logic to retrieve products by brand
        $data = Product::where('brand_id', $request->id)->with('category', 'brand')->get();
        return ResponseHelper::success($data, 'Product list retrieved successfully');
    }
    public function ListProductByRemarks(Request $request)
    {
        // Logic to retrieve products by remarks
        $data = Product::where('remarks', $request->remark)->with('category', 'brand')->get();
        return ResponseHelper::success($data, 'Product list retrieved successfully');
    }

    public function ListProductSlider()
    {
        $data = ProductSlider::all();
        return ResponseHelper::success($data, 'Product slider list retrieved successfully');
    }

    public function ProductDetailsById(Request $request)
    {
        $data = ProductDetail::where('product_id', $request->id)->with('product', 'product.brand', 'product.category')->get();

        return ResponseHelper::success($data, 'Product details retrieved successfully');
    }

    public function ListReviewByProduct(Request $request)
    {
        $data = ProductReview::where('product_id', $request->id)->with([
            'profile' => function ($query) {
                $query->select('id', 'cus_name', ); // Select only necessary fields
            },
        ])->get();
        return ResponseHelper::success($data, 'Product review list retrieved successfully');
    }

    public function ProductWishList(Request $request)
    {
        // Logic to retrieve product wishlist
        $user_id = $request->headers->get('id');

        // Check if ids parameter is provided for backward compatibility
        if ($request->has('ids') && $request->ids !== null) {
            // Handle array of IDs or comma-separated string
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $ids = array_filter($ids); // Remove empty values

            if (! empty($ids)) {
                $data = Product::whereIn('id', $ids)->with('category', 'brand')->get();
                return ResponseHelper::success($data, 'Product wishlist retrieved successfully');
            }
        }

        // Default behavior: get user's wishlist items
        $data = ProductWish::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::success($data, 'Product wishlist retrieved successfully');
    }

    public function CreateWishList(Request $request, $product_id)
    {
        // Logic to create a product wishlist
        $user_id = $request->headers->get('id');
        $data    = ProductWish::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            ['user_id' => $user_id, 'product_id' => $product_id]
        );
        return ResponseHelper::success($data, 'Product wishlist created successfully');
    }

    public function RemoveWishList(Request $request, $product_id)
    {
        // Logic to remove a product from the wishlist
        $user_id = $request->headers->get('id');
        $data    = ProductWish::where(['user_id' => $user_id, 'product_id' => $product_id])->delete();
        return ResponseHelper::success($data, 'Product wishlist removed successfully');
    }

    public function CreateCartList(Request $request)
    {
        // Logic to create a product cart
        $user_id    = $request->headers->get('id');
        $product_id = $request->input('product_id');
        $color      = $request->input('color');
        $size       = $request->input('size');
        $qty        = $request->input('qty');

        $product_details = Product::where('id', $product_id)->first();

        $unit_price = 0;
        if ($product_details->discount == 1) {
            $unit_price = $product_details->discount_price;
        } else {
            $unit_price = $product_details->price;
        }
        $total_price = $unit_price * $qty;

        $data = ProductCart::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            ['user_id' => $user_id, 'product_id' => $product_id, 'color' => $color, 'size' => $size, 'qty' => $qty, 'price' => $total_price]
        );
        return ResponseHelper::success($data, 'Product cart created successfully');
    }

    public function CartList(Request $request)
    {
        $user_id = $request->headers->get('id');
        $data    = ProductCart::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::success($data, 'Product cart list retrieved successfully');
    }

    public function DeleteCartList(Request $request)
    {
        $user_id = $request->headers->get('id');

        $data = ProductCart::where(['user_id' => $user_id, 'product_id' => $request->product_id])->delete();
        return ResponseHelper::success($data, 'Product cart deleted successfully');
    }

}
