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
                                        <a href="{{ route('blogs.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Blog
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
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Blog Category</th>
                                            <th>Popular</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($blogs && count($blogs) > 0)
                                            @foreach($blogs as $blog)
                                                <tr>
                                                    <td>
                                                        {{ ucfirst($blog->title) }}
                                                    </td>
                                                    <td>
                                                        @if($blog->image)
                                                            <img src="{{ asset('uploads/blogs/' . $blog->image) }}" width="100" height="80">
                                                        @else
                                                            <span>No Image</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ ucfirst($blog->category_name ?? 'Uncategorized') }}
                                                    </td>
                                                    <td>
                                                        {{ isset($blog->populer) && $blog->populer == 1 ? 'Yes' : 'No' }}
                                                    </td>
                                                    <td>
                                                        {{ $blog->created ? date('Y-m-d H:i', strtotime($blog->created)) : 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $blog->updated ? date('Y-m-d H:i', strtotime($blog->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($blog->status == 1)
                                                            <a href="{{ route('blogs.toggleStatus', [$blog->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('blogs.toggleStatus', [$blog->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('blogs.addEdit', $blog->id) }}" style="color:green;padding: 5px;" title="edit">
                                                                <i class="fa far fa-edit fa-lg"></i>
                                                            </a>
                                                            <a href="{{ route('blogs.view', $blog->id) }}" style="color:#3c8dbc;padding: 5px;" title="view">
                                                                <i class="fa far fa-eye fa-lg"></i>
                                                            </a>
                                                            <a href="{{ route('blogs.delete', $blog->id) }}" style="color:#d71b23;padding: 5px;" title="delete" onclick="return confirm('Are you sure you want to delete this blog?');">
                                                                <i class="fa fa-trash fa-lg"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center">List Empty.</td>
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

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [[ 2, "desc" ]]
    });
});
</script>
@endsection
