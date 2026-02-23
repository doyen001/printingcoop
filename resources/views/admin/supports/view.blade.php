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
                    {{ session('message_error') }}
                  </div>
                  <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                  </div>
                  <div class="form-role-area">
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="store_id">Website</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ $stores[$data->store_id]->name ?? 'Default Store' }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="name">Name</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($data->name) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="email">Email ID</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($data->email) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                           <label class="span2" for="phone">Contact No</label>
                         </div>
                         <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($data->phone) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                           <label class="span2" for="comment">Message</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div class="product-view-display">
                              <span>{{ ucfirst($data->comment) }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="text-right">
                      <a href="{{ route('supports.index') }}" class="btn btn-success">Back</a>
                    </div>
                  </div>
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
