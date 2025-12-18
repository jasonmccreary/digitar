

	<div class="row">

		<div class="col-md-12 editFileSection">
			<div class="grid simple">
				<div class="grid-title no-border">
					<h3>Eigenschappen</h3>
				</div>
				<div class="grid-body no-border">
					@if(Auth::user()->lookonly == 0)
					<form id="form_traditional_validation" action="/user/file/editdetails/{!! $file['id'] !!}" method="post">
					@endif

					<div class="form-group">
						<label class="form-label">Naam</label>
						<div class="input-with-icon right">
							<i class=""></i>
							<input type="text" name="name" id="form1Amount" value="{!! $file['name'] !!}" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="form-label">Datum</label>
						<br/>
						<div class="input-append success date no-padding" style="width:100%;">
		                    <input type="text" name="date" value="{!! simple_date($file['date']) !!}" class="form-control">
		                	<span class="add-on" style="margin-left:-36px;"><span class="arrow"></span><i class="fa fa-th"></i></span>
		                </div>
					</div>

					<div class="form-group" id="geboektcheck" @if(Folder::where('id', '=', $file['fid'])->where('bookedcheck', '=', '1')->count() == 0) style="display:none;" @endif>
						<p><div class="checkbox check-info">
                      		<input id="checkbox4" type="checkbox" name="geboekt" value="1" @if($file['geboekt'] == '1') checked="checked" @endif >
                      		<label for="checkbox4" style="padding-left: 25px;">Geboekt </label>
                    	</div></p>
					</div>

					@if(trim($file['note']) == '' && Auth::user()->lookonly == 0)
					<p><small>
						<a href="#note" class="addNote">Notitie toevoegen</a>
					</small></p>
					<div class="form-group noteArea" style="display:none;">
					@else
					<div class="form-group noteArea">
					@endif
						<label class="form-label">Notitie</label>
						<div class="input-with-icon right">
							<textarea name="note" rows="6" class="form-control">{!! $file['note'] !!}</textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="pull-left">
						@if(Auth::user()->lookonly == 0)
						  <button type="submit" class="btn btn-success btn-cons"><i class="icon-ok"></i> Opslaan</button>
						@endif
						  <button type="button" class="btn btn-white btn-cons" data-dismiss="modal">Sluiten</button>
						</div>
					</div>
					@if(Auth::user()->lookonly == 0)
					</form>
					@endif
				</div>
			</div>
		</div>

		<script type="text/javascript">

			@if(Auth::user()->lookonly == 0)
				$("select").select2();

				$('.date').datepicker({
			  		format: "dd-mm-yyyy",
						autoclose: true,
						todayHighlight: true
			   	});
		   	@endif

			$('.addNote').click(function(e) {

				e.preventDefault();
				$('.noteArea').slideDown('fast');
				$(this).parent().parent().slideUp('fast');
				//console.log($('.noteArea').html());
				return false;
			});

		   	$('select').on('change',function() {
		   		$.get('/user/geboektchecker/'+$(this).val(), function( data ) {
		   			if (data == true) {
		   				$('#checkbox4').attr('value', '1');
		   				$('#geboektcheck').slideDown('fast');
		   			}else{
		   				$('#checkbox4').removeAttr('value');
		   				$('#geboektcheck').slideUp('fast');
		   			}
		   		});
		   	});

		   	@if(Auth::user()->lookonly == 1)
		   		$('.editFileSection input, .editFileSection select, .editFileSection textarea').each(function() {
		   			$(this).attr("disabled","disabled");
		   		});
		   	@endif

		   	//setTimeout(function() {
		   		$('iframe').css('height',($(window).height()-70)+'px');
		   	//},1000);
		</script>

	</div>
