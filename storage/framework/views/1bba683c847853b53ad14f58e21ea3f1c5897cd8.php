<div class="top_menu <?php echo e(session('fix_show_menu') ? '' : 'fix-small'); ?>">
     <div class="action-menu">
          <button class="small-menu <?php echo e(session('fix_show_menu') ? '' : 'fix-small'); ?>">
               <i class="fa fa-bars" aria-hidden="true"></i>
          </button>
     </div>
     <div class="header-top aclr">
          <div class="breadc pull-left">
               <?php $exs = \Event::dispatch('vanhenry.manager.headertop.view',[]); ?>
               <?php $__currentLoopData = $exs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exk => $exvs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <?php if(is_array($exvs)): ?>
               <?php $__currentLoopData = $exvs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exvv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <?php echo $__env->make($exvv, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <?php endif; ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <a class="pull-right btn-func bottom" href="<?php echo e($admincp); ?>">
                    <i class="fa fa-home pull-left"></i>
                    <span><?php echo e(trans('db::home')); ?></span>
               </a>
               <a class="pull-right btn-func bottom" href="<?php echo e($admincp); ?>/deleteCache">
                    <i class="fa fa-trash-o pull-left" aria-hidden="true"></i>
                    <span><?php echo e(trans('db::delete_cache')); ?></span>
               </a>
               <a class="pull-right btn-func bottom" href="<?php echo e($admincp); ?>/editSitemap">
                    <i class="fa fa-sitemap pull-left" aria-hidden="true"></i>
                    <span>Sitemap</span>
               </a>
               <a class="pull-right btn-func bottom" href="<?php echo e($admincp); ?>/editRobot">
                    <i class="fa fa-android pull-left" aria-hidden="true"></i>
                    <span>Robots.txt</span>
               </a>
               <a class="pull-right btn-func bottom"  target="_blank" href="">
                    <i class="fa fa-external-link pull-left" aria-hidden="true"></i>
                    <span ><?php echo e(trans('db::see_website')); ?></span> 
               </a>
          </div>
          <div class="right_header_admin">
               <div class="r_h_t_admin">
                    <small>
                         <i class="fa fa-user" aria-hidden="true"></i>
                         <span><?php echo e(Auth::guard('h_users')->user()->username); ?></span>
                         <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </small>
                    <ul>
                         <li><a href="<?php echo e(asset('/')); ?>"><?php echo e(trans('db::see_website')); ?></a></li>
                         <li><a href="" data-toggle="modal" data-target="#changepass"><?php echo e(trans('db::change_pass')); ?></a></li>
                         <li><a href="<?php echo e($admincp); ?>/logout"><?php echo e(trans('db::logout')); ?></a></li>
                    </ul>
               </div>
          </div>
     </div>
</div>
<?php /**PATH H:\laragon\www\laravel-tech5s\packages\vanhenry\manager\src/views/static/header_main.blade.php ENDPATH**/ ?>