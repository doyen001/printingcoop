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
                                        {{-- <a href="{{ route('sections.addEdit') }}">
                                            <button>
                                                <i class="fa fas fa-plus-circle"></i> Add New Section
                                            </button>
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="custom-mini-table">
                                <table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1">
                                    <thead>
                                        <tr role="row">
                                            <th>Section Name</th>
                                            <th>Website</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($sections && count($sections) > 0)
                                            @foreach($sections as $section)
                                                <tr>
                                                    <td>{{ ucfirst($section['name']) }}</td>
                                                    <td>{{ $mainStoreList[$section['main_store_id']] ?? 'Unknown' }}</td>
                                                    <td>
                                                        {{ $section['created'] ? date('Y-m-d H:i', strtotime($section['created'])) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $section['updated'] ? date('Y-m-d H:i', strtotime($section['updated'])) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if($section['status'] == 1)
                                                            <a href="{{ route('sections.toggleStatus', [$section['id'], 0]) }}">
                                                                <button type="submit" class="custon-active">Active</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('sections.toggleStatus', [$section['id'], 1]) }}">
                                                                <button type="submit" class="custon-delete">Inactive</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('sections.addEdit', $section['id']) }}" style="color:green" title="edit">
                                                            <i class="fa far fa-edit fa-lg"></i>
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
                        {{-- @if($sections && $sections->links())
                            <div class="pagination-wrapper">
                                {{ $sections->links() }}
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
