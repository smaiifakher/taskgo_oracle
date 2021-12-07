@extends('layouts.admin')

@section('title')
    {{__('Buy Plan')}}
@endsection

@push('css')
    <style>
        #card-element {
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center mt-5 mb-1">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="border p-3 mt-4 mb-3 mt-lg-0 rounded">
                            <h4 class="header-title mb-3">{{__('Order Summary')}}</h4>
                            <div class="table-responsive">
                                <table class="table table-centered mb-0">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="m-0 d-inline-block align-middle">
                                            <h5 class="h6">{{ $plan->name }}</h5>
                                            <p> @if($plan->duration!='Unlimited') {{(env('CURRENCY') ? env('CURRENCY').$plan->price : '$'.$plan->price)}} / @endif {{ __($plan->duration) }} </p>
                                            </p>
                                        </td>
                                        <td class="text-right">
                                            {{(env('CURRENCY') ? env('CURRENCY').$plan->price : '$'.$plan->price)}}
                                        </td>
                                    </tr>
                                    <tr class="coupon-tr" style="display: none;">
                                        <td class="coupon-applied"> {{ __('Coupon Code Applied') }}</td>
                                        <td class="coupon-price text-right"></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td>
                                            <h5 class="m-0 text-left">{{__('Total')}}:</h5>
                                        </td>
                                        <td class="text-right font-weight-semibold final-price">
                                            {{(env('CURRENCY') ? env('CURRENCY').$plan->price : '$'.$plan->price)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($plan->price <= 0)
                            <form role="form" action="{{ route('update.user.plan') }}" method="post">
                                @csrf
                                <div class="row mt-1">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="code" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                {{__('Active Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            @if((env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET'))) && (env('ENABLE_PAYPAL') == 'on' && !empty(env('PAYPAL_CLIENT_ID')) && !empty(env('PAYPAL_SECRET_KEY'))))
                                <ul class="nav nav-pills pb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#stripe-payment" role="tab" aria-controls="stripe" aria-selected="true">{{ __('Stripe') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                                    </li>
                                </ul>
                            @endif
                            <div class="tab-content">
                                @if(env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')))
                                    <div class="tab-pane fade {{ ((env('ENABLE_STRIPE') == 'on' && env('ENABLE_PAYPAL') == 'on') || env('ENABLE_STRIPE') == 'on') ? "show active" : "" }}" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                        <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" id="payment-form">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="custom-radio">
                                                            <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                        </div>
                                                        <p class="mb-0 pt-1">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                                    </div>
                                                    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                        <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                        <img src="{{asset('assets/img/payments/paypal.png')}}" height="24" alt="paypal-card-img">
                                                        <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                        <img src="{{asset('assets/img/payments/american-express.png')}}" height="24" alt="american-express-card-img">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="card-name-on">{{__('Name on card')}}</label>
                                                            <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pt-2">
                                                        <div id="card-element">
                                                            <!-- A Stripe Element will be inserted here. -->
                                                        </div>
                                                        <div id="card-errors" role="alert"></div>
                                                    </div>
                                                    <div class="col-12 pt-4">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    {{--<label for="stripe_coupon">{{__('Coupon')}}</label>--}}
                                                                    <input type="text" id="stripe_coupon" name="coupon" class="form-control coupon" placeholder="{{__('Coupon Code')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 pt-1">
                                                                <div class="form-group apply-stripe-btn-coupon">
                                                                    <a href="#" class="btn btn-primary btn-sm apply-coupon">{{ __('Apply') }}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end Credit/Debit Card box-->
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <div class="text-sm-right">
                                                        <input type="hidden" name="code" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                                        <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}} (<span class="final-price">{{env('CURRENCY')}}{{$plan->price}}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                                @if(env('ENABLE_PAYPAL') == 'on' && !empty(env('PAYPAL_CLIENT_ID')) && !empty(env('PAYPAL_SECRET_KEY')))
                                    <div class="tab-pane fade {{ (env('ENABLE_STRIPE') == 'off' && env('ENABLE_PAYPAL') == 'on') ? "show active" : "" }}" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                        <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('plan.pay.with.paypal') }}">
                                            @csrf
                                            <input type="hidden" name="code" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">

                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            {{--<label for="paypal_coupon">{{__('Coupon')}}</label>--}}
                                                            <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{__('Coupon Code')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pt-1">
                                                        <div class="form-group apply-paypal-btn-coupon">
                                                            <a href="#" class="btn btn-primary btn-sm apply-coupon">{{ __('Apply') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <div class="text-sm-right">
                                                    <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}} (<span class="final-price">{{env('CURRENCY')}}{{$plan->price}}</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">

        // Apply Coupon
        $(document).on('click', '.apply-coupon', function (e) {
            e.preventDefault();

            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();

            if (coupon != '') {
                $.ajax({
                    url: '{{route('apply.coupon')}}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ $plan->id }}',
                        coupon: coupon
                    },
                    success: function (data) {
                        $('#stripe_coupon, #paypal_coupon').val(coupon);
                        if (data.is_success) {
                            $('.coupon-tr').show().find('.coupon-price').text(data.discount_price);
                            $('.final-price').text(data.final_price);
                            show_toastr('Success', data.message, 'success');
                        } else {
                            $('.coupon-tr').hide().find('.coupon-price').text('');
                            $('.final-price').text(data.final_price);
                            show_toastr('Error', data.message, 'error');
                        }
                    }
                })
            } else {
                show_toastr('Error', '{{__('Invalid Coupon Code.')}}', 'error');
            }
        });

        // Stripe Payment
            @if(env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')))

        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
        @endif


    </script>
@endpush
