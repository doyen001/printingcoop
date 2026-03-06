<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShoppingCartsController;
use App\Http\Controllers\CheckoutsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MyAccountsController;
use App\Http\Controllers\MyOrdersController;
use App\Http\Controllers\WishlistsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BlogsController;

/*
|--------------------------------------------------------------------------
| Public Web Routes (replicate CI routing)
|--------------------------------------------------------------------------
| Routes match exact CI URL structure: Controller/method/param
| Default controller: Homes (CI routes.php line 52)
*/

// Home Page (default controller)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/Homes', [HomeController::class, 'index']);
Route::get('/Homes/index', [HomeController::class, 'index']);

// Home AJAX Routes for printer search
Route::prefix('Home')->group(function () {
    Route::post('/PrinterSeries', [HomeController::class, 'printerSeries']);
    Route::post('/PrinterModel', [HomeController::class, 'printerModel']);
});

// Authentication Routes (Logins controller)
Route::prefix('Logins')->group(function () {
    // Login page
    Route::get('/', [LoginsController::class, 'index'])->name('login');

    // Registration page
    Route::get('/register', [LoginsController::class, 'showRegister'])->name('register');

    // AJAX / auth actions
    Route::post('/checkLoginByAjax', [LoginsController::class, 'checkLoginByAjax']);
    Route::get('/emailVerification/{id}', [LoginsController::class, 'emailVerification']);
    Route::get('/forgotPassword', [LoginsController::class, 'forgotPassword']);
    Route::post('/sendOtp', [LoginsController::class, 'sendOtp']);
    Route::post('/resetPassword', [LoginsController::class, 'resetPassword']);
    Route::get('/logout', [LoginsController::class, 'logout'])->name('logout');
    Route::post('/checkMobileByAjax', [LoginsController::class, 'checkMobileByAjax']);
    Route::post('/signup', [LoginsController::class, 'signup']);
    Route::post('/preferred_customer_signup', [LoginsController::class, 'preferred_customer_signup']);
});

// Products Routes
Route::prefix('Products')->group(function () {
    Route::get('/', [ProductsController::class, 'index']);
    Route::get('/index', [ProductsController::class, 'index']);
    Route::get('/index/{menu_id?}/{category_id?}/{sub_category_id?}', [ProductsController::class, 'index']);
    Route::get('/view/{id}', [ProductsController::class, 'view']);
    Route::post('/addToCart', [ProductsController::class, 'addToCart']);
    Route::post('/addToWishlist', [ProductsController::class, 'addToWishlist']);
    Route::post('/calculatePrice', [ProductsController::class, 'calculatePrice']);
    Route::post('/emailSubscribe', [ProductsController::class, 'emailSubscribe']);
    Route::post('/uploadImage', [ProductsController::class, 'uploadImage']);
    Route::post('/updateCumment', [ProductsController::class, 'updateCumment']);
    Route::post('/deleteImage', [ProductsController::class, 'deleteImage']);
    Route::post('/ProviderPrice', [ProductsController::class, 'providerPrice']);
    Route::post('/calculate-price', [ProductsController::class, 'calculatePrice']);
    Route::post('/saveEstimate', [ProductsController::class, 'saveEstimate']);
    Route::post('/refreshCaptcha', [ProductsController::class, 'refreshCaptcha']);
    Route::post('/searchProduct', [ProductsController::class, 'searchProduct']);
});

// ShoppingCarts Routes
Route::prefix('ShoppingCarts')->group(function () {
    Route::get('/', [ShoppingCartsController::class, 'index']);
    Route::get('/index', [ShoppingCartsController::class, 'index']);
    Route::post('/addToCart', [ShoppingCartsController::class, 'addToCart']);
    Route::post('/updateCart', [ShoppingCartsController::class, 'updateCart']);
    Route::get('/removeCart/{rowid}', [ShoppingCartsController::class, 'removeCart']);
    Route::post('/applyCoupon', [ShoppingCartsController::class, 'applyCoupon']);
    Route::get('/removeCoupon', [ShoppingCartsController::class, 'removeCoupon']);
    Route::post('/updateCartItem', [ShoppingCartsController::class, 'updateCartItem']);
    Route::post('/removeCartItem', [ShoppingCartsController::class, 'removeCartItem']);
    Route::get('/getCartItemByAjax', [ShoppingCartsController::class, 'getCartItemByAjax']);
    Route::post('/saveImage', [ShoppingCartsController::class, 'saveImage']);
});

