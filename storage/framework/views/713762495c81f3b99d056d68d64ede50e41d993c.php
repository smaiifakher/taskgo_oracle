<?php $__env->startSection('title'); ?>
    <?php echo e($project->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#tabs-1" class="list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-project-diagram"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Basic Setting')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('Likes name, description and dates.')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-2" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-coins"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Additional Setting')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('Likes budget, hours and currency.')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-3" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-tasks"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Task Stage')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('System will consider last stage as a completed / done task for get progress on project.')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-4" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-bell"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Notification')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('Change Email Notification.')); ?></p>
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
                        <h5 class=" h6 mb-0"><?php echo e(__('Basic Setting')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::model($project, ['route' => ['projects.update', $project->id], 'id' => 'edit_project', 'method' => 'PUT', 'enctype'=>'multipart/form-data'])); ?>

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <?php echo e(Form::label('name', __('Project name'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('name', null, ['class' => 'form-control','required'=>'required'])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                    <?php echo e(Form::label('descriptions', __('Description'),['class' => 'form-control-label'])); ?>

                                    <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
                                    <?php echo e(Form::textarea('descriptions', null, ['class' => 'form-control','rows' => '1','data-toggle' => 'autosize'])); ?>

                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <?php echo e(Form::label('status', __('Status'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::select('status',\App\Project::$status, null, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <?php echo e(Form::label('image', __('Project Image'),['class' => 'form-control-label'])); ?>

                                <input type="file" name="image" id="image" class="custom-input-file" accept="image/*"/>
                                <label for="image">
                                    <i class="fa fa-upload"></i>
                                    <span><?php echo e(__('Choose a file…')); ?></span>
                                </label> <br>
                                <img <?php echo e($project->img_image); ?> alt="<?php echo e($project->name); ?>" class="avatar avatar-xl"/>
                                <?php echo e(Form::hidden('from','basic')); ?>

                            </div>
                        </div>
                        <div class="text-right pt-3">
                            <?php echo e(Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class=" h6 mb-0"><?php echo e(__('Project Progress Calculation')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::model($project, ['route' => ['projects.update', $project->id], 'id' => 'edit_project', 'method' => 'PUT', 'enctype'=>'multipart/form-data'])); ?>

                        <div id="radio-result" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="radio-result-tab">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="progress_false" value="false" name="project_progress" class="custom-control-input" <?php echo e(($project->project_progress == 'false') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="progress_false"><?php echo e(__('Calculate Progress through tasks')); ?></label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="progress_true" value="true" name="project_progress" class="custom-control-input" <?php echo e(($project->project_progress == 'true') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="progress_true"><?php echo e(__('Manual Entry : ')); ?><b id="p_percentage"><?php echo e($project->progress); ?></b>%</label>
                            </div>
                        </div>
                        <div id="range-result" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="range-result-tab">
                            <input type="range" class="project_progress custom-range" value="<?php echo e($project->progress); ?>" id="progress_bar" name="progress" <?php if($project->project_progress == 'false'): ?> disabled <?php endif; ?>>
                            <?php echo e(Form::hidden('from','project_progress')); ?>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right pt-3">
                                    <?php echo e(Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class=" h6 mb-0"><?php echo e(__('Task Progress Calculation')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::model($project, ['route' => ['projects.update', $project->id], 'id' => 'edit_project', 'method' => 'PUT', 'enctype'=>'multipart/form-data'])); ?>

                        <div id="radio-result" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="radio-result-tab">
                            <div class="custom-control custom-radio">
                                <input type="radio" value="true" id="task_progress_false" name="task_progress" class="custom-control-input" <?php echo e(($project->task_progress == 'true') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="task_progress_false"><?php echo e(__('Based on checklist')); ?></label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" value="false" id="task_progress_true" name="task_progress" class="custom-control-input" <?php echo e(($project->task_progress == 'false') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="task_progress_true"><?php echo e(__('Manual Entry')); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right pt-3">
                                    <?php echo e(Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::hidden('from','task_progress_bar')); ?>

                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class=" h6 mb-0"><?php echo e(__('Danger zone')); ?></h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-sm btn-danger rounded-pill" data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-project-<?php echo e($project->id); ?>').submit();"><?php echo e(__('Delete Project')); ?></button>
                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['projects.destroy',$project->id],'id'=>'delete-project-'.$project->id]); ?>

                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
            <div id="tabs-2" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0"><?php echo e(__('Additional Setting')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::model($project, ['route' => ['projects.update', $project->id], 'id' => 'edit_project', 'method' => 'PUT', 'enctype'=>'multipart/form-data'])); ?>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('budget', __('Budget'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::number('budget', null, ['class' => 'form-control','required' => 'required','step' =>'0.01'])); ?>

                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('currency', __('Currency Symbol'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('currency', null, ['class' => 'form-control','required' => 'required'])); ?>

                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control','required' => 'required','min'=>'0','maxlength' => '8'])); ?>

                                </div>
                                <?php echo e(Form::hidden('from','financial')); ?>

                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <?php echo e(Form::label('tags', __('Tags'),['class' => 'form-control-label'])); ?>

                                    <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Seprated By Comma')); ?></small>
                                    <?php echo e(Form::text('tags', null, ['class' => 'form-control','data-toggle' => 'tags','placeholder'=>__('Type here..')])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="text-right pt-3">
                            <?php echo e(Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
            <div id="tabs-3" class="tabs-card d-none">
                <div class="card">
                    <div class="kanban-settings task-stage-repeater" data-value="<?php echo e(json_encode($project->taskstages)); ?>">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="h6 mb-0"><?php echo e(__('Task Stage')); ?></h5>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="actions" data-repeater-create>
                                        <a href="#" class="action-item">
                                            <i class="fas fa-plus"></i>
                                            <span class="d-none d-sm-inline-block"><?php echo e(__('Add')); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php echo e(Form::open(['route' => ['project.stages.store', $project->id, 'task']])); ?>

                            <table class="table align-items-center table-hover task-stages-list" width="100%" data-repeater-list="stages">
                                <tbody>
                                <tr data-repeater-item class="main-stage-tr">
                                    <td class="stage-input">
                                        <input type="hidden" name="id"/>
                                        <input type="text" name="name" class="form-control repeater-input" required/>
                                    </td>
                                    <td class="stage-move text-right"><i class="fas fa-arrows-alt task-sort-handler"></i></td>
                                    <td class="stage-remove text-right">
                                        <button type="button" class="btn close stage-remove-button" data-type="tasks">
                                            <span aria-hidden="true"><i class="fas fa-trash-alt text-danger text-sm"></i></span>
                                        </button>
                                        <button data-repeater-delete type="button" class="btn close trigger-close-button d-none" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="stage-save-button text-right mb-4 mr-4">
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill"><?php echo e(__('Save')); ?></button>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div id="tabs-4" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0"><?php echo e(__('Notification')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th> <?php echo e(__('Name')); ?></th>
                                    <th class="text-right"> <?php echo e(__('On/Off')); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($EmailTemplates->count() > 0 ): ?>
                                    <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php ($template = $EmailTemplate->template($project->id)); ?>
                                        <?php if($EmailTemplate->name != 'User Invite'): ?>
                                            <tr>
                                                <td><?php echo e($EmailTemplate->name); ?></td>
                                                <td class="action text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input email-template-checkbox" id="email_tempalte_<?php echo e($template->id); ?>" <?php if($template->is_active == 1): ?> checked="checked" <?php endif; ?> type="checkbox" value="<?php echo e($template->is_active); ?>" data-url="<?php echo e(route('status.email.language',[$template->id,$project->id])); ?>"/>
                                                        <label class="custom-control-label" for="email_tempalte_<?php echo e($template->id); ?>"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <th scope="col" colspan="2"><h6 class="text-center"><?php echo e(__('No Email Template found')); ?></h6></th>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/jquery-ui.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/repeater.js')); ?>"></script>
    <script>
        $('.list-group-item').on('click', function () {
            var href = $(this).attr('data-href');
            $('.tabs-card').addClass('d-none');
            $(href).removeClass('d-none');
            $('#tabs .list-group-item').removeClass('text-primary');
            $(this).addClass('text-primary');
        });

        // Remove task stage
        $(document).on('click', '.stage-remove-button', function (e) {
            e.preventDefault();

            var ele = $(this);
            var stage_id = ele.parents('.main-stage-tr').children('.stage-input').find('input')[0].value;

            if (!stage_id) {
                ele.next('button').trigger('click');
            } else {
                var stages = ele.attr('data-type');
                var url = '<?php echo e(route('stage.tasks', '__stage_id')); ?>'.replace('__stage_id', stage_id);

                $.ajax({
                    url: url,
                    success: function (data) {
                        if (data == 0) {
                            if (confirm('<?php echo e(__("Are you sure you want to delete this stage?")); ?>')) {
                                ele.next('button').trigger('click');
                            }
                        } else {
                            alert('<?php echo e(__("There is some tasks in this stage. Please move tasks from this stage.")); ?>');
                        }
                    }
                });
            }

        });

        // Task Stage move
        var $taskDragAndDrop = $("body .task-stage-repeater tbody").sortable({
            handle: '.task-sort-handler'
        });

        var $taskRepeater = $('.task-stage-repeater').repeater({
            initEmpty: true,
            defaultValues: {},
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {
                $taskDragAndDrop.on('drop', setIndexes);
            },
            isFirstItemUndeletable: true
        });

        var value = JSON.parse($(".task-stage-repeater").attr('data-value'));

        if (typeof value != 'undefined' && value.length > 0) {
            $taskRepeater.setList(value);
        }

        $(document).ready(function () {
            $('#progress_bar').change(function () {
                $('#p_percentage').html($(this).val());
            });

            $('input[type=radio][name=project_progress]').change(function () {
                if (this.value == 'true') {
                    $('#progress_bar').removeAttr('disabled');
                } else {
                    $('#progress_bar').attr('disabled', true);
                }
            });
        })

        // change email notification
        <?php if(Auth::user()->type != 'admin'): ?>
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\taskgo\resources\views/projects/edit.blade.php ENDPATH**/ ?>