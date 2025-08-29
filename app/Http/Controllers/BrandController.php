<?php
namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Brand;

class BrandController extends Controller
{
    //
    public function BrandList()
    {
        $data = Brand::all();
        return ResponseHelper::success($data, 'Brand list retrieved successfully');
    }
}
