@extends('layouts.admin')

@section('title')
    {{__('Invoice ').\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}
@endsection

@section('action-button')
    @if(Auth::user()->id == $invoice->created_by)
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-0" data-url="{{ route('invoices.products.add',$invoice->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Item')}}" data-toggle="tooltip" data-original-title="{{__('Add Item')}}">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-0" data-url="{{ route('invoices.payments.create',$invoice->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Payment')}}" data-toggle="tooltip" data-original-title="{{__('Add Payment')}}">
            <span class="btn-inner--icon"><i class="fas fa-shopping-cart"></i></span>
        </a>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-0" data-url="{{ route('invoices.edit',$invoice->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Edit ').\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
            <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
        </a>
    @endif
@endsection

@section('content')
    <div class="card card-body p-md-5">
        <div class="row align-items-center mb-5">
            <div class="col-sm-6 mb-3 mb-sm-0">
                @if(Auth::user()->mode == 'dark')
                    @if($invoice->client_id == Auth::user()->id)
                        <img src="{{ asset(Storage::url($right_address['dark_logo'])) }}" alt="" height="40">
                    @else
                        <img src="{{ asset(Storage::url($left_address['dark_logo'])) }}" alt="" height="40">
                    @endif
                @else
                    @if($invoice->client_id == Auth::user()->id)
                        <img src="{{ asset(Storage::url($right_address['light_logo'])) }}" alt="" height="40">
                    @else
                        <img src="{{ asset(Storage::url($left_address['light_logo'])) }}" alt="" height="40">
                    @endif
                @endif

            </div>
            <div class="col-sm-6 text-sm-right">
                <span class="badge badge-pill badge-{{__(\App\Invoice::$status_color[$invoice->status])}} ml-3">{{__(\App\Invoice::$status[$invoice->status])}}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6 col-md-6">
                <h6 class="">{{__('From :')}}</h6>
                <p class="text-sm font-weight-700 mb-0">
                    {{ Auth::user()->name }}
                </p>
                <span class="text-sm">
                    {{ $left_address['address'] }} <br>
                    {{ $left_address['city'] }}
                    @if(isset($left_address['city']) && !empty($left_address['city'])), @endif
                    {{$left_address['state']}}
                    @if(isset($left_address['zipcode']) && !empty($left_address['zipcode']))-@endif {{$left_address['zipcode']}}<br>
                    {{$left_address['country']}} <br>
                    {{$left_address['telephone']}}
                </span>
            </div>
            <div class="col-lg-6 col-md-6 text-right">
                <h6 class="">{{__('To :')}}</h6>
                <p class="text-sm font-weight-700 mb-0">
                    @if($invoice->client_id == Auth::user()->id)
                        {{ $invoice->user->name }}
                    @else
                        {{ $invoice->client->name }}
                    @endif
                </p>
                <span class="text-sm">
                    {{ $right_address['address'] }} <br>
                    {{ $right_address['city'] }}
                    @if(isset($right_address['city']) && !empty($right_address['city'])), @endif
                    {{$right_address['state']}}
                    @if(isset($right_address['zipcode']) && !empty($right_address['zipcode']))-@endif {{$right_address['zipcode']}}<br>
                    {{$right_address['country']}} <br>
                    {{$right_address['telephone']}}
                </span>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-4 text-sm">
                <strong>{{__('Project')}}</strong><br>
                {{$invoice->project->name}}<br>
            </div>
            <div class="col-md-4 text-sm text-center">
                <strong>{{__('Due Date')}}</strong><br>
                {{\App\Utility::getDateFormated($invoice->due_date)}}<br>
            </div>
            <div class="col-md-4 text-sm text-right">
                <strong>{{__('Due Amount')}}</strong><br>
                {{\App\Utility::projectCurrencyFormat($invoice->project_id,$invoice->getDue(),true)}}<br>
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-6 text-left pb-3">
                <h5>{{__('Item List')}}</h5>
            </div>
            @if(Auth::user()->id == $invoice->created_by)
                <div class="col-6 text-right pb-3">
                    <button type="button" class="btn btn-warning btn-xs btn-icon" data-url="{{ route('invoices.products.add',$invoice->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Item')}}">
                    <span class="btn-inner--icon">
                      <i class="fas fa-plus"></i>
                    </span>
                        <span class="btn-inner--text">{{__('Add Item')}}</span>
                    </button>
                </div>
            @endif
            <div class="col-12">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th class="px-0 bg-transparent border-top-0">{{__('Item')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Price')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Tax')}}</th>
                            @if(Auth::user()->id == $invoice->created_by)
                                <th class="px-0 bg-transparent border-top-0 text-right">{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="px-0">
                                    <span class="h6 text-sm">{{ $item->item }}</span>
                                </td>
                                <td class="px-0">{{ \App\Utility::projectCurrencyFormat($invoice->project_id,$item->price,true) }}</td>
                                <td class="px-0">{{ \App\Utility::projectCurrencyFormat($invoice->project_id,$item->tax(),true) }}</td>
                                @if(Auth::user()->id == $invoice->created_by)
                                    <td class="px-0 text-right">
                                        <a href="#" class="table-action table-action-delete text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$item->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['invoices.products.delete', $invoice->id,$item->id],'id'=>'delete-form-'.$item->id]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card my-5 bg-secondary">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                                <div class="d-flex align-items-center justify-content-md-end">
                                    <span class="d-inline-block mr-3 mb-0">{{__('Total value:')}}</span>
                                    <span class="h4 mb-0">{{ \App\Utility::projectCurrencyFormat($invoice->project_id,($invoice->getSubTotal()+$invoice->getTax()),true) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 order-md-1">
                                <div class="text text-sm">
                                    <span class="font-weight-bold">{{__('Subtotal')}}</span><br>
                                    <span class="text-dark">{{ \App\Utility::projectCurrencyFormat($invoice->project_id,$invoice->getSubTotal(),true) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 order-md-1">
                                <div class="text text-sm">
                                    <span class="font-weight-bold">{{(!empty($invoice->tax)?$invoice->tax->name:'Tax')}} ({{(!empty($invoice->tax)?$invoice->tax->rate:'0')}} %)</span><br>
                                    <span class="text-dark">{{ \App\Utility::projectCurrencyFormat($invoice->project_id,$invoice->getTax(),true) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h5 class="pb-3">{{__('Payment History')}}</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{__('Transaction ID')}}</th>
                            <th>{{__('Payment Date')}}</th>
                            <th>{{__('Payment Type')}}</th>
                            <th>{{__('Note')}}</th>
                            <th>{{__('Amount')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($invoice->payments->count())
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{sprintf("%05d", $payment->transaction_id)}}</td>
                                    <td>{{ \App\Utility::getDateFormated($payment->date) }}</td>
                                    <td>{{$payment->payment_type}}</td>
                                    <td>{{(!empty($payment->notes)) ? $payment->notes : '-'}}</td>
                                    <td>{{\App\Utility::projectCurrencyFormat($invoice->project_id,$payment->amount,true)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th scope="col" colspan="5"><h6 class="text-center">{{__('No Record Found.')}}</h6></th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function fillClient(project_id, selected = 0) {
            $.ajax({
                url: '{{route('project.client.json')}}',
                data: {project_id: project_id},
                type: 'POST',
                success: function (data) {
                    $('#client_id').html('');

                    if (data != '') {
                        $('#no_client').addClass('d-none');
                        $.each(data, function (key, data) {
                            var selected = '';
                            if (key == selected) {
                                selected = 'selected';
                            }
                            $("#client_id").append('<option value="' + key + '" ' + selected + '>' + data + '</option>');
                        });
                    } else {
                        $('#no_client').removeClass('d-none');
                    }
                }
            })
        }

        $(document).on('click', '.items_tab', function () {
            $('#from').val($(this).attr('id'));
        })
    </script>
@endpush
