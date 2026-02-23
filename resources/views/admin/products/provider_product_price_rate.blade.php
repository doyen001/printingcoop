<form id="price-rate-form" method="post" action="{{ route('admin.products.provider.product.price.rate', $product->id) }}">
    @csrf
    <div class="k-card">
        <div class="k-card-header">
            <h3 class="k-card-title">Update Price Rate</h3>
        </div>
        <div class="k-card-body">
            <div class="form-horizontal">
                <div class="form-body">
                    <div class="form-group">
                        <label for="price_rate" class="control-label col-md-3 col-sm-3">Price Rate</label>
                        <input class="form-control col-md-9 col-sm-9" type="text" placeholder="Price Rate" name="price_rate" value="{{ $product->price_rate }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="k-card-actions">
            <div class="stats"></div>
            <span class="k-card-action">
                <button type="submit" class="k-button k-button-flat-primary k-button-flat k-button-md k-rounded-md filter-submit">
                    <i class="fa fa-check"></i> Update
                </button>
            </span>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#price-rate-form').on('submit', function (e) {
            e.preventDefault();
            $('#loader-img').show();
            $.post('{{ route("admin.products.provider.product.price.rate.total", $product->id) }}', $(this).serialize())
            .done(function (response) {
                $('#loader-img').hide();
                if (!response) {
                    kendo.alert('Error occurred.');
                    return;
                } else if (!response.success) {
                    kendo.alert(response.message);
                    return;
                }
                refreshGrid('products-grid');
                $.magnificPopup.close();
            }).fail(function (error) {
                kendo.alert(error);
                $('#loader-img').hide();
            });
        });
    });
</script>
