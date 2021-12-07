@extends('layouts.admin')

@section('title')
    {{__('Manage Plans')}}
@endsection

@section('action-button')
    @if($allow == true)
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" data-url="{{ route('plans.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Plans')}}">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @else
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" id="prevent_plan">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endif
@endsection

@section('content')
    <div class="row">
        @foreach ($plans as $key => $plan)
            <div class="col-3">
                <div class="card card-pricing popular text-center px-3 mb-5 mb-lg-0">
                    <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white">{{ $plan->name }}</span>
                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Plan')}}" data-url="{{route('plans.edit',$plan->id)}}"><i class="mdi mdi-pencil mr-1"></i>{{__('Edit')}}</a>
                    <div class="card-header py-5 border-0">
                        <div class="h1 text-primary text-center mb-0" data-pricing-value="{{ $plan->price }}">{{(env('CURRENCY') ? env('CURRENCY') : '$')}}<span class="price">{{ $plan->price }}</span><span class="h6 ml-2">/ {{ __($plan->duration) }}</span></div>
                    </div>
                    <div class="card-body delimiter-top">
                        <ul class="list-unstyled mb-4">
                            <li>{{ ($plan->max_users < 0)?__('Unlimited'):$plan->max_users }} {{__('Users')}}</li>
                            <li>{{ ($plan->max_projects < 0)?__('Unlimited'):$plan->max_projects }} {{__('Projects')}}</li>
                            <li>
                                @if($plan->description)
                                    <small>{{$plan->description}}</small>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#prevent_plan', function () {
                show_toastr('Error', '{{__('Please Enter Stripe or PayPal Payment Details.')}}', 'error');
            });
        });
    </script>
@endpush
