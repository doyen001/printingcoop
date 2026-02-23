{{-- CI: application/views/Products/size_options.php --}}
@php
    $i = 2;
    $j = 1;
    $k = 2;
    $last = 1;
    
    if (!empty($AtirbuteProductSizes)) {
        $last = $last + count($AtirbuteProductSizes);
    }
@endphp

{{-- Size Dropdown Selector --}}
@if(!empty($options_size))
    @php
        if ($j == $last) {
            $onchange = "getPaperPrice('$i')";
        } else {
            $onchange = "showSizeQuantity()";
        }
    @endphp
    
    <div class="single-review size-selector">
        <label>
            {{ $language_name == 'french' ? 'Taille' : 'Size' }}
            <span class="required">*</span>
        </label>
        <select name="product_size_id" 
                required 
                onchange="{{ $onchange }}" 
                {{ $size_disabled ? 'disabled' : '' }}
                class="multipal_size form-select">
            {!! $options_size !!}
        </select>
    </div>
    
    @php
        $j++;
        $k++;
    @endphp
@endif

{{-- Dynamic Attribute Selectors (Cascading) --}}
@php
    $l = 1;
@endphp

@foreach($AtirbuteProductSizes as $mkey => $mval)
    @php
        // Determine attribute items based on selections
        if (!empty($product_quantity_id) && !empty($product_size_id)) {
            $attribute_items = isset($mval['attribute_items']) ? $mval['attribute_items'] : [];
        } else if (!empty($product_quantity_id) && empty($product_size_id)) {
            $attribute_items = [];
        } else {
            $attribute_items = [];
        }
        
        // Determine onchange handler
        if ($j == $last) {
            $onchange = "getPaperPrice('$i')";
        } else {
            $onchange = "getQuantityPrice('product_size_option_$k')";
        }
        
        // Determine disabled state (cascading selection)
        $disabled = 'disabled';
        if (!empty($product_size_id) && $l == 1) {
            $disabled = '';
        }
    @endphp
    
    <div class="single-review attribute-selector">
        <label>
            {{ $language_name == 'french' ? $mval['attributes_name_french'] : $mval['attribute_name'] }}
            <span class="required">*</span>
        </label>
        <select name="multiple_attribute_{{ $mkey }}" 
                required 
                id="product_size_option_{{ $j }}" 
                {{ $disabled }}
                onchange="{{ $onchange }}" 
                class="multipal_size multipal_size_item form-select">
            {!! $options !!}
            @foreach($attribute_items as $akey => $aval)
                <option value="{{ $akey }}">
                    {{ $language_name == 'french' ? $aval['attributes_item_name_french'] : $aval['attributes_item_name'] }}
                </option>
            @endforeach
        </select>
    </div>
    
    @php
        $j++;
        $k++;
        $l++;
    @endphp
@endforeach

{{-- AJAX Price Calculation Functions --}}
<script>
/**
 * Get quantity-based price calculation
 * Enables next attribute selector after successful calculation
 * @param {string} nid - Next input ID to enable
 */
function getQuantityPrice(nid) {
    // Show loading indicator
    $('#loader-img').show();
    $('.new-price-img').hide();
    
    var myForm = document.getElementById('cartForm');
    var formData = new FormData(myForm);
    
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("Products/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            // Hide loading indicator
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            try {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    // Enable next attribute selector (cascading)
                    $('#' + nid).attr("disabled", false);
                    
                    // Update price display
                    $('#total-price').html(json.price);
                    
                    // Update hidden price field
                    $('[name="price"]').val(json.price);
                }
            } catch (e) {
                console.error('Error parsing price response:', e);
            }
        },
        error: function(resp) {
            console.error('Price calculation error:', resp);
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            // Show error message
            if (resp.responseJSON && resp.responseJSON.message) {
                alert('{{ $language_name == "french" ? "Erreur de calcul du prix: " : "Price calculation error: " }}' + resp.responseJSON.message);
            }
        }
    });
}

/**
 * Get paper/final price calculation
 * Final step in cascading selection
 * @param {string} nid - Attribute ID
 */
function getPaperPrice(nid) {
    // Show loading indicator
    $('#loader-img').show();
    $('.new-price-img').hide();
    
    var myForm = document.getElementById('cartForm');
    var formData = new FormData(myForm);
    
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("Products/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            // Hide loading indicator
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            try {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    // Enable attribute if needed
                    $('#attribute_id_' + nid).attr("disabled", false);
                    
                    // Update price display
                    $('#total-price').html(json.price);
                    
                    // Update hidden price field
                    $('[name="price"]').val(json.price);
                }
            } catch (e) {
                console.error('Error parsing price response:', e);
            }
        },
        error: function(resp) {
            console.error('Price calculation error:', resp);
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            // Show error message
            if (resp.responseJSON && resp.responseJSON.message) {
                alert('{{ $language_name == "french" ? "Erreur de calcul du prix: " : "Price calculation error: " }}' + resp.responseJSON.message);
            }
        }
    });
}

