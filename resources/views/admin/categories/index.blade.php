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
                                        <span>{{ $page_title }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 text-right">
                                    <div class="all-vol-btn">
                                        <a href="{{ route('categories.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New
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
                                            <th class="hidden"></th>
                                            <th>Name</th>
                                            <th>Category Order</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($categories && count($categories) > 0)
                                            @foreach($categories as $list)
                                                <tr>
                                                    <td class="hidden"></td>
                                                    <td>{{ ucfirst($list['name']) }}</td>
                                                    <td>{{ $list['category_order'] ?? 0 }}</td>
                                                    <td>
                                                        @if($list['status'] == 1)
                                                            <a href="{{ route('categories.toggleStatus', [$list['id'], 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('categories.toggleStatus', [$list['id'], 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $list['created'] ? date('Y-m-d H:i', strtotime($list['created'])) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $list['updated'] ? date('Y-m-d H:i', strtotime($list['updated'])) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('categories.addEdit', $list['id']) }}" style="color:green" title="edit">
                                                            <i class="fa far fa-edit fa-lg"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('categories.delete', $list['id']) }}" style="color:#d71b23" title="delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                                            <i class="fa fa-trash fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">List Empty.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        {{-- @if($categories && $categories->links())
                            <div class="pagination-wrapper">
                                {{ $categories->links() }}
                            </div>
                        @endif --}}

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [[2, "asc"]]
    });
});
</script>
@endsection
