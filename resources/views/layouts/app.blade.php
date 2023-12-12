
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Akuntansi">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Akuntansi</title>

    <!-- select2 versi 4-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.rtl.min.css" />



    {{-- <link rel="apple-touch-icon" href="{{ asset('admin_remark_base/') }}/assets/images/apple-touch-icon.png"> --}}
    <link rel="shortcut icon" href="{{ asset('admin_remark_base/') }}/website/images/logo-yk.png">
    {{-- <link rel="shortcut icon" href="{{ asset('admin_remark_base/') }}/assets/examples/images/iconAkuntansi.png"> --}}

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/css/site.min.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/skins/blue.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/waves/waves.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/typeahead-js/typeahead.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-sweetalert/sweetalert.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/toastr/toastr.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/examples/css/advanced/toastr.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/select2/select2.css">
     <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/fonts/glyphicons/glyphicons.css">
     <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/dropify/dropify.css">
     <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-touchspin/bootstrap-touchspin.css">
      <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">

    <!-- datatable -->
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-bs4/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/examples/css/tables/datatable.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">
        <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/clockpicker/clockpicker.css">
         <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/css/imgareaselect-animated.css">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
         <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/chartist/chartist.css">
         <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/jvectormap/jquery-jvectormap.css">
         <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
         <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/examples/css/dashboard/v1.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/css/fileinput.css" rel="stylesheet">


    <!--[if lt IE 9]>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/media-match/media.match.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/respond/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/breakpoints/breakpoints.js"></script>
    <link href="{{ asset('filepond-master/') }}/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
    <script>
      Breakpoints();
    </script>
    <style>
.cancel.btn-default{
  background-color:white ;
  border-color:#3a6e96 ;
  color:#3a6e96;
}
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  /* font-size: 12px; */
}
.select2-results__option {
  font-family: Arial, Helvetica, sans-serif;
  /* font-size: 12px; */
}

.navbar-default .dropdown-menu.notify-drop {
  min-width: 320px;
  background-color: #fff;
  min-height: 360px;
  max-height: 360px;
}
.navbar-default .dropdown-menu.notify-drop .notify-drop-title {
  border-bottom: 1px solid #e2e2e2;
  padding: 5px 15px 10px 15px;
}
.navbar-default .dropdown-menu.notify-drop .drop-content {
  min-height: 280px;
  max-height: 280px;
  overflow-y: scroll;
}
.navbar-default .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar-track
{
  background-color: #F5F5F5;
}

.navbar-default .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar
{
  width: 8px;
  background-color: #F5F5F5;
}

