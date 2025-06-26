<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%$search%");
        }

        return response()->json($query->paginate(10));
    }

    public function show($id)
    {
        $product = Product::with('inventory')->findOrFail($id);
        return response()->json($product);
    }
}
