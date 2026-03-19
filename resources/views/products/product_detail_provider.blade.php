{{-- CI: application/views/Products/product_detail_provider.php --}}
<input type="hidden" name="provider_id" value="{{ $provider['id'] }}">
<input type="hidden" name="provider_product_id" value="{{ $providerProduct->provider_product_id }}">
<input type="hidden" name="product_id" value="{{ $provider['product_id'] }}">
<div class="col-md-12 col-md-12 col-md-8">
    @php
        $sina = config('sina');
        $shipping_extra_days = $sina['shipping_extra_days'] ?? 0;
    @endphp
    
    @foreach($provider['options'] as $option)
        <div class="single-review option-{{ preg_replace('/[ \?]+/i', '-', $option['name']) }}">
            <label>{{ ucfirst(!empty($option['attribute_id']) ? ($language_name == 'french' ? $option['attribute_name_french'] : $option['attribute_name']) : $option['label']) }} <span class="required">*</span></label>
            
            @if($option['html_type'] == 'input')
                <input type="text" class="product-option field" name="productOptions[{{ $option['name'] }}]" required id="attribute-{{ $option['id'] }}">
            @elseif($option['html_type'] == 'radio')
                <div class="field">
                    @if(isset($option['values']) && is_array($option['values']))
                        @foreach($option['values'] as $item)
                            <div class="shape-icon radio-icon">
                                <input id="attribute-{{ $item['provider_option_value_id'] }}" type="radio" class="product-option" 
                                    name="productOptions[{{ $option['name'] }}]" 
                                    value="{{ $providerProduct->information_type == 2 ? $item['value'] : $item['provider_option_value_id'] }}">
                                <label for="attribute-{{ $item['provider_option_value_id'] }}">
                                    @if(!empty($item['img_src']))
                                        <img class="no-lazy" src="https://sinalite.com/pub/{{ $item['img_src'] }}" style="width: 32px;margin-top: 8px;cursor: pointer;">
                                    @endif
                                    <div>{{ $item['value'] }}</div>
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            @else
                <select class="product-option field" name="productOptions[{{ $option['name'] }}]" required id="attribute-{{ $option['id'] }}">
                    <option value="">
                        @if(!empty($option['attribute_id']))
                            {{ ucfirst($language_name == 'french' ? "Sélectionnez {$option['attribute_name_french']}" : "Select {$option['attribute_name']}") }}
                        @else
                            Select {{ ucfirst($option['label']) }}
                        @endif
                    </option>
                    @if(isset($option['values']) && is_array($option['values']))
                        @foreach($option['values'] as $item)
                            <option data-zo="{{ $providerProduct->information_type }}" data-foo="{{ $item['price_rate'] ?? '' }}" value="{{ $providerProduct->information_type == 2 ? $item['value'] : $item['provider_option_value_id'] }}">
                                {{ ucfirst(($item['type'] ?? $option['type'] ?? 0) == 8 ? option_turnaround_add_days($item['value'], $shipping_extra_days) : $item['value']) }}
                            </option>
                            {{-- <option data-zo="{{ $providerProduct->information_type }}" data-foo="{{ $item['price_rate'] ?? '' }}" value="{{ $item['price_rate'] ?? '' }}">
                                {{ $item['price_rate'] ?? '' }}
                            </option> --}}
                        @endforeach
                    @endif
                </select>
            @endif
            <span style="color:red" id="attribute-{{ $option['id'] }}_error"></span>
        </div>
    @endforeach
</div>

<script>
    var realPriceRate = 0;
    $(document).ready(function() {
        $('.option-width').hide();
        $('.option-length').hide();
        $('.option-diameter').hide();

        $('.single-review select').on('change', updatePrice);
        $('.single-review input').on('change', updatePrice);
    });
    
    function updatePrice() {
        // Update realPriceRate if this is a select element with price_rate data
        if (this.tagName === 'SELECT') {
            getPrice(this);
        }
        
        if ($(this).attr('name') == 'productOptions[shape]') {
            var value = $(this).val();
            var label = $(this).closest('.shape-icon').find('div').text().trim().toLowerCase();
            // Only circle (ID: 1) needs diameter instead of width/length
            // Oval needs width AND length
            if (value == 'circle' || label == 'circle' || value == '1') {
                $('.option-width').hide().find('input').prop('required', false);
                $('.option-length').hide().find('input').prop('required', false);
                $('.option-diameter').show().find('input').prop('required', true);
            } else {
                $('.option-width').show().find('input').prop('required', true);
                $('.option-length').show().find('input').prop('required', true);
                $('.option-diameter').hide().find('input').prop('required', false);
            }
        }

        var formData = $('#cartForm').serializeArray();
        
        var filled = 0;
        for (var i = 0; i < formData.length; i++) {
            const regex = /productOptions\[(.*)\]/;
            const found = formData[i].name.match(regex);
            if (found) {
                var fieldName = found[1];
                if ($(`.single-review.option-${fieldName.replace(/[ \?]+/gm, '-')}`).is(":visible")) {
                    if (formData[i].value != null && formData[i].value != '')
                        filled++;
                }
            }
        }
        if (filled < $('.single-review:visible').length)
            return;

        $('#loader-img').show();
        $('.new-price-img').hide();
        $.ajax({
            url: '{{ url("Products/ProviderPrice") }}',
            type: 'POST',
            data: {params: $('#cartForm').serialize()},
            headers: { 
                accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // alert("success");
                if (filled == $('.single-review:visible').length) {
                    $('#loader-img').hide();
                    $('.new-price-img').show();
                }
                if (data.success) {
                    if(realPriceRate == null || realPriceRate == '' || realPriceRate == 0) {
                        realPriceRate = {{ $provider['price_rate'] ?? 1 }};
                    }
                    var price = isNaN(Number(data.price.price)) ? 0 : Number(data.price.price) * realPriceRate;
                    if (price == 0) {
                        alert('This options are not supported from the service provider. Please select another options.');
                    }
                    $('[name="price"]').val(price);
                    $('#total-price').html((price * $('#quantity').val()).toFixed(2));
                } else
                    alert(data.message);
            },
            error: function (resp) {
                // alert("error");
                console.log(resp);
                $('#loader-img').hide();
                $('.new-price-img').show();
            }
        });
        return;
    }

    function getPrice(selectElement) {
        if (selectElement && selectElement.selectedIndex >= 0) {
            var selected = selectElement.options[selectElement.selectedIndex];
            var priceRate = selected.getAttribute('data-foo');
            if (priceRate && priceRate !== '') {
                realPriceRate = Number(priceRate);
            }
        }
   }

// Initialize shape field visibility on page load
$(document).ready(function() {
    var selectedShape = $('input[name="productOptions[shape]"]:checked');
    if (selectedShape.length > 0) {
        var value = selectedShape.val();
        var label = selectedShape.closest('.shape-icon').find('div').text().trim().toLowerCase();
        // Only circle (ID: 1) needs diameter instead of width/length
        // Oval needs width AND length
        if (value == 'circle' || label == 'circle' || value == '1') {
            $('.option-width').hide().find('input').prop('required', false);
            $('.option-length').hide().find('input').prop('required', false);
            $('.option-diameter').show().find('input').prop('required', true);
        } else {
            $('.option-width').show().find('input').prop('required', true);
            $('.option-length').show().find('input').prop('required', true);
            $('.option-diameter').hide().find('input').prop('required', false);
        }
    }
});
</script>
