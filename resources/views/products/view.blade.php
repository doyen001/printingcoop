@extends('elements.app')

@section('title', $language_name == 'french' ? $Product['name_french'] ?? $Product['name'] : $Product['name'])

@section('content')

    <style>
        button.downloadButton {
            border: 0;
            background-color: transparent;
        }
    </style>

    <div class="page-title-section universal-bg-white">
        <div class="container">
            <div class="page-title-section-inner universal-half-spacing">
                <div class="inner-breadcrum">
                    @if ($language_name == 'french')
                        <a href="{{ url('/') }}">Accueil</a>
                        @if (!empty($Product['category_name']))
                            @php
                                $category = app('App\Models\Category')->find($Product['category_id']);
                            @endphp
                            @if ($category && $category->status == 1)
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '')) }}">
                                    {{ $Product['category_name_french'] }}
                                </a>
                            @endif
                        @endif
                        @if (!empty($Product['sub_category_name']))
                            @php
                                $subcategory = app('App\Models\SubCategory')->find($Product['sub_category_id']);
                            @endphp
                            @if ($subcategory && $subcategory->status == 1 && !empty($subcategory->subcategory_slug))
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '') . '&sub_category_id=' . base64_encode($Product['sub_category_id'] ?? '')) }}">
                                    {{ $Product['sub_category_name_french'] }}
                                </a>
                            @endif
                        @endif
                        /<span class="current">{{ $Product['name_french'] }}</span>
                    @else
                        <a href="{{ url('/') }}">Home</a>
                        @if (!empty($Product['category_name']))
                            @php
                                $category = app('App\Models\Category')->find($Product['category_id']);
                            @endphp
                            @if ($category && $category->status == 1)
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '')) }}">
                                    {{ $Product['category_name'] }}
                                </a>
                            @endif
                        @endif
                        @if (!empty($Product['sub_category_name']))
                            @php
                                $subcategory = app('App\Models\SubCategory')->find($Product['sub_category_id']);
                            @endphp
                            @if ($subcategory && $subcategory->status == 1 && !empty($subcategory->subcategory_slug))
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '') . '&sub_category_id=' . base64_encode($Product['sub_category_id'] ?? '')) }}">
                                    {{ $Product['sub_category_name'] }}
                                </a>
                            @endif
                        @endif
                        /<span class="current">{{ $Product['name'] }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="shop-single-section universal-spacing universal-bg-white">
        <div class="container">
            <div class="shop-single-section-inner">
                <div class="row">
                    <div class="col-md-5 col-lg-6 col-xl-6">
                        <div class="swiper-container-gallery-top">
                            <div class="swiper-wrapper">
                                @foreach ($ProductImages as $key => $ProductImage)
                                    <div class="swiper-slide">
                                        <div class="shop-product-img">
                                            <img src="{{ getProductImage($ProductImage['image'], 'large') }}"
                                                alt="{{ $language_name == 'french' ? $Product['name_french'] : $Product['name'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @php
                                $prod_name = $language_name == 'french' ? $Product['name_french'] : $Product['name'];
                            @endphp
                            @if (count($ProductImages) > 1)
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            @endif
                        </div>
                        <div class="product-sample-image">
                            <div class="swiper-container-gallery-thumbs">
                                <div class="swiper-wrapper">
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($ProductImages as $key => $ProductImage)
                                        @php
                                            $count++;
                                        @endphp
                                        <div class="swiper-slide">
                                            <div class="latest-single-product">
                                                <div class="latest-product-img">
                                                    <img src="{{ getProductImage($ProductImage['image']) }}"
                                                        alt="{{ $prod_name . '_' . $count }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-6 col-xl-6">
                        <div class="shop-product-detail-section">
                            <div class="shop-product-detail">
                                <h1>{{ $language_name == 'french' ? $Product['name_french'] : $Product['name'] }}</h1>
                                <div class="wishlist-area">
                                    <a data-toggle="tooltip" title="Add to wishlist" href="javascript:void(0)"
                                        onclick="addProductWishList('{{ $Product['id'] }}')">
                                        <i class="la la-heart-o"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    @php
                                        $multipalCategoryData = $Product['multipalCategoryData'];
                                    @endphp
                                    <div class="shop-category">
                                        <span>{{ $language_name == 'french' ? 'Catégorie' : 'Category' }} :
                                            @foreach ($multipalCategoryData as $key => $val)
                                                <font>{{ $language_name == 'french' ? $val['name_french'] : $val['name'] }}
                                                </font>
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="shop-category">
                                        <span>{{ $language_name == 'french' ? 'Disponibilité' : 'Availability' }} :
                                            <font>
                                                @if ($language_name == 'french')
                                                    {{ empty($Product['is_stock']) ? 'En Stock' : 'En rupture de stock' }}
                                                @else
                                                    {{ empty($Product['is_stock']) ? 'In Stock' : 'Out of Stock' }}
                                                @endif
                                            </font>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="universal-dark-info">
                                <span>{{ $language_name == 'french' ? $Product['short_description_french'] : $Product['short_description'] }}</span>
                            </div>

                            @php
                                $product_id = $Product['id'];
                                $buyNow = checkBuyNowProduct($Product['is_stock'], $Product['total_stock']);
                            @endphp

                            @if ($buyNow)
                                <form method="post" id="cartForm">
                                    @csrf
                                    <input type="hidden" id="product_id" value="{{ $Product['id'] }}" name="product_id">
                                    <input type="hidden" id="product_price" value="{{ $Product[$product_price_currency] }}"
                                        name="price">

                                    <div class="product-fields">
                                        <div class="row">
                                            @if ($provider)
                                                @include('products.product_detail_provider')
                                            @else
                                                @include('products.product_detail')
                                            @endif
                                        </div>
                                    </div>

                                    <div class="set-price-area">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md-6">
                                                <div class="shop-product-price">
                                                    <span>
                                                        <img src="{{ url('assets/images/loder.gif') }}" width="30"
                                                            style="display:none" id="loader-img" alt="loader-image">
                                                        <font class="new-price">
                                                            {{ $product_price_currency_symbol }}<span
                                                                id="total-price">{{ $Product[$product_price_currency] }}</span>
                                                        </font>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6">
                                                <div class="quant-cart">
                                                    <label>{{ $language_name == 'french' ? "Combien d'ensembles" : 'How many sets' }}</label>
                                                    <input type="text" value="1" id="quantity" name="quantity"
                                                        onkeypress="javascript:return isNumber(event)"
                                                        onchange="setQuantity()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($website_store_id != 5)
                                        <div class="file-upload-section">
                                            <div class="file-upload-area">
                                                <input type="file" name="file" id="file" style="display:none"
                                                    accept="image/jpeg, image/jpg, application/pdf">
                                                <span
                                                    class="info-span">{{ $language_name == 'french' ? 'Soumettre et télécharger le fichier (Taille autorisée par fichier: 250 Mo. Type de fichier autorisé: pdf, jpg, jpeg)' : 'Submit and Upload File (Allow size per file: 250 Mb. Allow file type:pdf, jpg, jpeg)' }}</span>
                                                <div class="upload-file upload-area" id="uploadfile">
                                                    <span
                                                        class="file-btn">{{ $language_name == 'french' ? 'Soumettre le téléchargement' : 'Submit Upload' }}</span>
                                                    <span
                                                        id="file-drop">{{ $language_name == 'french' ? 'Glisser-déposer des fichiers' : 'Drag & Drop Files' }}</span>
                                                </div>
                                            </div>

                                            <div class="uploaded-file-detail" id="upload-file-data">
                                                <!-- @if (session()->has("product_id.{$Product['id']}"))
                                                    @php
                                                        $file_data = session("product_id.{$Product['id']}");
                                                    @endphp
                                                    @foreach ($file_data as $key => $return_arr)
                                                        <div class="uploaded-file-single"
                                                            id="teb-{{ $return_arr['skey'] }}">
                                                            <div class="uploaded-file-single-inner">
                                                                <a href="{{ $return_arr['file_base_url'] }}"
                                                                    target="_blank">
                                                                    <div class="uploaded-file-img"
                                                                        style="background-image: url({{ $return_arr['src'] }})">
                                                                    </div>
                                                                </a>
                                                                <div class="uploaded-file-info">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-7">
                                                                            <div class="uploaded-file-name"><span><a
                                                                                        href="{{ $return_arr['file_base_url'] }}"
                                                                                        target="_blank">{{ $return_arr['name'] }}</a></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="upload-action-btn">
                                                                                <button type="button"
                                                                                    onclick="update_cumment('{{ $return_arr['skey'] }}')"
                                                                                    id="smc-{{ $return_arr['skey'] }}">
                                                                                    {{ $language_name == 'french' ? 'Note de mise à jour' : 'Update Note' }}
                                                                                </button>
                                                                                <button type="button" title="Delete"
                                                                                    onclick="delete_image('{{ $return_arr['skey'] }}')"
                                                                                    id="smd-{{ $return_arr['skey'] }}">
                                                                                    <i class="las la-trash"></i>
                                                                                </button>
                                                                                <input type="hidden"
                                                                                    value="{{ $return_arr['location'] }}"
                                                                                    id="location-{{ $return_arr['skey'] }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="upload-field">
                                                                        <textarea id="cumment-{{ $return_arr['skey'] }}">{{ $return_arr['cumment'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif -->
                                            </div>
                                        </div>
                                    @endif

                                    <div class="quant-cart">
                                        <input type="hidden" id="{{ $Product['id'] }}-rowid"
                                            value="{{ $productRowid }}">
                                        <input type="hidden" id="{{ $Product['id'] }}-productId">
                                        <button class="cart-adder" type="submit" id="btnSubmit">
                                            <span>{{ $language_name == 'french' ? 'Ajouter au chariot' : 'Add to Cart' }}</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-single-elements">
                <div class="featured-tabs tab">
                    <button class="tablinks active" id="defaultOpen" onclick="openCity(event, 'Description')">
                        {{ $language_name == 'french' ? 'La description' : 'Description' }}
                    </button>
                    @if (!empty($ProductDescriptions))
                        @foreach ($ProductDescriptions as $key => $val)
                            <button class="tablinks" id="defaultOpen{{ $val['id'] }}"
                                onclick="openCity(event, 'Description{{ $val['id'] }}')">
                                {{ $language_name == 'french' ? $val['title_french'] : $val['title'] }}
                            </button>
                        @endforeach
                    @endif
                    @if (!empty($ProductTemplates))
                        <button class="tablinks" id="defaultOpen-Template" onclick="openCity(event, 'template')">
                            {{ $language_name == 'french' ? 'Modèles' : 'Templates' }}
                        </button>
                    @endif
                </div>

                <div class="featured-tab-output">
                    <div id="Description" class="tabcontent">
                        <div class="tabcontent-inner">
                            <div class="universal-dark-title">
                                <span>{{ $language_name == 'french' ? 'La description' : 'Description' }}</span>
                            </div>
                            <div class="universal-dark-info">
                                <span>{!! $language_name == 'french' ? $Product['full_description_french'] : $Product['full_description'] !!}</span>
                            </div>
                        </div>
                    </div>

                    @if (!empty($ProductDescriptions))
                        @foreach ($ProductDescriptions as $key => $val)
                            <div id="Description{{ $val['id'] }}" class="tabcontent" style="display:none">
                                <div class="tabcontent-inner">
                                    <div class="universal-dark-title">
                                        <span>{{ $language_name == 'french' ? $val['title_french'] : $val['title'] }}</span>
                                    </div>
                                    <div class="universal-dark-info">
                                        <span>{!! $language_name == 'french' ? $val['description_french'] : $val['description'] !!}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (!empty($ProductTemplates))
                        <div id="template" class="tabcontent" style="display:none">
                            <div class="tabcontent-inner">
                                <div class="universal-dark-title">
                                    <span>{{ $language_name == 'french' ? 'Modèles' : 'Template' }}</span>
                                </div>
                                <div class="universal-dark-info">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'Dimensions finales' : 'Final Dimensions' }}
                                                </th>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'La description' : 'Description' }}
                                                </th>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'Télécharger' : 'Download' }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ProductTemplates as $key => $val)
                                                <tr>
                                                    <td>{{ $language_name == 'french' ? $val['final_dimensions_french'] : $val['final_dimensions'] }}
                                                    </td>
                                                    <td>{{ $language_name == 'french' ? $val['template_description_french'] : $val['template_description'] }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $path_ajax = '';
                                                            if ($val['template_file']) {
                                                                $path_ajax = url('uploads/templates/' . $val['template_file']);
                                                            }
                                                        @endphp
                                                        <button class="downloadButton"
                                                            data-image="{{ $val['template_file'] }}"
                                                            data-template-file="{{ $path_ajax }}">
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($language_name == 'french')
        <script src="{{ url('assets/file-upload/script-french.js') }}" type="text/javascript"></script>
    @else
        <script src="{{ url('assets/file-upload/script.js') }}" type="text/javascript"></script>
    @endif

    <script>
        function setQuantity() {
            var quantity = $('#quantity').val();
            if ($provider){
                if (quantity == '' || quantity == 0) {
                    $('#quantity').val('1');
                }
                $('#total-price').html($('[name="price"]').val() * $('#quantity').val());
            }
            else{
                if (quantity == '' || quantity == 0) {
                    $('#quantity').val('1');
                }
                var formData = new FormData($('#cartForm')[0]);
                $('#loader-img').show();
                $('.new-price-img').hide();

                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: "{{ url('products/calculate-price') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#loader-img').hide();
                        $('.new-price-img').show();

                        var json = JSON.parse(data);
                        if (json.success == 1) {
                            $('#total-price').html(json.price);
                        }
                    }
                });
            }
        }

        function update_cumment(skey) {
            var cumment = $('#cumment-' + skey).val();
            var product_id = '{{ $product_id }}';
            if (cumment == '') {
                alert('Enter cumment');
                return false
            }

            $('#smc-' + skey).prop('disabled', true);
            $('#smc-' + skey).html('<img src="assets/images/loder.gif" width=20>');
            $('#loader-img').show();
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: "{{ url('Products/updateCumment') }}",
                data: ({
                    'cumment': cumment,
                    'product_id': product_id,
                    'skey': skey
                }),
                success: function(data) {
                    $('#loader-img').hide();
                    $('#smc-' + skey).prop('disabled', false);
                    $('#smc-' + skey).html('Update Note');
                }
            });
        }

        function delete_image(skey) {
            var location = $('#location-' + skey).val();
            var product_id = '{{ $product_id }}';
            if (location == '') {
                return false
            }

            $('#smd-' + skey).prop('disabled', true);
            $('#smd-' + skey).html('<img src="assets/images/loder.gif" width=20>');
            $('#loader-img').show();
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: "{{ url('Products/deleteImage') }}",
                data: ({
                    'location': location,
                    'product_id': product_id,
                    'skey': skey
                }),
                success: function(data) {
                    $('#loader-img').hide();
                    $('#upload-file-data #teb-' + skey).remove();
                }
            });
        }

        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            return true;
        }

        $('form#cartForm').on('submit', function(e) {
            $('#loader-img').show();
            $('#btnSubmit').prop('disabled', true);
            e.preventDefault();
            var url = "{{ url('Products/addToCart') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    params: $(this).serialize()
                },
                cache: false,
                headers: {
                    accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#loader-img').hide();
                    var json = JSON.parse(data);
                    console.log('Cart AJAX Response:', json);
                    var status = json.status;
                    var msg = json.msg;
                    $('#btnSubmit').prop('disabled', false);
                    if (status == 1) {
                        console.log('Updating cart count to:', json.total_item);
                        $('.cart-contents-count').html(json.total_item);
                        getCartItem();
                        $('.addtocart-message').html('<span><i class="la la-cart-plus"></i>' + msg +
                            '.</span>').addClass('active');

                        setTimeout(function() {
                            $('.addtocart-message').removeClass('active');
                            location.assign("{{ url('ShoppingCarts') }}");
                        }, 1000);
                    } else {
                        $('.addtocart-message').html('<span><i class="la la-cart-plus"></i>' + msg +
                            '.</span>').addClass('active');
                        setTimeout(function() {
                            $('.addtocart-message').removeClass('active');
                        }, 2000);
                    }
                },
                error: function(error) {}
            });
        });

        $(document).ready(function() {
            $('.downloadButton').on('click', function() {
                var templateFileName = $(this).data('template-file');
                var Name = $(this).data('image');

                var downloadUrl = templateFileName;

                var $links = $('<a>').attr({
                    href: downloadUrl,
                    download: Name
                });

                $links.appendTo('body')[0].click();
                $links.remove();
            });
        });
    </script>
@endsection
