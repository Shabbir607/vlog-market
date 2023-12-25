<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MarketplacesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\JobApplyController;
//country selection
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PakistanController;
use App\Http\Controllers\IndiaController;
use App\Http\Controllers\UAEController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (){
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

//frontend section
Auth::routes(['register' => false]);

Route::get('user/login', [FrontendController::class, 'login'])->name('login.form');
Route::post('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit');
Route::get('user/logout', [FrontendController::class, 'logout'])->name('user.logout');

Route::get('user/register', [FrontendController::class, 'register'])->name('register.form');
Route::post('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit');
// Reset password0
// Route::post('password-reset', [FrontendController::class, 'showResetForm'])->name('password.reset');
// Socialite
Route::get('login/{provider}/', [LoginController::class, 'redirect'])->name('login.redirect');
Route::get('login/{provider}/callback/', [LoginController::class, 'Callback'])->name('login.callback');

// Auth::routes();
// Route::get('/admin', 'AdminController@index')->name('admin')->middleware('admin');

Auth::routes();
// Frontend Routes
Route::get('user/home', [FrontendController::class, 'home'])->name('user/home');
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail');
Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat');
Route::get('/product-brand/{slug}', [FrontendController::class, 'productBrand'])->name('product-brand');
Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');
Route::match(['get', 'post'], '/filter', [FrontendController::class, 'productFilter'])->name('shop.filter');
 

// Order Track
Route::get('/product/track', [OrderController::class, 'orderTrack'])->name('order.track');
Route::post('product/track/order', [OrderController::class, 'productTrackOrder'])->name('product.track.order');
// Blog
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog-detail/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
Route::get('/blog/search', [FrontendController::class, 'blogSearch'])->name('blog.search');
Route::post('/blog/filter', [FrontendController::class, 'blogFilter'])->name('blog.filter');
Route::get('blog-cat/{slug}', [FrontendController::class, 'blogByCategory'])->name('blog.category');
Route::get('blog-tag/{slug}', [FrontendController::class, 'blogByTag'])->name('blog.tag');


Route::group(['as'=>'admin.','prefix' => 'admin','namespace'=>'Admin','middleware'=>['auth','admin']], function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    

});

Route::group(['as'=>'superadmin','prefix'=>'admin','namespace'=>'SuperAdmin','middleware'=>['auth','superadmin']],function(){
    Route::get('dashboard',[SuperAdminController::class,'index'])->name('dashboard');

});

//profile Routes
Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile');
Route::post('/profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');
Route::get('settings', [AdminController::class, 'settings'])->name('settings');
Route::post('setting/update', [AdminController::class, 'settingsUpdate'])->name('settings.update');

