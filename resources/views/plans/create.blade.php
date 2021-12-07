<form class="pl-3 pr-3" method="post" action="{{ route('plans.store') }}">
    @csrf
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="name">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" required/>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="price">{{ __('Price') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{(env('CURRENCY') ? env('CURRENCY') : '$')}}</span>
                    </div>
                    <input type="number" min="0" class="form-control" id="price" name="price" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="max_users">{{ __('Maximum Users') }}</label>
                <input type="number" class="form-control" id="max_users" name="max_users" required/>
                <span><small>{{__('Note: "-1" for Unlimited')}}</small></span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="max_projects">{{ __('Maximum Projects') }}</label>
                <input type="number" class="form-control" id="max_projects" name="max_projects" required/>
                <span><small>{{__('Note: "-1" for Unlimited')}}</small></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="duration">{{ __('Duration') }}</label>
                <select name="duration" id="duration" class="form-control">
                    @foreach($plan->arrDuration() as $key => $duration)
                        <option value="{{$key}}">{{__($duration)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="description">{{ __('Description') }}</label>
                <textarea class="form-control" data-toggle="autosize" rows="1" id="description" name="description"></textarea>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Create Plan') }}</button>
    </div>
</form>
