<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

#[Title('Products Page - TokOl')]
class ProductPage extends Component
{
    use LivewireAlert;

    use WithPagination;
    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $featured;
    #[Url]
    public $sale;
    #[Url]
    public $price =50000;
    #[Url]
    public $sort = 'latest';

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'added to cart!', [
            'position' => 'center',
            'timer' => 1500,
            'toast' => true,
           ]);
    }




    public function render()
    
    {
        $products = Product::query()->where('is_active', 1);

        if(!empty($this->selected_categories)){
            $products->whereIn('category_id', $this->selected_categories);
        }
        if(!empty($this->selected_brands)){
            $products->whereIn('brand_id', $this->selected_brands);
        }
        if($this->featured){
            $products->where('is_feature', 1);
        }
        if($this->sale){
            $products->where('on_sale', 1);
        }
        if($this->price){
            $products->where('on_sale', 1);
        }
        if($this->price){
            $products->whereBetween('price', [0, $this->price]);
        }
        if($this->sort == 'latest'){
            $products->latest();
        }
        if($this->sort == 'price'){
            $products->orderBy('price');
        }
        return view('livewire.product-page',[
            'products' => $products->paginate(6),
            'brands' => Brand::where('is_active', 1)->get(['id','name','slug']),
            'categories' => Category::where('is_active', 1)->get(['id','name','slug'])
        ]);
    }
}
