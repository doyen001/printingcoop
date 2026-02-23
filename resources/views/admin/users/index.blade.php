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
                                <span>{{ ucfirst($page_title) }}</span>
                            </div>
                        </div>
                       <div class="col-md-6 col-xs-12 text-right">
                            <div class="all-vol-btn">
                                <div class="upload-area">
                                    <a href="{{ $BASE_URL }}admin/Users/exportCSV/{{ $status ?? '' }}"/>
                                    <button><i class="fa fas fa-file-csv"></i> Export CSV</button>
                                    </a>

                                </div>
                                <div class="upload-area">
                                    <form action="{{ $BASE_URL }}admin/Users/importCSV" id="ImportCSVFROM" enctype='multipart/form-data' method="post">
                                        @csrf
                                        <input type="file" onchange="$('#ImportCSVFROM').submit()" name="csv" accept=".csv">
                                    <input type="hidden" name="page_status" value="{{ $status ?? '' }}">
                                        <button><i class="fa fas fa-plus-circle"></i> Import CSV</button>
                                    </form>
                                </div>

                                @if (!empty($user_id))
                                    <div class="upload-area">
                                        <a href="{{ $BASE_URL }}admin/Users"><button><i class="fa fas fa-arrow-left"></i> Back</button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1">
                        <thead>
                            <tr role="row">
                                <th>Customer Code</th>
                                <th>Website</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Last Login</th>
                                <th>Last Login IP</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <script>
                $(document).ready(function() {
                    $('#example1').DataTable({
                        "processing": true,
                        "serverSide": true,
                          "columns": [
                                { data: "customer_code" },
                               { data: "website" },
                                { data: "name" },
                                { data: "mobile" },
                                { data: "email" },
                                { data: "password" },
                                { data: "last_login" },
                                { data: "last_login_ip" },
                                { data: "created" },
                                { data: "status" },
                                { data: "action" }
                            ],
                            "order": [[0, "desc"]],
                        "ajax": {
                            "url": "{{ $BASE_URL }}admin/Users/ajaxList/{{ $status ?? '' }}",
                            "type": "POST",
                            "data": function(d) {
                                d._token = '{{ csrf_token() }}';
                                console.log(d);
                                // d contains start, length, search, etc. by default
                                return d;
                            },
                            "dataSrc": function(json) {
                                console.log(json);
                                if (typeof json !== 'object') {
                                    alert('Server error: Invalid JSON returned.');
                                    return [];
                                }
                                return json.data || [];
                            },
                            "error": function(xhr, error, thrown) {
                                console.log(xhr);
                                alert('AJAX Error: ' + xhr.responseText);
                            }
                        },
                        "order": [[ 0, "desc" ]]
                    });
                });
                </script>
                   
                </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
</div>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
 </script>

@endsection
