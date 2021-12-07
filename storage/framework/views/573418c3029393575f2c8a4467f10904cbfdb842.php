<?php $__env->startSection('title'); ?>
    <?php echo e(__('Projects')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-button'); ?>
    <?php if($view == 'grid'): ?>
        <a href="<?php echo e(route('projects.list','list')); ?>" class="btn btn-sm bg-white btn-icon rounded-pill mr-2 m-0">
            <span class="btn-inner--text text-dark"><?php echo e(__('List View')); ?></span>
        </a>
    <?php else: ?>
        <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-sm bg-white btn-icon rounded-pill mr-2 m-0">
            <span class="btn-inner--text text-dark"><?php echo e(__('Card View')); ?></span>
        </a>
    <?php endif; ?>
    <div class="bg-neutral rounded-pill d-inline-block">
        <div class="input-group input-group-sm input-group-merge input-group-flush">
            <div class="input-group-prepend">
                <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" id="project_keyword" class="form-control form-control-flush" placeholder="<?php echo e(__('Search by Name or tag')); ?>">
        </div>
    </div>
    <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0">
        <a href="#" class="action-item text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-filter"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-steady" id="project_sort">
            <a class="dropdown-item active" href="#" data-val="created_at-desc">
                <i class="fas fa-sort-amount-down"></i><?php echo e(__('Newest')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="created_at-asc">
                <i class="fas fa-sort-amount-up"></i><?php echo e(__('Oldest')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="name-asc">
                <i class="fas fa-sort-alpha-down"></i><?php echo e(__('From A-Z')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="name-desc">
                <i class="fas fa-sort-alpha-up"></i><?php echo e(__('From Z-A')); ?>

            </a>
        </div>
    </div>
    <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0">
        <a href="#" class="action-item text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-flag"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right project-filter-actions dropdown-steady" id="project_status">
            <a class="dropdown-item filter-action filter-show-all pl-4 active" href="#"><?php echo e(__('Show All')); ?></a>
            <?php $__currentLoopData = \App\Project::$status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="dropdown-item filter-action pl-4" href="#" data-val="<?php echo e($key); ?>"><?php echo e(__($val)); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php if($allow == true): ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" data-url="<?php echo e(route('projects.create')); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Create Project')); ?>">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php else: ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" id="prevent_project">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row min-750" id="project_view"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        // ready
        $(function () {
            var sort = 'created_at-desc';
            var status = '';
            ajaxFilterProjectView('created_at-desc');

            // when change status
            $(".project-filter-actions").on('click', '.filter-action', function (e) {
                if ($(this).hasClass('filter-show-all')) {
                    $('.filter-action').removeClass('active');
                    $(this).addClass('active');
                } else {
                    $('.filter-show-all').removeClass('active');
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        $(this).blur();
                    } else {
                        $(this).addClass('active');
                    }
                }

                var filterArray = [];
                var url = $(this).parents('.project-filter-actions').attr('data-url');
                $('div.project-filter-actions').find('.active').each(function () {
                    filterArray.push($(this).attr('data-val'));
                });

                status = filterArray;

                ajaxFilterProjectView(sort, $('#project_keyword').val(), status);
            });

            // when change sorting order
            $('#project_sort').on('click', 'a', function () {
                sort = $(this).attr('data-val');
                ajaxFilterProjectView(sort, $('#project_keyword').val(), status);
                $('#project_sort a').removeClass('active');
                $(this).addClass('active');
            });

            // when searching by project name
            $(document).on('keyup', '#project_keyword', function () {
                ajaxFilterProjectView(sort, $(this).val(), status);
            });

            // project invite modal
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

            $(document).on('click', '.check-invite-members', function (e) {
                var ele = $(this);
                var emailele = $('#invite_email');
                var project_id = $('input[name="project_id"]').val();
                var email = emailele.val();

                // Email Field Validation
                $('.email-error-message').remove();
                if (email == '') {
                    emailele.focus().after('<small class="email-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                if (!emailReg.test(email)) {
                    emailele.focus().after('<small class="email-error-message text-danger"><?php echo e(__("Please enter valid email address.")); ?></small>');
                    return false;
                } else {
                    $('.invite_usr').addClass('d-none');

                    $.ajax({
                        url: '<?php echo e(route('user.exists')); ?>',
                        dataType: 'json',
                        data: {
                            'project_id': project_id,
                            'email': email
                        },
                        success: function (data) {
                            if (data.code == '202') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success');
                            } else if (data.code == '200') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success');
                                location.reload();
                            } else if (data.code == '404') {
                                $('.invite_user_div').removeClass('d-none');
                                $('.invite-warning').text(data.error).show();
                                $('#invite_email').prop('readonly', true);
                            }
                            ele.removeClass('check-invite-members').addClass('invite-members');
                        }
                    });
                }
            });

            $(document).on('click', '.invite-members', function () {
                var project_id = $('input[name="project_id"]').val();
                var useremail = $('#invite_email').val();
                var username = $('#username').val();
                var userpassword = $('#userpassword').val();
                var role = $('#usr_role').val();

                $('.username-error-message').remove();
                if (username == '') {
                    $('#username').focus().after('<small class="username-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                $('.userpassword-error-message').remove();
                if (userpassword == '') {
                    $('#userpassword').focus().after('<small class="userpassword-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                $('.email-error-message').remove();
                if (useremail == '') {
                    $('#invite_email').focus().after('<small class="email-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                if (!emailReg.test(useremail)) {
                    $('#invite_email').focus().after('<small class="email-error-message text-danger"><?php echo e(__("Please enter valid email address.")); ?></small>');
                    return false;
                } else {
                    $.ajax({
                        url: '<?php echo e(route('invite.project.user.member')); ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            'project_id': project_id,
                            'useremail': useremail,
                            'username': username,
                            'userpassword': userpassword,
                            'role': role,
                        },
                        success: function (data) {
                            if (data.code == '200') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success')
                                if ($('#project_users').length > 0) {
                                    loadProjectUser();
                                } else {
                                    ajaxFilterProjectView('created_at-desc', $('#project_keyword').val());
                                }
                            } else if (data.code == '404') {
                                show_toastr(data.status, data.error, 'error')
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.invite-btn', function () {
                var current = $(this);
                var id = current.attr('data-id');
                var project_id = $('input[name="project_id"]').val();
                var role = $('#usr_role').val();

                $.ajax({
                    url: '<?php echo e(route('user.exists')); ?>',
                    dataType: 'json',
                    data: {
                        'project_id': project_id,
                        'id': id,
                        'role': role
                    },
                    success: function (data) {
                        if (data.code == '200') {
                            current.html('Invited');
                            current.removeClass('btn-secondary');
                            current.addClass('btn-primary');

                            show_toastr(data.status, data.success, 'success');

                            if ($('#project_users').length > 0) {
                                loadProjectUser();
                            } else {
                                ajaxFilterProjectView('created_at-desc', $('#project_keyword').val());
                            }
                        } else if (data.code == '202') {
                            show_toastr(data.status, data.success, 'success');
                        } else if (data.code == '404') {
                            show_toastr(data.status, data.error, 'error');
                        }
                    }
                });
            });

            $(document).on('click', '#prevent_project', function () {
                show_toastr('Error', '<?php echo e(__('Your project limit is over, Please upgrade plan.')); ?>', 'error');
            });

            $(document).on('click', '.user_role', function () {
                $('#usr_role').val($(this).attr('data-val'));
            })
        });

        // For Filter
        var currentRequest = null;

        function ajaxFilterProjectView(project_sort, keyword = '', status = '') {
            var mainEle = $('#project_view');
            var view = '<?php echo e($view); ?>';
            var data = {
                view: view,
                sort: project_sort,
                keyword: keyword,
                status: status,
            }

            currentRequest = $.ajax({
                url: '<?php echo e(route('filter.project.view')); ?>',
                data: data,
                beforeSend: function () {
                    if (currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function (data) {
                    console.log(data)
                    mainEle.html(data.html);
                    $('[id^=fire-modal]').remove();
                    loadConfirm();
                },
                error:function (data){
                   console.log(data.responseText.html)
                    mainEle.html(data.html);
                    $('[id^=fire-modal]').remove();
                    loadConfirm();
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\taskgo\resources\views/projects/index.blade.php ENDPATH**/ ?>