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
                                        <a href="{{ route('categories.addEditSubCategory') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Sub Category
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
                                            <th>Sub Category Order</th>
                                            <th>Parent Category</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($subCategories && count($subCategories) > 0)
                                            @foreach($subCategories as $subCategory)
                                                <tr>
                                                    <td class="hidden"></td>
                                                    <td>
                                                        {{ ucfirst($subCategory->name) }}
                                                    </td>
                                                    <td>
                                                        {{ $subCategory->sub_category_order ?? 0 }}
                                                    </td>
                                                    <td>
                                                        {{ $subCategory->category_name }}
                                                    </td>
                                                    <td>
                                                        @if($subCategory->status == 1)
                                                            <a href="{{ route('categories.toggleSubCategoryStatus', [$subCategory->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('categories.toggleSubCategoryStatus', [$subCategory->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $subCategory->created ? date('Y-m-d H:i', strtotime($subCategory->created)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $subCategory->updated ? date('Y-m-d H:i', strtotime($subCategory->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('categories.addEditSubCategory', $subCategory->id) }}" style="color:green" title="edit">
                                                            <i class="fa far fa-edit fa-lg"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('categories.deleteSubCategory', $subCategory->id) }}" style="color:#d71b23" title="delete" onclick="return confirm('Are you sure you want to delete this sub-category?');">
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

                        <!-- Pagination -->
                        {{-- @if($subCategories && $subCategories->links())
                            <div class="pagination-wrapper">
                                {{ $subCategories->links() }}
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
