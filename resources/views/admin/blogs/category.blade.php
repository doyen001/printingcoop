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
                                        <a href="{{ route('blogs.addEditCategory') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Category
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
                                            <th>Category Name</th>
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
                                                        {{ ucfirst($blog->category_name) }}
                                                    </td>

                                                    <td>
                                                        {{ $blog->created ? date('Y-m-d H:i', strtotime($blog->created)) : 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $blog->updated ? date('Y-m-d H:i', strtotime($blog->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($blog->status == 1)
                                                            <a href="{{ route('blogs.toggleCategoryStatus', [$blog->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('blogs.toggleCategoryStatus', [$blog->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('blogs.addEditCategory', $blog->id) }}" style="color:green;padding: 5px;" title="edit">
                                                                <i class="fa far fa-edit fa-lg"></i>
                                                            </a>

                                                            <a href="{{ route('blogs.deleteCategory', $blog->id) }}" style="color:#d71b23;padding: 5px;" title="delete" onclick="return confirm('Are you sure you want to delete this blog category?');">
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
        "order": [[ 0, "asc" ]]
    });
});
</script>
@endsection
