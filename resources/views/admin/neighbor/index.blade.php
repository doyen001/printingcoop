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
                        <div class="row align-items-end">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12 text-left">
                                <div class="inner-title" style="margin-bottom: 20px;">
                                    <span>{{ ucfirst($page_title) }}</span>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-7 col-xl-4 col-xs-12 text-left">
                                <!--Search Neighbor Html-->
                                <div class="search-box-area">
                                    <div class="search-sugg">
                                        <label class="span2">Search</label>
                                        <input class="form-control" type="text" placeholder="Search Neighbor" onkeyup="searchNeighbor($(this).val())" id="searchSgedNeighborTextBox" name="searchSgedNeighborTextBox">
                                    </div>
                                    <div class="search-result" id="searchDiv" style="display:none"><!-- Add "active" class to show -->
                                        <a href="javascript:void(0)" onclick="hidesearchDiv()"><i class="fa fas fa-times" ></i></a>
                                        <ul id="NeighborListUl"></ul>
                                    </div>
                                </div>
                                <!--End Search Neighbor Html-->
                            </div>
                            <div class="col-md-12 col-lg-5 col-xl-4 col-xs-12 text-left">
                                <div class="select-options">
                                    <form action="{{ route('neighbor.index') }}" method="post">
                                        @csrf
                                        <div>
                                            <label class="span2">Order By</label>
                                            <select class="form-control" onchange="this.form.submit()" name="order">
                                                <option value="desc" {{ $order=='desc' ? 'selected="selected"' : '' }}>Latest Neighbor</option>
                                                <option value="asc" {{ $order=='asc' ? 'selected="selected"' : '' }}>Oldest Neighbor</option>
                                            </select>
                                        </div>
                                        <div style="margin-left: 10px;">
                                            <a href="{{ route('neighbor.index') }}"><button type="button">Reset</button></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-4 col-xs-12 text-right">
                                <div class="all-vol-btn">
                                    <form action="{{ route('neighbor.deleteAll') }}" method="post">
                                        @csrf
                                        <a href="{{ route('neighbor.edit') }}" style="margin-right: 5px;">
                                            <button type="button"><i class="fa fas fa-plus-circle"></i> Add New Neighbor</button>
                                        </a>
                                        <button><i class="fa fas fa-trash fa-lg"></i> Delete All</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="custom-mini-table">
                            <form action="{{ route('neighbor.deleteAll') }}" method="post">
                                @csrf
                                <table id="example3" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example3">
                                <thead>
                                    <tr role="row">
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th width="40%">Name</th>
                                        <th width="40%">URL</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if(count($list) > 0)
                                        @foreach($list as $key => $item)
                                            <tr>
                                                <td><input type="checkbox" name="neighbor_ids[]" class="neighbor_ids" value="{{ $item['id'] }}"></td>
                                                <td>{{ ucfirst($item['name']) }}</td>
                                                <td><a target="_blank" href="{{ $item['url'] }}">{{ $item['url'] }}</a></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="{{ route('neighbor.edit', $item['id']) }}"
                                                            style="color:green;padding: 5px;" title="edit">
                                                            <i class="fa far fa-edit fa-lg"></i>
                                                        </a>

                                                        <a href="{{ route('neighbor.delete', $item['id']) }}"
                                                            style="color:#d71b23;padding: 5px;" title="delete"
                                                            onclick="return confirm('Are you sure you want to delete this neighbor?');">
                                                            <i class="fa fa-trash fa-lg"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">List Empty.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($total_rows > $per_page)
                        <div class="pagination-wrapper">
                            {{ $list->links() ?: '' }}
                        </div>
                    @endif

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
</div>

<script>
function searchNeighbor(searchTerm) {
    if (searchTerm.length < 2) {
        $('#searchDiv').hide();
        return;
    }
    
    // AJAX search functionality
    $.ajax({
        url: '{{ route("neighbor.search") }}',
        method: 'POST',
        data: {
            search: searchTerm,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success && response.data.length > 0) {
                var html = '';
                response.data.forEach(function(neighbor) {
                    html += '<li><a href="{{ route("neighbor.edit") }}/' + neighbor.id + '">' + neighbor.name + '</a></li>';
                });
                $('#NeighborListUl').html(html);
                $('#searchDiv').show();
            } else {
                $('#NeighborListUl').html('<li>No results found</li>');
                $('#searchDiv').show();
            }
        },
        error: function() {
            $('#NeighborListUl').html('<li>Search failed</li>');
            $('#searchDiv').show();
        }
    });
}

function hidesearchDiv() {
    $('#searchDiv').hide();
    $('#searchSgedNeighborTextBox').val('');
}

$(document).ready(function() {
    $('#example3').DataTable({
        "order": [[0, "desc"]]
    });
    
    // Select all checkbox functionality (CI project style)
    $('#select-all').click(function () {
        if ($(this).prop('checked') == true) {
            $('.neighbor_ids').prop('checked', true);
        } else {
            $('.neighbor_ids').prop('checked', false);
        }
    });
});
</script>
@endsection
