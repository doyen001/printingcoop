{{-- 
    Expand Attribute IDs Component
    CI: application/views/Products/expand_attribute_ids.php
    
    Displays selected product attributes in a responsive grid layout
    
    @param array $attribute_ids - Array of attribute name/value pairs
    @param string $language_name - Language preference ('French' or 'english')
--}}

{{-- <div class="attribute-display-container"> --}}

    @if(!empty($attribute_ids) && is_array($attribute_ids))
        {{-- Display Standard Attributes --}}
        @foreach($attribute_ids as $key => $val)
            @if($key === 'custom')
                @continue
            @endif
            
            @php
                // Skip primitive values (we expect object/array structures)
                $is_object = is_object($val);
                $is_array = is_array($val);
                if (!$is_object && !$is_array) {
                    continue;
                }

                // Multi-language support - handle both object and array access
                if ($language_name == 'French') {
                    $attribute_name = $is_object
                        ? ($val->attribute_name_french ?? $val->attribute_name ?? '')
                        : ($val['attribute_name_french'] ?? ($val['attribute_name'] ?? ''));
                    $item_name = $is_object
                        ? ($val->item_name_french ?? $val->item_name ?? '')
                        : ($val['item_name_french'] ?? ($val['item_name'] ?? ''));
                } else {
                    $attribute_name = $is_object
                        ? ($val->attribute_name ?? '')
                        : ($val['attribute_name'] ?? '');
                    $item_name = $is_object
                        ? ($val->item_name ?? '')
                        : ($val['item_name'] ?? '');
                }
            @endphp
            
            <div class="col-md-12 col-lg-6 col-xl-6">
                <span><strong>{{ $attribute_name }}: {{ $item_name }}</strong></span>
            </div>
        @endforeach
        
        {{-- Display Custom Attributes --}}
        @if(isset($attribute_ids['custom']) && is_array($attribute_ids['custom']))
            @php
                $custom_label = ($language_name == 'French') ? 'Douane' : 'Custom';
            @endphp
            
            @foreach($attribute_ids['custom'] as $val)
                @php
                    // Multi-language support for custom attributes - handle both object and array access
                    $is_object = is_object($val);
                    $is_array = is_array($val);
                    if (!$is_object && !$is_array) {
                        continue;
                    }

                    if ($language_name == 'French') {
                        $attribute_name = $is_object
                            ? ($val->attribute_name_french ?? $val->attribute_name ?? '')
                            : ($val['attribute_name_french'] ?? ($val['attribute_name'] ?? ''));
                        $item_name = $is_object
                            ? ($val->item_name_french ?? $val->item_name ?? '')
                            : ($val['item_name_french'] ?? ($val['item_name'] ?? ''));
                    } else {
                        $attribute_name = $is_object
                            ? ($val->attribute_name ?? '')
                            : ($val['attribute_name'] ?? '');
                        $item_name = $is_object
                            ? ($val->item_name ?? '')
                            : ($val['item_name'] ?? '');
                    }
                @endphp
                
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <span><strong>{{ $attribute_name }} ({{ $custom_label }}): {{ $item_name }}</strong></span>
                </div>
            @endforeach
        @endif
    @endif
{{-- </div> --}}