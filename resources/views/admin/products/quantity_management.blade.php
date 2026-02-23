@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
  <section class="content">
    <div class="row" style="display: flex;justify-content: center;align-items: center;">
      <div class="col-md-12 col-xs-12">
        <div class="box box-success box-solid">
          <div class="box-body">
            <div class="inner-head-section">
              <div class="inner-title">
                <span>{{ $page_title }}</span>
              </div>
            </div>
            <div class="inner-content-area">
              <div class="row justify-content-center">
                <div class="col-md-7">
                  <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                  </div>
                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  
                  <form method="POST" action="{{ route('admin.products.productQuantity.addEdit', $id ?? 0) }}" class="form-horizontal" enctype="multipart/form-data">
                   @csrf
                   <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="id">
                   <div class="form-role-area">
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="name">Quantity</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="name" id="name" type="text" placeholder="Quantity"
                              value="{{ old('name', $postData['name'] ?? '') }}" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="name_french">French Quantity</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="name_french" id="name_french" type="text" placeholder="French Quantity" value="{{ old('name_french', $postData['name_french'] ?? '') }}" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                      <a href="{{ route('admin.products.productQuantity.index') }}" class="btn btn-success">Back</a>
                    </div>
                  </div>
                  </form>
                 </div>
               </div>
             </div>
          </div>
        </div><!-- /.box -->
      </div><!-- /.col-->
    </div><!-- ./row -->
  </section><!-- /.content -->
 </div>
@endsection
