<?php $__env->startSection('title'); ?>
    <?php echo e(__('Orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th><?php echo e(__('Order Id')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Plan Name')); ?></th>
                                <th><?php echo e(__('Price')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Type')); ?></th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Coupon')); ?></th>
                                <th class="text-right"><?php echo e(__('Invoice')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($orders->count() > 0): ?>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($order->order_id); ?></td>
                                        <td><?php echo e($order->user_name); ?></td>
                                        <td><?php echo e($order->plan_name); ?></td>
                                        <td><?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?> <?php echo e(number_format($order->price)); ?></td>
                                        <td>
                                            <?php if($order->payment_status == 'succeeded' || $order->payment_status == 'approved'): ?>
                                                <span class="badge badge-success"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($order->payment_type); ?></td>
                                        <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                                        <td><?php echo e(!empty($order->use_coupon)?$order->use_coupon->coupon_detail->name:'-'); ?></td>
                                        <td class="text-right">
                                            <?php if($order->receipt =='free coupon'): ?>
                                                <p><?php echo e(__('Used 100 % discount coupon code.')); ?></p>
                                            <?php elseif(!empty($order->receipt)): ?>
                                                <a href="<?php echo e($order->receipt); ?>" target="_blank" class="text-primary"><i class="fas fa-file-invoice"></i></a>
                                            <?php else: ?>
                                                <?php echo e('-'); ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <th scope="col" colspan="8"><h6 class="text-center"><?php echo e(__('No Orders Found.')); ?></h6></th>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\taskgo\resources\views/plans/orderlist.blade.php ENDPATH**/ ?>