@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-12 col-xs-12">
                <div class="box box-success box-solid">
                    <div class="box-body">
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>
                        <div class="inner-head-section">
                            <div class="inner-title">
                                <span>Support Query</span>
                            </div>
                        </div>
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="custom-mini-table">
                                <table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1">
                                    <thead>
                                        <tr role="row">
                                           <th>Website</th>
                                            <th>Name</th>
                                            <th>Email ID</th>
                                            <th>Contact No.</th>
                                            <th>Message</th>
                                            <th>Created On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($supportQueries && count($supportQueries) > 0)
                                            @foreach($supportQueries as $row)
                                                <tr>
                                                    <td>{{ $stores[$row->store_id]->name ?? 'Default Store' }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>{{ $row->phone }}</td>
                                                    <td>{{ Str::limit($row->comment, 50) }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($row->created)->format('d M Y H:i:s') }}
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                           <a href="{{ route('supports.view', $row->id) }}" style="color:#3c8dbc;padding: 5px;" title="view">
                                                            <i class="fa far fa-eye fa-lg"></i>
                                                           </a>

                                                           <a href="{{ route('supports.delete', $row->id) }}" style="color:#d71b23;padding: 5px;" title="delete" onclick="return confirm('Are you sure you want to delete this query?');">
                                                             <i class="fa fa-trash fa-lg"></i>
                                                           </a>
                                                       </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">No support queries found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col-->
        </div><!-- ./row -->
    </section><!-- /.content -->
 </div>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    /* $('#example1').DataTable({
        "order": [[ 4, "desc" ]]
    }); */
});
</script>
@endsection
