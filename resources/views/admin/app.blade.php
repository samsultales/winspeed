<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Voice Recorder</title>
  @routes
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/AdminLTE-3.2.0/dist/img/logo-circle.png') }}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/dropzone/min/dropzone.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css') }}">

  <!-- Notification -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/toastr/toastr.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome-animation@1.1.1/css/font-awesome-animation.min.css">

  <link href="{{ asset('assets/fancytree/src/skin-win8/ui.fancytree.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/fancytree/lib/prettify.css') }}" rel="stylesheet">

  <script src="https://unpkg.com/audiomotion-analyzer/dist"></script>

  @vite(['resources/js/app.js', 'resources/css/app.css'])

  <script>
    var base_url = '{{ url("/") }}';
    var csrf_token = '{{ csrf_token() }}';
  </script>

</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    @include('admin/header')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin/sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 955.4px;">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">{{$form['title']}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <!-- <li class="breadcrumb-item"><a href="#">{{$form['sub_title']}}</a></li> -->
                <li class="breadcrumb-item active">{{$form['sub_title']}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          @yield('content')
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <div style="clear:both"></div>
    <!-- Main Footer -->
    @include('admin/footer')
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/dist/js/adminlte.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/dropzone/min/dropzone.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/toastr/toastr.min.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

  <!-- include BlockUI -->
  <script src="{{ asset('assets/jquery.blockUI.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


  <!-- <script src="{{ asset('assets/fancytree/lib/jquery.js') }}"></script> -->
  <script src="{{ asset('assets/fancytree/src/jquery-ui-dependencies/jquery.fancytree.ui-deps.js') }}"></script>
  <script src="{{ asset('assets/fancytree/src/jquery.fancytree.js') }}"></script>
  <script src="{{ asset('assets/fancytree/lib/prettify.js') }}"></script>

  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

  <script src="{{ asset('assets/visualizer.js') }}"></script>

  <style>
    .content-wrapper {
      min-height: 1026.4px;
    }

    li>ul {
      margin-left: 5px;
    }
  </style>
  <script>
    var session = `{{ Session::get('auth')['key_id_unit_kerja']}}`;
    const incl = ["1", "0"];
    // Event Notifikasi 
    window.onload = function() {
      if (incl.includes(session)) {
        console.log('Echoing....')
        window.Echo.channel('broken-notifikasi').listen("AlatRusakNotifikasi", function(data) {
          // toastr.info(data.message.message);
          // console.log(data.message.message);
          toastr.warning(data.message.message);
          if (data.message.message2 !== '') {
            toastr.error(data.message.message2);
          }
         
          $('.vcount').text(data.message.count);
          $('.vcount2').text(data.message.count + ' Notifikasi');
          $('#target').empty();
          $.each(data.message.data, function(k, v) {
            /// do stuff
            $('#target').append('<a href="'+v.interface+'" class="dropdown-item"> <p class="lh-sm"> <i class="fas fa-envelope mr-2"></i>' + v.pesan + '</p></a><div class="dropdown-divider"></div>');
          });
        })
      }
    }

    // Proses Datatable ---
    $(document).ready(function() {
      // console.log("read Me");
      $.get("/getnotif",
        function(data, status) {
          $('.vcount').text(data.count);
          $('.vcount2').text(data.count + ' Notifikasi');
          $('#target').empty();
          $.each(data.data, function(k, v) {
            /// do stuff
            $('#target').append('<a href="'+v.interface+'" class="dropdown-item"> <p class="lh-sm"> <i class="fas fa-envelope mr-2"></i>' + v.pesan + '</p></a><div class="dropdown-divider"></div>');
          });
        });
    });

    // override these in your code to change the default behavior and style 
    $.blockUI.defaults = {
      message: '<h5><i class="fas fa-microphone faa-flash animated faa-slow"></i> &nbsp;&nbsp;Please Wait..</h5>',
      title: null, // title string; only used when theme == true 
      draggable: true, // only used when theme == true (requires jquery-ui.js to be loaded) 
      theme: false, // set to true to use with jQuery UI themes 
      css: {
        padding: 0,
        margin: 0,
        width: '30%',
        top: '40%',
        left: '45%',
        textAlign: 'center',
        opacity: .5,
        color: '#fff',
        border: '3px solid #ccc',
        border: 'none',
        padding: '15px',
        backgroundColor: '#000',
        cursor: 'wait'
      },
      themedCSS: {
        width: '30%',
        top: '40%',
        left: '35%'
      },
      overlayCSS: {
        backgroundColor: '#000',
        opacity: 0.6,
        cursor: 'wait'
      },
      cursorReset: 'default',
      growlCSS: {
        width: '350px',
        top: '10px',
        left: '',
        right: '10px',
        border: 'none',
        padding: '5px',
        opacity: 0.6,
        cursor: null,
        color: '#fff',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px'
      },
      iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',
      forceIframe: false,
      baseZ: 1000,
      centerX: true, // <-- only effects element blocking (page block controlled via css above) 
      centerY: true,
      allowBodyStretch: true,
      bindEvents: true,
      constrainTabKey: true,
      fadeIn: 200,
      fadeOut: 400,
      timeout: 0,
      showOverlay: true,
      focusInput: true,
      onBlock: null,
      onUnblock: null,
      quirksmodeOffsetHack: 4,
      blockMsgClass: 'blockMsg',
      ignoreIfBlocked: false
    };

    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function() {
      return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
      }).parentsUntil(".nav-sidebar > .nav-treeview")
      .css({
        'display': 'block'
      })
      .addClass('menu-open').prev('a')
      .addClass('active');
  </script>

  @yield('script')
</body>

</html>