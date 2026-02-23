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
                            <!--<div class="col-md-6 col-xs-12 text-right">
                                <div class="all-vol-btn">
                                <a href="{{ url('admin/Configrations/addEdit') }}"><button>
                                <i class="fa fas fa-plus-circle"></i>{{ $sub_page_title ?? 'Add New' }}</button>
                                </a>
                                </div>
                            </div>-->
                            </div>
                        </div>

                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="custom-mini-table">
                                <table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1">
                                    <thead>
                                        <tr role="row">
                                            <th style="display:none;"></th>
                                            <th>Website Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if(count($lists) > 0)
                                            @foreach($lists as $key => $list)
                                                @php
                                                    $websiteName = $MainStoreList[$list['main_store_id']];
                                                @endphp
                                                <tr>
                                                    <th style="display:none;">{{ ucfirst($list['id']) }}</th>
                                                    <td style="text-align: left;">
                                                        {{ $websiteName }}
                                                    </td>

                                                    <td>
                                                        <div class="action-btns">
                                                           <a href="{{ url('admin/Configrations/addEdit/' . $list['id']) }}" style="color:green;padding: 5px;" title="edit">
                                                                <i class="fa far fa-edit fa-lg"></i>
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

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
</script>
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "order": [[ 0, "asc" ]]
    });
});
</script>
@endsection
