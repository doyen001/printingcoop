@extends('layouts.admin')

@php
$tabname = 'attributes-view';
$pageSize = 10;
$pageSizes = [10, 15, 20, 50, 100];
@endphp

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/provider.css') }}">
<div class="content-wrapper dd">
    <section class="content">
        <h3>{{ $product['name'] }}</h3>
        @include('admin.shared.tabscript', ['tabname' => $tabname, 'position' => 'top'])
        <div id="{{ $tabname }}" style="display:none">
            <ul>
                @php
                $tabs = ['Attributes', 'Items'];
                @endphp
                @foreach($tabs as $i => $tab)
                    <li @if(session("$tabname-tab", 0) == $i) class="k-active" @endif>{{ $tab }}</li>
                @endforeach
            </ul>

            <div tab-index="0">
                <form id="attribute-search-form" method="post" action="{{ url('admin/Products/ProductAttributes') }}/{{ $product_id }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel light form-fit">
                                <div class="x_content form">
                                    <div class="form-horizontal">
                                        <div class="form-body">
                                            <div class="col-12 px-0">
                                                <div class="row align-items-end">
                                                    <div class="col-md-8 col-ms-12 col-12">
                                                        <div class="form-group mb-0">
                                                            <label class="control-label" for="q">Attribute Name</label>
                                                            <input class="form-control k-input text-box single-line"
                                                                id="q" name="q" type="text" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 col-12">
                                                        <div class="form-actions">
                                                            <div class="btn-group">
                                                                <button class="btn btn-success filter-submit" id="search-attributes">
                                                                    <i class="fa fa-search"></i> Search
                                                                </button>
                                                                <a id="attribute-create" href="{{ url('admin/Products/ProductAttributeCreate') }}/{{ $product_id }}" class="btn green">
                                                                    <i class="fa fa-plus"></i>
                                                                    <span class="d-none d-sm-inline"> Add New</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="x_content">
                                            <div id="attributes-grid"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="dlg-attribute-create" class="k-card" style="display:none;" method="post" action="{{ url('admin/Products/ProductAttributeCreate') }}/{{ $product_id }}">
                    @csrf
                    <div class="k-card-body">
                        <div class="form-horizontal">
                            <div class="form-body">
                                <div class="form-group">
                                    <label for="name" class="control-label col-md-3 col-sm-3">Attribute</label>
                                    @include('admin.shared.dropdown', ['data' => [
                                        'id' => 'attribute-create-attribute_id',
                                        'name' => 'attribute_id',
                                        'url' => url('admin/Products/AttributesMap'),
                                        'label' => 'Select an attribute...',
                                        'class' => 'form-control col-md-9 col-sm-9',
                                        'fieldText' => 'label',
                                        'template' => '#=label# (#=label_fr#) : #=name#',
                                    ]])
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Use Items</label>
                                    @include('admin.shared.select', ['name' => 'use_items', 'value' => 1, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Use Percentage</label>
                                    @include('admin.shared.select', ['name' => 'use_percentage', 'value' => 0, 'items' => [0 => 'No', 1=> 'Yes'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Min Value</label>
                                    @include('admin.shared.double', ['name' => 'value_min', 'value' => 0])
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Max Value</label>
                                    @include('admin.shared.double', ['name' => 'value_max', 'value' => 0])
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Additional Fee</label>
                                    @include('admin.shared.double', ['name' => 'additional_fee', 'value' => 0])
                                </div>
                                <div class="border fee-apply">
                                    <label for="type" class="control-label col-md-3 col-sm-3">Fee Apply To ...</label>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Size</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_size', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Width</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_width', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Length</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_length', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Diameter</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_diameter', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Depth</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_depth', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-3 col-sm-3">Pages</label>
                                        @include('admin.shared.select', ['name' => 'fee_apply_pages', 'value' => 0, 'items' => [1=> 'Yes', 0 => 'No'], 'index' => true, 'class' => 'form-control col-md-9 col-sm-9'])
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="show_order" class="control-label col-md-3 col-sm-3">Show Order</label>
                                    <input type="number" class="form-control col-md-9 col-sm-9" name="show_order" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="k-card-actions">
                        <div class="stats"></div>
                        <span class="k-card-action">
                            <button type="submit" class="k-button k-button-flat-primary k-button-flat k-button-md k-rounded-md filter-submit">
                                <i class="fa fa-check"></i> Save
                            </button>
                        </span>
                    </div>
                </form>
                <div id="dlg-attribute-items" class="k-card" style="display:none;">
                    <div class="k-card-body">
                        <div class="k-card-actions ">
                            <span class="k-card-action"><span id="attribute-item-create" class="k-button k-button-flat-base k-button-flat k-button-md k-rounded-md"><i class="fa fa-plus"> <span class="d-none d-sm-inline"> Add New</span></i></span></span>
                        </div>
                        <div id="dlg-attribute-items-grid"></div>
                    </div>
                    <div class="k-card-actions ">
                        <span class="k-card-action"><span class="k-button k-button-flat-primary k-button-flat k-button-md k-rounded-md" onclick="$('#dlg-attribute-items').data('kendoWindow').close();">Close</span></span>
                    </div>
                </div>
            </div>
            <div tab-index="1">
                <form id="attribute-item-search-form" method="post" action="{{ url('admin/Products/ProductAttributeItems') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel light form-fit">
                                <div class="x_content form">
                                    <div class="form-horizontal">
                                        <div class="form-body">
                                            <div class="col-12 px-0">
                                                <div class="row align-items-end">
                                                    <div class="col-md-8 col-ms-12 col-12">
                                                        <div class="form-group mb-0">
                                                            <label class="control-label" for="q">Item Name</label>
                                                            <input class="form-control k-input text-box single-line"
                                                                id="q" name="q" type="text" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 col-12">
                                                        <div class="form-actions">
                                                            <div class="btn-group">
                                                                <button class="btn btn-success filter-submit" id="search-attribute-items">
                                                                    <i class="fa fa-search"></i> Search
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="x_content">
                                            <div id="attribute-items-grid"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <form id="dlg-attribute-item-create" class="k-card" style="display:none;" method="post" action="{{ url('admin/Products/ProductAttributeItemCreate') }}/{{ $product_id }}">
            @csrf
            <div class="k-card-body">
                <div class="form-horizontal">
                    <div class="form-body">
                        <input type="hidden" name="attribute_id">
                        <div class="form-group">
                            <label for="name" class="control-label col-md-3 col-sm-3">Item</label>
                            @include('admin.shared.dropdown', ['data' => [
                                'id' => 'attribute-item-create-attribute_item_id',
                                'name' => 'attribute_item_id',
                                'urlFunction' => 'attributeItemUrl',
                                'label' => 'Select an item...',
                                'class' => 'form-control col-md-9 col-sm-9',
                                'fieldText' => 'name',
                            ]])
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label col-md-3 col-sm-3">Additional Fee</label>
                            @include('admin.shared.double', ['name' => 'additional_fee', 'value' => 0])
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label col-md-3 col-sm-3">Show Order</label>
                            @include('admin.shared.int32', ['name' => 'show_order', 'value' => 0])
                        </div>
                    </div>
                </div>
            </div>
            <div class="k-card-actions">
                <div class="stats"></div>
                <span class="k-card-action">
                    <button type="submit" class="k-button k-button-flat-primary k-button-flat k-button-md k-rounded-md filter-submit">
                        <i class="fa fa-check"></i> Save
                    </button>
                </span>
            </div>
        </form>
    </section>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Ensure the correct tab is selected after Kendo TabStrip initialization
        setTimeout(function() {
            var defaultTabIndex = {{ session($tabname.'-tab', 0) }};
            var tabStrip = $('#{{ $tabname }}').data('kendoTabStrip');
            if (tabStrip && tabStrip.select) {
                tabStrip.select(defaultTabIndex);
            }
        }, 100);
    });
    
    var typeNames = {!! json_encode(\App\Common\AttributeType::names) !!};
    var typeNamesList = [];
    for (const [key, value] of Object.entries(typeNames)) {
        typeNamesList.push({
            key: key,
            value: value
        });
    }
    var record = 0;
    var qsTypes = [{!! \App\Common\AttributeType::Quantity !!}, {!! \App\Common\AttributeType::Size !!}, {!! \App\Common\AttributeType::Width !!}, {!! \App\Common\AttributeType::Length !!}, {!! \App\Common\AttributeType::Diameter !!}, {!! \App\Common\AttributeType::Depth !!}, {!! \App\Common\AttributeType::Pages !!}, {!! \App\Common\AttributeType::Sheets !!}}];
    
    $('#attributes-grid').kendoGrid({
        dataSource: {
            transport: {
                read: {
                    url: '{{ url("admin/Products/ProductAttributes") }}/{{ $product_id }}',
                    type: 'POST',
                    dataType: 'json',
                    data: additionalDataAttribute
                },
                update: {
                    url:'{{ url("admin/Products/ProductAttributeUpdate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: []
                },
                destroy: {
                    url:'{{ url("admin/Products/ProductAttributeDelete") }}',
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
                        id: { editable: false, type: 'int' },
                        name: { editable: false, type: 'string'},
                        type: { editable: true, type: 'number' },
                        attribute_id: { editable: true, type: 'number' },
                        attribute_name: { editable: true, type: 'string' },
                        use_percentage: { editable: true, type: 'boolean' },
                        extra_price: { editable: true, type: 'number' },
                    }
                }
            },
            error: function(e) {
                if (typeof display_kendoui_grid_error === 'function') {
                    display_kendoui_grid_error(e);
                } else {
                    console.error('Grid error:', e);
                }
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
            pageSizes: {!! json_encode($pageSizes) !!}
        },
        editable: {
            confirmation: true,
            mode: 'inline',
        },
        scrollable: false,
        columns: [{
            field: 'id',
            title: '#',
            template: '#= ++record #',
        }, {
            field: 'name',
            title: 'Name',
        }, {
            field: 'type',
            title: 'Type',
            template: '#=typeNames[type]#',
        }, {
            field: 'attribute_name',
            title: 'Attribute',
        }, {
            field: 'use_percentage',
            title: '%',
            template: '#= use_percentage == 1 ? "Yes" : ""#',
            editor: booleanEditor,
        }, {
            field: 'extra_price',
            title: 'Extra Price',
        }, {
            title: 'Value Range',
            columns: [{
                field: 'value_min',
                title: 'Min',
            }, {
                field: 'value_max',
                title: 'Max',
            }]
        }, {
            field: 'additional_fee',
            title: 'Additional Fee',
        }, {
            title: 'Fee Apply to ...',
            columns: [{
                field: 'fee_apply_size',
                title: 'Size',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_size == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_width',
                title: 'Width',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_width == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_length',
                title: 'Length',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_length == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_diameter',
                title: 'Diameter',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_diameter == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_depth',
                title: 'Depth',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_depth == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_pages',
                title: 'Pages',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_pages == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }]
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
        }, {
            command: [{
                name: 'destroy',
                text: 'Delete'
            }],
            width: 100,
        }],
            dataBinding: function() {
                record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
            },
        });
        
        //search button
        $('#search-attributes').click(function () {
            var grid = $('#attributes-grid').data('kendoGrid');
            grid.dataSource.page(1);
            return false;
        });
    });

    function additionalDataAttribute() {
        return {
            q: $('#attribute-search-form #q').val()
        };
    }
    
    function additionalDataAttributeItem() {
        return {
            q: $('#attribute-item-search-form #q').val()
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

    function booleanEditor(container, options) {
        var model = options.model;
        var select = $('<select class="k-input" data-bind="value:' + options.field + '"/>');
        select.append('<option value="true">Yes</option>');
        select.append('<option value="false">No</option>');
        select.val(model.get(options.field));
        select.appendTo(container);
    }

    function booleanEditorQS(container, options) {
        var model = options.model;
        var select = $('<select class="k-input" data-bind="value:' + options.field + '"/>');
        select.append('<option value="true">Yes</option>');
        select.append('<option value="false">No</option>');
        select.val(model.get(options.field));
        select.appendTo(container);
    }

    function attributeItemCreate(e) {
        e.preventDefault();
        $('#dlg-attribute-item-create [name="attribute_id"]').val(curAttribute.attribute_id);
        var wnd = $('#dlg-attribute-item-create');
        if (!wnd.data('kendoWindow')) {
            wnd.kendoWindow({
                modal: true,
                title: 'Create a new item',
                actions: ['Close'],
                width: '70%',
                height: '80%',
            });
        }
        wnd.data('kendoWindow').center().open();
        $('.k-overlay').click(function() {
            $('#dlg-attribute-item-create').data('kendoWindow').close();
        });

        $('#attribute-item-create-attribute_item_id').data('kendoDropDownList').dataSource.read();
    }
</script>
@endpush
                    url: '{{ url("admin/Products/ProductAttributes") }}/{{ $product_id }}',
                    type: 'POST',
                    dataType: 'json',
                    data: additionalDataAttribute
                },
                update: {
                    url: '{{ url("admin/Products/ProductAttributeUpdate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: []
                },
                destroy: {
                    url: '{{ url("admin/Products/ProductAttributeDelete") }}',
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
                        id: { editable: false, type: 'int' },
                        name: { editable: false, type: 'string' },
                        type: { editable: false, type: 'int' },
                        label: { editable: false, type: 'string' },
                        label_fr: { editable: false, type: 'string' },
                        attribute_id: { editable: false, type: 'number' },
                        item_count: { editable: false, type: 'number' },
                    }
                }
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
            pageSizes: {!! json_encode($pageSizes) !!}
        },
        editable: {
            confirmation: true,
            mode: 'inline',
        },
        scrollable: false,
        columnMenu: true,
        columns: [{
            field: 'id',
            title: '#',
            template: '#= ++record #',
        }, {
            title: 'Attribute',
            columns: [{
                field: 'name',
                title: 'Name',
                template: '<a title="#=label#(#=label_fr#)">#=name#</a>',
                width: '200px'
            }, {
                field: 'type',
                title: 'Type',
                template: '#=typeNames[type]#',
            }, {
                field: 'item_count',
                title: 'Items',
            }]
        }, {
            field: 'use_items',
            title: 'Use Items',
            template: '#= use_items == 1 == 1 ? "Yes" : ""#',
            editor: booleanEditor,
        }, {
            field: 'use_percentage',
            title: '%',
            template: '#= use_percentage == 1 == 1 ? "Yes" : ""#',
            editor: booleanEditor,
        }, {
            title: 'Value Range',
            columns: [{
                field: 'value_min',
                title: 'Min',
            }, {
                field: 'value_max',
                title: 'Max',
            }]
        }, {
            field: 'additional_fee',
            title: 'Additional Fee',
        }, {
            title: 'Fee Apply to ...',
            columns: [{
                field: 'fee_apply_size',
                title: 'Size',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_size == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_width',
                title: 'Width',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_width == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_length',
                title: 'Length',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_length == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_diameter',
                title: 'Diameter',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_diameter == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_depth',
                title: 'Depth',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_depth == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }, {
                field: 'fee_apply_pages',
                title: 'Pages',
                template: '#if (qsTypes.indexOf(Number(type))<0 && use_percentage != 1) {##= fee_apply_pages == 1 ? "Yes" : ""##}#',
                editor: booleanEditorQS,
            }]
        }, {
            field: 'show_order',
            title: 'Show Order',
        }, {
            command: [{
                name: 'edit',
                text: {
                    edit: 'Edit',
                    update: 'Update',
                    cancel: 'Cancel'
                }
            }, {
                text: "Items",
                click: showItems,
                visible: function(dataItem) {
                    return dataItem.use_items == 1;
                }
            }, {
                name: 'destroy',
            }],
            width: 100,
        }],
        dataBinding: function() {
            record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
        },
    });
    // if ($('#attributes-grid').width() < 1400)
    //     $('#attributes-grid').width(1400);

    $('#search-attributes').click(function() {
        //search
        var grid = $('#attributes-grid').data('kendoGrid');
        grid.dataSource.page(1);
        return false;
    });

    $('#dlg-attribute-items-grid').kendoGrid({
        dataSource: {
            transport: {
                read: {
                    url: productAttributeItemUrl,
                    type: 'POST',
                    dataType: 'json',
                    // data: additionalDataAttribute
                },
                update: {
                    url: '{{ url("admin/Products/ProductAttributeItemUpdate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: []
                },
                destroy: {
                    url: '{{ url("admin/Products/ProductAttributeItemDelete") }}',
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
                        id: { editable: false, type: 'int' },
                        attribute_item_id: { editable: false, type: 'string' },
                    }
                }
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
            pageSizes: {!! json_encode($pageSizes) !!}
        },
        editable: {
            confirmation: true,
            mode: 'inline',
        },
        scrollable: false,
        columns: [{
            field: 'id',
            title: '#',
            template: '#= ++record #',
        }, {
            field: 'attribute_item_id',
            title: 'Item',
            template: '#=attribute_item_name#',
        }, {
            field: 'additional_fee',
            title: 'Additional Fee',
        }, {
            field: 'show_order',
            title: 'Show Order',
        }, {
            command: [{
                name: 'edit',
                text: {
                    edit: 'Edit',
                    update: 'Update',
                    cancel: 'Cancel'
                }
            }, {
                name: 'destroy',
            }],
            width: 100,
        }],
        dataBinding: function() {
            record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
        },
    });

    $('#attribute-create').click(attributeCreate);

    $('#dlg-attribute-create').on('submit', function(e) {
        e.preventDefault();
        $('#loader-img').show();
        $.post('{{ url("admin/Products/ProductAttributeCreate") }}/{{ $product_id }}', $(this).serialize())
        .done(function (response) {
            $('#loader-img').hide();
            if (!response) {
                kendo.alert('Error occurred.');
                return;
            } else if (!response.success) {
                kendo.alert(response.message);
                return;
            }
            refreshGrid('attributes-grid');
            $('#dlg-attribute-create').data('kendoWindow').close();
        }).fail(function (error) {
            kendo.alert(error);
            $('#loader-img').hide();
        });
    });
});

