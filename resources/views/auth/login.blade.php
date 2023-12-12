<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akuntansi - Sistem Informasi Akuntansi</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_remark_base/') }}/login/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_remark_base/') }}/login/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_remark_base/') }}/login/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_remark_base/') }}/login/css/iofrm-theme2.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/toastr/toastr.css">
    <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/assets/examples/css/advanced/toastr.css">
</head>
<body>
    <div class="form-body">
        <div class="website-logo">
            <a href="{{url('/')}}">
                <div class="logo">
                <?php $setting = App\Models\SettingAplikasi::select('*')->first(); ?>
                    <img src="logo/{{ isset($setting) ? $setting->logo : '' }}" class="img-fluid" alt="">

                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">

                        <h3>{{ isset($setting) ? $setting->nama : ' Sistem Akuntansi' }}</h3>
                        <p>Solusi Mudah dan Cepat untuk segala Urusan Akuntansi Anda.</p>
                        <div class="page-links">
                        </div>
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <input class="form-control" type="text" name="username" placeholder="superadministrator" required>
                            <input class="form-control" type="password" name="password" placeholder="password sandinya(password)" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Login</button>
                            </div>
                        </form>
                        <div class="other-links">
                            <center> <span> Created with <i class="fa fa-heart pulse"></i> by <a href="http://www.morbis.id">Morbis</a>
                                 © {{date('Y')}}. All Right Reserved.</span> </center>
                              <center><span>Versi Aplikasi :  {{ isset($setting) ? $setting->version : '1.0.0' }}<span></center>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('admin_remark_base/') }}/login/js/jquery.min.js"></script>
<script src="{{ asset('admin_remark_base/') }}/login/js/popper.min.js"></script>
<script src="{{ asset('admin_remark_base/') }}/login/js/bootstrap.min.js"></script>
<script src="{{ asset('admin_remark_base/') }}/login/js/main.js"></script>
<script src="{{ asset('admin_remark_base/') }}/assets/js/Site.js"></script>
<script src="{{ Asset('custom/dialog.js')}}" type="text/javascript"></script>
<script src="{{ asset('admin_remark_base/') }}/global/vendor/toastr/toastr.js"></script>

<script type="text/javascript">

@if ($errors->any())
        @foreach ($errors->all() as $error)
          notification("{!! $error !!}","error");
        @endforeach
      @endif
      @if(Session::get('messageType'))
        notification("{!! Session::get('message') !!}","{!! Session::get('messageType') !!}");
        <?php
        Session::forget('messageType');
        Session::forget('message');
        ?>
      @endif

</script>
</html>
