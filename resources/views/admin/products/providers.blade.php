@extends('layouts.admin')
@php
$tabname = 'provider-view';
@endphp
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/provider.css') }}"/>
@section('content')
<div class="content-wrapper dd">
    <section class="content">
        @include('admin.shared.tabscript', ['tabname' => $tabname, 'position' => 'top'])
        <div id="{{ $tabname }}" style="display:none">
            <ul>
                @php
                    $tabs = ['Providers'];
                    $tabs[] = 'Products';
                    $tabs[] = 'Options';
                    session([$tabname.'-tab' => 1]);
                @endphp
                @foreach ($tabs as $i => $tab)
                    <li {{ session($tabname.'-tab') == $i ? 'class="k-active"' : '' }}>{{ $tab }}</li>
                @endforeach
            </ul>

            <div tab-index="0">
                @include('admin.products.provider_list')
            </div>
            <div tab-index="1">
                @include('admin.products.provider_products')
            </div>
            <div tab-index="2">
                @include('admin.products.provider_options')
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Ensure the correct tab is selected after Kendo TabStrip initialization
        setTimeout(function() {
            var defaultTabIndex = {{ session($tabname.'-tab', 1) }};
            var tabStrip = $('#{{ $tabname }}').data('kendoTabStrip');
            if (tabStrip && tabStrip.select) {
                tabStrip.select(defaultTabIndex);
            }
        }, 100);
    });