/**
 * Show size quantity options
 * Called when size is changed to enable first attribute selector
 */
function showSizeQuantity() {
    // Show loading indicator
    $('#loader-img').show();
    $('.new-price-img').hide();
    
    var myForm = document.getElementById('cartForm');
    var formData = new FormData(myForm);
    
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("Products/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            // Hide loading indicator
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            try {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    // Enable first attribute selector
                    $('.multipal_size_item').first().attr("disabled", false);
                    
                    // Update price display
                    $('#total-price').html(json.price);
                    
                    // Update hidden price field
                    $('[name="price"]').val(json.price);
                    
                    // Reset subsequent selectors
                    $('.multipal_size_item').not(':first').attr("disabled", true);
                    $('.multipal_size_item').not(':first').val('');
                }
            } catch (e) {
                console.error('Error parsing price response:', e);
            }
        },
        error: function(resp) {
            console.error('Price calculation error:', resp);
            $('#loader-img').hide();
            $('.new-price-img').show();
            
            // Show error message
            if (resp.responseJSON && resp.responseJSON.message) {
                alert('{{ $language_name == "french" ? "Erreur de calcul du prix: " : "Price calculation error: " }}' + resp.responseJSON.message);
            }
        }
    });
}

/**
 * Initialize size options component
 */
$(document).ready(function() {
    // Disable all attribute selectors initially if no size is selected
    if (!$('[name="product_size_id"]').val()) {
        $('.multipal_size_item').attr('disabled', true);
    }
    
    // Add change handler for size selector
    $('[name="product_size_id"]').on('change', function() {
        // Reset all attribute selectors
        $('.multipal_size_item').val('');
        $('.multipal_size_item').attr('disabled', true);
    });
    
    // Add visual feedback for disabled selectors
    $('.multipal_size').on('change', function() {
        if ($(this).is(':disabled')) {
            $(this).addClass('disabled-select');
        } else {
            $(this).removeClass('disabled-select');
        }
    });
    
    // Trigger initial state
    $('.multipal_size').trigger('change');
});
</script>

{{-- Component Styling --}}
<style>
/* Size Options Component Styles */
.size-selector,
.attribute-selector {
    margin-bottom: 20px;
}

.size-selector label,
.attribute-selector label {
    display: block;
    margin-bottom: 10px;
    color: #183e73;
    font-weight: 500;
    font-size: 14px;
}

.size-selector .required,
.attribute-selector .required {
    color: #e74c3c;
    margin-left: 2px;
}

.form-select,
.multipal_size {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    appearance: none;
    cursor: pointer;
}

.form-select:focus,
.multipal_size:focus {
    outline: none;
    border-color: #183e73;
    box-shadow: 0 0 0 2px rgba(24, 62, 115, 0.1);
}

.form-select:disabled,
.multipal_size:disabled,
.disabled-select {
    background-color: #f5f5f5;
    cursor: not-allowed;
    opacity: 0.6;
}

.form-select:hover:not(:disabled),
.multipal_size:hover:not(:disabled) {
    border-color: #183e73;
}

/* Loading Indicator */
#loader-img {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: rgba(255, 255, 255, 0.95);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

#loader-img::after {
    content: "";
    display: block;
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #183e73;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Price Display */
.new-price-img {
    transition: opacity 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .size-selector,
    .attribute-selector {
        margin-bottom: 15px;
    }
    
    .form-select,
    .multipal_size {
        font-size: 16px; /* Prevent zoom on iOS */
    }
}

/* Accessibility */
.form-select:focus-visible,
.multipal_size:focus-visible {
    outline: 2px solid #183e73;
    outline-offset: 2px;
}

/* Error State */
.form-select.error,
.multipal_size.error {
    border-color: #e74c3c;
}

.form-select.error:focus,
.multipal_size.error:focus {
    box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.1);
}

/* Success State */
.form-select.success,
.multipal_size.success {
    border-color: #28a745;
}

/* Cascading Selection Visual Feedback */
.multipal_size_item:not(:disabled) {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tooltip for disabled selectors */
.multipal_size:disabled::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.multipal_size:disabled:hover::before {
    opacity: 1;
}
</style>
