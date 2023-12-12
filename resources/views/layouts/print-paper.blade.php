<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0" />
    <meta http-equiv="Expires" content="Sat, 26 Jul 1997 05:00:00 GMT" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak</title>

    <!-- Bootstrap -->
    {{-- <link href="{{ asset('metronic/') }}/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{ asset('metronic/') }}/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> --}}
    @yield('css')
    @stack('css')
    <style>
        @page{
          size: 76mm 297mm portrait;
          margin:0;
        }

        body {
            margin: 0;
            padding: 0;
        }
        .page-break  {
            /*clear: left;*/
            display:block;
            page-break-after:always;
        }
        .print-bar {
            position: fixed;
            bottom: 0;
            z-index: 10001;
            left: 0;
            width: 100%;
            border: none;
            padding: 20px 0;
            cursor: pointer;
            /*background-color: #36C6D3;*/
            background-color: #3a6e96;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }
        .print-bar:hover {
            /*background-color: #27A4B0;*/
            background-color: white;
             color: #3a6e96;
            
        }
        @media  print{
            body {
              margin: 0;
            }
            .print-bar {
                display: none;
            }
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
    @yield('content')
    <button type="button" class="print-bar" id="btn-print" onclick="myFunction()">
      <i class="fa fa-print"></i> Klik Disini Untuk Mulai Mencetak
    </button>
    <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
    <script src="admin_remark_base/global/vendor/jquery/jquery.min.js" type="text/javascript"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {{-- <script src="{{ asset('metronic/') }}/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> --}}
    <script type="text/javascript">
      $(document).ready(function() {
        $('#btn-print').on('click',function(){
          // $('#btn-print').parent().hide();
          window.print();
          window.close();
          // $('#btn-print').parent().show();
        });
      });

      function myFunction() {
        window.print();
      }
    </script>
    @yield('js')
    @stack('js')
  </body>
</html>
