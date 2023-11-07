@extends('layouts.main.app')

@section('head')
@include('layouts.main.headersection',['buttons'=>[
[
'name'=>'Back',
'url'=> route('user.schedule-message.index'),
]
]])
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-12">
		<div class="card">
			<div class="card-header row">
				<h4 class="text-left col-6">{{ __('Create Scheduled Message') }}</h4>
				
			</div>
			<div class="card-body">
				<form method="POST" action="{{ route('user.schedule-message.store') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
					@csrf
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>{{ __('Scheduled Name') }}</label>
								<input type="text" name="title" class="form-control" required="">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Select Number') }}</label>
								<select class="form-control"  name="device" required="">
									@foreach($devices as $device)
									<option value="{{ $device->id }}">{{ $device->phone }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								

								<label>{{ __('Receiver Group') }}</label>								
								
								<select class="form-control select2 "  name="group" >
									@foreach($groups as $group)
									<option value="{{$group->id}}">{{$group->name}} - ({{ $group->contacts_count }} {{ __('Contacts') }})</option>
									@endforeach
								</select>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label>{{ __('Delivery date and time') }}</label>
							<div class="input-group">							

								
								<input type="datetime-local" name="date" class="form-control" required="" min="{{ now()->format('Y-m-d') }}">
								<div class="input-group-append">
									<select name="timezone" class="form-control" required="">
										<option value="" selected="" disabled="">{{ __('Select TImezone') }}</option>
										@foreach (timezone_identifiers_list() as $timezone)
										<option value="{{ $timezone }}" >
											{{ $timezone }}
										</option>
										@endforeach
									</select>
								</div>						


							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Messaging Type') }}</label>
								<select class="form-control message_type"  name="message_type" required="">
									<option value="text">{{ __('Text Message') }}</option>
									<option value="template">{{ __('Template Message') }}</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row plain-text">
						<div class="col-sm-12 plain-text">
							<div class="form-group">
								<label>{{ __('Message') }}</label>
								<textarea class="form-control h-200" name="message" required="" maxlength="1000" placeholder="{{ __('note : {name} write the name according to the recipient.') }}"></textarea>
							</div>
						</div>
						
					</div>
					<div class="templates-list none">
						<div class="form-group">
							<label>{{ __('Select Template') }}</label>
							<select  class="form-control" name="template">
								@foreach($templates as $template)
								<option value="{{ $template->id }}">{{ $template->title }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-outline-primary submit-button">{{ __('Create Schedule') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')

<script src="{{ asset('assets/js/pages/user/schedule-create.js') }}"></script>
@endpush