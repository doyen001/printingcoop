@php
$pageSize = 10;
$pageSizes = [10, 15, 20, 50, 100];
$provider = $provider ?? 'sina'; // Use provider from controller
@endphp
<form id="product-search-form" method="post" action="{{ url('admin/Products/ProviderProducts') }}/{{ $provider }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel light form-fit popup-window">
                <div class="x_content form">
                    <div class="form-horizontal">
                        <div class="form-body">
                            <div class="col-12 px-0">
                                <div class="row align-items-end">
                                    <div class="col-md-8 col-ms-12 col-12">
                                        <div class="form-group mb-0">
                                            <label class="control-label" for="q">Product Name</label>
                                            <input class="form-control k-input text-box single-line" id="q" name="q" type="text" value="{{ request('q', '') }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-12">
                                        <div class="form-actions">
                                            <div class="btn-group">
                                                <button class="btn btn-success filter-submit" id="search-products">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div id="products-grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var record = 0;
    $(document).ready(function () {
        $('#products-grid').kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: '{{ route("admin.products.provider.products", $provider) }}',
                        type: 'POST',
                        dataType: 'json',
                        data: additionalData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                },
                schema: {
                    data: 'data',
                    total: 'total',
                    errors: 'errors'
                },
                error: function(e) {
                    display_kendoui_grid_error(e);
                    // Cancel the changes
                    this.cancelChanges();
                },
                pageSize: {{ $pageSize }},
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            pageable: {
                refresh: true,
                pageSizes: {!! json_encode($pageSizes) !!},
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
                template: function(dataItem) {
                    var bindUrl = "{{ route('admin.products.provider.product.bind', ['id' => ':id']) }}".replace(':id', dataItem.id);
                    var html = '<a href="' + bindUrl + '" class="k-link bind-product product-thumbs';
                    if (dataItem.product_id) {
                        html += ' active';
                    }
                    html += ' d-flex justify-content-center">';
                    html += '<img class="thumbs" src="' + (dataItem.product_image ?? '') + '" alt="' + (dataItem.product_name || '') + '">';
                    html += '<div class="action"><svg class="bi b-icon" width="2em" height="2em" fill="currentColor">';
                    html += '<use xlink:href="{{ asset("assets/images/bootstrap-icons.svg") }}#pencil-square"/></svg></div></a>';
                    if (dataItem.product_id) {
                        html += '<p>' + (dataItem.product_name || '') + '</p>';
                    }
                    return html;
                }
            }, {
                field: 'provider_product_id',
                title: 'Options',
                template: function(dataItem) {
                    if (dataItem.product_id) {
                        return '<a href="{{ url("admin/Products/ProviderProductOptions") }}/{{ $provider }}/' + dataItem.provider_product_id + '" class="k-link bind-product-attributes"><div class="action"><i class="fa fa-2x fa-pencil-square-o"></i></div></a>';
                    }
                    return '';
                }
            }, {
                field: 'price_rate',
                title: 'Price Rate',
                template: function(dataItem) {
                    return (dataItem.price_rate || '') + '<a href="{{ url("admin/Products/ProviderProductPriceRate") }}/' + dataItem.id + '" class="k-link bind-product-attributes"><div class="action"><i class="fa fa-pencil"></i></div></a>';
                }
            }],
            dataBinding: function() {
                record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
            },
            dataBound: function() {
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
            },
        });

        //search button
        $('#search-products').click(function () {
            //search
            var grid = $('#products-grid').data('kendoGrid');
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
            return false;
        });
        
        // Search on Enter key
        $('#product-search-form #q').on('keypress', function(e) {
            if (e.which === 13) {
                $('#search-products').click();
                return false;
            }
        });
    });

    function additionalData() {
        return {
            q: $('#product-search-form #q').val()
        };
    }
    
    function display_kendoui_grid_error(e) {
        if (e.errors) {
            var message = 'Error: ' + (e.errors[0] || 'Unknown error occurred');
            if (typeof kendo.alert === 'function') {
                kendo.alert(message);
            } else {
                alert(message);
            }
        } else {
            var message = 'Error: ' + (e.xhr.responseText || 'Unknown error occurred');
            if (typeof kendo.alert === 'function') {
                kendo.alert(message);
            } else {
                alert(message);
            }
        }
    }
</script>