// Checkout Routes
Route::prefix('Checkouts')->group(function () {
    Route::get('/', [CheckoutsController::class, 'index']);
    Route::get('/index', [CheckoutsController::class, 'index']);
    Route::get('/index/{step}/{order_id?}', [CheckoutsController::class, 'index']);
    Route::post('/saveAddress', [CheckoutsController::class, 'saveAddress']);
    Route::post('/saveShipping', [CheckoutsController::class, 'saveShipping']);
    Route::post('/placeOrder', [CheckoutsController::class, 'placeOrder']);
});

// Payment Routes
Route::prefix('Payments')->group(function () {
    Route::post('/process', [\App\Http\Controllers\PaymentsController::class, 'processPayment']);
    Route::get('/paypal_success/{order_id}', [\App\Http\Controllers\PaymentsController::class, 'paypalSuccess']);
    Route::post('/paypal_success/{order_id}', [\App\Http\Controllers\PaymentsController::class, 'paypalSuccess']);
    Route::get('/paypal_cancel/{order_id}', [\App\Http\Controllers\PaymentsController::class, 'paypalCancel']);
    Route::post('/stripe', [\App\Http\Controllers\PaymentsController::class, 'processStripe']);
});

// My Account Routes
Route::prefix('MyAccounts')->group(function () {
    Route::get('/', [MyAccountsController::class, 'index']);
    Route::get('/index', [MyAccountsController::class, 'index']);
    Route::match(['get', 'post'], '/EditAccount', [MyAccountsController::class, 'EditAccount']);
    Route::get('/changePassword', [MyAccountsController::class, 'changePassword']);
    Route::post('/saveChangePassword', [MyAccountsController::class, 'saveChangePassword']);
    Route::post('/sendOtp', [MyAccountsController::class, 'sendOtp']);
    Route::get('/manageAddress', [MyAccountsController::class, 'manageAddress']);
    Route::match(['get', 'post'], '/addEditAddress/{id?}', [MyAccountsController::class, 'addEditAddress']);
    Route::get('/deleteAddress/{id}', [MyAccountsController::class, 'deleteAddress']);
    Route::get('/orderHistory', [MyAccountsController::class, 'orderHistory']);
    Route::get('/viewOrder/{id}', [MyAccountsController::class, 'viewOrder']);
    Route::get('/wishlist', [MyAccountsController::class, 'wishlist']);
    Route::get('/removeWishlist/{id}', [MyAccountsController::class, 'removeWishlist']);
    Route::get('/notification', [MyAccountsController::class, 'notification']);
    Route::get('/logout', [MyAccountsController::class, 'logout']);
    Route::get('/getStateDropDownListByAjax/{country_id}', [MyAccountsController::class, 'getStateDropDownListByAjax']);
    Route::get('/getCityDropDownListByAjax/{state_id}', [MyAccountsController::class, 'getCityDropDownListByAjax']);
});

// My Orders Routes
Route::prefix('MyOrders')->group(function () {
    Route::get('/', [MyOrdersController::class, 'index']);
    Route::get('/index', [MyOrdersController::class, 'index']);
    Route::get('/view/{id}', [MyOrdersController::class, 'view']);
    Route::get('/deleteOrder/{id}', [MyOrdersController::class, 'deleteOrder']);
    Route::post('/changeOrderStatus', [MyOrdersController::class, 'changeOrderStatus']);
    Route::get('/downloadOrderPdf/{id}/{type?}', [MyOrdersController::class, 'downloadOrderPdf']);
    Route::get('/download/{filePath}/{name}', [MyOrdersController::class, 'download'])->where('filePath', '.*');
    Route::get('/reorder/{id}', [MyOrdersController::class, 'reorder']);
    Route::get('/trackOrder/{id}', [MyOrdersController::class, 'trackOrder']);
});

// Wishlist Routes
Route::prefix('Wishlists')->group(function () {
    Route::get('/', [WishlistsController::class, 'index']);
    Route::get('/index', [WishlistsController::class, 'index']);
    Route::post('/addByAjax', [WishlistsController::class, 'addByAjax']);
    Route::post('/deleteWishlist', [WishlistsController::class, 'deleteWishlist']);
    Route::get('/remove/{id}', [WishlistsController::class, 'remove']);
    Route::get('/moveToCart/{id}', [WishlistsController::class, 'moveToCart']);
    Route::get('/getWishlistCount', [WishlistsController::class, 'getWishlistCount']);
    Route::get('/shareWishlist', [WishlistsController::class, 'shareWishlist']);
    Route::get('/clearWishlist', [WishlistsController::class, 'clearWishlist']);
});

// Categories Routes
Route::prefix('Categories')->group(function () {
    Route::get('/', [CategoriesController::class, 'index']);
    Route::get('/index', [CategoriesController::class, 'index']);
});

