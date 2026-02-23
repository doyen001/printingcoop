@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>
                        <div class="inner-head-section">
                            <div class="row">
                                <div class="col-md-6 col-xs-12 text-left">
                                    <div class="inner-title">
                                        <span>{{ ucfirst($page_title) . ' List' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="col-sm-12 col-md-12 custom-mini-table">
                                <table id="example1" class="table table-bordered table-striped dataTable no-footer"
                                    role="grid" aria-describedby="example1">
                                    <thead>
                                        <tr role="row">
                                            <th width="20%">Website</th>
                                            <th width="20%">Contact Name</th>
                                            <th width="15%">Company Name</th>
                                            <th width="15%">Email</th>
                                            <th width="10%">Product Type</th>
                                            <th width="10%">Product Name</th>
                                            <th width="5%">Created On</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($estimates)
                                            @foreach($estimates as $list)
                                                <tr>
                                                    <td>
                                                        {{ $StoreList[$list['store_id']]['name'] ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-left">{{ $list['contact_name'] }}</td>
                                                    <td class="text-left">{{ $list['company_name'] }}</td>
                                                    <td class="text-left">{{ $list['email'] }}</td>
                                                    <td class="text-left">{{ $list['product_type'] }}</td>
                                                    <td class="text-left">{{ $list['product_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($list['created'])->format('Y-m-d') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.products.estimates.view', $list['id']) }}"
                                                            style="color:green;padding: 5px;" title="view">
                                                            <i class="fa fa-eye fa-lg"></i>
                                                        </a>

                                                        <a href="{{ route('admin.products.estimates.delete', $list['id']) }}"
                                                            style="color:#d71b23;padding: 5px;" title="delete"
                                                            onclick="return confirm('Are you sure you want to delete this estimate?');">
                                                            <i class="fa fa-trash fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">List Empty.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
</script>
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
});
</script>
@endsection
