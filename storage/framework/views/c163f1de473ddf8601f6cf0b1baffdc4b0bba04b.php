<div class="navigation" data-menu="<?php echo session('menu_status', 'off'); ?>">
     <div class="nav-top aclr">
         <a class="show pull-left" href="<?php echo e($admincp); ?>">
             <img class="imglogo" src="<?php echo vanhenry\helpers\helpers\SettingHelper::getSettingImage('logo_admin','img') ?>">
             <img class="smalllogo none" src="<?php echo vanhenry\helpers\helpers\SettingHelper::getSettingImage('logo_admin','img') ?>">
         </a>
     </div>
     <ul class="main-menu">
         <?php $__currentLoopData = $userglobal['menu'] ?: []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pmenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <?php
                $isHaveLinkActive = FCHelper::checkHaveActiveLinkMenuAdmin($admincp, $pmenu);
             ?>
             <li class="nav-item <?php echo e($isHaveLinkActive ? 'active' : ''); ?>">
                 <div class="menu-anchor">
                     <a href="javascript:void(0)">
                         <i class="<?php echo e($pmenu->icon); ?>"></i>
 
                         <span style="<?php session('menu_status', 'off') == 'on' ? 'display:inline-block;height:inherit;width:inherit;' : 'display:block;height:0px;width:0px'; ?>" class="txt"><?php echo e(FCHelper::ep($pmenu, 'name')); ?>

 
                         </span>
                     </a>
                     <button class="menu-show-icon">
                         <i class="fa fa-angle-down" aria-hidden="true"></i>
                     </button>
                 </div>
                 <ul class="sub <?php echo e($isHaveLinkActive ? 'active' : 'none'); ?>">
                     <?php $__currentLoopData = $pmenu->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <?php
                             $checkActiveLink = FCHelper::checkActiveLinkMenuAdmin($admincp . '/' . $cmenu->link);
                         ?>
                         <li><a href="<?php echo e($admincp); ?>/<?php echo e($cmenu->link); ?>"
                                 class="show <?php echo e($checkActiveLink ? 'active' : ''); ?>">
                                 <span class="txt"><?php echo e(FCHelper::ep($cmenu, 'name')); ?>

                                 </span>
                             </a>
                         </li>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 </ul>
             </li>
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </ul>
 </div><?php /**PATH C:\laragon\www\campionPhone\packages\vanhenry\manager\src/views/static/menu.blade.php ENDPATH**/ ?>