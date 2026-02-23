@php
$pageSize = 10;
$pageSizes = [10, 15, 20, 50, 100];
$provider = 'sina'; // Default provider
@endphp
<script id="attribute-popup-editor" type="text/x-kendo-template">
    <div class="form-horizontal" id="attribute-form">
        <div class="form-header center">
            <h3><span data-bind="html:name"></span></h3>
        </div>
        <div class="form-body">
            <div class="form-group">
                <label for="type" class="control-label col-md-3 col-sm-3">Type</label>
                <div class="col-md-9 col-sm-9">
                    <select class="form-control k-input text-box single-line" name="type" data-bind="value:type" >
                        @foreach(App\Common\AttributeType::names as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="attribute_id" class="control-label col-md-3 col-sm-3">Attribute</label>
                <div class="col-md-9 col-sm-9">
                    <input type="hidden" name="attribute_id" data-bind="value:attribute_id" />
                    <div class="search-box-area">
                        <div class="search-sugg">
                            <input class="form-control" type="text" placeholder="Search Attribute" onkeyup="searchAttribute($(this).val())" data-bind="value:attribute_name" name="attribute_name">
                        </div>
                        <div class="search-result" style="display:none">
                            <a href="javascript:void(0)" onclick="hideSearchResult()"><i class="fa fas fa-times" ></i></a>
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<form id="option-search-form" method="post" action="{{ route('admin.products.provider.options', $provider) }}">
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
                                            <label class="control-label" for="q">Option Name</label>
                                            <input class="form-control k-input text-box single-line" id="q" name="q" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-12">
                                        <div class="form-actions">
                                            <div class="btn-group">
                                                <button class="btn btn-success filter-submit" id="search-options">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div id="options-grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var optionTypeNames = {!! json_encode(App\Common\AttributeType::names) !!};
    var record = 0;
    $(document).ready(function () {
        $('#options-grid').kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: '{{ route("admin.products.provider.options", $provider) }}',
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
                confirmation: false,
                // mode: 'inline',
                mode: 'popup',
                template: kendo.template($('#attribute-popup-editor').html())
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
                record = this.dataSource.pageSize() * (this.dataSource.page() - 1);
            },
        });

        //search button
        $('#search-options').click(function () {
            //search
            var grid = $('#options-grid').data('kendoGrid');
            grid.dataSource.page(1);
            return false;
        });
    });

    function additionalDataOption() {
        return {
            q: $('#option-search-form #q').val(),
            _token: '{{ csrf_token() }}'
        };
    }

    function searchAttribute(q)
    {
        if (q !='') {
            $('#loader-img').show();
            $('.search-result').show();
            $('.search-result ul').html('');
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.products.attributes") }}',
                headers: { Accept: 'application/json; charset=utf-8' },
                data: { q: q, _token: '{{ csrf_token() }}' },
                success: function(data)
                {
                    data = data.data;
                    $('#loader-img').hide();
                    var html = '<div>';
                    for (var i = 0; i < data.length; i++) {
                        html += '<li><a class="k-link product-thumbs" onclick="setAttributeId(\'' + data[i].id + '\', \'' + data[i].name + '\')"><span>' + data[i].name + '</span></li></a>';
                    }
                    html += '</div>';
                    $('.search-result ul').html(html);
                },
                error: function (error) {
                }
            });
        } else {
            $('.search-result').hide();
            $('.search-result ul').html('');
            $('.search-sugg input').val('');
        }
    }
    function hideSearchResult()
    {
        $('.search-result').hide();
        $('.search-result ul').html('');
    }
    function setAttributeId(id, name)
    {
        $('#attribute-form input[name="attribute_id"]').val(id);
        $('#attribute-form input[name="attribute_name"]').val(name);

        var model = $('.k-popup-edit-form').data('kendoEditable').options.model;
        model.attribute_id = id;
        model.attribute_name = name;
        // model.dirty = true;
        hideSearchResult();
    }
</script>
