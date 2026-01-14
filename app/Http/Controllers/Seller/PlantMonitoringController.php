<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductGrowthLog;
use App\Models\ProductCareLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PlantMonitoringController extends Controller
{
    public function index()
    {
        $seller = Auth::user()->seller;

        // Safety check (optional but good practice)
        if (!$seller) {
            abort(403);
        }

        $plantCategoryIds = [2, 4, 5]; // flowers, vegetables, herbs

        $products = Product::where('seller_id', $seller->id)
            ->whereIn('category_id', $plantCategoryIds)
            ->with(['growthLogs', 'careLogs']) // eager load related logs
            ->get();

        return view('sellers.plants.index', compact('products'));
    }


    public function storeGrowth(Request $request, Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'growth_stage' => 'required|string',
            'height_cm' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $growthLog = ProductGrowthLog::create([
            'product_id' => $product->id,
            'seller_id' => $seller->id,
            'growth_stage' => $validated['growth_stage'],
            'height_cm' => $validated['height_cm'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // 🔥 Sync latest data to product
        $product->update([
            'current_stage' => $validated['growth_stage'],
            'last_height_cm' => $validated['height_cm'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Growth log saved',
            'data' => $growthLog
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
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }

        $growthLogs = ProductGrowthLog::where('product_id', $product->id)
            ->where('seller_id', $seller->id) // ✅ extra safety
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
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not own this product.'
            ], 403);
        }

        $careLogs = ProductCareLog::where('product_id', $product->id)
            ->where('seller_id', $seller->id) // ✅ extra safety
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



    public function show(Product $product)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'seller' || $product->seller_id !== $user->seller->id) {
            abort(403);
        }

        // Latest growth log
        $latestGrowth = ProductGrowthLog::where('product_id', $product->id)
            ->latest()
            ->first();

        // Latest care log
        $latestCare = ProductCareLog::where('product_id', $product->id)
            ->latest('care_date')
            ->first();

        // Growth progress calculation
        $stages = ['seedling', 'vegetative', 'flowering', 'mature'];
        $currentStage = strtolower($product->current_stage ?? '');

        $currentIndex = array_search($currentStage, $stages);
        $growthProgress = $currentIndex !== false
            ? round((($currentIndex + 1) / count($stages)) * 100)
            : 0;

        // Recent activities (merge growth + care)
        $recentActivities = collect();

        if ($latestGrowth) {
            $recentActivities->push((object) [
                'icon' => 'fa-seedling',
                'title' => 'Growth Updated',
                'description' => ucfirst($latestGrowth->growth_stage) .
                    ($latestGrowth->height_cm ? " ({$latestGrowth->height_cm} cm)" : ''),
                'created_at' => $latestGrowth->created_at
            ]);
        }

        if ($latestCare) {
            $recentActivities->push((object) [
                'icon' => 'fa-tint',
                'title' => ucfirst(str_replace('_', ' ', $latestCare->care_type)),
                'description' => $latestCare->description ?? 'Care activity recorded',
                'created_at' => $latestCare->created_at
            ]);
        }

        return view('sellers.plants.show', compact(
            'product',
            'latestGrowth',
            'latestCare',
            'growthProgress',
            'recentActivities'
        ));
    }

    public function careReport(Product $product)
    {
        $seller = auth()->user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403);
        }

        // Fetch growth and care logs
        $growthLogs = ProductGrowthLog::where('product_id', $product->id)
            ->where('seller_id', $seller->id)
            ->orderBy('created_at')
            ->get();

        $careLogs = ProductCareLog::where('product_id', $product->id)
            ->where('seller_id', $seller->id)
            ->orderBy('care_date')
            ->get();

        $latestGrowth = $growthLogs->last();

        // Generate PDF
        $pdf = Pdf::loadView('sellers.plants.care-report', compact(
            'product',
            'seller',
            'growthLogs',
            'careLogs',
            'latestGrowth'
        ));

        // Stream the PDF directly in browser
        return $pdf->stream("care-report-{$product->id}.pdf");
    }
}
