<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Admin Product Management Feature Tests
 * Tests exact behavior from CI admin/Products controller
 */
class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        
        // Create admin user
        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        // Login admin
        Session::put('adminLoginId', 1);
        Session::put('adminLoginEmail', 'admin@example.com');
        Session::put('adminLoginName', 'Test Admin');
        Session::put('adminLoginRole', 'admin');
        
        // Create test data
        DB::table('menus')->insert(['id' => 1, 'name' => 'Test Menu', 'status' => 1]);
        DB::table('categories')->insert(['id' => 1, 'menu_id' => 1, 'name' => 'Test Category', 'status' => 1]);
        DB::table('sub_categories')->insert(['id' => 1, 'menu_id' => 1, 'category_id' => 1, 'name' => 'Test Subcategory', 'status' => 1]);
        
        // Create test product
        DB::table('products')->insert([
            'id' => 1,
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'description' => 'Test description',
            'product_image' => 'test.jpg',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test admin products listing page loads
     * CI: admin/Products->index()
     */
    public function test_admin_products_listing_page_loads()
    {
        $response = $this->get('/admin/Products');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
    }

    /**
     * Test products listing requires admin authentication
     * CI: Admin_Controller checks session
     */
    public function test_products_listing_requires_authentication()
    {
        Session::forget('adminLoginId');
        
        $response = $this->get('/admin/Products');
        
        $response->assertRedirect('/pcoopadmin');
    }

    /**
     * Test add product page loads
     * CI: admin/Products->addEdit()
     */
    public function test_add_product_page_loads()
    {
        $response = $this->get('/admin/Products/addEdit');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.add_edit');
    }

    /**
     * Test create new product
     * CI: admin/Products->addEdit() POST
     */
    public function test_create_new_product()
    {
        $response = $this->post('/admin/Products/addEdit', [
            'name' => 'New Product',
            'slug' => 'new-product',
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'price' => 49.99,
            'description' => 'New product description',
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Products');
        $response->assertSessionHas('message_success', 'Product created successfully');

        // Verify product created in database
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'slug' => 'new-product',
            'price' => 49.99,
        ]);
    }

    /**
     * Test edit product page loads
     * CI: admin/Products->addEdit($id)
     */
    public function test_edit_product_page_loads()
    {
        $response = $this->get('/admin/Products/addEdit/1');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.add_edit');
        $response->assertViewHas('product');
    }

    /**
     * Test update existing product
     * CI: admin/Products->addEdit($id) POST
     */
    public function test_update_existing_product()
    {
        $response = $this->post('/admin/Products/addEdit/1', [
            'name' => 'Updated Product',
            'slug' => 'updated-product',
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'price' => 39.99,
            'description' => 'Updated description',
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Products');
        $response->assertSessionHas('message_success', 'Product updated successfully');

        // Verify product updated
        $product = DB::table('products')->where('id', 1)->first();
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals(39.99, $product->price);
    }

    /**
     * Test delete product
     * CI: admin/Products->delete($id)
     */
    public function test_delete_product()
    {
        $response = $this->get('/admin/Products/delete/1');

        $response->assertRedirect('/admin/Products');
        $response->assertSessionHas('message_success', 'Product deleted successfully');

        // Verify product deleted
        $product = DB::table('products')->where('id', 1)->first();
        $this->assertNull($product);
    }

    /**
     * Test activate/deactivate product
     * CI: admin/Products->activeInactive($id)
     */
    public function test_toggle_product_status()
    {
        // Deactivate
        $response = $this->get('/admin/Products/activeInactive/1');

        $response->assertRedirect('/admin/Products');

        $product = DB::table('products')->where('id', 1)->first();
        $this->assertEquals(0, $product->status);

        // Activate
        $response = $this->get('/admin/Products/activeInactive/1');

        $product = DB::table('products')->where('id', 1)->first();
        $this->assertEquals(1, $product->status);
    }

    /**
     * Test upload product image
     * CI: admin/Products->addEdit() - image upload
     */
    public function test_upload_product_image()
    {
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->post('/admin/Products/addEdit/1', [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'price' => 29.99,
            'product_image' => $file,
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Products');

        // Verify image uploaded
        Storage::disk('public')->assertExists('uploads/products/' . $file->hashName());
    }

    /**
     * Test upload multiple product images
     * CI: admin/Products->addEdit() - gallery images
     */
    public function test_upload_multiple_product_images()
    {
        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ];

        $response = $this->post('/admin/Products/addEdit/1', [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'price' => 29.99,
            'gallery_images' => $files,
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Products');

        // Verify images saved in database
        $images = DB::table('product_images')->where('product_id', 1)->count();
        $this->assertEquals(2, $images);
    }

    /**
     * Test set product multiple attributes
     * CI: admin/Products->SetMultipleAttributes($id)
     */
    public function test_set_product_multiple_attributes()
    {
        $response = $this->get('/admin/Products/SetMultipleAttributes/1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.attributes');
        $response->assertViewHas('product');
    }

    /**
     * Test add product quantity pricing
     * CI: admin/Products->AddEditProductQuantity($product_id, $id)
     */
    public function test_add_product_quantity_pricing()
    {
        $response = $this->post('/admin/Products/AddEditProductQuantity/1', [
            'qty' => 100,
            'price' => 25.99,
        ]);

        $response->assertRedirect('/admin/Products/SetMultipleAttributes/1');
        $response->assertSessionHas('message_success', 'Quantity pricing added');

        // Verify quantity pricing created
        $this->assertDatabaseHas('product_quantities', [
            'product_id' => 1,
            'qty' => 100,
            'price' => 25.99,
        ]);
    }

    /**
     * Test add product size
     * CI: admin/Products->AddEditProductSize($product_id, $quantity_id, $id)
     */
    public function test_add_product_size()
    {
        // Create quantity first
        DB::table('product_quantities')->insert([
            'id' => 1,
            'product_id' => 1,
            'qty' => 100,
            'price' => 25.99,
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/admin/Products/AddEditProductSize/1/1', [
            'width' => 10,
            'height' => 20,
            'price' => 29.99,
        ]);

        $response->assertRedirect('/admin/Products/SetMultipleAttributes/1');

        // Verify size created
        $this->assertDatabaseHas('product_sizes', [
            'product_id' => 1,
            'product_quantity_id' => 1,
            'width' => 10,
            'height' => 20,
        ]);
    }

    /**
     * Test delete product quantity
     * CI: admin/Products->deleteProductQuantity($product_id, $id)
     */
    public function test_delete_product_quantity()
    {
        // Create quantity
        DB::table('product_quantities')->insert([
            'id' => 1,
            'product_id' => 1,
            'qty' => 100,
            'price' => 25.99,
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Products/deleteProductQuantity/1/1');

        $response->assertRedirect('/admin/Products/SetMultipleAttributes/1');

        // Verify quantity deleted
        $this->assertDatabaseMissing('product_quantities', [
            'id' => 1,
        ]);
    }

    /**
     * Test set product single attributes
     * CI: admin/Products->SetSingleAttributes($id)
     */
    public function test_set_product_single_attributes()
    {
        $response = $this->post('/admin/Products/SetSingleAttributes/1', [
            'attributes' => [1, 2, 3],
        ]);

        $response->assertRedirect('/admin/Products');
        $response->assertSessionHas('message_success', 'Attributes set successfully');

        // Verify attributes saved
        $count = DB::table('product_attributes')->where('product_id', 1)->count();
        $this->assertEquals(3, $count);
    }

    /**
     * Test product validation - required fields
     * CI: admin/Products->addEdit() - validation
     */
    public function test_product_validation_required_fields()
    {
        $response = $this->post('/admin/Products/addEdit', [
            // Missing required fields
            'name' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'price']);

        // Verify product not created
        $count = DB::table('products')->count();
        $this->assertEquals(1, $count); // Only the setUp product
    }

    /**
     * Test product validation - unique slug
     * CI: admin/Products->addEdit() - slug validation
     */
    public function test_product_validation_unique_slug()
    {
        $response = $this->post('/admin/Products/addEdit', [
            'name' => 'Another Product',
            'slug' => 'test-product', // Duplicate slug
            'menu_id' => 1,
            'category_id' => 1,
            'price' => 39.99,
            'status' => 1,
        ]);

        $response->assertSessionHasErrors(['slug']);
    }

    /**
     * Test filter products by category
     * CI: admin/Products->index() - category filter
     */
    public function test_filter_products_by_category()
    {
        $response = $this->get('/admin/Products?category_id=1');

        $products = $response->viewData('products');
        
        foreach ($products as $product) {
            $this->assertEquals(1, $product['category_id']);
        }
    }

    /**
     * Test search products by name
     * CI: admin/Products->index() - search
     */
    public function test_search_products_by_name()
    {
        $response = $this->get('/admin/Products?search=Test Product');

        $products = $response->viewData('products');
        
        $this->assertCount(1, $products);
        $this->assertEquals('Test Product', $products[0]['name']);
    }

    /**
     * Test view product details
     * CI: admin/Products->view($id)
     */
    public function test_view_product_details()
    {
        $response = $this->get('/admin/Products/view/1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.view');
        $response->assertViewHas('product');
    }

    /**
     * Test sub-admin cannot delete products
     * CI: Admin_Controller role-based permissions
     */
    public function test_sub_admin_cannot_delete_products()
    {
        Session::put('adminLoginRole', 'sub_admin');

        $response = $this->get('/admin/Products/delete/1');

        $response->assertStatus(403);
        $response->assertSessionHas('error', 'You do not have permission');
    }

    /**
     * Test product slug auto-generation
     * CI: admin/Products->addEdit() - slug generation
     */
    public function test_product_slug_auto_generation()
    {
        $response = $this->post('/admin/Products/addEdit', [
            'name' => 'New Product Name',
            // No slug provided
            'menu_id' => 1,
            'category_id' => 1,
            'price' => 49.99,
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Products');

        // Verify slug auto-generated
        $product = DB::table('products')->where('name', 'New Product Name')->first();
        $this->assertEquals('new-product-name', $product->slug);
    }

    /**
     * Test bulk product status update
     * CI: admin/Products - bulk actions
     */
    public function test_bulk_product_status_update()
    {
        // Create additional products
        DB::table('products')->insert([
            ['id' => 2, 'name' => 'Product 2', 'slug' => 'product-2', 'price' => 19.99, 'status' => 1, 'created' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Product 3', 'slug' => 'product-3', 'price' => 29.99, 'status' => 1, 'created' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s')],
        ]);

        $response = $this->post('/admin/Products/bulkStatusUpdate', [
            'product_ids' => [2, 3],
            'status' => 0,
        ]);

        $response->assertRedirect('/admin/Products');

        // Verify products deactivated
        $product2 = DB::table('products')->where('id', 2)->first();
        $product3 = DB::table('products')->where('id', 3)->first();
        
        $this->assertEquals(0, $product2->status);
        $this->assertEquals(0, $product3->status);
    }
}
