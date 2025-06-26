<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, AuditLog, Inventory, PricingRule, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function process(Request $request)
    {
//        dd('hello');
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($request->product_id);
            $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();

            if ($inventory->quantity < $request->quantity) {
                return response()->json(['error' => 'Not enough inventory'], 400);
            }

            $finalPrice = $this->calculatePrice($product, $request->quantity);

            $transaction = Transaction::create([
                'type' => 'sale',
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'final_price' => $finalPrice,
            ]);


            $inventory->quantity -= $request->quantity;
            $inventory->save();

            AuditLog::create([
                'transaction_id' => $transaction->id,
                'action' => 'processed_sale',
                'data' => json_encode([
                    'product' => $product->sku,
                    'quantity' => $request->quantity,
                    'price' => $finalPrice,
                ]),
            ]);

            DB::commit();
            return response()->json(['message' => 'Transaction completed']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed', 'details' => $e->getMessage()], 500);
        }
    }

    private function calculatePrice($product, $quantity)
    {
        $now = Carbon::now();
        $rules = PricingRule::where('product_id', $product->id)
            ->orderBy('precedence')
            ->get();

        $pricePerItem = 100;

        foreach ($rules as $rule) {
            if ($rule->type == 'time' && $rule->days) {
                $days = explode(',', $rule->days);
                if (in_array($now->format('l'), $days)) {
                    if ($now->between(Carbon::parse($rule->start_time), Carbon::parse($rule->end_time))) {
                        $pricePerItem -= $pricePerItem * ($rule->discount_percentage / 100);
                    }
                }
            } elseif ($rule->type == 'quantity' && $quantity >= $rule->min_quantity) {
                $pricePerItem -= $pricePerItem * ($rule->discount_percentage / 100);
            }
        }

        return $pricePerItem * $quantity;
    }
}