</script>
@endpush
@push('scripts')
<script>
    // Product Grid JavaScript
    var record = 0;
    $(document).ready(function () {
        $('#products-grid').kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: '{{ route("admin.products.provider.products", "sina") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: additionalData
                    }
                },
                schema: {
                    data: 'data',
                    total: 'total',
                    errors: 'errors'
                },
                error: function(e) {
                    if (typeof display_kendoui_grid_error === 'function') {
                        display_kendoui_grid_error(e);
                    } else {
                        console.error('Grid error:', e);
                    }
                    this.cancelChanges();
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            pageable: {
                refresh: true,
                pageSizes: [10, 15, 20, 50, 100],
                change: function(e) {
                    var stateurl = new URL(location.href);
                    stateurl.searchParams.set('page', e.index);
                    window.history.replaceState({ path: stateurl.href }, '', stateurl.href);
                }
            },
            scrollable: false,
            columns: [{
                title: '#',
                template: '#= ++record #',
            }, {
                field: 'provider_product_id',
                title: 'ID',
            }, {
                field: 'name',
                title: 'Name',
            }, {
                field: 'category',
                title: 'Category',
            }, {
                field: 'sku',
                title: 'SKU',
            }, {
                field: 'product_id',
                title: 'Product Binding',
                template: function(data) {
                    if (!data.id) return '';
                    var bindUrl = "{{ route('admin.products.provider.product.bind', ['id' => ':id']) }}".replace(':id', data.id);
                    var assetUrl = "{{ asset('assets/images/bootstrap-icons.svg#pencil-square') }}";
                    var html = '<a href="' + bindUrl + '" class="k-link bind-product product-thumbs';
                    if (data.product_id) {
                        html += ' active';
                    }
                    html += ' d-flex justify-content-center">\n';
                    html += '    <img class="thumbs" src="' + (data.product_image || '') + '" alt="' + (data.product_name || '') + '">\n';
                    html += '    <div class="action">\n';
                    html += '        <svg class="bi b-icon" width="2em" height="2em" fill="currentColor">\n';
                    html += '            <use xlink:href="' + assetUrl + '"/>\n';
                    html += '        </svg>\n';
                    html += '    </div>\n';
                    html += '</a>\n';
                    if (data.product_id) {
                        html += '<p>' + (data.product_name || '') + '</p>';
                    }
                    return html;
                },
            }, {
                field: 'provider_product_id',
                title: 'Options',
                template: function(data) {
                    if (!data.product_id || !data.provider_product_id) return '';
                    var optionsUrl = "{{ route('admin.products.provider.product.options', ['provider' => 'sina', 'provider_product_id' => ':id']) }}".replace(':id', data.provider_product_id);
                    return '<a href="' + optionsUrl + '" class="k-link bind-product-attributes">\n' +
                           '    <div class="action"><i class="fa fa-2x fa-pencil-square-o"></i></div>\n' +
                           '</a>';
                },
            }, {
                field: 'price_rate',
                title: 'Price Rate',
                template: function(data) {
                    if (!data.id) return '';
                    var priceRateUrl = "{{ route('admin.products.provider.product.price.rate', ['id' => ':id']) }}".replace(':id', data.id);
                    return (data.price_rate || '') +
                           '<a href="' + priceRateUrl + '" class="k-link bind-product-attributes">\n' +
                           '    <div class="action"><i class="fa fa-pencil"></i></div>\n' +
                           '</a>';
                },
            }],
            dataBinding: function() {
                record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
            },
            dataBound: function() {
                if (typeof $.fn.magnificPopup === 'function') {
                    $('.bind-product, .bind-product-attributes').magnificPopup({
                        type: 'ajax',
                        settings: { cache: false, async: false },
                        midClick: true,
                        callbacks: {
                            parseAjax: function (mfpResponse) {
                                mfpResponse.data = $('<div></div>').html(mfpResponse.data);
                            },
                            ajaxContentAdded: function () {
                                $('.mfp-wrap').removeAttr('tabindex');
                            }
                        }
                    });
                }
            },
        });

        $('#search-products').click(function () {
            var grid = $('#products-grid').data('kendoGrid');
            if (grid) {
                grid.dataSource.page(1);
                var params = additionalData();
                var stateurl = new URL(location.href);
                stateurl.searchParams.set('page', 1);
                for (const item of Object.entries(params)) {
                    if (item[0] != '_token') {
                        if (item[1] != undefined && item[1] != '')
                            stateurl.searchParams.set(item[0], item[1]);
                        else {
                            stateurl.searchParams.delete(item[0]);
                        }
                    }
                }
                stateurl.searchParams.delete('timestamp');
                window.history.replaceState({ path: stateurl.href }, '', stateurl.href);
            }
            return false;
        });
    });

    function additionalData() {
        return {
            q: $('#product-search-form #q').val(),
            _token: '{{ csrf_token() }}'
        };
    }

    // Options Grid JavaScript
    var optionTypeNames = {
        1: 'Text',
        2: 'Number',
        3: 'Select',
        4: 'Checkbox',
        5: 'Radio'
    };
    var recordOptions = 0;
    $(document).ready(function () {
        $('#options-grid').kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: '{{ route("admin.products.provider.options", "sina") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: additionalDataOption
                    },
                    update: {
                        url:'{{ route("admin.products.provider.option.update") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: []
                    },
                    destroy: {
                        url: '',
                        type: 'POST',
                        dataType: 'json',
                        data: []
                    }
                },
                schema: {
                    data: 'data',
                    total: 'total',
                    errors: 'errors',
                    model: {
                        id: 'id',
                        fields: {
                            name: { editable: false, type: 'string'},
                            type: { editable: true, type: 'number' },
                            attribute_id: { editable: true, type: 'number' },
                            attribute_name: { editable: true, type: 'string' },
                        }
                    }
                },
                error: function(e) {
                    if (typeof display_kendoui_grid_error === 'function') {
                        display_kendoui_grid_error(e);
                    } else {
                        console.error('Grid error:', e);
                    }
                    this.cancelChanges();
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            pageable: {
                refresh: true,
                pageSizes: [10, 15, 20, 50, 100]
            },
            editable: {
                confirmation: false,
                mode: 'popup',
                template: kendo.template($('#attribute-popup-editor').html())
            },
            scrollable: false,
            columns: [{
                field: 'id',
                title: '#',
                template: '#= ++recordOptions #',
            }, {
                field: 'name',
                title: 'Name',
            }, {
                field: 'type',
                title: 'Type',
                template: '#=optionTypeNames[type]#',
            }, {
                field: 'html_type',
                title: 'HTML Type',
            }, {
                field: 'attribute_name',
                title: 'Attribute',
            }, {
                command: [{
                    name: 'edit',
                    text: {
                        edit: 'Edit',
                        update: 'Update',
                        cancel: 'Cancel'
                    }
                }],
                width: 100,
            }],
            dataBinding: function() {
                recordOptions = this.dataSource.pageSize() * (this.dataSource.page() - 1);
            },
        });

        $('#search-options').click(function () {
            var grid = $('#options-grid').data('kendoGrid');
            if (grid) {
                grid.dataSource.page(1);
            }
            return false;
        });
    });

    function additionalDataOption() {
        return {
            q: $('#option-search-form #q').val(),
            _token: '{{ csrf_token() }}'
        };
    }

    function searchAttribute(q) {
        if (q !='') {
            if ($('#loader-img').length) {
                $('#loader-img').show();
            }
            $('.search-result').show();
            $('.search-result ul').html('');
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.products.attributes") }}',
                headers: { Accept: 'application/json; charset=utf-8' },
                data: { q: q, _token: '{{ csrf_token() }}' },
                success: function(data) {
                    data = data.data;
                    if ($('#loader-img').length) {
                        $('#loader-img').hide();
                    }
                    var html = '<div>';
                    for (var i = 0; i < data.length; i++) {
                        html += `<li><a class="k-link product-thumbs" onclick="setAttributeId('${data[i].id}', '${data[i].name}')"><span></i>${data[i].name}</span></li></a>`;
                    }
                    html += '</div>';
                    $('.search-result ul').html(html);
                },
                error: function (error) {
                    console.error('Search error:', error);
                }
            });
        } else {
            $('.search-result').hide();
            $('.search-result ul').html('');
            $('.search-sugg input').val('');
        }
    }

    function hideSearchResult() {
        $('.search-result').hide();
        $('.search-result ul').html('');
    }

    function setAttributeId(id, name) {
        $('#attribute-form input[name="attribute_id"]').val(id);
        $('#attribute-form input[name="attribute_name"]').val(name);
        var model = $('.k-popup-edit-form').data('kendoEditable').options.model;
        model.attribute_id = id;
        model.attribute_name = name;
        hideSearchResult();
    }
</script>
@endpush
