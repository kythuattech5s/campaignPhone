'use strict';
var MENU = (function(){
    function clickshowMenu(){
        const divAnchors = document.querySelectorAll('.main-menu>li .menu-anchor');
        const btnIcons = document.querySelectorAll('.menu-show-icon');
        const submenus = document.querySelectorAll('.main-menu .sub');
        divAnchors.forEach(function(divAnchor,index){
            divAnchor.onclick = function(e){
                e.preventDefault();
                const li = divAnchor.parentElement;
                const submenu = li.querySelector('.sub');
                if(submenu.classList.contains('none')){
                    btnIcons.forEach(element => {
                        element.querySelector('i').animate([{transform:"rotate(0deg)"}],{
                            fill:"forwards",
                            duration:200
                        })
                    });

                    submenus.forEach(function(subMenuOther,indexMenuOther){
                        if(indexMenuOther !== index){
                            const animateSubmenu = subMenuOther.animate([{maxHeight:0}],{
                                duration:400,
                                fill:"forwards",
                            });
                            animateSubmenu.onfinish = function(){
                                subMenuOther.className = "sub none";
                                animateSubmenu.cancel();
                            }
                        }
                    })

                    li.querySelector('button i').animate([{transform:"rotate(-90deg)"}],{
                        duration:200,
                        fill:"forwards",
                    })
                    submenu.classList.remove('none');
                    submenu.animate([{maxHeight:"500px"}],{
                        duration:400,
                        fill:"forwards",
                    });
                }else{
                    const submenuAnimate = submenu.animate([{maxHeight:0}],{
                        duration:400,
                        fill:"forwards",
                    });
                    submenuAnimate.onfinish = function(){
                        submenu.className = "sub none";
                        submenuAnimate.cancel();
                    }
                    li.querySelector('button i').animate([{transform:"rotate(0deg)"}],{
                        fill:"forwards",
                        duration:200
                    })
                }
            }
        })
    }

    function hoverShowMenu(addEvent = true){
        const lis = document.querySelectorAll('.nav-item');
        lis.forEach(function(li){
            if(addEvent){
                li.addEventListener('mouseover',showSubMenu);
                li.addEventListener('mouseleave',hideSubMenu);
            }else{
                li.removeEventListener('mouseover',showSubMenu);
                li.removeEventListener('mouseleave',hideSubMenu);
            }
        })
    }
    
    function showSubMenu(e){
        const ulSub = e.target.closest('li').querySelector('ul');
        if(ulSub){
            ulSub.className = "sub fix-small";
        }
    }

    function hideSubMenu(e){
        const ulSub = e.target.closest('li').querySelector('ul');
        if(ulSub){
            ulSub.className = "sub none";
        }
    }

    function clickSmallMenu(){
        const btn = document.querySelector('.small-menu');
        const top = document.querySelector('.top_menu');
        btn.onclick = function(){
            const left = document.querySelector('.root-left');
            const right = document.querySelector('.root-right');
            var showMenu = true;
            if(btn.classList.contains('fix-small')){
                left.classList.remove('fix-small');
                btn.classList.remove('fix-small');
                top.classList.remove('fix-small');
                right.classList.remove('fix-small');
                clickshowMenu();
                hoverShowMenu(false);
                showMenu = true;
            }else{
                showMenu = false;
                left.classList.add('fix-small');
                top.classList.add('fix-small');
                btn.classList.add('fix-small');
                right.classList.add('fix-small');
                hoverShowMenu();
            }
            $.ajax({
                url:"/esystem/change-type-menu",
                method:"POST",
                data:{showMenu}
            });
        }
    }

    function init(){
        var btn = document.querySelector('.small-menu');
        if(btn && btn.classList.contains('fix-small')){
            hoverShowMenu();
        }
    }
    return {
        _:function(){
            init();
            clickshowMenu();
            clickSmallMenu();
        }
    }
})();
window.addEventListener('DOMContentLoaded', (event) => {
    MENU._();
})