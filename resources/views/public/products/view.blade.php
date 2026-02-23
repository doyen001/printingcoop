{{-- 
    Product detail page (replicate CI Products/view.php)
    
    Available variables:
    - $Product: Product data array
    - $ProductImages: Product images array
    - $ProductDescriptions: Product descriptions
    - $ProductTemplates: Product templates
    - $ProductAttributes: Product attributes
    - $ProductSizes: Product sizes/quantities
    - $ProductPages, $ProductSheets, $pageQuantity
    - $productRowid, $productQty: Cart data
    - $attributes, $attribute_items: New attribute structure
    - $provider, $providerProduct: Provider integration data
--}}

@extends('elements.app')

@section('content')
<div class="product-detail">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('Products') }}">Products</a></li>
            
            @if(isset($Product['multipalCategoryData']) && count($Product['multipalCategoryData']) > 0)
                @php $firstCategory = reset($Product['multipalCategoryData']); @endphp
                <li class="breadcrumb-item">
                    <a href="{{ url('Products?category_id=' . base64_encode($firstCategory['id'])) }}">
                        {{ config('store.language_name') == 'French' ? $firstCategory['name_french'] : $firstCategory['name'] }}
                    </a>
                </li>
            @endif
            
            <li class="breadcrumb-item active">
                {{ config('store.language_name') == 'French' ? $Product['name_french'] : $Product['name'] }}
            </li>
        </ol>
    </nav>
    
    <div class="row">
        {{-- Product Images --}}
        <div class="col-md-6">
            <div class="product-images">
                {{-- Main Image --}}
                <div class="main-image">
                    <img src="{{ url('uploads/products/large/' . $Product['product_image']) }}" 
                         alt="{{ $Product['name'] }}" 
                         class="img-fluid" 
                         id="mainProductImage">
                </div>
                
                {{-- Thumbnail Gallery --}}
                @if(count($ProductImages) > 0)
                <div class="image-gallery">
                    <div class="thumbnail">
                        <img src="{{ url('uploads/products/medium/' . $Product['product_image']) }}" 
                             alt="{{ $Product['name'] }}" 
                             onclick="changeMainImage(this.src)">
                    </div>
                    
                    @foreach($ProductImages as $image)
                    <div class="thumbnail">
                        <img src="{{ url('uploads/products/medium/' . $image->image) }}" 
                             alt="{{ $Product['name'] }}" 
                             onclick="changeMainImage(this.src)">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        {{-- Product Information --}}
        <div class="col-md-6">
            <div class="product-info">
                <h1 class="product-title">
                    {{ config('store.language_name') == 'French' ? $Product['name_french'] : $Product['name'] }}
                </h1>
                
                {{-- Rating --}}
                @if($Product['rating'] > 0)
                <div class="product-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa{{ $i <= $Product['rating'] ? 's' : 'r' }} fa-star"></i>
                    @endfor
                    <span>({{ $Product['reviews'] }} reviews)</span>
                </div>
                @endif
                
                {{-- Price --}}
                <div class="product-price">
                    <span class="price" id="productPrice">
                        {{ config('store.product_price_currency_symbol') }}{{ number_format($Product['price'], 2) }}
                    </span>
                </div>
                
                {{-- Short Description --}}
                @if(!empty($Product['short_description']))
                <div class="short-description">
                    {!! config('store.language_name') == 'French' ? $Product['short_description_french'] : $Product['short_description'] !!}
                </div>
                @endif
                
                {{-- Product Configuration Form --}}
                <form id="productConfigForm" class="product-config-form">
                    <input type="hidden" name="product_id" value="{{ $Product['id'] }}">
                    <input type="hidden" name="price" value="{{ $Product['price'] }}">
                    
                    {{-- Provider Product Options --}}
                    @if($provider !== false)
                    <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                    
                    <div class="provider-options">
                        <h4>Product Options</h4>
                        @foreach($provider->options as $option)
                        <div class="form-group">
                            <label>{{ $option->name }}</label>
                            <select name="productOptions[{{ $option->id }}]" class="form-control" required>
                                <option value="">Select {{ $option->name }}</option>
                                @if(isset($option->values))
                                    @foreach($option->values as $value)
                                    <option value="{{ $value->provider_option_value_id }}">
                                        {{ $value->value }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endforeach
                    </div>
                    @else
                    {{-- Regular Product Attributes --}}
                    @if(count($attributes) > 0)
                    <div class="product-attributes">
                        <h4>Product Attributes</h4>
                        @foreach($attributes as $attribute)
                        <div class="form-group">
                            <label>{{ $attribute->attribute_name }}</label>
                            
                            @if($attribute->use_items == 1)
                                <select name="attributes[{{ $attribute->id }}]" class="form-control">
                                    <option value="">Select {{ $attribute->attribute_name }}</option>
                                    @foreach($attribute_items as $item)
                                        @if($item->attribute_id == $attribute->id)
                                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <input type="number" 
                                       name="attributes[{{ $attribute->id }}]" 
                                       class="form-control" 
                                       min="{{ $attribute->value_min }}" 
                                       max="{{ $attribute->value_max }}" 
                                       step="0.01">
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    {{-- Product Sizes/Quantities --}}
                    @if(count($ProductSizes) > 0)
                    <div class="form-group">
                        <label>Quantity</label>
                        <select name="product_quantity_id" id="productQuantity" class="form-control">
                            <option value="">Select Quantity</option>
                            @foreach($ProductSizes as $size)
                            <option value="{{ $size->id }}" data-price="{{ $size->price }}">
                                {{ $size->qty_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    {{-- Width/Length Options --}}
                    @if($Product['add_length_width'] == 1)
                    <div class="dimension-options">
                        <h4>Custom Dimensions</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Length ({{ $Product['min_length'] }} - {{ $Product['max_length'] }})</label>
                                    <input type="number" 
                                           name="product_length" 
                                           class="form-control" 
                                           min="{{ $Product['min_length'] }}" 
                                           max="{{ $Product['max_length'] }}" 
                                           step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Width ({{ $Product['min_width'] }} - {{ $Product['max_width'] }})</label>
                                    <input type="number" 
                                           name="product_width" 
                                           class="form-control" 
                                           min="{{ $Product['min_width'] }}" 
                                           max="{{ $Product['max_width'] }}" 
                                           step="0.1">
                                </div>
                            </div>
                        </div>
                        
                        @if($Product['length_width_color_show'] == 1)
                        <div class="form-group">
                            <label>Color</label>
                            <select name="length_width_color" class="form-control">
                                <option value="black">Black</option>
                                <option value="color">Color</option>
                            </select>
                        </div>
                        @endif
                        
                        @if($Product['length_width_quantity_show'] == 1)
                        <div class="form-group">
                            <label>Quantity ({{ $Product['length_width_min_quantity'] }} - {{ $Product['length_width_max_quantity'] }})</label>
                            <input type="number" 
                                   name="product_total_page" 
                                   class="form-control" 
                                   min="{{ $Product['length_width_min_quantity'] }}" 
                                   max="{{ $Product['length_width_max_quantity'] }}">
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- Depth Options --}}
                    @if($Product['depth_add_length_width'] == 1)
                    <div class="depth-options">
                        <h4>3D Dimensions</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Length</label>
                                    <input type="number" name="product_depth_length" class="form-control" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Width</label>
                                    <input type="number" name="product_depth_width" class="form-control" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Depth</label>
                                    <input type="number" name="product_depth" class="form-control" step="0.1">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Recto Verso Option --}}
                    @if($Product['recto_verso'] == 1)
                    <div class="form-group">
                        <label>Recto Verso</label>
                        <select name="recto_verso" class="form-control">
                            <option value="No">No</option>
                            <option value="Yes">Yes (+{{ $Product['recto_verso_price'] }}%)</option>
                        </select>
                        <input type="hidden" name="recto_verso_price" value="{{ $Product['recto_verso_price'] }}">
                    </div>
                    @endif
                    @endif
                    
                    {{-- Quantity --}}
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" id="productQuantityInput">
                    </div>
                    
                    {{-- Add to Cart Button --}}
                    <div class="product-actions">
                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        
                        @if(!empty($productRowid))
                        <div class="cart-info">
                            Already in cart: {{ $productQty }} items
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Product Details Tabs --}}
    <div class="product-details-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#description">Description</a>
            </li>
            @if(count($ProductDescriptions) > 0)
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#specifications">Specifications</a>
            </li>
            @endif
            @if(count($ProductTemplates) > 0)
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#templates">Templates</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reviews">Reviews</a>
            </li>
        </ul>
        
        <div class="tab-content">
            {{-- Description Tab --}}
            <div id="description" class="tab-pane fade show active">
                {!! config('store.language_name') == 'French' ? $Product['description_french'] : $Product['description'] !!}
            </div>
            
            {{-- Specifications Tab --}}
            @if(count($ProductDescriptions) > 0)
            <div id="specifications" class="tab-pane fade">
                @foreach($ProductDescriptions as $desc)
                <div class="specification-item">
                    <h5>{{ config('store.language_name') == 'French' ? $desc->title_french : $desc->title }}</h5>
                    <p>{!! config('store.language_name') == 'French' ? $desc->description_french : $desc->description !!}</p>
                </div>
                @endforeach
            </div>
            @endif
            
            {{-- Templates Tab --}}
            @if(count($ProductTemplates) > 0)
            <div id="templates" class="tab-pane fade">
                <div class="templates-list">
                    @foreach($ProductTemplates as $template)
                    <div class="template-item">
                        <a href="{{ url('uploads/templates/' . $template->file) }}" download>
                            <i class="fas fa-download"></i> {{ $template->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Reviews Tab --}}
            <div id="reviews" class="tab-pane fade">
                <div class="reviews-section">
                    {{-- Review form and list would go here --}}
                    <h4>Customer Reviews</h4>
                    <p>Rating: {{ $Product['rating'] }}/5 ({{ $Product['reviews'] }} reviews)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(src) {
    document.getElementById('mainProductImage').src = src.replace('/medium/', '/large/');
}

function addToCart() {
    // Implement add to cart functionality
    const formData = new FormData(document.getElementById('productConfigForm'));
    
    fetch('{{ url("ShoppingCarts/addToCart") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status == 1) {
            alert(data.msg);
            location.reload();
        } else {
            alert(data.msg);
        }
    });
}
</script>
@endsection
