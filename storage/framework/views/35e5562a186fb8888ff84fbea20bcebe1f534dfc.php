<form class="pl-3 pr-3" method="post" action="<?php echo e(route('plans.update',$plan->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('put'); ?>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="name"><?php echo e(__('Name')); ?></label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($plan->name); ?>" required/>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="price"><?php echo e(__('Price')); ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?></span>
                    </div>
                    <input type="number" min="0" class="form-control" id="price" name="price" value="<?php echo e($plan->price); ?>" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="max_users"><?php echo e(__('Maximum Users')); ?></label>
                <input type="number" class="form-control" id="max_users" name="max_users" value="<?php echo e($plan->max_users); ?>" required/>
                <span><small><?php echo e(__('Note: "-1" for Unlimited')); ?></small></span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="max_projects"><?php echo e(__('Maximum Projects')); ?></label>
                <input type="number" class="form-control" id="max_projects" name="max_projects" value="<?php echo e($plan->max_projects); ?>" required/>
                <span><small><?php echo e(__('Note: "-1" for Unlimited')); ?></small></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="duration"><?php echo e(__('Duration')); ?></label>
                <select name="duration" id="duration" class="form-control">
                    <?php $__currentLoopData = $plan->arrDuration(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $duration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if($plan->duration == $key): ?> selected <?php endif; ?>><?php echo e(__($duration)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="form-control-label" for="description"><?php echo e(__('Description')); ?></label>
                <textarea class="form-control" data-toggle="autosize" rows="1" id="description" name="description"><?php echo e($plan->description); ?></textarea>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button class="btn btn-sm btn-primary rounded-pill" type="submit"><?php echo e(__('Update Plan')); ?></button>
    </div>
</form>
<?php /**PATH C:\wamp64\www\taskgo\resources\views/plans/edit.blade.php ENDPATH**/ ?>