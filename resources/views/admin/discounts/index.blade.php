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
                                        <span>{{ $page_title }} List</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 text-right">
                                    <div class="all-vol-btn">
                                        <a href="{{ route('discounts.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Discount
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="custom-mini-table">
                                <table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1">
                                    <thead>
                                        <tr role="row">
                                            <th>Discount Code</th>
                                            <th>Discount</th>
                                            <th>Discount Valid From</th>
                                            <th>Discount Valid Till</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($discounts && count($discounts) > 0)
                                            @foreach($discounts as $discount)
                                                <?php
                                                $discount_valid_to_str_time = strtotime($discount->discount_valid_to);
                                                $cr_date = date("Y-m-d H:i:s");
                                                $cr_date_str = strtotime($cr_date);
                                                $type = $type ?? 'all';
                                                ?>
                                                
                                                @if($type == "all" || ($type == "current" && $cr_date_str <= $discount_valid_to_str_time) || ($type == "expired" && $cr_date_str > $discount_valid_to_str_time))
                                                <tr>
                                                    <td>{{ $discount->code }}</td>
                                                    <td>
                                                        @if($discount->discount_type == 'discount_percent')
                                                            {{ number_format($discount->discount, 2) }}%
                                                        @else
                                                            ${{ number_format($discount->discount, 2) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $discount->discount_valid_from ? date('Y-m-d H:i', strtotime($discount->discount_valid_from)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $discount->discount_valid_to ? date('Y-m-d H:i', strtotime($discount->discount_valid_to)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $discount->created ? date('Y-m-d H:i', strtotime($discount->created)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $discount->updated ? date('Y-m-d H:i', strtotime($discount->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($discount->status == 1)
                                                            <a href="{{ route('discounts.toggleStatus', [$discount->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('discounts.toggleStatus', [$discount->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            @if($type == "current")
                                                                <a class="view-btn" href="{{ route('admin.products.index', $discount->id) }}" style="color:#3c8dbc" title="view">
                                                                    <i class="fa far fa-eye fa-lg"></i> View Products
                                                                </a>
                                                            @endif

                                                            <a href="{{ route('discounts.addEdit', $discount->id) }}" style="color:green" title="edit">
                                                                <i class="fa far fa-edit fa-lg"></i>
                                                            </a>
                                                            &nbsp;&nbsp;
                                                            <a href="{{ route('discounts.delete', $discount->id) }}" style="color:red" title="delete" onclick="return confirm('Are you sure you want to delete this discount code?');">
                                                                <i class="fa fa-trash fa-lg"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
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

                        <!-- Pagination -->
                        @if($discounts && $discounts->links())
                            <div class="pagination-wrapper">
                                {{ $discounts->links() }}
                            </div>
                        @endif

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [[0, "asc"]]
    });
});
</script>
@endsection
