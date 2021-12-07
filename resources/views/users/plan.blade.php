<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                @foreach($plans as $plan)
                    <tr>
                        <td>
                            <div class="font-style font-weight-bold">{{$plan->name}}</div>
                            <div class="text-job text-muted"> {{ env('CURRENCY').$plan->price }} {{' / '. $plan->duration}}</div>
                        </td>
                        <td>
                            <div class="font-weight-bold">{{$plan->max_users}}</div>
                            <div>{{__('Users')}}</div>
                        </td>
                        <td>
                            <div class="font-weight-bold">{{$plan->max_projects}}</div>
                            <div>{{__('Projects')}}</div>
                        </td>
                        <td>
                            @if($user->plan==$plan->id)
                                <div></div>
                                <div class="text-success"><h6>{{__('Active')}}</h6></div>
                            @else
                                <div>
                                    <a href="{{route('plan.active',[$user->id,$plan->id])}}" class="btn btn-primary btn-xs" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i></a>
                                </div>
                                <div class="text-success"><h6></h6></div>
                            @endif
                        </td>
                    </tr>
                    {{--<li class="media">
                        <div class="media-body">
                            <div class="media-title font-style">{{$plan->name}}</div>
                            <div class="text-job text-muted"> {{ env('CURRENCY').$plan->price }} {{' / '. $plan->duration}}</div>
                        </div>
                        <div class="media-items">
                            <div class="media-item">
                                <div class="media-value">{{$plan->max_users}}</div>
                                <div class="media-label">{{__('Users')}}</div>
                            </div>
                            <div class="media-item">
                                <div class="media-value">{{$plan->max_projects}}</div>
                                <div class="media-label">{{__('Projects')}}</div>
                            </div>
                            <div class="media-item">
                                @if($user->plan==$plan->id)
                                    <div class="media-value"></div>
                                    <div class="media-label text-success"><h6>{{__('Active')}}</h6></div>
                                @else
                                    <div class="media-value">
                                        <a href="{{route('plan.active',[$user->id,$plan->id])}}" class="btn btn-primary" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i></a>
                                    </div>
                                    <div class="media-label text-success"><h6></h6></div>
                                @endif
                            </div>
                        </div>
                    </li>--}}
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
