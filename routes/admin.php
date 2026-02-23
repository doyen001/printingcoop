<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProductProviderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AjaxController;

/*
|--------------------------------------------------------------------------
| Admin Routes (replicate CI admin routing)
|--------------------------------------------------------------------------
| CI routes.php line 56: pcoopadmin => admin/Logins
| CI routes.php line 57: pcoopadmin/reset-password/(:any) => admin/Logins/resetPassword/$1
| CI routes.php line 58: pcoopadmin/forgot-password => admin/Logins/forgotPassword
*/

// Admin Authentication Routes (CI: pcoopadmin)
Route::prefix('pcoopadmin')->group(function () {
    Route::match(['get', 'post'], '/', [LoginController::class, 'index'])->name('admin.login');
    Route::match(['get', 'post'], '/forgot-password', [LoginController::class, 'forgotPassword'])->name('admin.forgot-password');
    Route::match(['get', 'post'], '/reset-password/{id}', [LoginController::class, 'resetPassword'])->name('admin.reset-password');
});

// Protected Admin Routes (require authentication)
Route::prefix('admin')->middleware(['web', 'admin.auth'])->group(function () {
    
    // Dashboard Routes
    Route::prefix('Dashboards')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/index', [DashboardController::class, 'index']);
        Route::get('/logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout']);
    });
    
    // Search Route (CI: Products->searchProduct) - Within admin auth group
    Route::match(['get', 'post'], '/Products/searchProduct', [ProductsController::class, 'searchProduct'])->name('admin.products.search');
    
    // Accounts Routes
    Route::prefix('Accounts')->middleware(['admin.permissions'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AccountsController::class, 'index'])->name('admin.accounts.index');
        Route::get('/index', [\App\Http\Controllers\Admin\AccountsController::class, 'index'])->name('admin.accounts.index.ci');
        Route::match(['get', 'post'], '/changePassword', [\App\Http\Controllers\Admin\AccountsController::class, 'changePassword'])->name('admin.accounts.changePassword');
        Route::get('/logout', [\App\Http\Controllers\Admin\AccountsController::class, 'logout'])->name('admin.accounts.logout');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\AccountsController::class, 'addEdit'])->name('admin.accounts.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\AccountsController::class, 'delete'])->name('admin.accounts.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\AccountsController::class, 'activeInactive'])->name('admin.accounts.toggleStatus');
        Route::get('/{status?}', [\App\Http\Controllers\Admin\AccountsController::class, 'index'])->name('admin.accounts.index.status');
    });
    
    // Products Routes
    Route::prefix('Products')->name('admin.products.')->middleware(['admin.permissions'])->group(function () {
        // Basic CRUD Routes
        Route::match(['get', 'post'], '/', [ProductsController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/index/{product_id?}/{order?}', [ProductsController::class, 'index'])->name('index.filtered');
        Route::match(['get', 'post'], '/addEdit/{id?}', [ProductsController::class, 'addEdit'])->name('addEdit');
        Route::get('/view/{id}', [ProductsController::class, 'view'])->name('view');
        Route::get('/viewProduct/{id}', [ProductsController::class, 'view'])->name('viewProduct');
        Route::get('/deleteProduct/{id}', [ProductsController::class, 'deleteProduct'])->name('delete');
        Route::get('/delete/{id}', [ProductsController::class, 'delete'])->name('delete.alt');
        Route::get('/activeInactive/{id}/{status}', [ProductsController::class, 'activeInactive'])->name('activeInactive');
        
        // Update Print Auto (CI: Products->updatePrintAuto)
        Route::get('/updatePrintAuto', [ProductsController::class, 'updatePrintAuto'])->name('updatePrintAuto');
        
        // Auto Attribute Add (CI: Products->AutoAttributeAdd)
        Route::match(['get', 'post'], '/AutoAttributeAdd/{product_id?}/{id?}', [ProductsController::class, 'autoAttributeAdd'])->name('autoAttributeAdd');
        
        // Auto Attribute Item Add (CI: Products->autoAttributeItemAdd)
        Route::match(['get', 'post'], '/AutoAttributeItemAdd/{product_id}/{attribute_id}/{id?}', [ProductsController::class, 'autoAttributeItemAdd'])->name('autoAttributeItemAdd');
        
        // Auto Size Add (CI: Products->AutoSizeAdd)
        Route::match(['get', 'post'], '/AutoSizeAdd/{product_id}/{id?}', [ProductsController::class, 'autoSizeAdd'])->name('autoSizeAdd');
        
        // Product Attribute Create (CI: Products->ProductAttributeCreate)
        Route::post('/ProductAttributeCreate/{product_id}', [ProductsController::class, 'ProductAttributeCreate'])->name('ProductAttributeCreate');
        
        // Product Attribute Items (CI: Products->ProductAttributeItems)
        Route::match(['get', 'post'], '/ProductAttributeItems/{product_id}/{attribute_id?}', [ProductsController::class, 'ProductAttributeItems'])->name('ProductAttributeItems');
        
        // Utility Routes
        Route::post('/deleteImage/{id}', [ProductsController::class, 'deleteImage'])->name('deleteImage');
        Route::post('/uploadImage', [ProductsController::class, 'uploadImage'])->name('uploadImage');
        Route::post('/bulkAction', [ProductsController::class, 'bulkAction'])->name('bulkAction');
        Route::get('/ProductCopy/{id}', [ProductsController::class, 'copy'])->name('copy');
        Route::post('/deleteAllProduct', [ProductsController::class, 'deleteAllProduct'])->name('deleteAll');
        
        // Product Attributes Management
        Route::get('/SetMultipleAttributes/{id}', [ProductsController::class, 'SetMultipleAttributes'])->name('attributes.index');
        Route::match(['get', 'post'], '/ProductAttributes/{product_id}', [ProductsController::class, 'ProductAttributes'])->name('attributes.list');
        
        // Multiple Attributes Management (CI: MultipleAttributes)
        Route::get('/multipleAttributes', [ProductsController::class, 'multipleAttributes'])->name('multipleAttributes.index');
        Route::match(['get', 'post'], '/multipleAttributes/addEdit/{id?}', [ProductsController::class, 'multipleAttributesAddEdit'])->name('multipleAttributes.addEdit');
        
        // Quantity Management
        Route::match(['get', 'post'], '/AddEditProductQuantity/{product_id}/{id?}', [ProductsController::class, 'AddEditProductQuantity'])->name('quantity.addEdit');
        Route::get('/deleteProductQuantity/{product_id}/{id}', [ProductsController::class, 'deleteProductQuantity'])->name('quantity.delete');
        
        // Global Quantity Management (CI: MultipleAttributes->quantity)
        Route::get('/productQuantity', [ProductsController::class, 'productQuantity'])->name('productQuantity.index');
        Route::match(['get', 'post'], '/productQuantity/addEdit/{id?}', [ProductsController::class, 'productQuantityAddEdit'])->name('productQuantity.addEdit');
        
        // Size Management (Global sizes like CI MultipleAttributes)
        Route::match(['get', 'post'], '/AddEditProductSize/{product_id}/{quantity_id}/{id?}', [ProductsController::class, 'AddEditProductSize'])->name('size.addEdit');
        Route::get('/deleteProductSize/{product_id}/{quantity_id}/{id}', [ProductsController::class, 'deleteProductSize'])->name('size.delete');
        
        // Size Management (Global sizes like CI MultipleAttributes)
        Route::get('/sizes', [ProductsController::class, 'sizes'])->name('sizes.index');
        Route::match(['get', 'post'], '/sizes/addEdit/{id?}', [ProductsController::class, 'sizesAddEdit'])->name('sizes.addEdit');
        Route::get('/sizes/delete/{id}', [ProductsController::class, 'sizesDelete'])->name('sizes.delete');
        Route::get('/sizes/toggleStatus/{id}/{status}', [ProductsController::class, 'sizesToggleStatus'])->name('sizes.toggleStatus');
        
        // Size Options Management (CI: Products->sizeOptions)
        Route::get('/sizeOptions/{type}', [ProductsController::class, 'sizeOptions'])->name('sizeOptions');
        Route::match(['get', 'post'], '/sizeOptions/addEdit/{id}/{type}', [ProductsController::class, 'sizeOptionsAddEdit'])->name('sizeOptions.addEdit');
        Route::get('/sizeOptions/delete/{id}/{type}', [ProductsController::class, 'sizeOptionsDelete'])->name('sizeOptions.delete');
        Route::get('/sizeOptions/toggleStatus/{id}/{status}/{type}', [ProductsController::class, 'sizeOptionsToggleStatus'])->name('sizeOptions.toggleStatus');
        
        // Estimates Management (CI: Products->estimates)
        Route::get('/estimates', [ProductsController::class, 'estimates'])->name('estimates.index');
        Route::get('/estimates/view/{id}', [ProductsController::class, 'estimatesView'])->name('estimates.view');
        Route::get('/estimates/delete/{id}', [ProductsController::class, 'estimatesDelete'])->name('estimates.delete');
        
        // Single Attributes Management (CI: SingleAttributes)
        Route::get('/singleAttributes', [ProductsController::class, 'singleAttributes'])->name('singleAttributes.index');
        Route::match(['get', 'post'], '/singleAttributes/addEdit/{id?}', [ProductsController::class, 'singleAttributesAddEdit'])->name('singleAttributes.addEdit');
        Route::get('/singleAttributes/delete/{id}', [ProductsController::class, 'singleAttributesDelete'])->name('singleAttributes.delete');
        Route::get('/singleAttributes/toggleStatus/{id}/{status}', [ProductsController::class, 'singleAttributesToggleStatus'])->name('singleAttributes.toggleStatus');
        
        // Attribute Management
        Route::match(['get', 'post'], '/AddEditProductAttribute/{product_id}/{quantity_id}/{size_id}/{attribute_id}/{id?}', [ProductsController::class, 'AddEditProductAttribute'])->name('attribute.addEdit');
        Route::get('/deleteProductMultipalAttribute/{id}', [ProductsController::class, 'deleteProductMultipalAttribute'])->name('attribute.delete');
        
        // AJAX Helper Routes for Dynamic Content
        Route::get('/get-quantity-options', [ProductsController::class, 'getQuantityOptions'])->name('ajax.quantities');
        Route::get('/get-size-options/{product_id}/{quantity_id}', [ProductsController::class, 'getSizeOptions'])->name('ajax.sizes');
        Route::get('/get-attribute-options', [ProductsController::class, 'getAttributeOptions'])->name('ajax.attributes');
        
        // Provider Integration Routes (CI: Products->Provider, ProviderProducts, etc.)
        Route::get('/Provider/{provider?}', [ProductsController::class, 'provider'])->name('provider')->where('provider', '[a-zA-Z]+');
        
        // Provider Product Data Routes (CI: POST /admin/Products/ProviderProducts/{provider})
        Route::post('/ProviderProducts/{provider}', [ProductsController::class, 'providerProducts'])->name('provider.products');
        
        // Provider Options Routes (CI: POST /admin/Products/ProviderOptions/{provider})
        Route::post('/ProviderOptions/{provider}', [ProductsController::class, 'providerOptions'])->name('provider.options');
        
        // Provider Option Update (CI: POST /admin/Products/ProviderOptionUpdate)
        Route::post('/ProviderOptionUpdate', [ProductsController::class, 'providerOptionUpdate'])->name('provider.option.update');
        
        // Provider Product Bind (CI: GET/POST /admin/Products/ProviderProductBind/{id})
        Route::match(['get', 'post'], '/ProviderProductBind/{id}', [ProductProviderController::class, 'providerProductBind'])->name('provider.product.bind');
        
        // Provider Product Unbind (CI: POST /admin/Products/ProviderProductUnbind/{id})
        Route::post('/ProviderProductUnbind/{id}', [ProductProviderController::class, 'providerProductUnbind'])->name('provider.product.unbind');
        
        // Provider Product Options (CI: GET/POST /admin/Products/ProviderProductOptions/{provider}/{provider_product_id})
        Route::match(['get', 'post'], '/ProviderProductOptions/{provider}/{provider_product_id}', [ProductsController::class, 'providerProductOptions'])->name('provider.product.options');
        
        // Provider Product Price Rate (CI: GET/POST /admin/Products/ProviderProductPriceRate/{id})
        Route::match(['get', 'post'], '/ProviderProductPriceRate/{id}', [ProductProviderController::class, 'providerProductPriceRate'])->name('provider.product.price.rate');
        
        // Provider Product Price Rate Total (CI: GET/POST /admin/Products/ProviderProductPriceRateTotal/{id})
        Route::match(['get', 'post'], '/ProviderProductPriceRateTotal/{id}', [ProductProviderController::class, 'providerProductPriceRateTotal'])->name('provider.product.price.rate.total');
        
        // Provider Option Price Update (CI: POST /admin/Products/ProviderOptionPriceUpdate/{provider_product_id})
        Route::post('/ProviderOptionPriceUpdate/{provider_product_id}', [ProductsController::class, 'providerOptionPriceUpdate'])->name('provider.option.price.update');
        
        // Attributes for Provider Options (CI: POST /admin/Products/Attributes)
        Route::post('/Attributes', [ProductsController::class, 'attributes'])->name('attributes');
        
        // Attributes Map (CI: GET/POST /admin/Products/AttributesMap)
        Route::match(['get', 'post'], '/AttributesMap', [ProductsController::class, 'attributesMap'])->name('attributesMap');
        
        // Attribute Create Map (CI: POST /admin/Products/AttributeCreateMap)
        Route::post('/AttributeCreateMap', [ProductsController::class, 'attributeCreateMap'])->name('attributeCreateMap');
        
        // Session management for tabs
        Route::post('/session', [ProductsController::class, 'session'])->name('session');
        
        // Attribute CRUD routes
        Route::get('/AttributeCreate', [ProductsController::class, 'attributeCreatePage'])->name('attributeCreate');
        Route::get('/AttributeEdit/{id}', [ProductsController::class, 'attributeEdit'])->name('attributeEdit');
        Route::get('/AttributeDelete/{id}', [ProductsController::class, 'attributeDelete'])->name('attributeDelete');
        
        // Inline editing routes (CI project style)
        Route::post('/attributeUpdate', [ProductsController::class, 'attributeUpdate'])->name('attributeUpdate');
        Route::post('/attributeDelete', [ProductsController::class, 'attributeDeletePost'])->name('attributeDeletePost');
        
        // Grid create route (CI project style)
        Route::post('/attributeCreate', [ProductsController::class, 'attributeCreateGrid'])->name('attributeCreateGrid');
        
        // Attribute Items routes (CI project style)
        Route::match(['get', 'post'], '/AttributeItemsMap/{attribute_id?}', [ProductsController::class, 'attributeItemsMap'])->name('attributeItemsMap');
        Route::post('/AttributeItemCreateMap', [ProductsController::class, 'attributeItemCreateMap'])->name('attributeItemCreateMap');
        Route::post('/AttributeItemUpdateMap', [ProductsController::class, 'attributeItemUpdateMap'])->name('attributeItemUpdateMap');
        Route::post('/AttributeItemDeleteMap', [ProductsController::class, 'attributeItemDeleteMap'])->name('attributeItemDeleteMap');
    });
    
    // Legacy Routes (for backward compatibility - CI style URLs)
    Route::get('/SingleAttributes/index', [ProductsController::class, 'singleAttributes'])->name('legacy.singleAttributes.index');
    Route::get('/SingleAttributes/addEdit/{id?}', [ProductsController::class, 'singleAttributesAddEdit'])->name('legacy.singleAttributes.addEdit');
    Route::get('/SingleAttributes/delete/{id}', [ProductsController::class, 'singleAttributesDelete'])->name('legacy.singleAttributes.delete');
    Route::get('/SingleAttributes/activeInactive/{id}/{status}', [ProductsController::class, 'singleAttributesToggleStatus'])->name('legacy.singleAttributes.toggleStatus');
    Route::get('/viewProduct/{id}', [ProductsController::class, 'view'])->name('view.legacy');
    
    // CI MultipleAttributes Legacy Routes
    Route::get('/MultipleAttributes/index', [ProductsController::class, 'multipleAttributes'])->name('legacy.multipleAttributes.index');
    Route::get('/MultipleAttributes/quantity', [ProductsController::class, 'productQuantity'])->name('legacy.multipleAttributes.quantity');
    Route::get('/MultipleAttributes/quantity/addEdit/{id?}', [ProductsController::class, 'productQuantityAddEdit'])->name('legacy.multipleAttributes.quantity.addEdit');
    Route::get('/MultipleAttributes/quantity/delete/{id}', [ProductsController::class, 'productQuantityDelete'])->name('legacy.multipleAttributes.quantity.delete');
    Route::get('/MultipleAttributes/quantity/toggleStatus/{id}/{status}', [ProductsController::class, 'productQuantityToggleStatus'])->name('legacy.multipleAttributes.quantity.toggleStatus');
    Route::get('/MultipleAttributes/sizes', [ProductsController::class, 'productSizes'])->name('legacy.multipleAttributes.sizes');
    Route::get('/MultipleAttributes/sizes/addEdit/{id?}', [ProductsController::class, 'productSizesAddEdit'])->name('legacy.multipleAttributes.sizes.addEdit');
    Route::get('/MultipleAttributes/sizes/delete/{id}', [ProductsController::class, 'productSizesDelete'])->name('legacy.multipleAttributes.sizes.delete');
    Route::get('/MultipleAttributes/sizes/toggleStatus/{id}/{status}', [ProductsController::class, 'productSizesToggleStatus'])->name('legacy.multipleAttributes.sizes.toggleStatus');
    
    // Multiple Attributes Management (CI: MultipleAttributes)
    Route::get('/MultipleAttributes', [ProductsController::class, 'multipleAttributes'])->name('multipleAttributes.index');
    Route::match(['get', 'post'], '/MultipleAttributes/addEdit/{id?}', [ProductsController::class, 'multipleAttributesAddEdit'])->name('multipleAttributes.addEdit');
    Route::get('/MultipleAttributes/delete/{id}', [ProductsController::class, 'multipleAttributesDelete'])->name('multipleAttributes.delete');
    Route::get('/MultipleAttributes/toggleStatus/{id}/{status}', [ProductsController::class, 'multipleAttributesToggleStatus'])->name('multipleAttributes.toggleStatus');
    
    // Product Quantity Management (CI: MultipleAttributes->quantity)
    Route::get('/quantity', [ProductsController::class, 'productQuantity'])->name('MultipleAttributes.quantity');
    Route::match(['get', 'post'], '/quantity/addEdit/{id?}', [ProductsController::class, 'productQuantityAddEdit'])->name('MultipleAttributes.quantity.addEdit');
    Route::get('/quantity/delete/{id}', [ProductsController::class, 'productQuantityDelete'])->name('MultipleAttributes.quantity.delete');
    Route::get('/quantity/toggleStatus/{id}/{status}', [ProductsController::class, 'productQuantityToggleStatus'])->name('MultipleAttributes.quantity.toggleStatus');
    
    // Product Sizes Management (CI: MultipleAttributes->sizes)
    Route::get('/sizes', [ProductsController::class, 'productSizes'])->name('MultipleAttributes.sizes');
    Route::match(['get', 'post'], '/sizes/addEdit/{id?}', [ProductsController::class, 'productSizesAddEdit'])->name('MultipleAttributes.sizes.addEdit');
    Route::get('/sizes/delete/{id}', [ProductsController::class, 'productSizesDelete'])->name('MultipleAttributes.sizes.delete');
    Route::get('/sizes/toggleStatus/{id}/{status}', [ProductsController::class, 'productSizesToggleStatus'])->name('MultipleAttributes.sizes.toggleStatus');
    
    // Orders Routes
    Route::prefix('Orders')->middleware(['admin.permissions'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('orders.index');
        Route::get('/index/{statusStr?}/{user_id?}', [\App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('orders.index.status');
        Route::get('/getOrdersByStatus/{status}', [\App\Http\Controllers\Admin\OrdersController::class, 'getOrdersByStatus'])->name('orders.getOrdersByStatus');
        Route::get('/viewOrder/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'viewOrder'])->name('orders.view');
        Route::post('/changeOrderStatus', [\App\Http\Controllers\Admin\OrdersController::class, 'changeOrderStatus'])->name('orders.changeStatus');
        Route::post('/changeOrderPaymentStatus', [\App\Http\Controllers\Admin\OrdersController::class, 'changeOrderPaymentStatus'])->name('orders.changePaymentStatus');
        Route::get('/deleteOrder/{id}/{page_status?}', [\App\Http\Controllers\Admin\OrdersController::class, 'deleteOrder'])->name('orders.delete');
        Route::get('/exportOrders', [\App\Http\Controllers\Admin\OrdersController::class, 'exportOrders'])->name('orders.export');
        Route::get('/downloadInvoice/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
        Route::get('/downloadOrder/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'downloadOrder'])->name('orders.downloadOrder');
        Route::get('/download/{filePath}/{fileName}', [\App\Http\Controllers\Admin\OrdersController::class, 'download'])->name('orders.download')->where('filePath', '.*');
        Route::get('/testDownload', function() { return 'Download route works'; });
        Route::post('/personaliseDetail', [\App\Http\Controllers\Admin\OrdersController::class, 'personaliseDetail'])->name('orders.personaliseDetail');
        Route::get('/createOrder', [\App\Http\Controllers\Admin\OrdersController::class, 'createOrder'])->name('orders.create');
        Route::post('/saveOrder', [\App\Http\Controllers\Admin\OrdersController::class, 'saveOrder'])->name('orders.save');
        Route::post('/save', [\App\Http\Controllers\Admin\OrdersController::class, 'saveOrder'])->name('orders.save.alt');
        Route::get('/get-subcategories/{category_id}', [\App\Http\Controllers\Admin\OrdersController::class, 'getSubcategories'])->name('orders.getSubcategories');
        Route::get('/get-products/{subcategory_id}', [\App\Http\Controllers\Admin\OrdersController::class, 'getProducts'])->name('orders.getProducts');
        Route::post('/add-product', [\App\Http\Controllers\Admin\OrdersController::class, 'addProduct'])->name('orders.addProduct');
        Route::get('/data', [\App\Http\Controllers\Admin\OrdersController::class, 'getData'])->name('orders.data');
        Route::post('/list', [\App\Http\Controllers\Admin\OrdersController::class, 'list'])->name('orders.list');
        Route::get('/export-csv', [\App\Http\Controllers\Admin\OrdersController::class, 'exportCsv'])->name('orders.exportCsv');
    });
    
    // Users Routes
    Route::prefix('Users')->middleware(['admin.permissions'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
        Route::get('/index/{status?}', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index.status');
        Route::post('/ajaxList/{status?}', [\App\Http\Controllers\Admin\UsersController::class, 'ajaxList'])->name('users.ajaxList');
        Route::match(['get', 'post'], '/addEdit/{id?}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'addEdit'])->name('users.addEdit');
        Route::get('/viewUser/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'viewUser'])->name('users.view');
        Route::get('/deleteUser/{id}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'deleteUser'])->name('users.delete');
        Route::get('/activeInactive/{id}/{status}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'activeInactive'])->name('users.toggleStatus');
        Route::match(['get', 'post'], '/changePassword/{id}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'changePassword'])->name('users.changePassword');
        Route::post('/savePassword/{id}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'savePassword'])->name('users.savePassword');
        Route::get('/viewWishlist/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'viewUser'])->name('users.viewWishlist');
        Route::get('/preferredCustomer/{status?}', [\App\Http\Controllers\Admin\UsersController::class, 'preferredCustomer'])->name('users.preferredCustomer');
        Route::get('/activeInactiveUserType/{id}/{user_type}/{page_status?}', [\App\Http\Controllers\Admin\UsersController::class, 'activeInactiveUserType'])->name('users.toggleUserType');
        Route::get('/subscribeEmail', [\App\Http\Controllers\Admin\UsersController::class, 'subscribeEmail'])->name('users.subscribeEmail');
        Route::get('/subscribeEmails', [\App\Http\Controllers\Admin\UsersController::class, 'subscribeEmail'])->name('users.subscribeEmails');
        Route::get('/deleteSubscribeEmail/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'deleteSubscribeEmail'])->name('users.deleteSubscribeEmail');
        Route::get('/exportUsers', [\App\Http\Controllers\Admin\UsersController::class, 'exportUsers'])->name('users.export');
        Route::get('/exportCSV/{status?}', [\App\Http\Controllers\Admin\UsersController::class, 'exportCSV'])->name('users.exportCSV');
        Route::post('/ImportCSV', [\App\Http\Controllers\Admin\UsersController::class, 'ImportCSV'])->name('users.import');
    });
    
    // Categories Routes
    Route::prefix('Categories')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories.index');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\CategoriesController::class, 'addEdit'])->name('categories.addEdit');
        Route::get('/deleteCategory/{id}', [\App\Http\Controllers\Admin\CategoriesController::class, 'deleteCategory'])->name('categories.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\CategoriesController::class, 'activeInactive'])->name('categories.toggleStatus');
        
        // Sub Categories
        Route::get('/subCategories/{category_id?}', [\App\Http\Controllers\Admin\CategoriesController::class, 'subCategories'])->name('categories.subCategories');
        Route::get('/SubCategories/{category_id?}', [\App\Http\Controllers\Admin\CategoriesController::class, 'subCategories'])->name('categories.subCategories.ci');
        Route::match(['get', 'post'], '/addEditSubCategory/{id?}', [\App\Http\Controllers\Admin\CategoriesController::class, 'addEditSubCategory'])->name('categories.addEditSubCategory');
        Route::get('/deleteSubCategory/{id}', [\App\Http\Controllers\Admin\CategoriesController::class, 'deleteSubCategory'])->name('categories.deleteSubCategory');
        Route::get('/activeInactiveSubCategory/{id}/{status}', [\App\Http\Controllers\Admin\CategoriesController::class, 'activeInactiveSubCategory'])->name('categories.toggleSubCategoryStatus');
        
        // Tags
        Route::get('/tag', [\App\Http\Controllers\Admin\CategoriesController::class, 'tag'])->name('categories.tags');
        Route::get('/Tags', [\App\Http\Controllers\Admin\CategoriesController::class, 'tag'])->name('categories.tags.ci');
        Route::match(['get', 'post'], '/addEditTag/{id?}', [\App\Http\Controllers\Admin\CategoriesController::class, 'addEditTag'])->name('categories.addEditTag');
        Route::get('/deleteTag/{id}', [\App\Http\Controllers\Admin\CategoriesController::class, 'deleteTag'])->name('categories.deleteTag');
        Route::get('/activeInactiveTag/{id}/{status}', [\App\Http\Controllers\Admin\CategoriesController::class, 'activeInactiveTag'])->name('categories.toggleTagStatus');
    });
    
    // Attributes Routes
    Route::prefix('Attributes')->group(function () {
        // Multiple Attributes
        Route::get('/multipleAttributes', [\App\Http\Controllers\Admin\AttributesController::class, 'multipleAttributes']);
        Route::match(['get', 'post'], '/addEditMultipleAttribute/{id?}', [\App\Http\Controllers\Admin\AttributesController::class, 'addEditMultipleAttribute']);
        Route::get('/deleteMultipleAttribute/{id}', [\App\Http\Controllers\Admin\AttributesController::class, 'deleteMultipleAttribute']);
        Route::get('/activeInactiveMultipleAttribute/{id}/{status}', [\App\Http\Controllers\Admin\AttributesController::class, 'activeInactiveMultipleAttribute']);
        
        // Attribute Items
        Route::get('/attributeItems/{attribute_id}', [\App\Http\Controllers\Admin\AttributesController::class, 'attributeItems']);
        Route::match(['get', 'post'], '/addEditAttributeItem/{attribute_id}/{id?}', [\App\Http\Controllers\Admin\AttributesController::class, 'addEditAttributeItem']);
        Route::get('/deleteAttributeItem/{attribute_id}/{id}', [\App\Http\Controllers\Admin\AttributesController::class, 'deleteAttributeItem']);
    });
    
    // Blogs Routes
    Route::prefix('Blogs')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BlogsController::class, 'index'])->name('blogs.index');
        Route::get('/index', [\App\Http\Controllers\Admin\BlogsController::class, 'index'])->name('blogs.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\BlogsController::class, 'addEdit'])->name('blogs.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\BlogsController::class, 'delete'])->name('blogs.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\BlogsController::class, 'activeInactive'])->name('blogs.toggleStatus');
        Route::get('/view/{id}', [\App\Http\Controllers\Admin\BlogsController::class, 'view'])->name('blogs.view');
        Route::get('/Category', [\App\Http\Controllers\Admin\BlogsController::class, 'Category'])->name('blogs.category');
        Route::match(['get', 'post'], '/addEditCategory/{id?}', [\App\Http\Controllers\Admin\BlogsController::class, 'addEditCategory'])->name('blogs.addEditCategory');
        Route::get('/deleteCategory/{id}', [\App\Http\Controllers\Admin\BlogsController::class, 'deleteCategory'])->name('blogs.deleteCategory');
        Route::get('/activeInactiveCategory/{id}/{status}', [\App\Http\Controllers\Admin\BlogsController::class, 'activeInactiveCategory'])->name('blogs.toggleCategoryStatus');
    });
    
    // Pages Routes
    Route::prefix('Pages')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PagesController::class, 'index'])->name('pages.index');
        Route::get('/index', [\App\Http\Controllers\Admin\PagesController::class, 'index'])->name('pages.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\PagesController::class, 'addEdit'])->name('pages.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\PagesController::class, 'delete'])->name('pages.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\PagesController::class, 'activeInactive'])->name('pages.toggleStatus');
    });
    
    // Banners Routes
    Route::prefix('Banners')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BannersController::class, 'index'])->name('banners.index');
        Route::get('/index', [\App\Http\Controllers\Admin\BannersController::class, 'index'])->name('banners.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\BannersController::class, 'addEdit'])->name('banners.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\BannersController::class, 'delete'])->name('banners.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\BannersController::class, 'activeInactive'])->name('banners.toggleStatus');
    });
    
    // FAQ Routes
    Route::prefix('Faq')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FaqController::class, 'index']);
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\FaqController::class, 'addEdit']);
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'delete']);
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\FaqController::class, 'activeInactive']);
    });
    
    // Services Routes
    Route::prefix('Services')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServicesController::class, 'index'])->name('services.index');
        Route::get('/index', [\App\Http\Controllers\Admin\ServicesController::class, 'index'])->name('services.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\ServicesController::class, 'addEdit'])->name('services.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\ServicesController::class, 'delete'])->name('services.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\ServicesController::class, 'activeInactive'])->name('services.toggleStatus');
    });
    
    // Sections Routes
    Route::prefix('Sections')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SectionsController::class, 'index'])->name('sections.index');
        Route::get('/index', [\App\Http\Controllers\Admin\SectionsController::class, 'index'])->name('sections.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\SectionsController::class, 'addEdit'])->name('sections.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\SectionsController::class, 'delete'])->name('sections.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\SectionsController::class, 'activeInactive'])->name('sections.toggleStatus');
    });
    
    // Discounts Routes
    Route::prefix('Discounts')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DiscountsController::class, 'index'])->name('discounts.index');
        Route::get('/index/{type?}', [\App\Http\Controllers\Admin\DiscountsController::class, 'index'])->name('discounts.index.type');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\DiscountsController::class, 'addEdit'])->name('discounts.addEdit');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\DiscountsController::class, 'activeInactive'])->name('discounts.toggleStatus');
        Route::get('/deleteDiscount/{id}', [\App\Http\Controllers\Admin\DiscountsController::class, 'deleteDiscount'])->name('discounts.delete');
        Route::post('/validateDiscount', [\App\Http\Controllers\Admin\DiscountsController::class, 'validateDiscount'])->name('discounts.validate');
        Route::post('/applyDiscount', [\App\Http\Controllers\Admin\DiscountsController::class, 'applyDiscount'])->name('discounts.apply');
        Route::post('/removeDiscount', [\App\Http\Controllers\Admin\DiscountsController::class, 'removeDiscount'])->name('discounts.remove');
    });
    
    // Sales Reports Routes
    Route::prefix('SalesReports')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SalesReportsController::class, 'index']);
        Route::get('/index', [\App\Http\Controllers\Admin\SalesReportsController::class, 'index']);
        Route::get('/salesByProduct', [\App\Http\Controllers\Admin\SalesReportsController::class, 'salesByProduct']);
        Route::get('/salesByCategory', [\App\Http\Controllers\Admin\SalesReportsController::class, 'salesByCategory']);
        Route::get('/revenueReport', [\App\Http\Controllers\Admin\SalesReportsController::class, 'revenueReport']);
        Route::get('/exportCSV', [\App\Http\Controllers\Admin\SalesReportsController::class, 'exportCSV']);
        Route::get('/exportPDF', [\App\Http\Controllers\Admin\SalesReportsController::class, 'exportPDF']);
    });
    
    // Configurations Routes
    Route::prefix('Configurations')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'index'])->name('configurations.index');
        Route::get('/index', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'index'])->name('configurations.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'addEdit'])->name('configurations.addEdit');
        Route::post('/saveConfigurations', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'saveConfigurations'])->name('configurations.save');
        Route::match(['get', 'post'], '/emailSettings', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'emailSettings'])->name('configurations.email');
        Route::match(['get', 'post'], '/paymentSettings', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'paymentSettings'])->name('configurations.payment');
        Route::match(['get', 'post'], '/shippingSettings', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'shippingSettings'])->name('configurations.shipping');
        Route::match(['get', 'post'], '/taxSettings', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'taxSettings'])->name('configurations.tax');
    });
    
    // CI-style Configrations Routes (with 'r' - legacy compatibility)
    Route::prefix('Configrations')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'index'])->name('configrations.index');
        Route::get('/index', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'index'])->name('configrations.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'addEdit'])->name('configrations.addEdit');
        Route::post('/saveConfigrations', [\App\Http\Controllers\Admin\ConfigurationsController::class, 'saveConfigurations'])->name('configrations.save');
    });
    
    // Stores Routes
    Route::prefix('Stores')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StoresController::class, 'index'])->name('stores.index');
        Route::get('/index', [\App\Http\Controllers\Admin\StoresController::class, 'index'])->name('stores.index.ci');
        Route::match(['get', 'post'], '/addEdit/{id?}', [\App\Http\Controllers\Admin\StoresController::class, 'addEdit'])->name('stores.addEdit');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\StoresController::class, 'delete'])->name('stores.delete');
        Route::get('/activeInactive/{id}/{status}', [\App\Http\Controllers\Admin\StoresController::class, 'activeInactive'])->name('stores.toggleStatus');
    });
    
    // AJAX Routes
    Route::prefix('Ajax')->group(function () {
        Route::get('/getCategoryDropDownListByAjax/{menu_id?}', [AjaxController::class, 'getCategoryDropDownListByAjax']);
        Route::get('/getSubCategoryDropDownListByAjax/{category_id?}', [AjaxController::class, 'getSubCategoryDropDownListByAjax']);
        Route::get('/getPrinterSeriesListByAjax/{printer_brand_id?}', [AjaxController::class, 'getPrinterSeriesListByAjax']);
        Route::get('/getProductDropDownListByAjax/{menu_id?}', [AjaxController::class, 'getProductDropDownListByAjax']);
        Route::post('/removeProductImage', [AjaxController::class, 'removeProductImage']);
        Route::get('/getSubCategoryAndProductDropDownListByAjax/{category_id?}', [AjaxController::class, 'getSubCategoryAndProductDropDownListByAjax']);
        Route::get('/getActiveProductDropDownListByAjax/{sub_category_id?}', [AjaxController::class, 'getActiveProductDropDownListByAjax']);
    });
    
    // Neighbor Management (CI: Neighbor)
    Route::match(['get', 'post'], '/Neighbor/index/{neighbor_id?}/{order?}', [\App\Http\Controllers\Admin\NeighborController::class, 'index'])->name('neighbor.index');
    Route::match(['get', 'post'], '/Neighbor/edit/{neighbor_id?}/{attribute_id?}/{attribute_item_id?}/{order?}', [\App\Http\Controllers\Admin\NeighborController::class, 'edit'])->name('neighbor.edit');
    Route::get('/Neighbor/delete/{id}', [\App\Http\Controllers\Admin\NeighborController::class, 'delete'])->name('neighbor.delete');
    Route::post('/Neighbor/deleteAll', [\App\Http\Controllers\Admin\NeighborController::class, 'deleteAll'])->name('neighbor.deleteAll');
    Route::post('/Neighbor/search', [\App\Http\Controllers\Admin\NeighborController::class, 'search'])->name('neighbor.search');
    
    // Printers Routes
    Route::prefix('Printers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PrintersController::class, 'index'])->name('printers.index');
        Route::get('/index/{type?}', [\App\Http\Controllers\Admin\PrintersController::class, 'index'])->name('printers.index.type');
        Route::match(['get', 'post'], '/addEdit/{id?}/{type?}', [\App\Http\Controllers\Admin\PrintersController::class, 'addEdit'])->name('printers.addEdit');
        Route::get('/activeInactive/{id}/{status}/{type}', [\App\Http\Controllers\Admin\PrintersController::class, 'activeInactive'])->name('printers.activeInactive');
        Route::get('/deletePrinter/{id}/{type}', [\App\Http\Controllers\Admin\PrintersController::class, 'deletePrinter'])->name('printers.deletePrinter');
    });
    
    // Supports Routes
    Route::prefix('Supports')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SupportsController::class, 'index'])->name('supports.index');
        Route::get('/index', [\App\Http\Controllers\Admin\SupportsController::class, 'index'])->name('supports.index.ci');
        Route::get('/view/{id}', [\App\Http\Controllers\Admin\SupportsController::class, 'view'])->name('supports.view');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\SupportsController::class, 'delete'])->name('supports.delete');
    });
    
    // Additional Admin Modules (add as needed)
    // Banners, Blogs, Configrations, Discounts, MultipleAttributes, 
    // Neighbor, Pages, Payments, Printers, Salesreports, Sections,
    // Services, SingleAttributes, Stores, Supports, Tickets
});

// Public Admin Routes (no authentication required - like CI project)
Route::prefix('admin')->middleware(['web'])->group(function () {
    // Admin Login (public access like CI project)
    Route::match(['get', 'post'], '/login', [LoginController::class, 'index'])->name('admin.login.public');
    
    // Accounts changePassword (forgot password - no auth required like CI)
    Route::match(['get', 'post'], '/Accounts/changePassword', [\App\Http\Controllers\Admin\AccountsController::class, 'changePassword'])->name('admin.accounts.changePassword');
});
