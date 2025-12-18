@extends('master')

@section('content')
		
	</div>
</div>
<style>
	h3 { margin-bottom: 15px; border-bottom: 1px solid rgba(0, 0, 0, .0); }
	.form-group { margin-bottom: 0; }
	.form-label { margin-top: 0;}
	.heading .form-label { padding-left: 10px; margin: 0; }
	.invoicerow-placeholder { height: 47px; }
	.form-control[readonly] { cursor: auto; background-color: #fff; }
</style>
{{ Form::open(array('class' => 'invoice')) }}

<div class="grid simple">
	<div class="grid-body">

		<div class="row">
			<div class="col-md-12 m-b-20">
				<div class="row form-row">
					<div class="col-md-4">
						<label class="form-label">Debiteur</label>
						
						<select id="selectDebtor" style="width:100%;" name="debtor">
							<option disabled selected="">- Maak een keuze -</option>
							@foreach(Debtors::where('cid','=',Auth::user()->cid)->get() as $debtor)
								<option value="{{ $debtor->id }}" @if(Input::old('debtor') == $debtor->id) selected @elseif($i->did == $debtor->id) selected @endif>{{ $debtor->debnumber }} {{ $debtor->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="row form-row">
					<div class="col-md-6">
						<div id="debtorInfo" class="m-l-20"></div>
					</div>
					<div class="col-md-6">
						<div class="row form-row">
							<div class="col-md-12 form-group">
								<label class="form-label">Factuurnummer</label>
								{{ Form::text('invoicenumber', (strlen(Input::old('invoicenumber')) > 0 ? Input::old('invoicenumber') : $i->invoicenumber), array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="row form-row">
							<div class="col-md-12 form-group">
								<label class="form-label">Factuur datum</label>
								<div class="input-append success date no-padding" style="width:100%;">
				                    <input type="text" name="date" value="{{ simple_date((strlen(Input::old('date')) > 0 ? Input::old('date') : $i->date)) }}" class="form-control">
				                	<span class="add-on" style="margin-left:-36px;"><span class="arrow"></span><i class="fa fa-th"></i></span>
				                </div>
				            </div>
			            </div>
			            <div class="row form-row">
							<div class="col-md-12 form-group">
								<label class="form-label">Referentie</label>
								{{ Form::text('reference', (strlen(Input::old('reference')) > 0 ? Input::old('reference') : $i->reference), array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="row form-row">
							<div class="col-md-12 form-group">
								<label class="form-label">Layout</label>
								{{ Form::select2('layout', Layouts::getInvoiceselect(), $i->layout, array('style' => 'width:100%;'),array(0)) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<div class="grid simple">
	<div class="grid-body m-t-30">

		<div class="row">
			<div class="col-md-12">
				<div class="row form-row heading">
					<div class="col-md-1 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Datum</label>
					</div>
					<div class="col-md-2 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Artikel</label>
					</div>
					<div class="col-md-1 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Aantal</label>
					</div>
					<div class="col-md-4 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Omschrijving</label>
					</div>
					<div class="col-md-1 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">BTW</label>
					</div>
					<div class="col-md-1 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Prijs excl. BTW</label>
					</div>
					<div class="col-md-1 form-group">
						<label class="form-label" style="line-height:37px;font-size:12px;">Totaal excl. BTW</label>
					</div>
				</div>
				<div id="invoicerow-copy" style="display:none;">
					<div class="row form-row">
						<input type="hidden" name="r-type" value="1" />
						<div class="col-md-1 form-group">
							<input type="text" name="r-date" class="form-control input-sm date" value="{{ simple_date(date('Y-m-d')) }}" />
						</div>
						<div class="col-md-2 form-group sm-select">
							{{ Form::select2('r-product', Products::getDropdown(), '0', array('class' => 'product-select leave', 'style' => 'width:100%;'),array(0)) }}
						</div>
						<div class="col-md-1 form-group">
							<input type="text" name="r-amount" class="form-control input-sm amount auto" data-v-min="-999.99" data-v-max="999.99" data-a-dec="." data-a-sep="," value="1" />
						</div>
						<div class="col-md-4 form-group">
							<input type="text" name="r-description" class="form-control input-sm description" value="" />
						</div>
						<div class="col-md-1 form-group">
							<input type="text" name="r-tax" class="form-control input-sm tax auto" data-v-min="0" data-v-max="99" value="21" />
						</div>
						<div class="col-md-1 form-group">
							<input type="text" name="r-price" class="form-control input-sm price auto" data-a-sep="." data-a-dec="," data-a-sign="€ " value="" />
						</div>
						<div class="col-md-1 form-group">
							<input type="text" class="form-control input-sm total auto" data-a-sep="." data-a-dec="," data-a-sign="€ " readonly="true" tabindex="-1" />
						</div>
						<div class="col-md-1 form-group" style="text-align:right;">
							<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini handle"><i class="fa fa-arrows-v"></i></a></label>
							<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini delete"><i class="fa fa-trash-o"></i></a></label>
						</div>
					</div>
				</div>
				<div id="textrow-copy" style="display:none;">
					<div class="row form-row">
						<input type="hidden" name="r-type" value="9" />
						<div class="col-md-11 form-group">
							<input type="text" name="r-description" class="form-control input-sm" value="" />
						</div>
						<div class="col-md-1 form-group" style="text-align:right;">
							<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini handle"><i class="fa fa-arrows-v"></i></a></label>
							<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini delete"><i class="fa fa-trash-o"></i></a></label>
						</div>
					</div>
				</div>
				<div id="invoicerows">

					@foreach(Invoicerows::where('iid','=',$i->id)->get() as $k => $ir)
						@if ($ir->type == 1)
							<div class="row form-row">
								<input type="hidden" name="r-type" value="1" />
								<div class="col-md-1 form-group">
									<input type="text" name="r-date" class="form-control input-sm date" value="{{ simple_date($ir->date) }}" />
								</div>
								<div class="col-md-2 form-group sm-select">
									{{ Form::select2('r-product', Products::getDropdown(), $ir->pid, array('class' => 'product-select', 'style' => 'width:100%;'),array(0)) }}
								</div>
								<div class="col-md-1 form-group">
									<input type="text" name="r-amount" class="form-control input-sm amount auto" data-v-min="-999.99" data-v-max="999.99" data-a-sep="," data-a-dec="." value="{{ $ir->amount }}" />
								</div>
								<div class="col-md-4 form-group">
									<input type="text" name="r-description" class="form-control input-sm description" value="{{ htmlspecialchars($ir->description) }}" />
								</div>
								<div class="col-md-1 form-group">
									<input type="text" name="r-tax" class="form-control input-sm tax auto" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," value="{{ $ir->tax }}" />
								</div>
								<div class="col-md-1 form-group">
									<input type="text" name="r-price" class="form-control input-sm price auto" data-a-sep="." data-a-dec="," data-a-sign="€ " value="{{ $ir->price }}" />
								</div>
								<div class="col-md-1 form-group">
									<input type="text" class="form-control input-sm total auto" data-a-sep="." data-a-dec="," data-a-sign="€ " readonly="true" value="{{ ($ir->amount * $ir->price) }}" tabindex="-1" />
								</div>
								<div class="col-md-1 form-group" style="text-align:right;">
									<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini handle"><i class="fa fa-arrows-v"></i></a></label>
									<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini delete"><i class="fa fa-trash-o"></i></a></label>
								</div>
							</div>
						@else
							<div class="row form-row">
								<input type="hidden" name="r-type" value="9" />
								<div class="col-md-11 form-group">
									<input type="text" name="r-description" class="form-control" value="{{ htmlspecialchars($ir->description) }}" />
								</div>
								<div class="col-md-1 form-group" style="text-align:right;">
									<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini handle"><i class="fa fa-arrows-v"></i></a></label>
									<label class="form-label" style="line-height:37px;"><a class="btn btn-white btn-xs btn-mini delete"><i class="fa fa-trash-o"></i></a></label>
								</div>
							</div>
						@endif
					@endforeach

				</div>
				<div class="row form-row heading addnewinvoicerows">
					<div class="col-md-12 form-group">
						<a href="javascript;" class="form-label addnewrow" style="line-height:37px;"><i class="fa fa-plus"></i> &nbsp;Factuurregel toevoegen</a>
						<a href="javascript;" class="form-label addnewtextrow" style="line-height:37px;"><i class="fa fa-plus"></i> &nbsp;Tekstregel toevoegen</a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group m-t-40">
			<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
		</div>

	</div>
</div>
{{ Form::close() }}
<div class="grid simple">
	<div class="grid-body">

@endsection