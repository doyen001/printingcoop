<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Printing Coop') }}-Admin-@yield('title', 'Admin')</title>
    @yield('before_head')
    <link rel="shortcut icon" type="image/png" href="{{ url('assets/images/favicon.png') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/ionicons.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/morris.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/blueAdminLTE.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/_all-skins.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/BsMultiSelect.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/admin/css/jquery.datetimepicker.min.css') }}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/bootstrap/css/daterangepicker.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/build/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/simple-line-icons/simple-line-icons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/magnific-popup/magnific-popup.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/summernote/summernote.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/jquery-ui-themes/smoothness/jquery-ui-1.10.3.custom.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/elfinder/css/elfinder.full.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/elfinder/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/administration/kendo/styles/2022.2.802/kendo.default-main.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/build/css/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/administration/fineuploader/fineuploader-4.2.2.min.css') }}" />
    
    @yield('styles')

    <script src="{{ url('assets/administration/build/js/jquery.min.js') }}"></script>
    <script src="{{ url('assets/administration/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ url('assets/administration/build/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/administration/bootstrap/js/daterangepicker.min.js') }}"></script>
    <script src="{{ url('assets/administration/typeahead.js') }}"></script>
    <script src="{{ url('assets/administration/admin.search.js') }}"></script>
    <script src="{{ url('assets/administration/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/administration/jquery.validate.unobtrusive.min.js') }}"></script>
    <script src="{{ url('assets/administration/admin.common.js') }}"></script>
    <script src="{{ url('assets/administration/kendo/scripts/2022.2.802/kendo.all.min.js') }}"></script>
</head>

