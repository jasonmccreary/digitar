@extends('master')

@section('content')

    @if(isset($deleted) && $deleted > 0)
    <div class="alert alert-error">
        <button class="close" data-dismiss="alert"></button>
        <span class="semi-bold">{!! $deleted !!}</span> bestanden zijn verwijderd omdat de eigenaar niet gevonden kon worden.
    </div>
    @endif

    @if(isset($exec) && $exec > 0)
    <div class="alert alert-success">
        <button class="close" data-dismiss="alert"></button>
        <span class="semi-bold">{!! $exec !!}</span> bestanden zijn verwerkt.
    </div>
    @endif

	@if($amount > 0)
        
        <p>
            Er zijn <span class="semi-bold">{!! $amount !!}</span> bestanden gevonden waar de gegevens van kunnen worden opgeslagen.
        </p>

        <form method="post">
            <button name="getcontents" value="1" class="btn btn-white btn-xs btn-mini" title="Inhoud ophalen en opslaan">Inhoud ophalen en opslaan</button>
        </form>

	@else

		<h1>Alle bestanden zijn up-to-date!</h1>

	@endif

@stop