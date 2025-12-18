<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta charset="utf-8" />
  <title>Digitar - archief</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <link href="/assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
  <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css">
  <link href="/assets/plugins/boostrapv3/css/bootstrap.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/dropzone/css/dropzone2.css?23042384723946208123" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/dropzone/css/dropzone.css?23042384723946208123" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/plugins/jquery-notifications/css/messenger-theme-future.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/assets/css/style.css?23042384723946208123" rel="stylesheet" type="text/css"/>
  <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="/assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>

  @if (Request::is('billing*'))
    <link rel="stylesheet" href="/assets/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="/assets/codemirror/theme/neo.css">
    <script src="/assets/codemirror/lib/codemirror.js"></script>
    <script src="/assets/codemirror/mode/javascript/javascript.js"></script>
    <script src="/assets/codemirror/addon/selection/active-line.js"></script>
    <script src="/assets/codemirror/addon/edit/matchbrackets.js"></script>
  @endif
  @if (isset($trueblank))
    <style type="text/css">
      body { background: #fff; padding: 10px !important; }
    </style>
  @endif
</head>
<curURL data="{{ $_SERVER['REQUEST_URI'] }}"></curURL>
@if (Auth::user())
  <curUser data-cid="{{ Auth::user()->cid }}"></curUser>
@endif
<body class="">
    <div class="dropzone-previews preview-container dz-preview uploaded-files"><div class="flex">
      <h1>Drop files to upload</h1>

      <div id="previews" class="dropzone2">
        <div id="onyx-dropzone-template">
          <div class="onyx-dropzone-info">
            <div class="thumb-container">
              <img data-dz-thumbnail />
            </div>
            <div class="details"><div>
              <span data-dz-name></span> <span data-dz-size></span>
            </div>
              <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
              <div class="dz-error-message"><span data-dz-errormessage></span></div>
            </div>
          </div>
        </div>
      </div>

    </div></div>

    @if (! Auth::user() || isset($errorpage) || isset($superlogin))
      @include('layouts.login')
    @elseif (isset($trueblank))
      @yield('content')
    @else
      @include('layouts.admin')
    @endif


    <script src="/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

    <script src="/assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/breakpoints.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <!-- END CORE JS FRAMEWORK -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <script src="/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-autonumeric/autoNumeric.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-autonumeric/autoNumeric.js" type="text/javascript"></script>

    {{-- <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script> --}}
    <script src="/assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

    <script src="/assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.js" type="text/javascript"></script>
    {{-- <script src="//onyxdev.net/files/battlefield/javascript/dropzonejs-example-with-translations-custom-preview-and-upload-delete-file-with-php/assets/js/dropzone.min.js" type="text/javascript"></script> --}}
    <script src="/assets/plugins/dropzone/dropzone.min.js" type="text/javascript"></script>

    <script src="/assets/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

    {{-- <script src="/assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script> --}}
    {{-- <script src="/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script> --}}
    <script src="/assets/js/hotkeys.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="/assets/js/datatables.js?2340" type="text/javascript"></script>
    <script src="/assets/js/core.js" type="text/javascript"></script>
    <script src="/assets/js/chat.js" type="text/javascript"></script>
    <script src="/assets/js/app.js?65532" type="text/javascript"></script>

    @if (Request::is('billing*'))
    <script src="/assets/js/billing.js" type="text/javascript"></script>
    @endif

    {{-- @if (Auth::user() && Auth::user()->rights == 1)
      <script src="https://socket.digitar.nu:8080/socket.io/socket.io.js"></script>
      <script src="https://socket.digitar.nu/user.js"></script>
    @endif --}}




    @include('layouts.messages')
  </body>
</html>