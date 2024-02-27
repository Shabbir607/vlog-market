<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Brand;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\jobsave;
use App\Models\blogpost;
use App\Models\BlogPostCategory;
use App\Models\ProductReview;
use App\Models\BlogPostTag;
use App\Models\Notification;
use App\Models\Marketplace;
use App\Models\Country;

use Auth;
use Ramsey\Uuid\Rfc4122\Validator;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function index(Request $request)
    {

        return redirect()->route($request->user()->role);
    }

    public function home()
    {
        // Replace with the actual country code for Pakistan
        $countryCode = ['PK', 'IN', 'AE'];

        $country = Country::where('code', $countryCode)->first();

        $categoryId = Category::where('title', 'classifieds')->value('id');

        // Fetch all marketplaces with related country and city
        $markets = Marketplace::with("country:id,name")->get();

        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'Sale Property')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();

        $featured = Product::where('status', 'active')->where('is_featured', 1)->orderBy('price', 'DESC')->limit(2)->get();
        $posts = Blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(8)->get();
        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();
        $categories = Category::where('status', 'active')->orderBy('title', 'ASC')->get();
        $subcategories = Category::where('status', 'active')->where('is_parent', 0)->get();

        return view('frontend.index')
            ->with('posts', $posts)
            ->with('product_lists', $products)
            ->with('categories', $categories)
            ->with('category_lists', $category)
            ->with('country', $country)
            ->with('filteredProducts',$filteredProducts)
            ->with('markets', $markets);

    }
    public function getCategories($country_id)
    {
        $parentCategories = Category::where('status', 'active')->where('is_parent', 1)->where('country_id', $country_id)->get();
        return response()->json($parentCategories);
    }

    public function getSubcategories($category_id)
    {
        $subcategories = Category::where('status', 'active')->where('parent_id', $category_id)->get();
        return response()->json($subcategories);
    }


    public function showMotors()
    {
        $product_detail = Product::getProductreview();
        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'motors')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        //dd($filteredProducts);

        $reviews = ProductReview::getAllReview();

        return view('frontend.pages.motor-product')->with('filteredProducts', $filteredProducts)->with('product_detail', $product_detail)->with('reviews', $reviews)->with('categoryId',$categoryId);
    }


    public function showSaleProperty()
    {
        $product_detail = Product::getProductreview();
        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'Sale Property')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        //dd($filteredProducts);
        $reviews = ProductReview::getAllReview();

        return view('frontend.pages.SaleProperty-product')->with('filteredProducts', $filteredProducts)->with('product_detail', $product_detail)->with('reviews', $reviews);
    }

    public function showClassifieds()
    {
        $product_detail = Product::getProductreview();
        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'classifieds')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        //dd($filteredProducts);
        $reviews = ProductReview::getAllReview();

        return view('frontend.pages.Classifieds-product')->with('filteredProducts', $filteredProducts)->with('product_detail', $product_detail)->with('reviews', $reviews);
    }

    public function showGarden_Furniture()
    {
        $product_detail = Product::getProductreview();
        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'Garden Furniture')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        //dd($filteredProducts);
        $reviews = ProductReview::getAllReview();

        return view('frontend.pages.Garden-Furniture-product')->with('filteredProducts', $filteredProducts)->with('product_detail', $product_detail)->with('reviews', $reviews);
    }

    public function showMobiles_Tablets()
    {
        $product_detail = Product::getProductreview();
        // Assuming 'category' is a column in the 'categories' table
        $categoryId = Category::where('title', 'Mobiles & Tablets')->value('id');

        // Assuming 'cat_id' is a column in the 'products' table
        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        //dd($filteredProducts);
        $reviews = ProductReview::getAllReview();

        return view('frontend.pages.Mobiles-Tablets-product')->with('filteredProducts', $filteredProducts)->with('product_detail', $product_detail)->with('reviews', $reviews);
    }

    public function community()
    {
        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->get();

        return view('frontend.pages.community')->with('rcnt_post', $rcnt_post);
    }

    public function notification_index(Request $request)
    {
        $notification = Auth()->user()->notifications()->where('id', $request->id)->first();
        return view('frontend.pages.notification')->with('notification', $notification);
    }

    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function productDetail($slug)
    {
        $product_detail = Product::getProductBySlug($slug);
        // dd($product_detail);
        return view('frontend.pages.product_detail')->with('product_detail', $product_detail);
    }

    public function productGrids()
    {
        $products = Product::query();
        $categories = Category::query();
        $market = Marketplace::query();


        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            //dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids);
            // return $products;
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);

            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(9);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-grids')->with('products', $products)->with('recent_products', $recent_products);
    }

    public function productLists()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids)->paginate;
            // return $products;
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(6);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-lists')->with('products', $products)->with('recent_products', $recent_products);
    }
    public function productList(Request $request)
    {

        // Replace with the actual country code for Pakistan
        $countryCode = ['PK', 'IN', 'AE'];

        $country = Country::where('code', $countryCode)->first();

        $categoryId = Category::where('title', 'classifieds')->value('id');

        // Fetch all marketplaces with related country and city
        $markets = Marketplace::with("country:id,name")->get();

        $featured = Product::where('status', 'active')->where('is_featured', 1)->orderBy('price', 'DESC')->limit(2)->get();
        $posts = Blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(8)->get();
        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();
        $categories = Category::where('status', 'active')->orderBy('title', 'ASC')->get();

        return view('frontend.pages.product-lists')
            ->with('posts', $posts)
            ->with('product_lists', $products)
            ->with('categories', $categories)
            ->with('category_lists', $category)
            ->with('country', $country)
            ->with('markets', $markets);
    }

    public function productFilter(Request $request)
    { // Replace with the actual country code for Pakistan
        $countryCode = ['PK', 'IN', 'AE'];


        $country = Country::where('code', $countryCode)->first();

        $categoryId = Category::where('title', 'classifieds')->value('id');

        // Fetch all marketplaces with related country and city
        $markets = Marketplace::with("country:id,name")->get();

        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();
        $categories = Category::where('status', 'active')->orderBy('title', 'ASC')->get();
        $post = PostCategory::getBlogByCategory($request->slug);


        return view('frontend.pages.product-grids')
            ->with('categories', $categories)
            ->with('category_lists', $category)
            ->with('country', $country)
            ->with('markets', $markets);

    }

    public function product_Filter(Request $request)
    {

        $countryCode = ['PK', 'IN', 'AE'];

        $country = Country::where('code', $countryCode)->first();

        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();
        $categories = Category::where('status', 'active')->orderBy('title', 'ASC')->get();
        // return $request->all();
        $categoryId = Category::where('title', 'classifieds')->value('id');
        $subcategories = Category::where('status', 'active')->where('is_parent', 0)->orderBy('title', 'ASC')->get();


        // Fetch all marketplaces with related country and city
        $markets = Marketplace::with("country:id,name")->get();

        $rcnt_Product = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $Product = Product::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orwhere('category', 'like', '%' . $request->search . '%')
            ->orWhere('price', 'like', $request->search . '%')
            ->orWhere('type', 'like', $request->search . '%')
            ->orWhere('marketplace', 'like', $request->search . '%')
            ->orWhere('salary', 'like', $request->search . '%')
            ->orderBy('id', 'DESC');

        return view('frontend.pages.product-filter')
            ->with('categories', $categories)
            ->with('category_lists', $category)
            ->with('subcategories', $subcategories)
            ->with('country', $country)
            ->with('products', $Product)
            ->with('markets', $markets) ;
//        if (request()->is('vlog-market.loc/product-grids')) {
//            return redirect()->route('product-grids', );
//        } else {
//            return redirect()->route('products-filter', );
//        }
    }

    public function motorProductSearch(Request $request)
    {
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->get();
        $categoryId = Category::where('title', 'Motors')->value('id');

        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        $products = Product::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('price', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate('9');
        return view('frontend.pages.motor-product-search')->with('products', $products)->with('filteredProducts', $filteredProducts);
    }

    public function mobileProductSearch(Request $request)
    {
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->get();
        $categoryId = Category::where('title', 'Mobiles & Tablets')->value('id');

        $filteredProducts = Product::where('cat_id', $categoryId)->get();
        $products = Product::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('price', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate('9');


        return view('frontend.pages.mobile-search')->with('products', $products)->with('recent_products', $filteredProducts);
    }

    public function productBrand(Request $request)
    {
        $products = Brand::getProductByBrand($request->slug);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        if (request()->is('vlog-market.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->products)->with('recent_products', $recent_products);
        }
    }

    public function productCat(Request $request)
    {
        $products = Category::getProductByCat($request->slug);

        // return $request->slug;
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        if (request()->is('vlog-market.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->products)->with('recent_products', $recent_products);
        }

    }
    public function productSubCat(Request $request, $category = null, $subcategory = null)
    {
        // Ensure $subcategory is obtained from the function parameters
        $subSlug = $subcategory ?? $request->sub_slug;

        // Retrieve products based on the subcategory
        $products = Category::getProductBySubCat($subSlug);

        // Check if products are found
        if ($products) {
            // Retrieve recent products
            $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

            // Determine the view based on the request URL
            $view = request()->is('vlog-market.loc/product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';

            // Pass products and recent_products to the appropriate view
            return view($view, [
                'products' => $products->sub_products ?? null,
                'recent_products' => $recent_products,
            ]);
        }
    }

    public function productsCat(Request $request,$category=null,$subcategory =null)
    {
        $products = Category::getProductBySubCat($request->slug);
        // return $products;
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();


        if (request()->is('vlog-market.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-grids')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        }

    }

    public function blog()
    {
        $post = blogpost::query();
        // dd($post);
        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = BlogPostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();

            return $cat_ids;
            $post->whereIn('post_cat_id', $cat_ids);
            // return $post;
        }
        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            // dd($slug);
            $tag_ids = BlogPostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // return $tag_ids;

            $post->where('post_tag_id', $tag_ids);
            // return $post;
        }

        if (!empty($_GET['show'])) {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);

        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }

    public function favourite()
    {
        $products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(8)->get();
        return view('frontend.pages.favourite')->with('products', $products);
    }
    public function help()
    {
        return view('frontend.pages.help');
    }
    public function faq()
    {
        return view('frontend.pages.faq');
    }
    public function blogDetail($slug)
    {
        $post = blogpost::getPostBySlug($slug);
        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // return $post;

        return view('frontend.pages.blog-detail')->with('post', $post)->with('recent_posts', $rcnt_post);
    }
    public function blogSearch(Request $request)
    {
        // return $request->all();
        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $posts = blogpost::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('quote', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts', $posts)->with('recent_posts', $rcnt_post);
    }
    public function productsSearch(Request $request)
    {
        //        dd($request->all());
        // return $request->all();
       $Product = Product::where("cat_id",$request->category)->get();

        $reviews = ProductReview::getAllReview();
        return view('frontend.pages.motor-product')->with('filteredProducts', $Product)->with('reviews',$reviews);
    }
    public function blogFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        // return $tagURL;
        // return $catURL;
        return redirect()->route('blog', $catURL . $tagURL);
    }
    public function blogByCategory(Request $request)
    {
        $post = BlogPostCategory::getBlogByCategory($request->slug);
        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post->post)->with('recent_posts', $rcnt_post);
    }
    public function blogByTag(Request $request)
    {
        // dd($request->slug);
        $post = blogpost::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post = blogpost::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }
    public function jobs()
    {
        $post = Post::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = PostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            return $cat_ids;
            $post->whereIn('post_cat_id', $cat_ids);
            // return $post;
        }

        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            // dd($slug);
            $tag_ids = PostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // return $tag_ids;
            $post->where('post_tag_id', $tag_ids);
            // return $post;
        }

        if (!empty($_GET['show'])) {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.job')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }
    public function jobsDetail($slug)
    {
        $post = Post::getPostBySlug($slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // return $post;
        return view('frontend.pages.job-detail')->with('post', $post)->with('recent_posts', $rcnt_post);
    }
    public function jobsSearch(Request $request)
    {


//        dd($request->search);
        // Base query for searching posts
        $posts  = Post::where('status', 'active')->
                where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('summary', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search. '%')
                    ->orWhere('slug', 'like', '%' .$request->search . '%')
                    ->orWhere('company', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search. '%')
                    ->orWhere('location', 'like', '%' .$request->search . '%')
                    ->orWhere('salary', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' .$request->search. '%')
            ->get();



        // Pass the retrieved data to the view
        return view('frontend.pages.job-search', compact('posts'));
    }





    public function jobsFilter(Request $request)
    {
        $data = $request->all();
        // return $data;

        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        // return $tagURL;
        // return $catURL;
        return redirect()->route('jobs', $catURL . $tagURL);
    }
    public function jobsByCategory(Request $request)
    {
        $post = PostCategory::getBlogByCategory($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.job')->with('posts', $post->post)->with('recent_posts', $rcnt_post);
    }
    public function jobsByTag(Request $request)
    {
        // dd($request->slug);
        $post = Post::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.job')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }
    //jobsApply
    public function Applyjobs($slug)
    {
        $post = Post::getPostBySlug($slug);
        return view('frontend.pages.job-apply')->with('post', $post);
    }
    public function store(Request $request)
    {
        if (!Auth::check()) {
            // Redirect to the login form
            return redirect()->route('login.form')->with('error', 'Please log in to apply.');
        }
        $request->validate([
            // 'name' => 'required|string|max:255',
            // 'date_of_birth' => 'required',
            // 'location' => 'required|string',
            // 'gender' => 'required|string',
            // 'nationality' => 'required|string',
            // 'education' => 'required|string',
            // 'career_level' => 'required|string',
            // 'experience' => 'required|string',
            // 'position' => 'required|string',
            // 'salary_expectation' => 'required|numeric',
            // 'commitment_level' => 'required|string',
            // 'visa_status' => 'required',
            // 'record_video' => 'required',
            // 'cv' => 'required|mimes:pdf,doc,docx|max:2048',
            // 'drop_note' => 'nullable|string',
        ]);

        // Handle file upload
        // $cvPath = $request->file('cv')->store('cv', 'public');

        // Create a new instance of JobApplication
        $job = new JobApplication();

        // Set values for the job application
        $job['user_id'] = $request->user()->id;
        $job->name = $request->name;
        $job->date_of_birth = $request->date_of_birth;
        $job->current_location = $request->current_location;
        $job->gender = $request->gender;
        $job->nationality = $request->nationality;
        $job->education = $request->education;
        $job->career_level = $request->career_level;
        $job->experience = $request->experience;
        $job->position = $request->position;
        $job->salary_expectation = $request->salary_expectation;
        $job->commitment_level = $request->commitment_level;
        $job->visa_status = $request->visa_status;
        $job->drop_note = $request->drop_note;
        // $job->record_video = $request->record_video;

        if ($request->hasFile('record_video')) {

            $videoPath = time() . '_' . $request->file('record_video')->getClientOriginalName();

            $request->file('record_video')->move(public_path('video/'), $videoPath);

            $job->record_video = $videoPath;
        }

        // $job->cv_path = $cvPath;
        if ($request->hasFile('cv')) {
            // Generate a unique filename for the CV
            $cvPath = time() . '_' . $request->file('cv')->getClientOriginalName();

            // Move the uploaded CV to the storage directory
            $request->file('cv')->move(public_path('pdfcv/'), $cvPath);

            // Save the CV path to the job application model
            $job->cv_path = $cvPath;
        }

        // Set the file path
        $job->application_number = 'APPL-' . strtoupper(Str::random(10));
        // Assuming user ID is stored in 'id' field

        // Save the job application to the database
        $job->save();

        return view('frontend.pages.jobs_forum_apply_successfully')->with('success', 'Application submitted successfully!');

    }
    public function saveApplication($slug)
    {
        $user = Auth::user();

        // Find the post by its slug
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            // Handle the case where the post is not found
            return redirect()->back()->with('error', 'Post not found.');
        }

        // Check if the application is already saved by the user
        if (!$user->savedJobs()->where('application_id', $post->id)->exists()) {
            // Save the application
            $jobSave = new JobSave([
                'user_id' => $user->id,
                'application_id' => $post->id,
            ]);


            $jobSave->save();

            // Redirect or return a response as needed
            return redirect()->back()->with('success', 'Application saved successfully.');
        }

        // Handle the case where the application is already saved
        return redirect()->back()->with('message', 'Application is already saved.');
    }
    // Login
    public function login()
    {

        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Successfully login');

            // Check if the user is authenticated and redirect to the appropriate route
            if (Auth::check()) {
                return redirect('/select-country');
            }
        } else {
            request()->session()->flash('error', 'Invalid email and password, please try again!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Logout successfully');
        return back();
    }

    public function register()
    {
        // dd("Register");
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'string|required|min:2',
            'email' => 'string|required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $data = $request->all();
        // dd($data);
        $check = $this->create($data);
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', 'Successfully registered');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Please try again!');
            return back();
        }
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ]);
    }

    // Reset password
    public function showResetForm()
    {
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);
            if (Newsletter::lastActionSucceeded()) {
                request()->session()->flash('success', 'Subscribed! Please check your email');
                return redirect()->route('home');
            } else {
                Newsletter::getLastError();
                return back()->with('error', 'Something went wrong! please try again');
            }
        } else {
            request()->session()->flash('error', 'Already Subscribed');
            return back();
        }
    }

}
