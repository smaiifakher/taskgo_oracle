@extends('layouts.admin')

@section('title')
    {{__('Site Settings')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#tabs-1" class="list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-cog pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Invoice Setting')}}</a>
                                <p class="mb-0 text-sm">{{__('Detail of your Invoice.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-2" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-file pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('My Billing Detail')}}</a>
                                <p class="mb-0 text-sm">{{__('This detail will show in your Invoice.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-3" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-percent pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Tax')}}</a>
                                <p class="mb-0 text-sm">{{__('You can manage your tax rate here.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            <div id="tabs-1" class="tabs-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{__('Invoice Setting')}}</h5>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'],'id' => 'update_setting','enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    {{ Form::label('light_logo', __('Light Logo'),['class' => 'form-control-label']) }}
                                    <input type="file" name="light_logo" id="light_logo" class="custom-input-file"/>
                                    <label for="light_logo">
                                        <i class="fa fa-upload"></i>
                                        <span>{{__('Choose a file…')}}</span>
                                    </label>
                                    @error('light_logo')
                                    <span class="light_logo" role="alert">
                                        <small class="text-danger">{{ $message }}</small>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                @if(!empty($details['light_logo']))
                                    <img src="{{ asset(Storage::url($details['light_logo'])) }}" class="img_setting"/>
                                @else
                                    <img src="{{ asset(Storage::url('logo/logo.png')) }}" class="img_setting"/>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('dark_logo', __('Dark Logo'),['class' => 'form-control-label']) }}
                                    <input type="file" name="dark_logo" id="dark_logo" class="custom-input-file"/>
                                    <label for="dark_logo">
                                        <i class="fa fa-upload"></i>
                                        <span>{{__('Choose a file…')}}</span>
                                    </label>
                                    @error('dark_logo')
                                    <span class="dark_logo" role="alert">
                                        <small class="text-danger">{{ $message }}</small>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                @if(!empty($details['dark_logo']))
                                    <img src="{{ asset(Storage::url($details['dark_logo'])) }}" class="img_setting"/>
                                @else
                                    <img src="{{ asset(Storage::url('logo/logo.png')) }}" class="img_setting"/>
                                @endif
                            </div>
                        </div>
                        <div class="text-right pt-3">
                            {{ Form::hidden('from','invoice_setting') }}
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill">{{__('Save changes')}}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div id="tabs-2" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{__('My Billing Detail')}}</h5>
                        <small>{{__('This detail will show in your Invoice.')}}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'],'id' => 'update_billing_setting','enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address', __('Address'),['class' => 'form-control-label']) }}
                                    {{ Form::text('address', $details['address'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('city', __('City'),['class' => 'form-control-label']) }}
                                    {{ Form::text('city', $details['city'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('state', __('State'),['class' => 'form-control-label']) }}
                                    {{ Form::text('state', $details['state'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('zipcode', __('Zip/Post Code'),['class' => 'form-control-label']) }}
                                    {{ Form::text('zipcode', $details['zipcode'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('country', __('Country'),['class' => 'form-control-label']) }}
                                    {{ Form::text('country', $details['country'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('telephone', __('Telephone'),['class' => 'form-control-label']) }}
                                    {{ Form::text('telephone', $details['telephone'], ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            {{ Form::hidden('from','billing_setting') }}
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill">{{__('Save changes')}}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div id="tabs-3" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{__('Tax')}}</h6>
                            </div>
                            <div class="col-auto">
                                <div class="actions">
                                    <a href="#" class="action-item" data-url="{{ route('taxes.create') }}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Tax')}}">
                                        <i class="fas fa-plus"></i>
                                        <span class="d-sm-inline-block">{{__('Add')}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Rate %')}}</th>
                                    <th class="w-25">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(Auth::user()->taxes->count() > 0)
                                    @foreach(Auth::user()->taxes as $tax)
                                        <tr>
                                            <td>{{ $tax->name }}</td>
                                            <td>{{ $tax->rate }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="#" class="action-item px-2" data-url="{{ route('taxes.edit',$tax) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Edit')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="action-item text-danger px-2" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}|{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-tax-{{$tax->id}}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['taxes.destroy',$tax->id],'id'=>'delete-tax-'.$tax->id]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th scope="col" colspan="3"><h6 class="text-center">{{__('No Taxes Found.')}}</h6></th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // For Sidebar Tabs
        $(document).ready(function () {
            $('.list-group-item').on('click', function () {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-item').removeClass('text-primary');
                $(this).addClass('text-primary');
            });
        });
    </script>
@endpush
