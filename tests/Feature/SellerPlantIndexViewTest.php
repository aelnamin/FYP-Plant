<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SellerPlantIndexViewTest extends TestCase
{
    public function test_plant_index_renders_with_zero_one_and_multiple_products(): void
    {
        $seller = new Seller(['business_name' => 'Test Seller']);
        $seller->id = 999999;

        $user = new User(['name' => 'Test Seller', 'role' => 'seller']);
        $user->id = 999999;
        $user->setRelation('seller', $seller);
        $user->setRelation('sellerProfile', $seller);
        Auth::setUser($user);

        $products = collect([
            $this->makeProduct(1, 'Plant One'),
            $this->makeProduct(2, 'Plant Two'),
        ]);

        foreach ([collect(), $products->take(1), $products] as $productSet) {
            $html = view('sellers.plants.index', ['products' => $productSet])->render();

            $this->assertStringContainsString('Plant Growth &amp; Care Monitoring', $html);
            $this->assertStringNotContainsString('Undefined variable $product', $html);
        }
    }

    private function makeProduct(int $id, string $name): Product
    {
        $product = new Product([
            'product_name' => $name,
            'stock_quantity' => 5,
        ]);
        $product->id = $id;
        $product->setRelation('images', collect());

        return $product;
    }
}