// About Us / Contact Us Routes
Route::get('/AboutUs', [AboutUsController::class, 'index']);
Route::get('/AboutUs/index', [AboutUsController::class, 'index']);
Route::get('/ContactUs', [ContactUsController::class, 'index']);
Route::get('/ContactUs/index', [ContactUsController::class, 'index']);

// Tickets Routes
Route::prefix('Tickets')->group(function () {
    Route::get('/', [\App\Http\Controllers\TicketsController::class, 'index']);
    Route::get('/index/{status?}', [\App\Http\Controllers\TicketsController::class, 'index']);
    Route::get('/getTickets/{status?}', [\App\Http\Controllers\TicketsController::class, 'getTickets']);
    Route::match(['get', 'post'], '/getChat/{ticket_id?}', [\App\Http\Controllers\TicketsController::class, 'getChat']);
    Route::get('/getLetestChat/{ticket_id}', [\App\Http\Controllers\TicketsController::class, 'getLetestChat']);
    Route::match(['get', 'post'], '/createTicket', [\App\Http\Controllers\TicketsController::class, 'createTicket']);
    Route::get('/deleteTicket/{id}', [\App\Http\Controllers\TicketsController::class, 'deleteTicket']);
});

// FAQ Routes
Route::get('/Faq', [\App\Http\Controllers\FaqController::class, 'index']);
Route::get('/Faq/index', [\App\Http\Controllers\FaqController::class, 'index']);

// Additional Pages Routes
Route::get('/Cooperative', [\App\Http\Controllers\CooperativeController::class, 'index']);
Route::get('/Cooperative/index', [\App\Http\Controllers\CooperativeController::class, 'index']);
Route::get('/Privacy', [\App\Http\Controllers\PrivacyController::class, 'index']);
Route::get('/Privacy/index', [\App\Http\Controllers\PrivacyController::class, 'index']);
Route::get('/TermsConditions', [\App\Http\Controllers\TermsConditionsController::class, 'index']);
Route::get('/TermsConditions/index', [\App\Http\Controllers\TermsConditionsController::class, 'index']);
Route::get('/PrefferedCustomer', [\App\Http\Controllers\PrefferedCustomerController::class, 'index']);
Route::get('/PrefferedCustomer/index', [\App\Http\Controllers\PrefferedCustomerController::class, 'index']);

// Pages Routes (CI routes.php line 59: Page/(:any) => Pages/index/$1)
Route::get('/Page/{slug}', [PagesController::class, 'index']);
Route::get('/Pages/index/{slug}', [PagesController::class, 'index']);
Route::get('/Pages/contactUs', [PagesController::class, 'contactUs']);
Route::get('/Pages/prefferedCustomer', [PagesController::class, 'prefferedCustomer']);
Route::match(['get', 'post'], '/Pages/estimate', [PagesController::class, 'estimate']);
Route::get('/Pages/estimate_submitted', [PagesController::class, 'estimateSubmitted'])->name('estimate.submitted');
Route::get('/Pages/faq', [PagesController::class, 'faq']);
Route::post('/Pages/saveContactUs', [PagesController::class, 'saveContactUs']);

// Blogs Routes (CI: Blogs controller)
Route::prefix('Blogs')->group(function () {
    Route::get('/', [BlogsController::class, 'index']);
    Route::get('/index', [BlogsController::class, 'index']);
    Route::get('/category/{blog_category_slug?}', [BlogsController::class, 'category']);
    Route::get('/search', [BlogsController::class, 'search']);
    Route::get('/singleview/{slug}', [BlogsController::class, 'singleview']);
});

// AJAX Routes
Route::prefix('Ajax')->group(function () {
    Route::get('/getCategoryDropDownListByAjax/{menu_id?}', [AjaxController::class, 'getCategoryDropDownListByAjax']);
    Route::get('/getSubCategoryDropDownListByAjax/{menu_id?}/{category_id?}', [AjaxController::class, 'getSubCategoryDropDownListByAjax']);
    Route::get('/getProductDropDownListByAjax/{menu_id?}', [AjaxController::class, 'getProductDropDownListByAjax']);
    Route::get('/removeProductImage/{product_id}/{image_id?}/{imageName?}', [AjaxController::class, 'removeProductImage']);
});

// Public AJAX Routes (for forms accessible without login)
Route::get('/getStateDropDownListByAjax/{country_id}', [MyAccountsController::class, 'getStateDropDownListByAjax']);
Route::get('/getCityDropDownListByAjax/{state_id}', [MyAccountsController::class, 'getCityDropDownListByAjax']);
