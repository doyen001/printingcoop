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
                <div class="col-md-12">
                  <div class="text-center" style="color:red">
                    @if(session('message_error'))
                        {{ session('message_error') }}
                    @endif
                  </div>
                  <div class="form-role-area">
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="name">Product Name</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($Product['name']) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="short_description">Product Short Description</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($Product['short_description']) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="full_description">Product Full Description</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{!! $Product['full_description'] !!}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="price">Product Price</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="row align-items-center">
                              <div class="col-md-6">
                                <div class="product-view-display">
                                  <span>{{ number_format($Product['price'], 2) }}</span>
                                </div>
                                <label class="form-inner-label">List Price CAD</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="code">Product Code</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ $Product['code'] }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="model">Product Model</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ $Product['model'] }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="status">Status</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ $Product['status'] == 1 ? 'Active' : 'Inactive' }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    @if(!empty($ProductImages))
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2">Product Images</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <div class="row">
                                @foreach($ProductImages as $image)
                                <div class="col-md-3 mb-3">
                                  <img src="{{ url('uploads/products/' . $image->image) }}" 
                                       class="img-fluid" alt="Product Image">
                                </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col-->
    </div>
    <!-- ./row -->
  </section>
  <!-- /.content -->
</div>

@push('styles')
<style>
.product-view-display {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
    min-height: 30px;
    display: flex;
    align-items: center;
}

.form-inner-label {
    font-size: 11px;
    color: #666;
    margin-top: 5px;
    display: block;
}
</style>
@endpush
