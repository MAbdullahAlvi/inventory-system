<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\PricingRule;
use Illuminate\Http\Request;



class SaveRuleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:time,quantity',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'min_quantity' => 'nullable|integer|min:1',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'days' => 'nullable|string',
            'precedence' => 'nullable|integer|min:1',
        ]);

        $data = [
            'product_id' => $validated['product_id'],
            'type' => $validated['type'],
            'discount_percentage' => $validated['discount_percentage'] ?? null,
            'min_quantity' => $validated['min_quantity'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'days' => $validated['days'] ?? null,
            'precedence' => $validated['precedence'] ?? null,
        ];

        $rule = PricingRule::create($data);

        return response()->json(['message' => 'Rule saved successfully', 'data' => $rule], 201);
    }

}
