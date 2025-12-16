<ul style="margin: 40px auto;">
  <li> <a href="javascript:;"> <span class="title">Klanten</span> <span class="arrow "></span> </a>
    <ul class="sub-menu">
      <li > <a href="/organization/clients">Overzicht </a> </li>
      <li > <a href="/organization/client/add">Toevoegen</a> </li>
    </ul>
  </li>
  <?php
  $org = new Organizations();
  if ($org->where('uid', '=', Auth::user()->id)->where('id', '=', Auth::user()->oid)->count()) {
    echo '
    <li> <a href="javascript:;"> <span class="title">Organisaties</span> <span class="arrow "></span> </a>
      <ul class="sub-menu">
        <li > <a href="/organization/linkedorganizations">Overzicht </a> </li>
      </ul>
    </li>
    ';
  }
  ?>
  <li> <a href="javascript:;"> <span class="title">Beheerders</span> <span class="arrow "></span> </a>
    <ul class="sub-menu">
      <li > <a href="/organization/moderators">Overzicht </a> </li>
      <li > <a href="/organization/moderator/add">Toevoegen</a> </li>
      <li > <a href="/organization/moderator/link">Koppelen</a> </li>
    </ul>
  </li>
  <li> <a href="javascript:;"> <span class="title">Standaard mappen</span> <span class="arrow "></span> </a>
    <ul class="sub-menu">
      <li > <a href="/organization/folders">Overzicht </a> </li>
      <li > <a href="/organization/folder/add">Toevoegen</a> </li>
    </ul>
  </li>
</ul>