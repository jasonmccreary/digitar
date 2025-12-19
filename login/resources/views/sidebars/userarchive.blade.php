    <ul style="margin:20px 0 20px 0;">
      @if(User::checkBilling())
      <li class="">
        <a href="/billing">
          <i class="fa fa-list-alt"></i>
          <span class="title">Facturatie</span>
        </a>
      </li>
      <li class="devider"></li>
      @endif

      <!-- BEGIN DEFAULT FOLDERS -->
      @if(Auth::user()->onverwerkt == 1)
          <li class="start">
            <a href="/user/folder/inbox">
              <i class="fa fa-inbox" style="font-size:18px;"></i>
              <span class="title">Onverwerkt</span>
              @if(Files::getNumUnsorted(Auth::user()->cid) > 0)
                <span class="selected"></span>
                <span class="badge badge-important pull-right">{!! App\Models\Files::getNumUnsorted(Auth::user()->cid) !!}</span>
              @endif
            </a>
          </li>
          <li class="">
            <a href="/user/files">
              <i class="fa fa-file-text"></i>
              <span class="title">Bestanden</span>
            </a>
          </li>
      @endif
      <!-- END DEFAULT FOLDERS -->

      <!-- BEGIN FOLDER LISTING -->
      <li style="height:15px;"></li>
      @foreach($aFolders as $folder)
        <?php $subfolders = App\Models\Folder::getSubfolders($folder->id); ?>
        <li>
          <a href="/user/folder/{!! $folder->id !!}">
            <i><div class="folder-circle" style="border-color:{!! $folder->color !!};"></div></i>
            <span class="title">{!! $folder->name !!}</span>
            @if(count($subfolders) > 0)<span class="arrow"></span>@endif
          </a>
          @if(count($subfolders) > 0)
            <ul class="sub-menu">
              @foreach($subfolders as $sf)
                <li><a href="/user/folder/{!! $sf->id !!}">
                    <div class="folder-circle" style="border-color:{!! $sf->color !!};"></div>
                    {!! $sf->name !!}
                </a></li>
              @endforeach
            </ul>
          @endif
        </li>
      @endforeach
    </ul>
