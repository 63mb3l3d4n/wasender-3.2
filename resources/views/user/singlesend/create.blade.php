@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Single Send')])
@endsection
@push('topcss')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/css/uikit.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header row">
                <h4 class="text-left col-6">{{ __('Sent Custom Message') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-3">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#mode_1" role="tab" aria-controls="home" aria-selected="true">{{ __('Plain Text') }}</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_2" role="tab" aria-controls="profile" aria-selected="false">{{ __('Text With Media') }}</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_3" role="tab" aria-controls="profile" aria-selected="false">{{ __('Message With Button') }}</a>
                            </li>
                            <li class="nav-item mt-2" style="display: none;">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_4" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ __('Template Message ') }} <small class="text-danger">{{ __('(Beta)') }}</small>
                                </a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_5" role="tab" aria-controls="profile" aria-selected="false">{{ __('List Message') }}</a>
                            </li>
                            
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_7" role="tab" aria-controls="profile" aria-selected="false">{{ __('Send Location') }}</a>
                            </li>
                           

                            <li class="nav-item mt-2 none">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mo" role="tab" aria-controls="profile" aria-selected="false"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-9">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade show active" id="mode_1" role="tabpanel" aria-labelledby="home-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','plain-text') }}" class="ajaxform_reset_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="" data-toggle="select">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To (Receiver)') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Message') }}</label>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea', '```', '```')">Monospace</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm">Emoji</button>
                                                <textarea id="myTextarea" class="form-control h-200 one mt-1" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                     
                                         <div class="col-sm-12 plain-title none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                             <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="plain-text"  data-target=".plain-title" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="tab-pane fade" id="mode_2" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','text-with-media') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select File') }}</label>
                                                <input id="phone" type="file" class="form-control" name="file" required="" />
                                               <small>{{__(' Supported file type:')}}</small> <small class="text-danger">{{ __('jpg,jpeg,png,webp,pdf,docx,xlsx,csv,txt') }}</small>
                                            </div>
                                            

                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Message') }}</label>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea1', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea1', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea1', '```', '```')">Monospace</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea1', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm">Emoji</button>
                                                <textarea id="myTextarea1" class="form-control h-200 one mt-1" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                       

                                          <div class="col-sm-12 text-with-file none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                               <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="text-with-file"  data-target=".text-with-file" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="text-with-file"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="mode_3" role="tabpanel" aria-labelledby="profile-tab4">
                              
                              

                                <form method="POST" action="{{ route('user.sent.customtext','text-with-button') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Message') }}</label>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea2', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea2', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea2', '```', '```')">Monospace</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea2', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm">Emoji</button>
                                                <textarea id="myTextarea2" class="form-control h-200 one mt-1" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Footer Text') }}</label>
                                                <input type="text" class="form-control" name="footer_text" required="" autofocus="" maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="list-button-appendarea">
                                            <div class="form-group plain_button_message_text">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>{{ __('Button 1 Text') }}</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0)" id="add-more" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i class="fa fa-plus"></i>&nbsp{{ __('Add More') }}</a>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="buttons[]" required="" autofocus="" maxlength="50" />
                                            </div>
                                        </div>

                                        <div class="col-sm-12 plain-title-with-button none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                               <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="plain-text-with-button"  data-target=".plain-title-with-button" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-button"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                      </div>

                                    </div>
                                </form>
                                <div class="alert bg-gradient-primary text-white alert-dismissible fade show success-alert mt-4" role="alert">
                                   
                                   <span class="alert-text">{{ __('Note: ') }} {{ __('This features working with only for IOS and Whatsapp Web') }}</span>
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                  </button>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="mode_4" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','text-with-template') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Message') }}</label>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea3', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea3', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea3', '```', '```')">Monospace</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea3', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm">Emoji</button>
                                                <textarea id="myTextarea3" class="form-control h-200 one mt-1" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Footer Text') }}</label>
                                                <input type="text" class="form-control" name="footer_text" required="" autofocus="" maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="list-button-appendarea">
                                            <div class="form-group button_message_text">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4 class="mt-2">{{ __('Call To Action Buttons') }}</h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0)" id="add-more-action" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i class="fa fa-plus"></i>&nbsp{{ __('Add More') }}</a>
                                                    </div>
                                                </div>
                                               
                                                <div id="action-body">

                                                    <div class="card card-primary mt-2 call-to-action-area">
                                                        <div class="card-header">
                                                            <h4>{{ __('Button 1') }}</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-sm-4">
                                                                    <label>
                                                                        {{ __('Select Action Type') }}
                                                                    </label>
                                                                    <select class="form-control action-type" name="buttons[0][type]" required="" >
                                                                        <option value="urlButton" data-key="0">{{ __('Url Button') }}</option>
                                                                        <option value="callButton" data-key="0">{{ __('Phone number (Call Button)') }}</option>
                                                                        <option value="quickReplyButton" data-key="0">{{ __('Quick Reply Button') }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label>
                                                                        {{ __('Button Display Text') }}
                                                                    </label>
                                                                    <input type="text" class="form-control" name="buttons[0][displaytext]" required="" autofocus="" maxlength="50" placeholder="{{ __('Button Display Text') }}" />
                                                                </div>
                                                                <div class="form-group col-sm-4 action-area0">
                                                                    <label>
                                                                        {{ __('Button Click To Action Value') }}
                                                                    </label>
                                                                    <input type="text" class="form-control input_action0" name="buttons[0][action]" required="" autofocus="" maxlength="50" placeholder="https://www.google.com/" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                

                                                </div>
                                            </div>
                                        </div>

                                         <div class="col-sm-12 plain-title-with-template none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                               <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="plain-text-with-template"  data-target=".plain-title-with-template" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-template"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </form>
                                <div class="alert bg-gradient-primary text-white alert-dismissible fade show success-alert mt-4" role="alert">

                                 <span class="alert-text">{{ __('Note:') }} {{ __('This features working with only for IOS and Whatsapp Web') }}</span>
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                              </button>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="mode_5" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','text-with-list') }}" class="ajaxform_reset_form">
                                     @csrf
                                  <div class="row">
                                      <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Title (Header)') }}</label>
                                                <input  type="text" class="form-control" name="header_title" placeholder="{{ __('Example: Amazing boldfaced list title') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Message (Body)') }}</label>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea4', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea4', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea4', '```', '```')">Monospace</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="insertTag('myTextarea4', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm">Emoji</button>

                                                <textarea id="myTextarea4" class="form-control h-200" required="" name="message" placeholder="{{ __('Example: This is a list') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Footer Text') }}</label>
                                                <input  type="text" class="form-control" name="footer_text" placeholder="{{ __('Example: Thank you') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Button Text for select option') }}</label>
                                                <input  type="text" class="form-control" name="button_text" placeholder="{{ __('Example: Required, text on the button to view the list') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="list-option-area">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4 class="mt-2">{{ __('List Options') }}</h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0)" id="add-more-option" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i class="fa fa-plus"></i>&nbsp; {{ __('Add More Card') }}</a>
                                                    </div>
                                                </div>
                                                <div class="list-area">
                                                    <div class="card card-primary card-item">
                                                        <div class="card-header">
                                                            <h4>{{ __('List 1') }}</h4>
                                                          
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label>{{ __('List Section Title') }}</label>
                                                                        <input  type="text" class="form-control" name="section[1][title]" placeholder="{{ __('Example: Select a fruit') }}" value="" required=""  maxlength="50" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row list-item-area1">

                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Enter List Value Name') }}</label>
                                                                        <input  type="text" class="form-control itemval-1" name="section[1][value][1][title]" placeholder="{{ __('Example: Banana') }}" value="" required=""  maxlength="50" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Enter List Value Description') }}</label>

                                                                        <input  type="text" class="form-control" name="section[1][value][1][description]" placeholder="{{ __('Example: Banana is a healthly food') }}" value=""   maxlength="50" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-12 text-right">
                                                                    <a href="javascript:void(0)" class="text-right btn btn-sm btn-neutral add-more-option-item option-item-btn1" data-target=".list-item-area1" data-key="1"><i class="fas fa-plus"></i>&nbsp{{ __('Add More Item') }}</a>
                                                                </div>    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                          <div class="col-sm-12 plain-title-with-list none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                               <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="plain-text-with-list"  data-target=".plain-title-with-list" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-list"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                              </form>
                               <div class="alert bg-gradient-primary text-white alert-dismissible fade show success-alert mt-4" role="alert">
                                   
                                   <span class="alert-text">{{ __('Note:') }} {{ __('This features working with only for IOS and Whatsapp Web') }}</span>
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                  </button>
                              </div>
                            </div>
                             <div class="tab-pane fade" id="mode_7" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','text-with-location') }}" class="ajaxform_instant_reload">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Latitude') }}</label>
                                                <input type="number" step="any" name="degreesLatitude" required="" class="form-control" placeholder="Example: 24.121231">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Longitude') }}</label>
                                                <input type="number" step="any" name="degreesLongitude" required="" class="form-control" placeholder="Example: 55.1121221">
                                            </div>
                                        </div>
                                           <div class="col-sm-12 plain-title-with-location none">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="row">
                                               <div class="col-sm-8 d-flex">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox"  name="templatestatus" id="plain-text-with-location"  data-target=".plain-title-with-location" class="save-template">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-location"><h4>{{ __('Save this as a template?') }}</h4></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-outline-primary submit-button float-right">{{ __('Send Message') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="mode_6" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.sent.customtext','text-with-vcard') }}" class="ajaxform_reset_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Device') }}</label>
                                                <select class="form-control" name="device" required="">
                                                    @foreach($devices as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Message To') }}</label>
                                                <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Enter phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Display Name') }}</label>
                                                <input  type="text" class="form-control" name="display_name" placeholder="{{ __('Enter Display Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <div class="row">
                                                <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Full Name (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="full_name" placeholder="{{ __('Enter Full Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Organization of the contact (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="org_name" placeholder="{{ __('Enter Organization Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('User Contact Number (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="contact_number" placeholder="{{ __('Enter Contact Full Number') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('User Whatsapp Number (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="wa_number" placeholder="{{ __('Enter Whatsapp Full Number') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input class="custom-control-input" id="plain-text-vcard" type="checkbox" name="templatestatus">
                                            <label class="custom-control-label pt-1" for="plain-text-vcard">{{ __('Save this as a template') }}</label>
                                        </div>
                                            <button type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://woody180.github.io/vanilla-javascript-emoji-picker/vanillaEmojiPicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit-icons.min.js"></script>

<script type="text/javascript" src="{{ asset('assets/js/pages/bulk/template.js') }}"></script>
       <script>

        new EmojiPicker({
            trigger: [
                {
                    selector: '.emojipick',
                    insertInto: ['.one'] // '.selector' can be used without array
                }
            ],
            closeButton: true,
            //specialButtons: green
        });

   
        function insertTag(textareaId, openTag, closeTag) {
            var textarea = document.getElementById(textareaId);
            var startPos = textarea.selectionStart;
            var endPos = textarea.selectionEnd;
            var selectedText = textarea.value.substring(startPos, endPos);
            var newText = openTag + selectedText + closeTag;
            textarea.value = textarea.value.substring(0, startPos) + newText + textarea.value.substring(endPos, textarea.value.length);
            textarea.focus();
            textarea.setSelectionRange(endPos + openTag.length + closeTag.length, endPos + openTag.length + closeTag.length);
        }
    </script>
@endpush