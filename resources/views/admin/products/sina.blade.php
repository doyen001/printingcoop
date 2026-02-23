@extends('layouts.admin')

@section('before_head')
<link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<!-- DEBUG: sina.blade.php is being loaded -->
<div class="content-wrapper dd" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-12 col-xs-12">
                <div id="products-editor">
                    <div v-for="(product, i) in products" class="" :index="product.id">
                        @{{product.name}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/vue-app.js') }}" defer></script>
<script src="{{ asset('assets/js/vue-app.js') }}"></script>
<script>
    var productVue = new Vue({
        el: '#products-editor',
        data: function () {
            return {
                products: [],
            }
        },
        methods: {
            getProduct(token) {
                axios.get(`https://api.sinaliteuppy.com/product`,
                {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.products = response.data;
                    response.data.forEach (function(e, i) {
                        console.log(e, i);
                    });
                })
                .catch(err => {
                    Object.keys(err.response.data.errors).forEach (key => {
                        kendo.alert(err.response.data.errors[key]);
                    });
                });
            }
        },
        created() {
            var data = {
                client_id: 'd9L5eSnZGSvTPAzNRcMleHD0cFhIWCa2',
                client_secret: '3UiQ3bPxx1SkRgoQQqkh8xyRDTZiphYIMGLQT_Mqb0xsqOQPVCaHHtCdI7MwZ-SX',
                audience: 'https://apiconnect.sinalite.com',
                grant_type: 'client_credentials'
            };
            axios.post(`https://api.sinaliteuppy.com/auth/token`, data)
            .then(response => {
                var token = response.data.access_token;
                this.getProduct(token);
            })
            .catch(err => {
                Object.keys(err.response.data.errors).forEach (key => {
                    kendo.alert(err.response.data.errors[key]);
                });
            });
        },
    });
</script>
@endpush