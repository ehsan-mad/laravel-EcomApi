<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function CategoryList()
    {
        // Logic to retrieve and return the list of categories
        $data= Category::all();
        return ResponseHelper::success($data, 'Category list retrieved successfully');
    }
}
