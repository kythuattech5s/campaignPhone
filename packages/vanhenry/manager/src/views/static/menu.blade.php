<div class="navigation" data-menu="<?php echo session('menu_status', 'off'); ?>">
     <div class="nav-top aclr">
         <a class="show pull-left" href="{{ $admincp }}">
             <img class="imglogo" src="{Ilogo_admin.imgI}">
             <img class="smalllogo none" src="{Ilogo_admin.imgI}">
         </a>
     </div>
     <ul class="main-menu">
         @foreach ($userglobal['menu'] ?: [] as $pmenu)
             @php
                $isHaveLinkActive = FCHelper::checkHaveActiveLinkMenuAdmin($admincp, $pmenu);
             @endphp
             <li class="nav-item {{ $isHaveLinkActive ? 'active' : '' }}">
                 <div class="menu-anchor">
                     <a href="javascript:void(0)">
                         <i class="{{ $pmenu->icon }}"></i>
 
                         <span style="<?php session('menu_status', 'off') == 'on' ? 'display:inline-block;height:inherit;width:inherit;' : 'display:block;height:0px;width:0px'; ?>" class="txt">{{ FCHelper::ep($pmenu, 'name') }}
 
                         </span>
                     </a>
                     <button class="menu-show-icon">
                         <i class="fa fa-angle-down" aria-hidden="true"></i>
                     </button>
                 </div>
                 <ul class="sub {{ $isHaveLinkActive ? 'active' : 'none' }}">
                     @foreach ($pmenu->childs as $cmenu)
                         @php
                             $checkActiveLink = FCHelper::checkActiveLinkMenuAdmin($admincp . '/' . $cmenu->link);
                         @endphp
                         <li><a href="{{ $admincp }}/{{ $cmenu->link }}"
                                 class="show {{ $checkActiveLink ? 'active' : '' }}">
                                 <span class="txt">{{ FCHelper::ep($cmenu, 'name') }}
                                 </span>
                             </a>
                         </li>
                     @endforeach
                 </ul>
             </li>
         @endforeach
     </ul>
 </div>