function additionalDataAttribute() {
    return {
        q: $('#attribute-search-form #q').val(),
        _token: '{{ csrf_token() }}'
    };
}

function booleanEditor(container, options) {
    $('<input required name="' + options.field + '"/>')
        .appendTo(container)
        .kendoDropDownList({
            value: options.model[options.field],
            dataSource: [{key: 1, value: 'Yes'}, {key: 0, value: 'No'}],
            dataTextField: "value",
            dataValueField: "key",
        });
}

function booleanEditorQS(container, options) {
    if (qsTypes.indexOf(Number(options.model.type))<0) {
        booleanEditor(container, options);
    }
}

var curAttribute = null;

function showItems(e) {
    e.preventDefault();
    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    curAttribute = dataItem;
    var wnd = $('#dlg-attribute-items');
    if (!wnd.data('kendoWindow')) {
        wnd.kendoWindow({
            modal: true,
            title: 'Attribute Items',
            actions: ['Close'],
            width: '70%',
            height: '80%',
        });
    }
    wnd.data('kendoWindow').center().open();
    wnd.data('kendoWindow').title('Items of ' + curAttribute.name);
    $('.k-overlay').click(function() {
        $('#dlg-attribute-items').data('kendoWindow').close();
    });
    refreshGrid('dlg-attribute-items-grid');
}

