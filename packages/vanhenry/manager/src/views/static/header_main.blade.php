<div class="top_menu {{ session('fix_show_menu') ? '' : 'fix-small' }}">
     <div class="action-menu">
          <button class="small-menu {{ session('fix_show_menu') ? '' : 'fix-small' }}">
               <i class="fa fa-bars" aria-hidden="true"></i>
          </button>
     </div>
     <div class="header-top aclr">
          <div class="breadc pull-left">
               <?php $exs = \Event::dispatch('vanhenry.manager.headertop.view',[]); ?>
               @foreach ($exs as $exk => $exvs)
               @if(is_array($exvs))
               @foreach($exvs as $exvv)
               @include($exvv)
               @endforeach
               @endif
               @endforeach
               <a class="pull-right btn-func bottom" href="{{$admincp}}">
                    <i class="fa fa-home pull-left"></i>
                    <span>{{trans('db::home')}}</span>
               </a>
               <a class="pull-right btn-func bottom" href="{{$admincp}}/deleteCache">
                    <i class="fa fa-trash-o pull-left" aria-hidden="true"></i>
                    <span>{{trans('db::delete_cache')}}</span>
               </a>
               <a class="pull-right btn-func bottom" href="{{$admincp}}/editSitemap">
                    <i class="fa fa-sitemap pull-left" aria-hidden="true"></i>
                    <span>Sitemap</span>
               </a>
               <a class="pull-right btn-func bottom" href="{{$admincp}}/editRobot">
                    <i class="fa fa-android pull-left" aria-hidden="true"></i>
                    <span>Robots.txt</span>
               </a>
               <a class="pull-right btn-func bottom"  target="_blank" href="">
                    <i class="fa fa-external-link pull-left" aria-hidden="true"></i>
                    <span >{{trans('db::see_website')}}</span> 
               </a>
          </div>
          <div class="right_header_admin">
               <div class="r_h_t_admin">
                    <small>
                         <i class="fa fa-user" aria-hidden="true"></i>
                         <span>{{Auth::guard('h_users')->user()->username}}</span>
                         <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </small>
                    <ul>
                         <li><a href="{{asset('/')}}">{{trans('db::see_website')}}</a></li>
                         <li><a href="" data-toggle="modal" data-target="#changepass">{{trans('db::change_pass')}}</a></li>
                         <li><a href="{{$admincp}}/logout">{{trans('db::logout')}}</a></li>
                    </ul>
               </div>
          </div>
     </div>
</div>
