<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');

        $products = Product::where('is_sold', false)
            ->doesntHave('purchasers') 
            ->get();

        $myFavorites = [];
        if ($tab === 'mylist' && Auth::check()) {
            $myFavorites = Auth::user()->favorites()
                ->with('categories', 'conditions')
                ->doesntHave('purchasers') // 購入済み商品を除外
                ->get();
        }

        return view('index', compact('products', 'tab', 'myFavorites'));
    }


    public function favorite($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        if ($user->favorites()->where('product_id', $id)->exists()) {
            $user->favorites()->detach($id);
        } else {
            $user->favorites()->attach($id);
        }

        return back();
    }

    public function myFavorites()
    {
        dd('a');
        if (Auth::check()) {
            $products = Auth::user()->favorites()->with('categories', 'conditions')->get();

            return view('index', [
                'products' => $products,
                'tab' => 'mylist', 
            ]);
        }

        return redirect()->route('login')->with('status', 'マイリストを見るにはログインが必要です。');
    }



    public function showComments(Product $product)
    {
        $comments = $product->comments()->with('user')->get();
        return view('comments', compact('product', 'comments'));
    }

    public function postComment(Request $request, Product $product)
    {
        $request->validate(['content' => 'required|string|max:255']);

        $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('item.comments', $product);
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_image' => 'required|image',
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'category' => 'required|integer',
            'condition' => 'required|integer',
        ]);

        $path = $request->file('product_image')->store('product_images', 'public');

        $product = Product::create([
            'user_id' => Auth::id(),
            'product_photo_path' => "storage/$path",
            'name' => $validated['name'],
            'brand_name' => $validated['brand_name'] ?? '',
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        $product->categories()->attach($validated['category']);
        $product->conditions()->attach($validated['condition']);

        return redirect()->route('product.index')->with('status', '商品が正常に出品されました。');
    }

    public function show($id)
    {
        // 商品詳細を取得
        $product = Product::with('categories', 'conditions')->findOrFail($id);
        // ->withCount('likes', 'comments')


        return view('items', compact('product'));
    }

    public function edit(Product $product)
    {
        //
    }


    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->input('sort_by', 'relevance');
        $category_id = $request->input('category_id'); 
        $price_min = $request->input('price_min'); 
        $price_max = $request->input('price_max'); 

        $products = Product::query();

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('brand_name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($category_id) {
            $products->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        }

        if ($price_min) {
            $products->where('price', '>=', $price_min);
        }
        if ($price_max) {
            $products->where('price', '<=', $price_max);
        }

        $allowedSortOptions = ['price_asc', 'price_desc', 'newest', 'relevance'];
        if (in_array($sort_by, $allowedSortOptions)) {
            switch ($sort_by) {
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
            }
        }

        $products = $products->get();

        return view('index', compact('products', 'query', 'sort_by', 'category_id', 'price_min', 'price_max'));
    }
}
