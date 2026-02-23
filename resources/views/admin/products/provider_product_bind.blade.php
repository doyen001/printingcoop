<div class="k-card">
    <div class="k-card-header">
        <h2 class="k-card-title">{{ $product->name }}</h2>
        @if($product->product_id)
        <a class="k-card-subtitle provider-product-unlink" href="{{ route('admin.products.provider.product.unbind', $product->id) }}" title="Unbind">
            <div class="action">
                <svg class="bi b-icon" width="2em" height="2em" fill="currentColor">
                    <use xlink:href="{{ asset('assets/images/bootstrap-icons.svg#link-45deg') }}"/>
                </svg>
                <svg class="bi b-icon" width="2em" height="2em" fill="currentColor">
                    <use xlink:href="{{ asset('assets/images/bootstrap-icons.svg#x') }}"/>
                </svg>
            </div>
        </a>
        @endif
    </div>
    <div class="k-card-body">
        <div class="inner-head-section">
            <div class="row align-items-end">
                <div class="col-md-12">
                    <div class="search-box-area">
                        <div class="search-sugg">
                            <label class="span2">Search</label>
                            <input class="form-control" type="text" placeholder="Search Product" value="{{ $product->product_name }}" onkeyup="searchProduct($(this).val())">
                            <!--<button type="button"><i class="fa fas fa-search"></i></button>-->
                        </div>
                        <div class="search-result" style="display:none"><!-- Add "active" class to show -->
                            <a href="javascript:void(0)" onclick="hideSearchResult()"><i class="fa fas fa-times" ></i></a>
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        searchProduct('{{ $product->product_name }}');
        $('.provider-product-unlink').click(function(e) {
            e.preventDefault();
            $('#loader-img').show();
            $.ajax({
                type: 'POST',
                url: $(this).attr('href'),
                headers: { Accept: 'application/json; charset=utf-8' },
                success: function(data)
                {
                    $('#loader-img').hide();
                    $('.search-box-area input.form-control').val('');
                    searchProduct('');
                },
                error: function (error) {
                    $('#loader-img').hide();
                }
            });
            return false;
        });
    });
    function searchProduct(searchtext)
    {
        if (searchtext !='') {
            $('#loader-img').show();
            $('.search-result').show();
            $('.search-result ul').html('');
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.products.search") }}',
                headers: { Accept: 'application/json; charset=utf-8' },
                data: { 'searchtext': searchtext }, // serializes the form's elements.
                success: function(data)
                {
                    $('#loader-img').hide();
                    var html = '<div>';
                    for (var i = 0; i < data.length; i++) {
                        html += '<li><a class="k-link product-thumbs" onclick="bindProduct(' + {{ $id }} + ', ' + data[i].id + ')"><img src="' + data[i].product_image + '" width=50><span>' + data[i].name + '</span></li></a>';
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
    function bindProduct(id, product_id)
    {
        $('#loader-img').show();
        $.ajax({
            type: 'POST',
            url: '{{ route("admin.products.provider.product.bind", $id) }}',
            headers: { Accept: 'application/json; charset=utf-8' },
            data: { product_id: product_id },
            success: function(data)
            {
                $('#loader-img').hide();
                hideSearchResult();
                if (data.success) {
                    var grid = $('#products-grid').data('kendoGrid');
                    grid.dataSource.read();
                    grid.refresh();
                }
                $.magnificPopup.close();
            },
            error: function (error) {
                $('#loader-img').hide();
                console.log(error);
            }
        });
    }
</script>
