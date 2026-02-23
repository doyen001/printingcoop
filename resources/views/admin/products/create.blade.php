{{-- 
    Admin product create form (replicate CI admin/Products/addEdit.php for create)
    
    Available variables:
    - $postData: Product data array
    - $StoreList: Store dropdown list
    - $Categoty: Categories and subcategories
    - $quantity: Quantity dropdown list
--}}

@extends('layouts.admin')

@section('content')
<div class="admin-product-create-page">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>{{ $page_title }}</h1>
        <div class="header-actions">
            <a href="{{ url('admin/Products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
    
    {{-- Product Form --}}
    <form method="POST" action="{{ url('admin/Products/addEdit') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                {{-- Basic Information --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Basic Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Product Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Product Name (French)</label>
                            <input type="text" name="name_french" class="form-control" value="{{ old('name_french') }}">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code (French)</label>
                                    <input type="text" name="code_french" class="form-control" value="{{ old('code_french') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Model</label>
                                    <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Model (French)</label>
                                    <input type="text" name="model_french" class="form-control" value="{{ old('model_french') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Short Description (English)</label>
                            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Short Description (French)</label>
                            <textarea name="short_description_french" class="form-control" rows="3">{{ old('short_description_french') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Full Description (English)</label>
                            <textarea name="full_description" class="form-control" rows="5">{{ old('full_description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Full Description (French)</label>
                            <textarea name="full_description_french" class="form-control" rows="5">{{ old('full_description_french') }}</textarea>
                        </div>
                    </div>
                </div>
                
                {{-- Product Images --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Product Images</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Upload Images</label>
                            <input type="file" name="files[]" class="form-control" multiple accept="image/*">
                            <small class="form-text text-muted">You can select multiple images. Supported formats: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>
                
                {{-- Shipping Box Dimensions --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Shipping Box Dimensions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Length</label>
                                    <input type="number" step="0.01" name="shipping_box_length" class="form-control" value="{{ old('shipping_box_length', '0') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Width</label>
                                    <input type="number" step="0.01" name="shipping_box_width" class="form-control" value="{{ old('shipping_box_width', '0') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Height</label>
                                    <input type="number" step="0.01" name="shipping_box_height" class="form-control" value="{{ old('shipping_box_height', '0') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Weight</label>
                                    <input type="number" step="0.01" name="shipping_box_weight" class="form-control" value="{{ old('shipping_box_weight', '0') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                {{-- Product Options --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Product Options</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_stock" name="is_stock" value="1" {{ old('is_stock') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_stock">Track Stock</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="use_custom_size" name="use_custom_size" value="1" {{ old('use_custom_size') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="use_custom_size">Use Custom Size</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="votre_text" name="votre_text" value="1" {{ old('votre_text') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="votre_text">Votre Text</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="recto_verso" name="recto_verso" value="1" {{ old('recto_verso') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="recto_verso">Recto Verso</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Recto Verso Price</label>
                            <input type="number" step="0.01" name="recto_verso_price" class="form-control" value="{{ old('recto_verso_price', '0') }}">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="call" name="call" value="1" {{ old('call') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="call">Call for Price</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        </div>
                    </div>
                </div>
                
                {{-- Length/Width Settings --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Length/Width Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="add_length_width" name="add_length_width" value="1" {{ old('add_length_width') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="add_length_width">Enable Length/Width</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Min Length</label>
                                    <input type="number" step="0.01" name="min_length" class="form-control" value="{{ old('min_length', '0') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Length</label>
                                    <input type="number" step="0.01" name="max_length" class="form-control" value="{{ old('max_length', '0') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Min Width</label>
                                    <input type="number" step="0.01" name="min_width" class="form-control" value="{{ old('min_width', '0') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Width</label>
                                    <input type="number" step="0.01" name="max_width" class="form-control" value="{{ old('max_width', '0') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Min Length/Min Width Price</label>
                            <input type="number" step="0.01" name="min_length_min_width_price" class="form-control" value="{{ old('min_length_min_width_price', '0') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Unit Price (Black)</label>
                            <input type="number" step="0.01" name="length_width_unit_price_black" class="form-control" value="{{ old('length_width_unit_price_black', '0') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Price (Color)</label>
                            <input type="number" step="0.01" name="length_width_price_color" class="form-control" value="{{ old('length_width_price_color', '0') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Form Actions --}}
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <a href="{{ url('admin/Products') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
