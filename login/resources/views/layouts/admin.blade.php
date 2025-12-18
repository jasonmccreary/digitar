<?php

$aYears = Files::getYears();

?>
<!-- BEGIN MOBILE SEARCH BAR -->
<div class="mobile-search">
  {{-- <form method="POST" action="/user/search"> --}}
    <input name="search" value="{!! getSearchVal() !!}" type="text" class="no-boarder search" autocomplete="off" placeholder="Doorzoek archief">
  {{-- </form> --}}
</div>
<!-- END MOBILE SEARCH BAR -->

<div class="header navbar navbar-inverse">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <!-- BEGIN NAVIGATION HEADER -->
    <div class="header-seperation">
      <!-- BEGIN MOBILE HEADER -->
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none;">
        <li class="dropdown">
          <a id="main-menu-toggle" href="#main-menu" class="">
            <div class="iconset top-menu-toggle-white"></div>
          </a>
        </li>
      </ul>
      @if (Auth::user()->rights == 1 && Auth::user()->lookonly != 1)
      <ul class="nav quick-section show-mobile" style="margin-left: 0;margin-right:0;display:none;">
        <li class="quicklinks">
          <a href="/user/upload" class="tip" title="Uploaden" data-placement="bottom">
            <i class="fa fa-cloud-upload" style="color:#ffffff !important;font-size:22px;margin-top:0;"></i>
          </a>
        </li>
        <li class="quicklinks"> <span class="h-seperate"></span></li>
        <li class="quicklinks">
          <a href="javascript:;" class="search">
            <i class="fa fa-search" style="color:#ffffff !important;font-size:20px;margin-top:0;"></i>
          </a>
        </li>
      </ul>
      @endif
      <div class="pull-right" id="main-menu-toggle-wrapper2" style="display:none">
        <ul class="nav quick-section">
          {{-- start years dropdown --}}
          <li class="quicklinks">
            <a data-toggle="dropdown" class="dropdown-toggle pull-right" href="#" id="user-years" style="background:transparent;padding:2px 0 0 0 !important;">
              <span style="color:#fff;font-size:15px;float:left;">{!! Session::get('year') !!}</span>
              <div class="iconset top-down-arrow" style="display:inline-block;float:left;margin:6px 5px 2px 10px;"></div>
            </a>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-years">
              @if(count($aYears) > 0)
                @foreach($aYears as $f)
                  <li><a href="/user/year/{!! $f->year !!}" style="font-size:15px;text-align:center;">{!! $f->year !!}</a></li>
                  <li class="divider"></li>
                @endforeach
              @else
                <li><a href="/user/year/{!! Session::get('year') !!}" style="font-size:15px;text-align:center;">{!! Session::get('year') !!}</a></li>
              @endif
            </ul>
          </li>
          {{-- end years dropdown --}}
          <li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="quicklinks">
            <a href="/logout" style="background:transparent;padding:1px 0 0 5px !important;">
              <i class="fa fa-power-off" style="color:#ffffff !important;"></i>
            </a>
          </li>
        </ul>
      </div>
      <!-- END MOBILE HEADER -->
      <div class="organisation-title hide-phone" style="font-size:24px;color:#fff;text-align:center;line-height:60px;height:60px;overflow: hidden;">{!! User::getOrganizationName() !!}</div>
    </div>
    <!-- END NAVIGATION HEADER -->
    <!-- BEGIN CONTENT HEADER -->
    <div class="header-quick-nav">
      <div class="pull-left">

        @if (Auth::user()->rights == 1 && Auth::user()->lookonly != 1)
        <ul class="nav quick-section">
          <li class="quicklinks">
            <a href="/user/upload" class="tip" title="Uploaden" data-placement="bottom">
              <i class="fa fa-cloud-upload" style="color:#4C5264 !important;font-size:20px;margin-top:0;"></i>
            </a>
          </li>
          <li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="m-r-10 input-prepend inside search-form no-boarder">
            <span class="add-on"><span class="iconset top-search"></span></span>
            @if (Request::is('billing*'))
              <form method="POST" action="/billing/search" style="float:left;">
                <input name="billing-search" value="{{ Request::get('search') }}" type="text" class="no-boarder" autocomplete="off" placeholder="Doorzoek facturatie" style="width:350px;">
              </form>
            @else
              {{-- <form method="POST" action="/user/search" style="float:left;"> --}}
                <input name="search" value="{!! getSearchVal() !!}" type="text" class="no-boarder" autocomplete="off" placeholder="Doorzoek archief" style="width:350px;">
              {{-- </form> --}}
            @endif
          </li>
        </ul>
        @endif

      </div>

      <div class="pull-right">
        <ul class="nav quick-section">
          @if (Auth::user()->rights == 1)
          {{-- start years dropdown --}}
          <li class="quicklinks">
            <a data-toggle="dropdown" class="dropdown-toggle pull-right" href="#" id="user-years" style="padding:2px 0 0 0 !important;">
              <span class="bold" style="color:#1b1e24;font-size:15px;float:left;">{!! Session::get('year') !!}</span>
              <div class="iconset top-down-arrow" style="display:inline-block;float:left;margin:6px 10px;"></div>
            </a>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-years">
              @if(count($aYears) > 0)
                @foreach($aYears as $f)
                  <li><a href="/user/year/{!! $f->year !!}" style="font-size:15px;text-align:center;">{!! $f->year !!}</a></li>
                  <li class="divider"></li>
                @endforeach
              @else
                <li><a href="/user/year/{!! Session::get('year') !!}" style="font-size:15px;text-align:center;">{!! Session::get('year') !!}</a></li>
              @endif
            </ul>
          </li>
          {{-- end years dropdown --}}
          {{-- <li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="quicklinks">
            <a href="#" style="padding:1px 0 0 10px !important;" class="tip" title="Instellingen" data-placement="bottom">
              <i class="fa fa-gear" style="margin-right:0;"></i>
            </a>
          </li> --}}
          {{-- <li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="quicklinks">
            <a href="#" style="padding:1px 10px 0 10px !important;position:relative;" class="chat-menu-toggle tip" title="Berichten" data-placement="bottom">
              <i class="fa fa-envelope" style="margin-right:0;"></i>

            </a>
          </li> --}}
          <li class="quicklinks"> <span class="h-seperate"></span></li>
          @endif
          <li class="quicklinks">
            <a href="/logout" style="padding:1px 0 0 10px !important;" class="tip" title="Uitloggen" data-placement="bottom">
              <i class="fa fa-power-off" style="margin-right:0;"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- END CONTENT HEADER -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<div class="page-container row-fluid">
  <!-- BEGIN SIDEBAR -->
  <!-- BEGIN MENU -->
  <div class="page-sidebar" id="main-menu">
    <div class="page-sidebar-wrapper scrollbar-hidden" id="main-menu-wrapper">
      @if(User::getOrganizationName() != User::getClientName())
      <div class="user-info-wrapper">
        <div class="user-info">
          <div class="greeting" style="font-weight:600;">{!! User::getClientName() !!}</div>
          @if(User::getClientName() != User::getUserName(Auth::user()->id))
          <div class="username" style="font-weight:200;">{!! User::getUserName(Auth::user()->id) !!}</div>
          @endif
        </div>
      </div>
      @endif

      @if (Auth::user()->rights == 5)
          @include('sidebars.administrator')
      @elseif (Auth::user()->rights == 4)
          @include('sidebars.organization')
      @elseif (Auth::user()->rights == 3)
          @include('sidebars.moderator')
      @elseif (Auth::user()->rights == 2)
          @include('sidebars.client')
      @elseif (Auth::user()->rights == 1)
          @if (Request::is('*billing*'))
            @include('sidebars.userbilling')
          @else
            @include('sidebars.userarchive')
          @endif
      @endif
    </div>
  </div>

  <a href="#" class="scrollup">Scroll</a>

  @if (Auth::user()->rights == 1)
  <?php
    $size = ToolsController::getUserDirSize(Auth::user()->id);
    $maxSize = 1024000;
    $percentage = round(($size/$maxSize)*100);
    $size = ceil($size / 1024);

  ?>
  <div class="footer-widget">
    <div class="progress transparent progress-small no-radius no-margin">
      <div data-percentage="{!! $percentage !!}%" class="progress-bar progress-bar-success animate-progress-bar"></div>
    </div>
    <div class="pull-right">
      <div class="details-status tip" title="{!! $size !!} mb data in gebruik" data-placement="left">
        <span data-animation-duration="560" data-value="{!! $percentage !!}" class="animate-number"></span>%
      </div>
      <a href="/help" class="circle tip" title="Veel gestelde vragen &amp; uitleg" data-placement="left">
        <i class="fa fa-question"></i>
      </a>
    </div>
  </div>
  @endif

  <div class="page-content">
    <div class="clearfix"></div>
    @if(!isset($noGrid))
    <div class="content">
      @if(isset($title) && strlen($title) > 0)
      <div class="page-title">
        <h3>{!! $title ?? 'home' !!}</h3>
      </div>
      @endif

      <div class="row-fluid">
        <div class="span12">
          <div class="grid simple ">
            <div class="grid-body ">@yield('content')</div>
          </div>
        </div>
      </div>
    </div>
    @else
      @yield('content')
    @endif
  </div>
</div>


