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
                                        <a href="{{ route('pages.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Page
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
                                            <th>Page Name</th>
                                            <th>Website</th>
                                            <th>Page Order</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($pages && count($pages) > 0)
                                            @foreach($pages as $page)
                                                <tr>
                                                    <td>{{ ucfirst($page->title) }}</td>
                                                    <td>{{ $page->main_store_id ?? 'Default' }}</td>
                                                    <td>{{ $page->shortOrder ?? 0 }}</td>
                                                    <td>
                                                        {{ $page->created ? date('Y-m-d H:i', strtotime($page->created)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $page->updated ? date('Y-m-d H:i', strtotime($page->updated)) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($page->status == 1)
                                                            <a href="{{ route('pages.toggleStatus', [$page->id, 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('pages.toggleStatus', [$page->id, 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('pages.addEdit', $page->id) }}" style="color:green" title="edit">
                                                            <i class="fa fa-edit fa-lg"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('pages.delete', $page->id) }}" style="color:red" title="delete" onclick="return confirm('Are you sure you want to delete this page?');">
                                                            <i class="fa fa-trash fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">No records found.</td>
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

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [[2, "asc"]],
        "language": {
            "emptyTable": "No records found."
        }
    });
});
</script>
@endsection
