<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        $inventory = Inventory::where('product_id', $request->product_id)->first();
        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }

        $inventory->quantity = $request->quantity;
        $inventory->save();

        return response()->json(['message' => 'Inventory updated']);
    }
}

