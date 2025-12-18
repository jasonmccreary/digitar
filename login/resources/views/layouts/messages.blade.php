@if(count(Alert::all()) > 0)
	<script type="text/javascript">
		$(document).ready(function() {

			Messenger.options = {
			    extraClasses: 'messenger-fixed messenger-on-top',
			    theme: 'flat'
			}
			
			setTimeout(function() {
				@foreach(Alert::get('error') as $alert)

					Messenger().post({
					 	message: '{!! $alert !!}',
					 	type: 'error',
					    showCloseButton: true
					});

				@endforeach

				@foreach(Alert::get('success') as $alert)

					Messenger().post({
					 	message: '{!! $alert !!}',
					 	type: 'success',
					    showCloseButton: true
					});

				@endforeach

				@foreach(Alert::get('warning') as $alert)

					Messenger().post({
					 	message: '{!! $alert !!}',
					 	type: 'info',
					    showCloseButton: true
					});

				@endforeach

				@foreach(Alert::get('info') as $alert)

					Messenger().post({
					 	message: '{!! $alert !!}',
					 	type: 'info',
					    showCloseButton: true
					});

				@endforeach
			},500);
		});
	</script>
@endif
