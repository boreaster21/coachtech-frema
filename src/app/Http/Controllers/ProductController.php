<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');

        // おすすめ商品の取得（購入済みの商品を除外）
        $products = Product::where('is_sold', false)
            ->doesntHave('purchasers') // 購入者がいない商品を取得
            ->get();

        // マイリスト用のお気に入り商品を取得
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
            // ユーザーのお気に入り商品を取得
            $products = Auth::user()->favorites()->with('categories', 'conditions')->get();

            return view('index', [
                'products' => $products,
                'tab' => 'mylist', // 現在のタブを明示
            ]);
        }

        // 未ログインの場合リダイレクト
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('status', '出品するにはログインが必要です。');
        }
        
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('status', '出品するにはログインが必要です。');
        }
        // バリデーション
        $request->validate([
            'product_image' => 'required|image',
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'category' => 'required|integer',
            'condition' => 'required|integer',
        ]);

        // 画像のアップロード処理
        $path = $request->file('product_image')->store('product_images', 'public');

        // 商品の登録
        $product = Product::create([
            'user_id' => Auth::id(),
            'product_photo_path' => "storage/$path",
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // 中間テーブルにカテゴリーと状態を保存
        $product->categories()->attach($request->category);
        $product->conditions()->attach($request->condition);

        return redirect()->route('product.index')->with('status', '商品が正常に出品されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // 商品詳細を取得
        $product = Product::with('categories', 'conditions')->findOrFail($id);
        // ->withCount('likes', 'comments')
        

        return view('items', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->input('sort_by', 'relevance');
        $category_id = $request->input('category_id'); // カテゴリフィルタ
        $price_min = $request->input('price_min'); // 価格フィルタ(最小値)
        $price_max = $request->input('price_max'); // 価格フィルタ(最大値)

        $products = Product::query();

        // キーワード検索
        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('brand_name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        // カテゴリフィルタ
        if ($category_id) {
            $products->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        }

        // 価格フィルタ
        if ($price_min) {
            $products->where('price', '>=', $price_min);
        }
        if ($price_max) {
            $products->where('price', '<=', $price_max);
        }

        // 並び替え
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