<body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="javascript:void(0)" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src="{{ url('assets/admin/images/printing.coopLogo.png') }}"></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg" style="padding-top: 10px;"><img src="{{ url('assets/admin/images/printing.coopLogo.png') }}"></span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <i class="fa fas fa-bars"></i>
                </a>
                <div class="logo-section for-mobile text-center">
                    <img src="{{ url('assets/admin/images/printing.coopLogo.png') }}">
                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span class="hidden-xs">
                                    <span class="large">Admin Detail</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ url('assets/admin/images/user2-160x160.jpg') }}" class="img-circle"
                                        alt="User Image" />
                                    <p>
                                        <span class="large">Welcome {{ ucfirst(session('name', 'Admin')) }}</span><br>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="{{ url('admin/Accounts/logout') }}"
                                            class="btn btn-warning btn-flat">Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="after-header"></div>
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <ul class="sidebar-menu">
                    <li class="treeview {{ request()->is('admin/Dashboards*') ? 'active' : '' }}">
                        <a href="{{ url('admin/Dashboards') }}">
                            <i class="fa fa-tachometer"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    @php
                        $menuService = new \App\Services\AdminMenuService();
                        $menuItems = $menuService->getMenuItems();
                        $currentClass = request()->segment(2) ?? '';
                        $currentMethod = request()->segment(3) ?? 'index';
                        $currentParameter = request()->segment(4) ?? '';
                    @endphp
                    
                    @foreach($menuItems as $moduleId => $menuItem)
                        @php
                            $module = $menuItem['module'];
                            $subModules = $menuItem['sub_modules'];
                            $moduleUrlArray = $menuService->getModuleUrlArray($module);
                            $isModuleActive = in_array($currentClass, $moduleUrlArray);
                        @endphp
                        
                        <li class="treeview {{ $isModuleActive ? 'active' : '' }}">
                            @if($subModules->count() > 0)
                                <a href="javascript:void(0)">
                                    <i class="{{ $module->class }}"></i>
                                    <span>{{ $module->module_name }}</span>
                                    <i class="fa fa-chevron-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @foreach($subModules as $subModule)
                                        @php
                                            $urlParts = explode('/', $subModule->url);
                                            $subClass = $urlParts[0] ?? '';
                                            $subAction = $urlParts[1] ?? 'index';
                                            $subParameter = $urlParts[2] ?? '';
                                            
                                            $isSubModuleActive = $currentClass === $subClass && 
                                                              $currentMethod === $subAction && 
                                                              $currentParameter === $subParameter;
                                        @endphp
                                        
                                        <li class="{{ $isSubModuleActive ? 'active' : '' }}">
                                            <a href="{{ url('admin/' . $subModule->url) }}">
                                                <i class="{{ $subModule->sub_module_class }}"></i>
                                                {{ $subModule->sub_module_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ url('admin/' . $module->url) }}">
                                    <i class="{{ $module->class }}"></i>
                                    <span>{{ $module->module_name }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                    
                    <li class="treeview">
                        <a href="{{ url('admin/Accounts/logout') }}">
                            <i class="fa fa-unlock-alt"></i> <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
    </div>
    @yield('content')
    <div id="loader-img">
        <div id="loader-img-inner"><img src="{{ url('assets/images/loder.gif') }}" width="100"></div>
    </div>
<footer class="main-footer text-center">
    <strong>&copy; {{ date('Y') }} Printingcoop</strong>
</footer>
<div class='control-sidebar-bg'></div>
</div>
<div class="modal" id="MsgModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="text-right" style="padding-right: 10px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="cart-modal modal fade view-details-modal" id="personalisemodal" tabindex="-1" role="dialog"
    aria-labelledby="cartmodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="personalised-box-inner">
                    <div class="personalised-product-name">
                        <span>Product Name</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-img product-image-zoom single-image zoom-available">
                                <div class="product-image-gallery">
                                    <img id="zoom_05" src="" data-zoom-image="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="personalised-edit-box">
                                <div class="personalised-edit-box-inner">
                                    <div class="personalised-edit-single">
                                        <div class="personalised-edit-single-title">
                                            <span>Choose Color:</span>
                                        </div>
                                        <div class="personalised-edit-single-fields">
                                            <div class="personalised-edit-single-input">
                                                <span>Choose Color:</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- title -->
                                    <div class="personalised-edit-single">
                                        <div class="personalised-edit-single-title">
                                            <span>title:</span>
                                        </div>
                                        <div class="personalised-edit-single-fields">
                                            <div class="personalised-edit-single-example">
                                                <span>lable</span>
                                            </div>
                                            <div class="personalised-edit-single-input">
                                                <span>value</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personalised-edit-single">
                                        <div class="personalised-edit-single-title">
                                            <span> Paragraph</span>
                                        </div>
                                        <div class="personalised-edit-single-fields">
                                            <p>safdbdbdfbfbnrrrthrtbfbrhrbrthrt</p>
                                        </div>
                                    </div>
                                    <div class="personalised-edit-single">
                                        <div class="personalised-edit-single-title">
                                            <span>Photo:</span>
                                        </div>
                                        <div class="personalised-edit-single-fields">
                                            <div class="personalised-upload">
                                                <div class="row">
                                                    <!-- photo -->
                                                    <div class="col-md-4">
                                                        <div class="personalised-single-upload">
                                                            <div class="personalised-single-upload-area">
                                                                <div class="personalised-action-img">
                                                                    <span><i class="fa fas fa-plus"></i></span>
                                                                    <img class="personalised-display-img" src="">
                                                                </div>
                                                            </div>
                                                            <div class="personalised-image-num"><span>Photo</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- end -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Load jQuery first -->
<script src="{{ url('assets/admin/js/jquery.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/admin/js/bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/admin/js/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/admin/js/chart.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/admin/js/app.js') }}" type="text/javascript"></script>
<!-- Load DataTables after jQuery -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ url('assets/administration/fineuploader/jquery.fineuploader-4.2.2.min.js') }}"></script>
<script src="{{ url('assets/administration/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ url('assets/administration/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('assets/administration/build/js/smartresize.js') }}"></script>
<script src="{{ url('assets/administration/build/js/custom.js') }}"></script>
<script src="{{ url('assets/administration/summernote/summernote.min.js') }}"></script>
<script src="{{ url('assets/administration/elfinder/js/elfinder.min.js') }}"></script>
<script src="{{ url('assets/administration/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ url('assets/administration/jquery.tmpl.min.js') }}"></script>
<script src="{{ url('assets/js/kendo-util.js') }}"></script>

<script>
$(document).ready(function() {
    $('#a1').click(function() {
        $('.p-field').show();
    });
    $('#a2').click(function() {
        $('.p-field').hide();
    });
    $('#single').click(function() {
        $('.single').toggle();
    });

    if ($('#example1').length) {
        $('#example1').DataTable();
    }

    $('.has-clear input[type="text"]').on('input propertychange', function() {
        var $this = $(this);
        var visible = Boolean($this.val());
        $this.siblings('.form-control-clear').toggleClass('hidden', !visible);
    }).trigger('propertychange');

    $('.form-control-clear').click(function() {
        $(this).siblings('input[type="text"]').val('')
            .trigger('propertychange').focus();
    });
});

function showpersonale(id) {
    // alert(id);
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url('admin/Orders/personaliseDetail') }}',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            console.log(JSON.parse(data.personaliseDetail));
            $('#personalisemodal').modal('show');
        }
    });
}
</script>

<!-- Loader element for AJAX operations (CI compatibility) -->
<div id="loader-img" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
    <div id="loader-img-inner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
        <img src="{{ asset('assets/images/loder.gif') }}" width="100" alt="Loading...">
    </div>
</div>

<!-- AJAX CSRF Token Setup (Laravel compatibility) -->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

@stack('scripts')
@yield('before_body')
</body>

</html>
