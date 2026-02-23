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
                                        <a href="{{ route('banners.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Banner
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
                                            <th>Banner Name</th>
                                            <th>Website</th>
                                            <th>Image</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($banners && count($banners) > 0)
                                            @foreach($banners as $banner)
                                                <tr>
                                                    <td>
                                                        {{ ucfirst($banner['name']) }}
                                                    </td>
                                                    <td>{{ $mainStoreList[$banner['main_store_id']] ?? 'Unknown' }}</td>
                                                    <td>
                                                        @if($banner['banner_image'])
                                                            <img src="{{ asset('uploads/banners/large/' . $banner['banner_image']) }}" width="auto" height="80"
                                                                 onerror="this.src='{{ asset('uploads/banners/' . $banner['banner_image']) }}';">
                                                        @else
                                                            <span>No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $banner['created'] ? date('Y-m-d H:i', strtotime($banner['created'])) : 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $banner['updated'] ? date('Y-m-d H:i', strtotime($banner['updated'])) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($banner['status'] == 1)
                                                            <a href="{{ route('banners.toggleStatus', [$banner['id'], 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('banners.toggleStatus', [$banner['id'], 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('banners.addEdit', $banner['id']) }}" style="color:green;padding: 5px;" title="edit">
                                                                <i class="fa far fa-edit fa-lg"></i>
                                                            </a>
                                                            <a href="{{ route('banners.delete', $banner['id']) }}" style="color:#d71b23;padding: 5px;" title="delete" onclick="return confirm('Are you sure you want to delete this banner?');">
                                                                <i class="fa fa-trash fa-lg"></i>
                                                            </a>
                                                        </div>
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
                        {{-- @if($banners && $banners->links())
                            <div class="pagination-wrapper">
                                {{ $banners->links() }}
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
        "order": [[0, "asc"]]
    });
});
</script>
@endsection
