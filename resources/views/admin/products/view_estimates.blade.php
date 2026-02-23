@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
  <section class="content">
    <div class="row" style="display: flex;justify-content: center;align-items: center;">
      <div class="col-md-12 col-xs-12">
        {{-- <a href="{{ route('admin.products.estimates.index') }}" class="btn btn-success">Back to Estimates</a> --}}
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
                  <div class="form-role-area">
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-12">
                          <label class="span2" for="customer_info">Customer information</label>
                        </div>
                        <div class="col-md-12">
                          <div class="controls">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>
                                    {{ $StoreList[$Product['store_id']]['name'] ?? 'N/A' }}</span>
                                </div>
                                <label class="form-inner-label">Website</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['contact_name']) }}</span>
                                </div>
                                <label class="form-inner-label">Contact Name</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['company_name']) }}</span>
                                </div>
                                <label class="form-inner-label">Company Name</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['email']) }}</span>
                                </div>
                                <label class="form-inner-label">Email</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['phone_number']) }}</span>
                                </div>
                                <label class="form-inner-label">Phone Number</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['street']) }}</span>
                                </div>
                                <label class="form-inner-label">Street</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['city']) }}</span>
                                </div>
                                <label class="form-inner-label">City</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['province']) }}</span>
                                </div>
                                <label class="form-inner-label">Province</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['country']) }}</span>
                                </div>
                                <label class="form-inner-label">Country</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['postal_code']) }}</span>
                                </div>
                                <label class="form-inner-label">Postal Code</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-12">
                          <label class="span2" for="info">Product Information</label>
                        </div>
                        <div class="col-md-12">
                          <div class="controls">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['product_type']) }}</span>
                                </div>
                                <label class="form-inner-label">Product Type</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['product_name']) }}</span>
                                </div>
                                <label class="form-inner-label">Product Name</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ $Product['has_quote_form'] ? 'Yes' : 'No' }}</span>
                                </div>
                                <label class="form-inner-label">Have You Requested The Same Quote Before?</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['qty_1']) }}</span>
                                </div>
                                <label class="form-inner-label">Qty1</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['qty_2']) }}</span>
                                </div>
                                <label class="form-inner-label">Qty2</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['qty_3']) }}</span>
                                </div>
                                <label class="form-inner-label">Qty3</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['more_qty']) }}</span>
                                </div>
                                <label class="form-inner-label">More Qty</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['flat_size']) }}</span>
                                </div>
                                <label class="form-inner-label">Flat Size ( Inches )</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['finish_size']) }}</span>
                                </div>
                                <label class="form-inner-label">Finished Size ( Inches )</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['paper_stock']) }}</span>
                                </div>
                                <label class="form-inner-label">Paper/Stock</label>
                              </div>
                              <div class="col-md-4">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['finish_size']) }}</span>
                                </div>
                                <label class="form-inner-label">Finished Size ( Inches )</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </hr>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-12">
                            <label class="span2" for="size">Side Size</label>
                          </div>
                          <div class="col-md-12">
                            <div class="controls">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="product-view-display">
                                    <span>{{ ucfirst($Product['no_of_sides']) }}</span>
                                  </div>
                                  <label class="form-inner-label">Number Of Sides</label>
                                </div>
                                <div class="col-md-6">
                                  <div class="product-view-display">
                                    <span>{{ ucfirst($Product['folding']) }}</span>
                                  </div>
                                  <label class="form-inner-label">Folding</label>
                                </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-12">
                          <label class="span2" for="size">Side Size</label>
                        </div>
                        <div class="col-md-12">
                          <div class="controls">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['no_of_sides']) }}</span>
                                </div>
                                <label class="form-inner-label">Number Of Sides</label>
                              </div>
                              <div class="col-md-6">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['folding']) }}</span>
                                </div>
                                <label class="form-inner-label">Folding</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-12">
                          <label class="span2" for="finishing">FINISHING</label>
                        </div>
                        <div class="col-md-12">
                          <div class="controls">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['total_versions']) }}</span>
                                </div>
                                <label class="form-inner-label">How Many Versions Would You Like?</label>
                              </div>
                              <div class="col-md-6">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['shipping_methods']) }}</span>
                                </div>
                                <label class="form-inner-label">Shipping Methods</label>
                              </div>
                              <div class="col-md-12">
                                <div class="product-view-display">
                                  <span>{{ ucfirst($Product['notes']) }}</span>
                                </div>
                                <label class="form-inner-label">Notes</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-right">
                  <a href="{{ route('admin.products.estimates.index') }}" class="btn btn-success">Back</a>
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
