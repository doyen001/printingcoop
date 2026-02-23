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
                                        <a href="{{ route('categories.addEditTag') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Tag
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
                                            <th>Tag Order</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($tags && count($tags) > 0)
                                            @foreach($tags as $tag)
                                                <tr>
                                                    <td class="hidden"></td>
                                                    <td>{{ ucfirst($tag->name) }}</td>
                                                    <td>{{ $tag->tag_order ?? 0 }}</td>
                                                    <td>
                                                        @if($tag->status == 1)
                                                            <a href="{{ route('categories.toggleTagStatus', [$tag->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('categories.toggleTagStatus', [$tag->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $tag->created ? date('Y-m-d H:i', strtotime($tag->created)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $tag->updated ? date('Y-m-d H:i', strtotime($tag->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('categories.addEditTag', $tag->id) }}" style="color:green" title="edit">
                                                            <i class="fa far fa-edit fa-lg"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('categories.deleteTag', $tag->id) }}" style="color:#d71b23" title="delete" onclick="return confirm('Are you sure you want to delete this tag?');">
                                                            <i class="fa fa-trash fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Tag Empty.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        {{-- @if($tags && $tags->links())
                            <div class="pagination-wrapper">
                                {{ $tags->links() }}
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
