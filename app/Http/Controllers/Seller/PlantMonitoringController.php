<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductGrowthLog;
use App\Models\ProductCareLog;
use Illuminate\Http\Request;

class PlantMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('seller_id', Auth::id())
            ->with(['growthLogs', 'careLogs'])
            ->get();

        $selectedProduct = null;

        if ($request->product_id) {
            // Use find() instead of firstWhere() for better performance
            $selectedProduct = Product::where('seller_id', Auth::id())
                ->with(['growthLogs', 'careLogs'])
                ->find($request->product_id);

            // If not found in DB, try to get from already loaded collection
            if (!$selectedProduct) {
                $selectedProduct = $products->firstWhere('id', $request->product_id);
            }
        }

        return view('sellers.plants.monitor', compact(
            'products',
            'selectedProduct'
        ));
    }

    public function storeGrowth(Request $request, $productId)
    {
        // Optional: Verify product belongs to seller before creating log
        $product = Product::where('id', $productId)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'height_cm' => 'nullable|numeric|min:0',
            'leaf_count' => 'nullable|integer|min:0',
            'growth_stage' => 'required|string|in:seedling,vegetative,flowering,fruiting,mature',
            'notes' => 'nullable|string|max:500',
        ]);

        ProductGrowthLog::create([
            'product_id' => $productId,
            'seller_id' => Auth::id(),
            'height_cm' => $request->height_cm,
            'leaf_count' => $request->leaf_count,
            'growth_stage' => $request->growth_stage,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Growth log added successfully!');
    }

    public function storeCare(Request $request, $productId)
    {
        // Optional: Verify product belongs to seller
        $product = Product::where('id', $productId)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'care_type' => 'required|string|in:watering,fertilizing,pruning,repotting,pest_control,disease_treatment,other',
            'notes' => 'nullable|string|max:500',
            'care_date' => 'required|date|before_or_equal:today',
        ]);

        ProductCareLog::create([
            'product_id' => $productId,
            'seller_id' => Auth::id(),
            'care_type' => $request->care_type,
            'notes' => $request->notes,
            'care_date' => $request->care_date,
        ]);

        return back()->with('success', 'Care log added successfully!');
    }

    // API endpoints - These are optional but good to have
    public function getGrowthData($productId)
    {
        $product = Product::where('id', $productId)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        $growthLogs = ProductGrowthLog::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'logs' => $growthLogs,
                'latest_log' => $growthLogs->first(),
                'product' => $product
            ]
        ]);
    }

    public function getCareData($productId)
    {
        $product = Product::where('id', $productId)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        $careLogs = ProductCareLog::where('product_id', $productId)
            ->orderBy('care_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'logs' => $careLogs,
                'latest_log' => $careLogs->first(),
                'watering_logs' => $careLogs->where('care_type', 'watering')->values(),
                'product' => $product
            ]
        ]);
    }
}