<?php echo e(Form::open(['url' => 'projects','id' => 'create_project','enctype' => 'multipart/form-data'])); ?>

<div class="row">
    <div class="col-12 col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Project name'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control','required'=>'required'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <?php echo e(Form::label('start_date', __('Start date'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::date('start_date', null, ['class' => 'form-control'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <?php echo e(Form::label('end_date', __('End date'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::date('end_date', null, ['class' => 'form-control'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('image', __('Project Image'),['class' => 'form-control-label'])); ?>

            <input type="file" name="image" id="image" class="custom-input-file" accept="image/*"/>
            <label for="image">
                <i class="fa fa-upload"></i>
                <span><?php echo e(__('Choose a file…')); ?></span>
            </label>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-group">
            <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control','min'=>'0','maxlength' => '8'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-group">
            <?php echo e(Form::label('budget', __('Budget'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::number('budget', null, ['class' => 'form-control','step' =>'0.01'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-group">
            <?php echo e(Form::label('currency', __('Currency Symbol'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::text('currency', '$', ['class' => 'form-control','required' => 'required'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('descriptions', __('Description'),['class' => 'form-control-label'])); ?>

            <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
            <?php echo e(Form::textarea('descriptions', null, ['class' => 'form-control','rows' => '1','data-toggle' => 'autosize'])); ?>

        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('tags', __('Tags'),['class' => 'form-control-label'])); ?>

            <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Seprated By Comma')); ?></small>
            <?php echo e(Form::text('tags', null, ['class' => 'form-control','data-toggle' => 'tags','placeholder'=>__('Type here..')])); ?>

        </div>
    </div>
</div>

<div class="text-right">
    <?php echo e(Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

    <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\wamp64\www\taskgo\resources\views/projects/create.blade.php ENDPATH**/ ?>