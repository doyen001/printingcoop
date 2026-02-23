@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
  <section class="content">
    <div class="row" style="display: flex;justify-content: center;align-items: center;">
      <div class="col-md-12 col-xs-12">
        <div class="box box-success box-solid">
          <div class="box-body">
            <h3 class="text-center mb-5" style="color:#555 !important;">{{ $page_title }}</h3>
            <div class="text-center" style="color:red">
              @if(session('message_error'))
                {{ session('message_error') }}
              @endif
            </div>

            @if($blog)
              <div class="row">
                <div class="control-group info col-sm-4">
                  <label class="span2" for="website">Website</label>
                </div>
                <div class="control-group info col-sm-8">
                  <div class="controls">
                    @php
                      $store_ids = $blog->store_id;
                      if (!empty($store_ids)) {
                        $store_ids = explode(',', $store_ids);
                      } else {
                        $store_ids = array();
                      }

                      foreach ($storeList as $key => $val) {
                        if (in_array($key, $store_ids)) {
                          echo '<label style="margin-left:5px;">' . $val['name'] . '</label>';
                        }
                      }
                    @endphp
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="control-group info col-sm-4">
                  <label class="span2" for="title">Title</label>
                </div>
                <div class="control-group info col-sm-8">
                  <div class="controls">
                    <p>
                      {{ ucfirst($blog->title) }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="control-group info col-sm-4">
                  <label class="span2" for="content">Content</label>
                </div>
                <div class="control-group info col-sm-8">
                  <div class="controls">
                    <p>
                      {!! ucfirst($blog->content) !!}
                    </p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="control-group info col-sm-4">
                  <label class="span2" for="image">Image</label>
                </div>
                <div class="control-group info col-sm-8">
                  <div class="controls">
                    @if($blog->image)
                      <img src="{{ url('uploads/blogs/large/' . $blog->image) }}" width="700" height="400"
                           onerror="this.src='{{ url('uploads/blogs/' . $blog->image) }}';">
                    @endif
                  </div>
                </div>
              </div>

              @if($blogComments && $blogComments->count() > 0)
                <div class="row mt-4">
                  <div class="col-md-12">
                    <h4>Blog Comments</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Comment</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($blogComments as $comment)
                            <tr>
                              <td>{{ $comment->name ?? 'N/A' }}</td>
                              <td>{{ $comment->email ?? 'N/A' }}</td>
                              <td>{{ $comment->comment ?? 'N/A' }}</td>
                              <td>{{ $comment->created ? date('Y-m-d H:i:s', strtotime($comment->created)) : 'N/A' }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              @endif

            @else
              <div class="row">
                <div class="col-md-12 text-center">
                  <p>Blog not found.</p>
                </div>
              </div>
            @endif

            <div class="text-right">
              <a href="{{ url('admin/Blogs') }}" class="btn btn-success">Back</a>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    </div><!-- /.col-->
  </div><!-- ./row -->
</section><!-- /.content -->
</div>
@endsection