function productAttributeItemUrl() {
    return '{{ url("admin/Products/ProductAttributeItems") }}/{{ $product_id }}/' + (curAttribute ? curAttribute.attribute_id : 0);
}

function attributeCreate(e) {
    e.preventDefault();
    var wnd = $('#dlg-attribute-create');
    if (!wnd.data('kendoWindow')) {
        wnd.kendoWindow({
            modal: true,
            title: 'Create a new attribute',
            actions: ['Close'],
            width: '70%',
            height: '80%',
        });
    }
    wnd.data('kendoWindow').center().open();
    $('.k-overlay').click(function() {
        $('#dlg-attribute-create').data('kendoWindow').close();
    });
}

function attributeItemUrl() {
    return '{{ url("admin/Products/AttributeItemsMap") }}/' + (curAttribute == null ? 0 : curAttribute.attribute_id);
}

$(document).ready(function() {
    $('#attribute-items-grid').kendoGrid({
        dataSource: {
            transport: {
                read: {
                    url: '{{ url("admin/Products/ProductAttributeItems") }}/{{ $product_id }}',
                    type: 'POST',
                    dataType: 'json',
                    data: additionalDataAttributeItem
                },
                update: {
                    url: '{{ url("admin/Products/ProductAttributeItemUpdate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: []
                },
                destroy: {
                    url: '{{ url("admin/Products/ProductAttributeItemDelete") }}',
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
                        id: { editable: false, type: 'int' },
                        name: { editable: false, type: 'string' },
                        attribute_name: { editable: false, type: 'string' },
                        attribute_label: { editable: false, type: 'string' },
                        attribute_item_name: { editable: false, type: 'string' },
                        attribute_item_name_fr: { editable: false, type: 'string' },
                    }
                }
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
            pageSizes: {!! json_encode($pageSizes) !!}
        },
        editable: {
            confirmation: true,
            mode: 'inline',
        },
        scrollable: false,
        columns: [{
            field: 'id',
            title: '#',
            template: '#= ++record #',
        }, {
            field: 'attribute_name',
            title: 'Attribute',
        }, {
            field: 'attribute_item_name',
            title: 'Item',
        }, {
            field: 'attribute_item_name_fr',
            title: 'Nom',
            template: '#=unescape(attribute_item_name_fr)#',
        }, {
            field: 'additional_fee',
            title: 'Additional Fee',
        }, {
            field: 'show_order',
            title: 'Show Order',
        }, {
            command: [{
                name: 'edit',
                text: {
                    edit: 'Edit',
                    update: 'Update',
                    cancel: 'Cancel'
                }
            }, {
                name: 'destroy',
            }],
            width: 100,
        }],
        dataBinding: function() {
            record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
        },
    });

    $('#search-attribute-items').click(function() {
        //search
        var grid = $('#attribute-items-grid').data('kendoGrid');
        grid.dataSource.page(1);
        return false;
    });

    $('#attribute-item-create').click(attributeItemCreate);

    $('#dlg-attribute-item-create').on('submit', function(e) {
        e.preventDefault();
        $('#loader-img').show();
        $.post('{{ url("admin/Products/ProductAttributeItemCreate") }}/{{ $product_id }}', $(this).serialize())
        .done(function (response) {
            $('#loader-img').hide();
            if (!response) {
                kendo.alert('Error occurred.');
                return;
            } else if (!response.success) {
                kendo.alert(response.message);
                return;
            }
            refreshGrid('attribute-items-grid');
            refreshGrid('dlg-attribute-items-grid');
            $('#dlg-attribute-item-create').data('kendoWindow').close();
        }).fail(function (error) {
            kendo.alert(error);
            $('#loader-img').hide();
        });
    });
});

function additionalDataAttributeItem() {
    return {
        q: $('#attribute-item-search-form #q').val(),
        _token: '{{ csrf_token() }}'
    };
}

function attributeItemCreate(e) {
    e.preventDefault();
    $('#dlg-attribute-item-create [name="attribute_id"]').val(curAttribute.attribute_id);
    var wnd = $('#dlg-attribute-item-create');
    if (!wnd.data('kendoWindow')) {
        wnd.kendoWindow({
            modal: true,
            title: 'Create a new item',
            actions: ['Close'],
            width: '70%',
            height: '80%',
        });
    }
    wnd.data('kendoWindow').center().open();
    $('.k-overlay').click(function() {
        $('#dlg-attribute-item-create').data('kendoWindow').close();
    });

    $('#attribute-item-create-attribute_item_id').data('kendoDropDownList').dataSource.read();
}
