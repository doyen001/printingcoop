{{-- CI: application/views/elements/HomeSections/our_ink_printed_products.php --}}
{{-- EcoInk: Our Ink Products Section --}}
<div class="product-main-section service-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="trend-section-inner">
            <div class="universal-dark-title">
                <span>
                    {{ $language_name == 'french' ? "Nos produits d'encre" : 'Our Ink Products' }}
                </span>
            </div>
            
            @if(isset($our_ink_printed_products) && count($our_ink_printed_products) > 0)
                <div class="universal-row">
                    <div class="row justify-content-center">
                        @php $i = 1; @endphp
                        @foreach($our_ink_printed_products as $key => $list)
                            @php
                                // Check if product belongs to category 13 and limit to 12 items (CI lines 19-21)
                                $multipalCategory = DB::table('product_category')
                                    ->where('product_id', $list->id)
                                    ->pluck('category_id')
                                    ->toArray();
                                
                                $category_id = 13;
                            @endphp
                            
                            @if(in_array($category_id, $multipalCategory) && $i <= 12)
                                @php $i++; @endphp
                                <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                                    <div class="all-services">
                                        <div class="single-service">
                                            <div class="single-service-inner">
                                                <div class="single-service-content">
                                                    <div class="universal-small-dark-title">
                                                        <a href="{{ url('Products/view/' . base64_encode($list->id)) }}">
                                                            <img src="{{ url('uploads/products/' . $list->product_image) }}" alt="{{ $list->name }}">
                                                            <span>
                                                                {{ $language_name == 'french' ? ucfirst($list->name_french ?? $list->name) : ucfirst($list->name) }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            
            {{-- View All button (CI lines 49-52) --}}
            <div class="universal-dark-info" style="text-align: center; margin: 0px;">
                <a href="{{ url('Products') }}">
                    <button style="margin: 0px;" type="button" class="checkout-view">
                        {{ $language_name == 'french' ? 'Voir tout' : 'View All' }}
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