// Wishlist
Route::get('/wishlist', function () {
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}', [WishlistController::class, 'wishlistDelete'])->name('wishlist-delete');
// Cart section
Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');
// Notification
Route::get('/notification/{id}', [NotificationController::class, 'show'])->name('admin.notification');
Route::get('/notifications', [NotificationController::class, 'index'])->name('all.notification');
Route::delete('/notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
// Password Change
Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');
// Product
Route::get('/product' ,[ProductController::class, 'index'])->name('/product');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product.create' ,[ProductController::class, 'create'])->name('/product.create');
Route::post('/product.store' ,[ProductController::class, 'store'])->name('/product.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
// category
Route::get('/category' ,[CategoryController::class, 'index'])->name('/category');
Route::get('/category.create' ,[CategoryController::class, 'create'])->name('/category.create');
Route::post('/category.store' ,[CategoryController::class, 'store'])->name('/category.store');
Route::get('/category_edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
//brand
Route::get('/brand' ,[BrandController::class, 'index'])->name('/brand');
Route::get('/brand.create' ,[BrandController::class, 'create'])->name('/brand.create');
Route::post('/brand.store' ,[BrandController::class, 'store'])->name('/brand.store');
Route::get('/brand/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
Route::post('/brand/{id}', [BrandController::class, 'update'])->name('brand.update');
Route::delete('/brand/{id}/destroy', [BrandController::class, 'destroy'])->name('brand.destroy');
// Shipping
Route::get('/shipping' ,[ShippingController::class, 'index'])->name('/shipping');
Route::get('/shipping.create' ,[ShippingController::class, 'create'])->name('/shipping.create');
Route::post('/shipping.store' ,[ShippingController::class, 'store'])->name('/shipping.store');
Route::get('/shipping/{id}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
Route::post('/shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
Route::delete('/shipping/{id}/destroy', [ShippingController::class, 'destroy'])->name('shipping.destroy');
//order
Route::get('/order' ,[OrderController::class, 'index'])->name('/order');
Route::get('/order.create' ,[OrderController::class, 'create'])->name('/order.create');
Route::post('/order.store' ,[OrderController::class, 'store'])->name('order.store');
Route::get('/order/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
Route::post('/order/{id}', [OrderController::class, 'update'])->name('order.update');
Route::get('/order/{id}/show', [OrderController::class, 'show'])->name('order.show');
Route::get('/order/{id}/pdf', [OrderController::class, 'pdf'])->name('order.pdf');
Route::delete('/order/{id}/destroy', [OrderController::class, 'destroy'])->name('order.destroy');
// Payment
Route::get('payment', [PayPalController::class, 'payment'])->name('payment');
Route::get('cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
Route::get('payment/success', [PayPalController::class, 'success'])->name('payment.success');
// Route::get('payment',[PayPalController::class,'payment'])->name('payment');
//Product Review
Route::get('/review' ,[ProductReviewController::class, 'index'])->name('/review');
Route::get('/review.create' ,[ProductReviewController::class, 'create'])->name('/review.create');
Route::post('/order.store' ,[ProductReviewController::class, 'store'])->name('review.store');
Route::get('/review/{id}/edit', [ProductReviewController::class, 'edit'])->name('review.edit');
Route::post('/review/{id}', [ProductReviewController::class, 'update'])->name('review.update');
Route::get('/review/{id}/show', [ProductReviewController::class, 'show'])->name('review.show');
Route::delete('/review/{id}/destroy', [ProductReviewController::class, 'destroy'])->name('review.destroy');
//Product Review
Route::get('/review' ,[ProductReviewController::class, 'index'])->name('/review');
Route::get('/review.create' ,[ProductReviewController::class, 'create'])->name('/review.create');
Route::post('/order.store' ,[ProductReviewController::class, 'store'])->name('review.store');
Route::get('/review/{id}/edit', [ProductReviewController::class, 'edit'])->name('review.edit');
Route::post('/review/{id}', [ProductReviewController::class, 'update'])->name('review.update');
Route::get('/review/{id}/show', [ProductReviewController::class, 'show'])->name('review.show');
Route::delete('/review/{id}/destroy', [ProductReviewController::class, 'destroy'])->name('review.destroy');
//users
Route::get('/users' ,[UsersController::class, 'index'])->name('/users');
Route::get('/users.create' ,[UsersController::class, 'create'])->name('/users.create');
Route::post('/users.store' ,[UsersController::class, 'store'])->name('/users.store');
Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('/users.edit');
Route::post('/users{id}', [UsersController::class, 'update'])->name('/users.update');
Route::delete('/users/{id}/destroy', [UsersController::class, 'destroy'])->name('/users.destroy');
//marketplaces
Route::get('/market' ,[MarketplacesController::class, 'indexPK'])->name('/market');
Route::get('/market.create' ,[MarketplacesController::class, 'create'])->name('/market.create');
Route::post('/market.store' ,[MarketplacesController::class, 'store'])->name('/market.store');
Route::get('/market/{id}/edit', [MarketplacesController::class, 'edit'])->name('market.edit');
Route::post('/market/{id}', [MarketplacesController::class, 'update'])->name('market.update');
Route::delete('/market/{id}/destroy', [MarketplacesController::class, 'destroy'])->name('market.destroy');
//post
Route::get('/post' ,[PostController::class, 'index'])->name('/post');
Route::get('/post.create' ,[PostController::class, 'create'])->name('/post.create');
Route::post('/post.store' ,[PostController::class, 'store'])->name('/post.store');
Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('/post.edit');
Route::post('/post/{id}', [PostController::class, 'update'])->name('/post.update');
Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('/post.destroy');

//postCategary
Route::get('/post-category' ,[PostCategoryController::class, 'index'])->name('/post-category');
Route::get('/post-category.create' ,[PostCategoryController::class, 'create'])->name('/post-category.create');
Route::post('/post-category.store' ,[PostCategoryController::class, 'store'])->name('/post-category.store');
Route::get('/post-category/{id}/edit', [PostCategoryController::class, 'edit'])->name('post-category.edit');
Route::post('/post-category/{id}', [PostCategoryController::class, 'update'])->name('post-category.update');
Route::delete('/post-category/{id}/destroy', [PostCategoryController::class, 'destroy'])->name('post-category.destroy');
// Ajax for sub category

 Route::match(['get', 'post'], 'category/{id}/child', 'PostCategoryController@yourMethod');


//tags
Route::get('/post-tag' ,[PostTagController::class, 'index'])->name('/post-tag');
Route::get('/post-tag.create' ,[PostTagController::class, 'create'])->name('/post-tag.create');
Route::post('/post-tag.store' ,[PostTagController::class, 'store'])->name('/post-tag.store');
Route::get('/post-tag/{id}/edit', [PostTagController::class, 'edit'])->name('/post-tag.edit');
Route::post('/post-tag/{id}', [PostTagController::class, 'update'])->name('/post-tag.update');
Route::delete('/post-tag/{id}/destroy', [PostTagController::class, 'destroy'])->name('/post-tag.destroy');

//post Comment
Route::get('/comment' ,[PostCommentController::class, 'index'])->name('/comment');
Route::get('/comment.create' ,[PostCommentController::class, 'create'])->name('/comment.create');
Route::post('/comment.store' ,[PostCommentController::class, 'store'])->name('/comment.store');
Route::get('/comment/{id}/edit', [PostCommentController::class, 'edit'])->name('comment.edit');
Route::post('/comment/{id}', [PostCommentController::class, 'update'])->name('comment.update');
Route::delete('/comment/{id}/destroy', [PostCommentController::class, 'destroy'])->name('comment.destroy');

//order
Route::get('/jobapply' ,[JobApplyController::class, 'index'])->name('/jobapply');
Route::get('/jobapply.create' ,[JobApplyController::class, 'create'])->name('/jobapply.create');
Route::post('/jobapply.store' ,[JobApplyController::class, 'store'])->name('jobapply.store');
Route::get('/jobapply/{id}/edit', [JobApplyController::class, 'edit'])->name('jobapply.edit');
Route::post('/order/{id}', [JobApplyController::class, 'update'])->name('jobapply.update');
Route::get('/jobapply/{id}/show', [JobApplyController::class, 'show'])->name('jobapply.show');
Route::delete('/jobapply/{id}/destroy', [JobApplyController::class, 'destroy'])->name('jobapply.destroy');

// Jobs
Route::get('/jobs', [FrontendController::class, 'jobs'])->name('jobs');
Route::get('/jobs-detail/{slug}', [FrontendController::class, 'jobsDetail'])->name('jobs.detail');
Route::get('/jobs/search', [FrontendController::class, 'jobsSearch'])->name('jobs.search');
Route::post('/jobs/filter', [FrontendController::class, 'jobsFilter'])->name('jobs.filter');
Route::get('jobs-cat/{slug}', [FrontendController::class, 'jobsByCategory'])->name('jobs.category');
Route::get('jobs-tag/{slug}', [FrontendController::class, 'jobsByTag'])->name('jobs.tag');

// Jobs Apply
Route::get('/jobs-apply/{slug}', [FrontendController::class, 'Applyjobs'])->name('jobs.apply');
Route::post('job.application.store', [FrontendController::class, 'store'])->name('job.application.store');
// Post Comment
Route::post('post/{slug}/comment', [PostCommentController::class, 'store'])->name('post-comment.store');
Route::resource('/comment', 'PostCommentController');
// NewsLetter
Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');

// User section start

Route::group(['prefix' => '/user', 'middleware' => ['user']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('/home');

    // Profile
    Route::get('/profile', [HomeController::class, 'profile'])->name('user-profile');
    Route::post('/profile/{id}', [HomeController::class, 'profileUpdate'])->name('user-profile-update');
    //  Order
    Route::get('/order', "HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}', "HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}', [HomeController::class, 'userOrderDelete'])->name('user.order.delete');
    // Product Review
    Route::get('/user-review', [HomeController::class, 'productReviewIndex'])->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}', [HomeController::class, 'productReviewDelete'])->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}', [HomeController::class, 'productReviewEdit'])->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}', [HomeController::class, 'productReviewUpdate'])->name('user.productreview.update');

    // Post comment
    Route::get('user-post/comment', [HomeController::class, 'userComment'])->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}', [HomeController::class, 'userCommentDelete'])->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}', [HomeController::class, 'userCommentEdit'])->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}', [HomeController::class, 'userCommentUpdate'])->name('user.post-comment.update');

    

});

// Password Change
// Route::get('change-password', [HomeController::class, 'changePassword'])->name('user.change.password.form');
// Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');

//Country selection 
// Replace YourController with the actual name of your controller

// Route::get('/select-country', [CountryController::class, 'selectCountry'])->name('selectCountry');
Route::post('/post-country', [CountryController::class, 'postCountry'])->name('postCountry');

Route::match(['get', 'post'], '/select-country', [CountryController::class, 'selectCountry']);
Route::get('/{countryCode}/cities', [CountryController::class, 'showCities'])->name('showCities');
Route::post('/select-city', [CountryController::class, 'selectCity'])->name('selectCity');
Route::get('pakistan-route', [CountryController::class, 'showPakistan'])->name('pakistan-route');
Route::get('india-route', [CountryController::class, 'showIndia'])->name('india-route');
Route::get('uae-route', [CountryController::class, 'showUae'])->name('uae-route');

// Route::get('/pakistan-route', [CountryController::class, 'showPakistan'])->name('pakistan-route');
