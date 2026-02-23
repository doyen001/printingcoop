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
                                <span>{{ ucfirst($page_title).' List' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 text-right">
                            <div class="all-vol-btn">
                            <a href="{{ route($sub_page_url) }}"><button>
                            <i class="fa fas fa-plus-circle"></i>{{ $sub_page_title }}</button>
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
                                    <th style="display:none"></th>
                                    <th width="30%">Attributes Name</th>
                                    <th width="10%">Created On</th>
                                    <th width="10%">Updated On</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($lists) > 0)
                                    @foreach($lists as $key => $list)
                                        <tr>
                                            <th style="display:none">{{ ucfirst($list['id']) }}</th>
                                            <td>{{ ucfirst($list['name']) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($list['created'])->format('Y-m-d') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($list['updated'])->format('Y-m-d') }}</td>
                                            <td>
                                                @if($list['status'] == 1)
                                                    <a href="{{ route($sub_page_url_active_inactive, [$list['id'], 0]) }}">
                                                        <button type="submit" class="custon-active">Active</button>
                                                    </a>
                                                @else
                                                    <a href="{{ route($sub_page_url_active_inactive, [$list['id'], 1]) }}">
                                                        <button type="submit" class="custon-delete">Inactive</button>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route($sub_page_url, $list['id']) }}"
                                                    style="color:green;padding: 5px;" title="edit">
                                                    <i class="fa far fa-edit fa-lg"></i>
                                                </a>

                                                <a href="{{ route($sub_page_delete_url, $list['id']) }}"
                                                    style="color:#d71b23;padding: 5px;" title="delete"
                                                    onclick="return confirm('Are you sure you want to delete this attribute?');">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">List Empty.</td>
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
            [0, "asc"]
        ]
    });
});
</script>
@endsection
