<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PricingController extends Controller
{
    public function calculatePrice(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'datetime' => 'nullable|date'
        ]);

        $product = Product::with('inventory')->findOrFail($productId);
        $quantity = $request->input('quantity', 1);
        $datetime = $request->datetime ? Carbon::parse($request->datetime) : now();

        $basePrice = $product->inventory->cost;
        $finalPrice = $this->applyPricingRules($product, $basePrice, $quantity, $datetime);

        return response()->json([
            'product_id' => $product->id,
            'base_price' => $basePrice,
            'final_price' => round($finalPrice, 2),
            'quantity' => $quantity,
            'datetime' => $datetime->toDateTimeString(),
            'discounts_applied' => $this->getAppliedDiscounts($product, $basePrice, $quantity, $datetime)
        ]);
    }


    private function applyPricingRules(Product $product, float $basePrice, int $quantity, Carbon $datetime): float
    {
        $rules = PricingRule::where('product_id', $product->id)
            ->orderBy('precedence', 'desc')
            ->get();

        $currentPrice = $basePrice;

        foreach ($rules as $rule) {
            if ($this->ruleApplies($rule, $quantity, $datetime)) {
                $currentPrice = $this->applyRule($rule, $currentPrice);
            }
        }

        return max($currentPrice, 0);
    }


    private function ruleApplies(PricingRule $rule, int $quantity, Carbon $datetime): bool
    {
        if ($rule->type === 'quantity') {
            return $quantity >= $rule->min_quantity;
        }

        if ($rule->type === 'time') {
            // Convert string times to Carbon
            $startTime = Carbon::parse($rule->start_time);
            $endTime = Carbon::parse($rule->end_time);

            $timeMatches = $datetime->between(
                $datetime->copy()->setTimeFrom($startTime),
                $datetime->copy()->setTimeFrom($endTime)
            );

            $dayMatches = in_array($datetime->format('l'), explode(',', $rule->days));

            return $timeMatches && $dayMatches;
        }

        return false;
    }

    private function applyRule(PricingRule $rule, float $currentPrice): float
    {
        if ($rule->discount_percentage) {
            return $currentPrice * (1 - ($rule->discount_percentage / 100));
        }

        return $currentPrice;
    }


    private function getAppliedDiscounts(Product $product, float $basePrice, int $quantity, Carbon $datetime): array
    {
        $rules = PricingRule::where('product_id', $product->id)
            ->orderBy('precedence', 'desc')
            ->get();

        $discounts = [];
        $runningPrice = $basePrice;

        foreach ($rules as $rule) {
            if ($this->ruleApplies($rule, $quantity, $datetime)) {
                $oldPrice = $runningPrice;
                $runningPrice = $this->applyRule($rule, $runningPrice);

                $discounts[] = [
                    'rule_id' => $rule->id,
                    'type' => $rule->type,
                    'description' => $this->getRuleDescription($rule),
                    'discount_amount' => round($oldPrice - $runningPrice, 2),
                    'new_price' => round($runningPrice, 2)
                ];
            }
        }

        return $discounts;
    }


    private function getRuleDescription(PricingRule $rule): string
    {
        if ($rule->type === 'quantity') {
            return "Buy {$rule->min_quantity}+ get {$rule->discount_percentage}% off";
        }

        // Convert string times to Carbon for formatting
        $startTime = Carbon::parse($rule->start_time);
        $endTime = Carbon::parse($rule->end_time);

        return "{$rule->discount_percentage}% off on {$rule->days} between "
            . $startTime->format('H:i') . "-"
            . $endTime->format('H:i');
    }
}
