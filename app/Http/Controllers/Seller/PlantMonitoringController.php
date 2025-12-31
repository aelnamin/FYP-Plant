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
    public function index()
    {
        $plantCategoryIds = [2, 4, 5]; // flowers, vegetables, herbs
        $products = Product::whereIn('category_id', $plantCategoryIds)->get();

        return view('sellers.plants.index', compact('products'));
    }

    public function storeGrowth(Request $request, Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }


        $validated = $request->validate([
            'growth_stage' => 'required|string',
            'height_cm' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        ProductGrowthLog::create([
            'product_id' => $product->id,
            'seller_id' => $seller->id,
            'growth_stage' => $validated['growth_stage'],
            'height_cm' => $validated['height_cm'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Growth log saved successfully'
        ]);
    }

    public function storeCare(Request $request, Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }

        $validated = $request->validate([
            'care_type' => 'required|string|in:watering,fertilizing,pruning,repotting,pest_control,disease_treatment,other',
            'description' => 'nullable|string|max:500',
            'care_date' => 'required|date|before_or_equal:today', // ADD THIS LINE
        ]);

        ProductCareLog::create([
            'product_id' => $product->id,
            'seller_id' => $seller->id,
            'care_type' => $validated['care_type'],
            'description' => $validated['description'] ?? null,
            'care_date' => $validated['care_date'], // ADD THIS LINE
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Care log saved successfully'
        ]);
    }


    public function getGrowthData(Product $product)
    {
        $user = Auth::user();

        // 1️⃣ Make sure user is logged in and is a seller
        if (!$user || $user->role !== 'seller') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only sellers can view growth data.'
            ], 403);
        }

        // 2️⃣ Check product ownership
        if ($product->seller_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }

        // 3️⃣ Fetch growth logs
        $growthLogs = ProductGrowthLog::where('product_id', $product->id)
            ->orderByDesc('created_at')
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

    public function getCareData(Product $product)
    {
        $user = Auth::user();

        // 1️⃣ Make sure user is logged in and is a seller
        if (!$user || $user->role !== 'seller') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only sellers can view care data.'
            ], 403);
        }

        // 2️⃣ Check product ownership
        if ($product->seller_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }

        // 3️⃣ Fetch care logs
        $careLogs = ProductCareLog::where('product_id', $product->id)
            ->orderByDesc('care_date')
            ->orderByDesc('created_at')
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
