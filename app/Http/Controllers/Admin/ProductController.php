<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Product listing (replicate CI Products->index lines 26-56)
     */
    public function index(Request $request, $product_id = 0, $order = 'desc')
    {
        if ($request->isMethod('post')) {
            $order = $request->input('order');
            return redirect('admin/Products/index/' . $product_id . '/' . $order);
        }
        
        $data = [];
        $data['page_title'] = 'Products';
        $data['sub_page_title'] = 'Add New Product';
        $data['sub_page_url'] = 'addEdit';
        $data['sub_page_view_url'] = 'viewProduct';
        $data['sub_page_delete_url'] = 'deleteProduct';
        $data['sub_page_url_active_inactive'] = 'activeInactive';
        
        // Pagination (lines 41-52)
        $perPage = 20;
        $page = $request->get('page', 0);
        
        $total = $this->getProductTotal($product_id);
        $products = $this->getProductList('', $product_id, $perPage, $page, $order);
        
        $data['lists'] = $products;
        $data['order'] = $order;
        $data['total'] = $total;
        $data['perPage'] = $perPage;
        
        return view('admin.products.index', $data);
    }
    
    /**
     * View product details (replicate CI Products->viewProduct lines 58-75)
     */
    public function viewProduct($id = null)
    {
        if (empty($id)) {
            return redirect('admin/Products');
        }
        
        $data = [];
        $data['page_title'] = 'Product Details';
        $data['main_page_url'] = '';
        
        $ProductImages = $this->getProductImageDataByProductId($id);
        $data['ProductImages'] = $ProductImages;
        
        $Product = $this->getProductList($id);
        $data['Product'] = $Product;
        
        $data['tagList'] = $this->getTagsList(1);
        
        return view('admin.products.view', $data);
    }
    
    /**
     * Add/Edit product (replicate CI Products->addEdit lines 77-540)
     */
    public function addEdit(Request $request, $id = null)
    {
        $data = [];
        $data['page_title'] = $id ? 'Edit Product' : 'Add New Product';
        $data['main_page_url'] = '';
        
        $postData = [];
        
        if ($id) {
            $postData = $this->getProductDataById($id);
        }
        
        $ProductImages = $this->getProductImageDataByProductId($id);
        $data['ProductImages'] = $ProductImages;
        
        $quantity = $this->getQuantityListDropDown();
        $data['quantity'] = $quantity;
        
        $data['StoreList'] = $this->getStoreDropDownList();
        
        $ProductDescriptions = [];
        $ProductTemplates = [];
        
        if ($id) {
            $ProductDescriptions = $this->getProductDescriptionById($id);
            $ProductTemplates = $this->getProductTemplatesById($id);
        }
        
        $data['ProductDescriptions'] = $ProductDescriptions;
        $data['ProductTemplates'] = $ProductTemplates;
        
        $Categoty = $this->getMultipalCategoriesAndSubCategories();
        $data['Categoty'] = $Categoty;
        
        $ProductCategory = [];
        if ($id) {
            $ProductCategory = $this->getProductMultipalCategoriesAndSubCategories($id);
        }
        $data['ProductCategory'] = $ProductCategory;
        
        if ($request->isMethod('post')) {
            // Validation rules would be defined here
            
            // Build postData from request (lines 132-323)
            if ($id) {
                $postData['id'] = $id;
            }
            
            $postData['name'] = $request->input('name');
            $postData['name_french'] = $request->input('name_french');
            $postData['price'] = $request->input('price');
            $postData['short_description'] = $request->input('short_description');
            $postData['short_description_french'] = $request->input('short_description_french');
            $postData['full_description'] = $request->input('full_description');
            $postData['full_description_french'] = $request->input('full_description_french');
            $postData['code'] = $request->input('code');
            $postData['code_french'] = $request->input('code_french');
            $postData['model'] = $request->input('model');
            $postData['model_french'] = $request->input('model_french');
            $postData['is_stock'] = $request->input('is_stock', 0);
            
            // Product tags
            $product_tag = $request->input('product_tag', []);
            if (!empty($product_tag)) {
                $product_tag = implode(',', $product_tag);
            }
            $postData['product_tag'] = $product_tag;
            
            $postData['use_custom_size'] = $request->input('use_custom_size');
            
            // Length/Width fields (lines 160-197)
            $postData['add_length_width'] = $request->input('add_length_width', 0);
            $postData['min_length'] = $request->input('min_length', 0);
            $postData['max_length'] = $request->input('max_length', 0);
            $postData['min_width'] = $request->input('min_width', 0);
            $postData['max_width'] = $request->input('max_width', 0);
            $postData['min_length_min_width_price'] = $request->input('min_length_min_width_price', 0);
            $postData['length_width_unit_price_black'] = $request->input('length_width_unit_price_black', 0);
            $postData['length_width_price_color'] = $request->input('length_width_price_color', 0);
            $postData['length_width_color_show'] = $request->input('length_width_color_show', 0);
            $postData['length_width_pages_type'] = $request->input('length_width_pages_type', 'input');
            $postData['length_width_quantity_show'] = $request->input('length_width_quantity_show', '0');
            $postData['length_width_min_quantity'] = $request->input('length_width_min_quantity', 25);
            $postData['length_width_max_quantity'] = $request->input('length_width_max_quantity', 5000);
            
            // Page Length/Width fields (lines 199-248)
            $postData['page_add_length_width'] = $request->input('page_add_length_width', 0);
            $postData['page_min_length'] = $request->input('page_min_length', 0);
            $postData['page_max_length'] = $request->input('page_max_length', 0);
            $postData['page_min_width'] = $request->input('page_min_width', 0);
            $postData['page_max_width'] = $request->input('page_max_width', 0);
            $postData['page_min_length_min_width_price'] = $request->input('page_min_length_min_width_price', 0);
            $postData['page_length_width_price_color'] = $request->input('page_length_width_price_color', 0);
            $postData['page_length_width_price_black'] = $request->input('page_length_width_price_black', 0);
            $postData['page_length_width_color_show'] = $request->input('page_length_width_color_show', 0);
            $postData['page_length_width_pages_type'] = $request->input('page_length_width_pages_type', 'dropdown');
            $postData['page_length_width_pages_show'] = $request->input('page_length_width_pages_show', '0');
            $postData['page_length_width_sheets_type'] = $request->input('page_length_width_sheets_type', 'dropdown');
            $postData['page_length_width_sheets_show'] = $request->input('page_length_width_sheets_show', '0');
            $postData['page_length_width_quantity_type'] = $request->input('page_length_width_quantity_type', 'input');
            $postData['page_length_width_quantity_show'] = $request->input('page_length_width_quantity_show', '0');
            $postData['page_length_width_min_quantity'] = $request->input('page_length_width_min_quantity', 25);
            $postData['page_length_width_max_quantity'] = $request->input('page_length_width_max_quantity', 5000);
            
            // Depth fields (lines 250-293)
            $postData['depth_add_length_width'] = $request->input('depth_add_length_width', 0);
            $postData['min_depth'] = $request->input('min_depth', 0);
            $postData['max_depth'] = $request->input('max_depth', 0);
            $postData['depth_min_length'] = $request->input('depth_min_length', 0);
            $postData['depth_max_length'] = $request->input('depth_max_length', 0);
            $postData['depth_min_width'] = $request->input('depth_min_width', 0);
            $postData['depth_max_width'] = $request->input('depth_max_width', 0);
            $postData['depth_width_length_price'] = $request->input('depth_width_length_price', 0);
            $postData['depth_unit_price_black'] = $request->input('depth_unit_price_black', 0);
            $postData['depth_price_color'] = $request->input('depth_price_color', 0);
            $postData['depth_color_show'] = $request->input('depth_color_show', 0);
            $postData['depth_width_length_type'] = $request->input('depth_width_length_type', 'input');
            $postData['depth_width_length_quantity_show'] = $request->input('depth_width_length_quantity_show', '0');
            $postData['depth_min_quantity'] = $request->input('depth_min_quantity', 25);
            $postData['depth_max_quantity'] = $request->input('depth_max_quantity', 5000);
            
            // Other fields (lines 295-323)
            $postData['votre_text'] = $request->input('votre_text', 0);
            $postData['recto_verso'] = $request->input('recto_verso', 0);
            $postData['recto_verso_price'] = $request->input('recto_verso_price', 0);
            $postData['call'] = $request->input('call', 0);
            $postData['phone_number'] = $request->input('phone_number', '');
            
            // Shipping box fields (lines 310-323)
            $postData['shipping_box_length'] = $request->input('shipping_box_length', '0');
            $postData['shipping_box_width'] = $request->input('shipping_box_width', '0');
            $postData['shipping_box_height'] = $request->input('shipping_box_height', '0');
            $postData['shipping_box_weight'] = $request->input('shipping_box_weight', '0');
            
            // Handle image uploads (lines 328-367)
            $uploadData = [];
            $saveData = true;
            
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach ($files as $i => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads/products'), $filename);
                        
                        // Resize images (lines 357-359)
                        $this->resizeImage($filename, 'small');
                        $this->resizeImage($filename, 'medium');
                        $this->resizeImage($filename, 'large');
                        
                        $uploadData[$i]['file_name'] = $filename;
                    }
                }
            }
            
            if ($saveData) {
                // Merge old and new images (lines 370-380)
                $old_image = $request->input('old_image', []);
                $uploadDataNew = [];
                
                foreach ($uploadData as $k => $v) {
                    $uploadDataNew[] = $v['file_name'];
                }
                
                $uploadDataNew = array_merge($old_image, $uploadDataNew);
                
                // Save product (line 381)
                $insert_id = $this->saveProduct($postData);
                
                if ($insert_id > 0) {
                    // Save product images (lines 384-397)
                    $data = [];
                    foreach ($uploadDataNew as $k => $v) {
                        $sdata = [
                            'image' => $v,
                            'created' => date('Y-m-d H:i:s'),
                            'updated' => date('Y-m-d H:i:s'),
                            'product_id' => $insert_id,
                        ];
                        $data[] = $sdata;
                    }
                    
                    if ($this->saveProductImage($data, $insert_id)) {
                        $product_main_image = isset($data[0]) ? $data[0] : '';
                        $this->saveProduct(['id' => $insert_id, 'product_image' => $product_main_image['image']]);
                    }
                    
                    // Save product descriptions (lines 399-437)
                    $this->saveProductDescriptions($request, $insert_id);
                    
                    // Save product templates (lines 439-540)
                    $this->saveProductTemplates($request, $insert_id);
                    
                    session()->flash('message_success', 'Product saved successfully.');
                    return redirect('admin/Products');
                } else {
                    session()->flash('message_error', 'Product save failed.');
                }
            }
        }
        
        $data['postData'] = $postData;
        
        return view('admin.products.' . ($id ? 'edit' : 'create'), $data);
    }
    
    /**
     * Delete product (replicate CI Products->deleteProduct)
     */
    public function deleteProduct($id)
    {
        if ($this->deleteProductById($id)) {
            session()->flash('message_success', 'Product deleted successfully.');
        } else {
            session()->flash('message_error', 'Product delete failed.');
        }
        
        return redirect('admin/Products');
    }
    
    // ========== Private Helper Methods ==========
    
    private function getProductTotal($product_id = 0)
    {
        $query = DB::table('products');
        if ($product_id > 0) {
            $query->where('id', $product_id);
        }
        return $query->count();
    }
    
    private function getProductList($id = '', $product_id = 0, $limit = null, $offset = 0, $order = 'desc')
    {
        $query = DB::table('products');
        
        if (!empty($id)) {
            $query->where('id', $id);
            $result = $query->first();
            return $result ? (array) $result : [];
        }
        
        if ($product_id > 0) {
            $query->where('id', $product_id);
        }
        
        $query->orderBy('id', $order);
        
        if ($limit) {
            $query->limit($limit)->offset($offset);
        }
        
        $results = $query->get();
        return array_map(function($item) {
            return (array) $item;
        }, $results->toArray());
    }
    
    private function getProductDataById($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        return $product ? (array) $product : [];
    }
    
    private function getProductImageDataByProductId($product_id)
    {
        if (empty($product_id)) {
            return [];
        }
        
        $images = DB::table('product_images')->where('product_id', $product_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $images->toArray());
    }
    
    private function getQuantityListDropDown()
    {
        return DB::table('quantities')->pluck('name', 'id')->toArray();
    }
    
    private function getStoreDropDownList()
    {
        return DB::table('stores')->pluck('name', 'id')->toArray();
    }
    
    private function getProductDescriptionById($product_id)
    {
        $descriptions = DB::table('product_descriptions')->where('product_id', $product_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $descriptions->toArray());
    }
    
    private function getProductTemplatesById($product_id)
    {
        $templates = DB::table('product_templates')->where('product_id', $product_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $templates->toArray());
    }
    
    private function getMultipalCategoriesAndSubCategories()
    {
        return DB::table('categories')->with('subcategories')->get()->toArray();
    }
    
    private function getProductMultipalCategoriesAndSubCategories($product_id)
    {
        return DB::table('product_categories')->where('product_id', $product_id)->get()->toArray();
    }
    
    private function getTagsList($status = null)
    {
        $query = DB::table('tags');
        if ($status !== null) {
            $query->where('status', $status);
        }
        return $query->get()->toArray();
    }
    
    private function saveProduct($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('products')->where('id', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('products')->insertGetId($data);
        }
    }
    
    private function saveProductImage($data, $product_id)
    {
        DB::table('product_images')->where('product_id', $product_id)->delete();
        return DB::table('product_images')->insert($data);
    }
    
    private function saveProductDescriptions($request, $product_id)
    {
        DB::table('product_descriptions')->where('product_id', $product_id)->delete();
        
        $title = $request->input('title', []);
        $title_french = $request->input('title_french', []);
        $description = $request->input('description', []);
        $description_french = $request->input('description_french', []);
        
        if (!empty($title)) {
            $data = [];
            foreach ($title as $key => $val) {
                if (!empty($val)) {
                    $data[] = [
                        'title' => $val,
                        'title_french' => $title_french[$key] ?? '',
                        'description' => $description[$key] ?? '',
                        'description_french' => $description_french[$key] ?? '',
                        'product_id' => $product_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
            if ($data) {
                DB::table('product_descriptions')->insert($data);
            }
        }
    }
    
    private function saveProductTemplates($request, $product_id)
    {
        DB::table('product_templates')->where('product_id', $product_id)->delete();
        
        $final_dimensions = $request->input('final_dimensions', []);
        $final_dimensions_french = $request->input('final_dimensions_french', []);
        $template_description = $request->input('template_description', []);
        $template_description_french = $request->input('template_description_french', []);
        $template_file_old = $request->input('template_file_old', []);
        
        if (!empty($final_dimensions)) {
            $data = [];
            foreach ($final_dimensions as $key => $val) {
                if (!empty($val)) {
                    $data[] = [
                        'final_dimensions' => $val,
                        'final_dimensions_french' => $final_dimensions_french[$key] ?? '',
                        'template_description' => $template_description[$key] ?? '',
                        'template_description_french' => $template_description_french[$key] ?? '',
                        'template_file' => $template_file_old[$key] ?? '',
                        'product_id' => $product_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
            if ($data) {
                DB::table('product_templates')->insert($data);
            }
        }
    }
    
    private function deleteProductById($id)
    {
        return DB::table('products')->where('id', $id)->delete();
    }
    
    private function resizeImage($filename, $size)
    {
        $sizes = [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ];
        
        if (!isset($sizes[$size])) {
            return;
        }
        
        $sourcePath = public_path('uploads/products/' . $filename);
        $destPath = public_path('uploads/products/' . $size . '/' . $filename);
        
        if (file_exists($sourcePath)) {
            $img = Image::make($sourcePath);
            $img->resize($sizes[$size][0], $sizes[$size][1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($destPath);
        }
    }
    
    // ========== Attribute Management Methods ==========
    
    /**
     * Set multiple attributes (replicate CI Products->SetMultipleAttributes lines 905-925)
     */
    public function SetMultipleAttributes($id = null)
    {
        if (empty($id)) {
            return redirect('admin/Products');
        }
        
        $data = [];
        $data['page_title'] = 'Set Multiple Attributes';
        $data['main_page_url'] = '';
        
        $postData = $this->getProductDataById($id);
        $data['ProductSizes'] = $this->ProductQuantitySizeAttributeDropDown($id);
        $data['MultipleAttributes'] = $this->getMultipleAttributesDropDown();
        $data['postData'] = $postData;
        
        return view('admin.products.product_multiple_attributes', $data);
    }
    
    /**
     * Add/Edit product quantity (replicate CI Products->AddEditProductQuantity lines 927-985)
     */
    public function AddEditProductQuantity(Request $request, $product_id = null, $id = null)
    {
        $quantity = $this->getQuantityListDropDown();
        $data['quantity'] = $quantity;
        $data['BASE_URL'] = url('/');
        
        $quantity_price = $quantity_id = '';
        $success = '0';
        
        if ($request->isMethod('post')) {
            $quantity_id = $request->input('quantity_id');
            $quantity_price = $request->input('quantity_price');
            $product_id = $request->input('product_id');
            $id = $request->input('id');
            
            $ProductSizes = $this->ProductOnlyQuantityDropDown($product_id);
            $QuantityIds = array_keys($ProductSizes);
            
            $quantity_price = !empty($quantity_price) ? $quantity_price : 0;
            $SavedData = [
                'qty' => $quantity_id,
                'price' => $quantity_price,
                'product_id' => $product_id,
            ];
            
            $saveQuantity = true;
            if ($id) {
                $SavedData['id'] = $id;
            }
            
            if ($id != $quantity_id && in_array($quantity_id, $QuantityIds)) {
                session()->flash('message_error', 'This quantity already added to this product.');
                $saveQuantity = false;
            }
            
            if ($saveQuantity) {
                $insert_id = $this->saveProductQty($SavedData, $product_id);
                if ($insert_id > 1) {
                    $success = 1;
                    if ($id) {
                        session()->flash('message_success', 'Updated Quantity Successfully.');
                    } else {
                        session()->flash('message_success', 'Added Quantity Successfully.');
                    }
                } else {
                    session()->flash('message_error', 'Saved Quantity Unsuccessfully.');
                }
            }
        } else {
            $ProductSizes = $this->ProductOnlyQuantityDropDown($product_id);
            $quantity_id = $id;
            $quantity_price = isset($ProductSizes[$quantity_id]['price']) ? $ProductSizes[$quantity_id]['price'] : '';
        }
        
        $data['id'] = $id;
        $data['product_id'] = $product_id;
        $data['quantity_price'] = $quantity_price;
        $data['quantity_id'] = $quantity_id;
        $data['success'] = $success;
        
        return view('admin.products.add_edit_product_quantity', $data);
    }
    
    /**
     * Add/Edit product size (replicate CI Products->AddEditProductSize lines 997-1059)
     */
    public function AddEditProductSize(Request $request, $product_id = null, $quantity_id = null, $id = null)
    {
        $sizes = $this->getSizeListDropDown();
        $data['sizes'] = $sizes;
        $data['BASE_URL'] = url('/');
        
        $size_price = $size_id = '';
        $success = '0';
        
        if ($request->isMethod('post')) {
            $product_id = $request->input('product_id');
            $quantity_id = $request->input('quantity_id');
            $size_price = $request->input('size_price');
            $size_id = $request->input('size_id');
            $id = $request->input('id');
            
            $ProductSizes = $this->ProductOnlySizeDropDown($product_id, $quantity_id);
            $SizesIds = array_keys($ProductSizes);
            
            $size_price = !empty($size_price) ? $size_price : 0;
            $SavedData = [
                'product_id' => $product_id,
                'qty' => $quantity_id,
                'size_id' => $size_id,
                'extra_price' => $size_price,
            ];
            
            $saveQuantity = true;
            if ($id) {
                $SavedData['id'] = $id;
            }
            
            if ($id != $size_id && in_array($size_id, $SizesIds)) {
                session()->flash('message_error', 'This size already added to this product & Quantity');
                $saveQuantity = false;
            }
            
            if ($saveQuantity) {
                $insert_id = $this->saveProductSizeData($SavedData, $product_id);
                if ($insert_id > 1) {
                    $success = 1;
                    if ($id) {
                        session()->flash('message_success', 'Updated Size Successfully.');
                    } else {
                        session()->flash('message_success', 'Added Size Successfully.');
                    }
                } else {
                    session()->flash('message_error', 'Saved Size Unsuccessfully.');
                }
            }
        } else {
            $ProductSizes = $this->ProductOnlySizeDropDown($product_id, $quantity_id);
            $size_id = $id;
            $size_price = isset($ProductSizes[$size_id]['extra_price']) ? $ProductSizes[$size_id]['extra_price'] : '';
        }
        
        $data['product_id'] = $product_id;
        $data['quantity_id'] = $quantity_id;
        $data['id'] = $id;
        $data['size_price'] = $size_price;
        $data['size_id'] = $size_id;
        $data['success'] = $success;
        
        return view('admin.products.add_edit_product_size', $data);
    }
    
    /**
     * Add/Edit product attribute (replicate CI Products->AddEditProductAttribute lines 1061-1135)
     */
    public function AddEditProductAttribute(Request $request, $product_id = null, $quantity_id = null, $size_id = null, $attribute_id = null, $id = null)
    {
        $MultipleAttributes = $this->getMultipleAttributesDropDown();
        $data['BASE_URL'] = url('/');
        
        $attributeData = [];
        $attribute_item_id = $extra_price = '';
        $success = '0';
        
        if ($request->isMethod('post')) {
            $product_id = $request->input('product_id');
            $quantity_id = $request->input('quantity_id');
            $size_id = $request->input('size_id');
            $attribute_id = $request->input('attribute_id');
            $attribute_item_id = $request->input('attribute_item_id');
            $id = $request->input('id');
            $extra_price = $request->input('extra_price');
            
            $ProductSizes = $this->ProductOnlySizeMultipleAttributesDropDown($product_id, $quantity_id, $size_id, $attribute_id);
            $attributeItemsIds = array_keys($ProductSizes);
            
            $extra_price = !empty($extra_price) ? $extra_price : 0;
            $SavedData = [
                'product_id' => $product_id,
                'qty' => $quantity_id,
                'size_id' => $size_id,
                'attribute_id' => $attribute_id,
                'attribute_item_id' => $attribute_item_id,
                'extra_price' => $extra_price,
            ];
            
            $saveQuantity = true;
            $attribute_item_id_old = '';
            
            if ($id) {
                $SavedData['id'] = $id;
                $attributData = $this->ProductSizeMultipleAttributeById($id);
                $attribute_item_id_old = $attributData['attribute_item_id'];
            }
            
            if ($attribute_item_id_old != $attribute_item_id && in_array($attribute_item_id, $attributeItemsIds)) {
                session()->flash('message_error', 'This attribute item already added to this product & Quantity & size');
                $saveQuantity = false;
            }
            
            if ($saveQuantity) {
                $insert_id = $this->saveSizeMultipleAttributesData($SavedData, $product_id);
                if ($insert_id > 1) {
                    $success = 1;
                    if ($id) {
                        session()->flash('message_success', 'Updated attribute item successfully.');
                    } else {
                        session()->flash('message_success', 'Added attribute item Successfully.');
                    }
                } else {
                    session()->flash('message_error', 'Saved attribute item Unsuccessfully.');
                }
            }
        } else {
            if (!empty($id)) {
                $attributData = $this->ProductSizeMultipleAttributeById($id);
            }
            
            $attribute_item_id = isset($attributData['attribute_item_id']) ? $attributData['attribute_item_id'] : '';
            $extra_price = isset($attributData['extra_price']) ? $attributData['extra_price'] : 0;
        }
        
        $data['product_id'] = $product_id;
        $data['quantity_id'] = $quantity_id;
        $data['size_id'] = $size_id;
        $data['attribute_id'] = $attribute_id;
        $data['attribute_item_id'] = $attribute_item_id;
        $data['extra_price'] = $extra_price;
        $data['id'] = $id;
        $data['MultipleAttributes'] = $MultipleAttributes;
        $data['success'] = $success;
        
        return view('admin.products.add_edit_product_multiple_attribute', $data);
    }
    
    /**
     * Set single attributes (replicate CI Products->SetSingleAttributes lines 1157-1232)
     */
    public function SetSingleAttributes(Request $request, $id = null)
    {
        if (empty($id)) {
            return redirect('admin/Products');
        }
        
        $data = [];
        $data['page_title'] = 'Set Single Attributes';
        $data['main_page_url'] = '';
        
        $postData = $this->getProductDataById($id);
        $AttributesList = $this->getAttributesListDropDown();
        $data['AttributesList'] = $AttributesList;
        
        $ProductAttributes = $this->getProductAttributesByItemId($id);
        $data['ProductAttributes'] = $ProductAttributes;
        
        if ($request->isMethod('post')) {
            $postData['id'] = $request->input('id');
            
            $insert_id = $this->saveProduct($postData);
            
            if ($insert_id > 0) {
                $attributes_data = [];
                $attributes_item_data = [];
                
                foreach ($AttributesList as $key => $val) {
                    $attribute_name = 'attribute_id_' . $key;
                    $attribute_id = $request->input($attribute_name);
                    
                    if (!empty($attribute_id)) {
                        $attributes_sdata = [
                            'attribute_id' => $attribute_id,
                            'show_order' => $request->input('attribute_order_' . $attribute_id, 0),
                            'created' => date('Y-m-d H:i:s'),
                            'updated' => date('Y-m-d H:i:s'),
                            'product_id' => $insert_id,
                        ];
                        
                        $product_attribute_item_ids = $request->input('attribute_item_id_' . $attribute_id, []);
                        $attribute_item_orders = $request->input('attribute_item_order_' . $attribute_id, []);
                        $attribute_item_extra_prices = $request->input('attribute_item_extra_price_' . $attribute_id, []);
                        
                        foreach ($product_attribute_item_ids as $subkey => $subval) {
                            if (!empty($subval)) {
                                $attributes_item_sdata = [
                                    'attribute_id' => $attribute_id,
                                    'attribute_item_id' => $subval,
                                    'show_order' => $attribute_item_orders[$subkey],
                                    'extra_price' => $attribute_item_extra_prices[$subkey],
                                    'created' => date('Y-m-d H:i:s'),
                                    'updated' => date('Y-m-d H:i:s'),
                                    'product_id' => $insert_id,
                                ];
                                $attributes_item_data[] = $attributes_item_sdata;
                            }
                        }
                        
                        $attributes_data[] = $attributes_sdata;
                    }
                }
                
                $this->saveProductAttributesData($attributes_data, $attributes_item_data, $insert_id);
                
                session()->flash('message_success', 'Set Single Attributes Successfully.');
                return redirect('admin/Products');
            } else {
                session()->flash('message_error', 'Set Single Attributes Unsuccessfully.');
            }
        }
        
        $data['postData'] = $postData;
        $data['ProductAttributes'] = $ProductAttributes;
        
        return view('admin.products.product_single_attribute', $data);
    }
    
    /**
     * Delete methods for attributes
     */
    public function deleteProductQuantity($product_id = null, $id = null)
    {
        if (!empty($product_id) && !empty($id)) {
            DB::table('product_quantities')->where('product_id', $product_id)->where('qty', $id)->delete();
        }
        return response()->json(['success' => true]);
    }
    
    public function deleteProductSize($product_id = null, $quantity_id = null, $id = null)
    {
        if (!empty($product_id) && !empty($quantity_id) && !empty($id)) {
            DB::table('product_sizes')->where('product_id', $product_id)->where('qty', $quantity_id)->where('size_id', $id)->delete();
        }
        return response()->json(['success' => true]);
    }
    
    public function deleteProductMultipalAttribute($id = null)
    {
        if (!empty($id)) {
            DB::table('product_size_multiple_attributes')->where('id', $id)->delete();
        }
        return response()->json(['success' => true]);
    }
    
    // ========== Attribute Helper Methods ==========
    
    private function ProductQuantitySizeAttributeDropDown($product_id)
    {
        $quantities = DB::table('product_quantities')->where('product_id', $product_id)->get();
        $result = [];
        
        foreach ($quantities as $qty) {
            $sizes = DB::table('product_sizes')->where('product_id', $product_id)->where('qty', $qty->qty)->get();
            foreach ($sizes as $size) {
                $attributes = DB::table('product_size_multiple_attributes')
                    ->where('product_id', $product_id)
                    ->where('qty', $qty->qty)
                    ->where('size_id', $size->size_id)
                    ->get();
                
                $result[$qty->qty][$size->size_id] = [
                    'qty' => $qty->qty,
                    'size_id' => $size->size_id,
                    'attributes' => $attributes->toArray(),
                ];
            }
        }
        
        return $result;
    }
    
    private function getMultipleAttributesDropDown()
    {
        $attributes = DB::table('attributes')->where('status', 1)->get();
        $result = [];
        
        foreach ($attributes as $attr) {
            $items = DB::table('attribute_items')->where('attribute_id', $attr->id)->where('status', 1)->get();
            $result[$attr->id] = [
                'name' => $attr->name,
                'items' => $items->toArray(),
            ];
        }
        
        return $result;
    }
    
    private function ProductOnlyQuantityDropDown($product_id)
    {
        $quantities = DB::table('product_quantities')->where('product_id', $product_id)->get();
        $result = [];
        
        foreach ($quantities as $qty) {
            $result[$qty->qty] = (array) $qty;
        }
        
        return $result;
    }
    
    private function ProductOnlySizeDropDown($product_id, $quantity_id)
    {
        $sizes = DB::table('product_sizes')->where('product_id', $product_id)->where('qty', $quantity_id)->get();
        $result = [];
        
        foreach ($sizes as $size) {
            $result[$size->size_id] = (array) $size;
        }
        
        return $result;
    }
    
    private function ProductOnlySizeMultipleAttributesDropDown($product_id, $quantity_id, $size_id, $attribute_id)
    {
        $attributes = DB::table('product_size_multiple_attributes')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->where('size_id', $size_id)
            ->where('attribute_id', $attribute_id)
            ->get();
        
        $result = [];
        foreach ($attributes as $attr) {
            $result[$attr->attribute_item_id] = (array) $attr;
        }
        
        return $result;
    }
    
    private function ProductSizeMultipleAttributeById($id)
    {
        $attr = DB::table('product_size_multiple_attributes')->where('id', $id)->first();
        return $attr ? (array) $attr : [];
    }
    
    private function getSizeListDropDown()
    {
        return DB::table('sizes')->where('status', 1)->pluck('name', 'id')->toArray();
    }
    
    private function getAttributesListDropDown()
    {
        return DB::table('attributes')->where('status', 1)->get()->toArray();
    }
    
    private function getProductAttributesByItemId($product_id)
    {
        $attributes = DB::table('product_attributes')->where('product_id', $product_id)->get();
        $result = [];
        
        foreach ($attributes as $attr) {
            $items = DB::table('product_attribute_items')->where('product_id', $product_id)->where('attribute_id', $attr->attribute_id)->get();
            $result[$attr->attribute_id] = [
                'attribute' => (array) $attr,
                'items' => $items->toArray(),
            ];
        }
        
        return $result;
    }
    
    private function saveProductQty($data, $product_id)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('product_quantities')->where('product_id', $product_id)->where('qty', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('product_quantities')->insertGetId($data);
        }
    }
    
    private function saveProductSizeData($data, $product_id)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('product_sizes')->where('product_id', $product_id)->where('size_id', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('product_sizes')->insertGetId($data);
        }
    }
    
    private function saveSizeMultipleAttributesData($data, $product_id)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('product_size_multiple_attributes')->where('id', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('product_size_multiple_attributes')->insertGetId($data);
        }
    }
    
    private function saveProductAttributesData($attributes_data, $attributes_item_data, $product_id)
    {
        DB::table('product_attributes')->where('product_id', $product_id)->delete();
        DB::table('product_attribute_items')->where('product_id', $product_id)->delete();
        
        if (!empty($attributes_data)) {
            DB::table('product_attributes')->insert($attributes_data);
        }
        
        if (!empty($attributes_item_data)) {
            DB::table('product_attribute_items')->insert($attributes_item_data);
        }
        
        return true;
    }
}