.navbar-default .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar-thumb
{
  background-color: #ccc;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li {
  border-bottom: 1px solid #e2e2e2;
  padding: 10px 0px 5px 0px;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li:nth-child(2n+0) {
  background-color: #fafafa;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li:after {
  content: "";
  clear: both;
  display: block;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li:hover {
  background-color: #fcfcfc;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li:last-child {
  border-bottom: none;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li .notify-img {
  float: left;
  display: inline-block;
  width: 45px;
  height: 45px;
  margin: 0px 20px 8px 0px;
}
.navbar-default .dropdown-menu.notify-drop .allRead {
  margin-right: 7px;
}
.navbar-default .dropdown-menu.notify-drop .rIcon {
  float: right;
  color: #999;
}
.navbar-default .dropdown-menu.notify-drop .rIcon:hover {
  color: #333;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li a {
  font-size: 12px;
  font-weight: normal;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li {
  font-weight: bold;
  font-size: 11px;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li hr {
  margin: 5px 0;
  width: 70%;
  border-color: #e2e2e2;
}
.navbar-default .dropdown-menu.notify-drop .drop-content .pd-l0 {
  padding-left: 0;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li p {
  font-size: 11px;
  font-family: sans-serif;
  color: #666;
  font-weight: normal;
  margin: 3px 0;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li p.time {
  font-size: 10px;
  font-weight: 600;
  top: -6px;
  margin: 8px 0px 0px 0px;
  padding: 0px 3px;
  border: 1px solid #e2e2e2;
  position: relative;
  background-image: linear-gradient(#fff,#f2f2f2);
  display: inline-block;
  border-radius: 2px;
  color: #B97745;
}
.navbar-default .dropdown-menu.notify-drop .drop-content > li p.time:hover {
  background-image: linear-gradient(#fff,#fff);
}
.navbar-default .dropdown-menu.notify-drop .notify-drop-footer {
  border-top: 1px solid #e2e2e2;
  bottom: 0;
  position: relative;
  padding: 8px 15px;
}
.navbar-default .dropdown-menu.notify-drop .notify-drop-footer a {
  color: #777;
  text-decoration: none;
}
.navbar-default .dropdown-menu.notify-drop .notify-drop-footer a:hover {
  color: #333;
}

a.notification-link,
a.notification-link:hover {
  color: inherit;
  text-decoration:none;
}
div.dataTables_info[aria-live="polite"] {
    position: inherit;
}

/*  .page > .page-content.container-fluid{
    background: linear-gradient(45deg,#3a6e96,#9df980);
  }*/

  div.sa-button-container {
      display: inline-block;
  }

  /* =============  custom blue theme ============= */
  .site-menu-item a {
      color: rgb(0 0 0 / 90%);
  }
  /* .site-menu > .site-menu-item.active {
    background: rgb(0 164 255 / 8%);
    background: #007bff4d;
    border-top: 1px solid rgba(0, 0, 0, .04);
    border-bottom: 1px solid rgba(0, 0, 0, .04);
  } */

    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    color: #000000;
    }
    .table {
        color: #000000;
    }
    .table thead th, .table tfoot th {
        color: #000000;
    }
    .page {
        background: #e2f1f6b0;
        /* background: #dfeef3; */
    }
  /* ============= end custom blue theme ============= */

</style>
  </head>
  <body class="animsition dashboard">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    @include('layouts.inc.header')

    <!-- Page -->
    <div class="page" style="color: black !important">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      {{-- <div class="page-content container-fluid"> --}}
        @yield('content')
      {{-- </div> --}}
    </div>
    <!-- End Page -->


    <!-- Footer -->
    <footer class="site-footer">
      <div class="site-footer-legal"><a href="{{ url('/') }}">Akuntansi</a></div>
      <div class="site-footer-right">
        {{--  Crafted with <i class="red-600 icon md-favorite"></i> by <a href="https://mbi.biz.id">Medika Buwana Informatika</a>  --}}
        <span style="color: #3a6e96;">Version 1.0.0</span> &nbsp&nbsp Hak Cipta <a href="https://hl.morbis.id">Akuntansi</a> © {{date('Y')}}
      </div>
    </footer>
    <!-- Core  -->
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/jquery/jquery.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/popper-js/umd/popper.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap/bootstrap.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/animsition/animsition.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/asscrollable/jquery-asScrollable.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/waves/waves.js"></script>
    {{-- <script src="{{ asset('admin_remark_base/') }}/assets/js/jquery.form.js"></script> --}}

    <!-- Plugins -->
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/switchery/switchery.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/intro-js/intro.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/screenfull/screenfull.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/slidepanel/jquery-slidePanel.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/formvalidation/formValidation.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/formvalidation/framework/bootstrap4.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/typeahead-js/bloodhound.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/typeahead-js/typeahead.jquery.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/typeahead-js/handlebars.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-sweetalert/sweetalert.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/toastr/toastr.js"></script>
    {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/jquery-placeholder/jquery.placeholder.js"></script> --}}
    <!-- datatable -->
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net/jquery.dataTables.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-bs4/dataTables.bootstrap4.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-fixedheader/dataTables.fixedHeader.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-rowgroup/dataTables.rowGroup.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-scroller/dataTables.scroller.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-responsive/dataTables.responsive.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons/dataTables.buttons.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons/buttons.html5.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons/buttons.flash.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons/buttons.print.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons/buttons.colVis.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/datatables.net-rowgroup/dataTables.rowsGroup.v2.js"></script>
      {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/asrange/jquery-asRange.min.js"></script> --}}
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/bootbox/bootbox.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>
      <script src="{{ asset('admin_remark_base/') }}/global/vendor/clockpicker/bootstrap-clockpicker.js"></script>

    <!-- Scripts -->
    <script src="{{ asset('admin_remark_base/') }}/global/js/Component.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Base.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Config.js"></script>

    <script src="{{ asset('admin_remark_base/') }}/assets/js/Section/Menubar.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/Section/GridMenu.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/Section/Sidebar.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/Section/PageAside.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/Plugin/menu.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/dropify/dropify.min.js"></script>

    <script src="{{ asset('admin_remark_base/') }}/global/js/config/colors.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/config/tour.js"></script>
    <script>Config.set('assets', '{{ asset('admin_remark_base/') }}/assets');</script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/bootstrap-touchspin/bootstrap-touchspin.min.js"></script>
    {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/chartist/chartist.min.js"></script> --}}
    {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.js"></script> --}}
    {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/jvectormap/jquery-jvectormap.min.js"></script> --}}
    {{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/jvectormap/maps/jquery-jvectormap-world-mill-en.js"></script> --}}
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/matchheight/jquery.matchHeight-min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/vendor/peity/jquery.peity.min.js"></script>
    <!-- Page -->
    <script src="{{ asset('admin_remark_base/') }}/assets/js/Site.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/bootstrap-touchspin.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/asscrollable.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/slidepanel.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/switchery.js"></script>

    <script src="{{ asset('admin_remark_base/') }}/global/vendor/select2/select2.full.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/select2.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/assets/js/jquery.imgareaselect.min.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/dropify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">


    {{-- <script src="//code.jquery.com/jquery-3.2.1.slim.min.js"></script> --}}
    <script src="{{ asset('admin_remark_base/') }}/simple.money.format.js"></script>

    <script src="{{ asset('custom/dialog.js')}}" type="text/javascript"></script>
    <script src="{{ asset('filepond-master/') }}/dist/filepond.js"></script>
    {{-- <script src="{{ asset('admin_remark_base/') }}/assets/examples/js/dashboard/v1.js"></script> --}}

    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/js/fileinput.js"></script>

    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/matchheight.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/jvectormap.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/peity.js"></script>
    <script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/jquery.table2excel.min.js"></script>
    <script src="{{asset('js/')}}/moment.js"></script>
    {{-- <script src="{{asset('js/')}}/echo.js"></script> --}}
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>


    {{-- pdf datatable --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js" integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        @if($errors->any())
            @foreach($errors->all() as $error)
                notification("{!! $error !!}","error");
            @endforeach
        @endif
        @if(Session::get('messageType'))
            notification("{!! Session::get('message') !!}","{!! Session::get('messageType') !!}");
            @php
                Session::forget('messageType');
                Session::forget('message');
            @endphp
        @endif
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        (function(document, window, $){
            'use strict';

            var Site = window.Site;
            $(document).ready(function(){
            Site.run();
            });
        })(document, window, jQuery);

        // $( document ).ready(function() {
        //   // modals untuk menampilkan halaman form
        //   $('.data-modal').click(function(){
        //     $.ajax({
        //         url: $(this).attr('value'),
        //         dataType: 'text',
        //         success: function(data) {
        //           $("#formModal").html(data);
        //           $("#formModal").modal('show');
        //           // todo:  add the html to the dom...
        //         }
        //       });
        //     });
        // });

        function hapus(url) { // clear error string
            var token = $("meta[name='csrf-token']").attr("content");
            swal({
                title: "Hapus",
                text: "Apakah anda yakin akan menghapus data ?",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya",
                showCancelButton: true,
                cancelButtonText: "Tidak",
                closeOnConfirm: false,
            },
            function(){
                $.ajax({
                    url : url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success:function(response){
                        if (response.error) {
                            swal(response.error.message, ' ', 'error');
                        } else {
                            swal({
                                title : 'Data Berhasil Dihapus',
                                type : 'success'
                            },
                            function () {
                                location.reload();
                            });
                        }
                    },
                    error:function(){
                    swal('Data gagal dihapus karena terkait data lain.', ' ', 'error');
                    },
                });
            });
        }

        function replaceToCurrency(value) {
            // return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return value.replace(/(?!\,)\D/g, "")       // hapus selain koma dan number
            // .replace(/(?<=\,*)\./g, "")                 // hapus titik sebelum koma
            .replace(/(?<=\,\d\d).*/g, "")              // hapus stelah 2 digit dibelakang koma
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");     // beri titik stiap 3 digit sebelum koma
        }

        $(document).ready(function(){
            var element = $('ul.site-menu-sub li.site-menu-item a');
            var element1=$('li.site-menu-item a.waves-effect');
            // console.log(element1);
            $.each(element,function(key,val){
                // console.log(val.children);
                if(val.children[0].innerHTML.length>20)
                {
                    $( this).find('span').css( "font-size", "9pt" );
                }
            });

            $.each(element1,function(key,val){
                // console.log($(this).find('span.site-menu-title').html().length);
                if($(this).find('span.site-menu-title').html().length>20)
                {
                    // console.log(val.innerHTML);
                    $( this).find('span.site-menu-title').css( "font-size", "9pt" );
                }
            });

        });
    </script>

    @yield('js')
    @stack('js')

  </body>
</html>
