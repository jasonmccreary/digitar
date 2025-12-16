<div class="side-widget-title">Nieuwe Berichten</div>
<div class="side-widget-content" id="newMessages">

  @if(count(Messages::get(Auth::user()->cid)) > 0)
    @foreach(Messages::get(Auth::user()->cid) as $message)
      <?php
        $d = new DateTime($message->created_at);
        $dateCreated = $d->format("j F Y H:i");
      ?>
      <div class="user-details-wrapper" data-id="{{ $message->id }}">
        <div class="remove">
          <i class="fa fa-trash-o"></i>
        </div>
        <div class="user-details">
          <div class="user-name">
            {{ $message->title }}
            <span class="pull-right" style="margin-right:20px;"><i class="fa fa-clock-o tip" title="{{ $dateCreated }}" data-placement="left"></i></span>
          </div>
          <div class="user-more">
            {{ $message->message }}
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    @endforeach
  @else
    <p style="padding:5px 0px 5px 20px;">Geen nieuwe berichten</p>
  @endif

</div>
<div class="side-widget-title">Gelezen Berichten</div>
<div class="side-widget-content" id="newMessages">
  @if(count(Messages::get(Auth::user()->cid,true)) > 0)
    @foreach(Messages::get(Auth::user()->cid,true) as $message)
      <?php
        $d = new DateTime($message->created_at);
        $dateCreated = $d->format("j F Y H:i");
      ?>
      <div class="user-details-wrapper" data-id="{{ $message->id }}">
        <div class="remove">
          <i class="fa fa-trash-o"></i>
        </div>
        <div class="user-details">
          <div class="user-name">
            {{ $message->title }}
            <span class="pull-right" style="margin-right:20px;"><i class="fa fa-clock-o tip" title="{{ $dateCreated }}" data-placement="left"></i></span>
          </div>
          <div class="user-more">
            {{ $message->message }}
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    @endforeach
  @else
    <p style="padding:5px 0px 5px 20px;">Geen gelezen berichten</p>
  @endif

</div>