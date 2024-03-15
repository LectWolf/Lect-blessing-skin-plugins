<?php

namespace HidePartialMenu;
use App\Services\Hook;
use App\Services\Facades\Option;
use Blessing\Filter;
use Illuminate\Support\Facades\Route;

return function (Filter $filter) {
    $filter->add('side_menu', function ($menu, $type) {
        $sideMenuConfig = json_decode(Option::get('hide-partial-menu.side_menu','{}'),true);
        $sideDisabledMenuConfig = json_decode(Option::get('hide-partial-menu.disable_side_menu','{}'),true);

        //保存菜单数据
        if(!isset($sideMenuConfig[$type])){
            $sideMenuConfig[$type] = [];
            foreach($menu as $item){
                $sideMenuConfig[$type][] = $item['title'];
            }
            Option::set('hide-partial-menu.side_menu',json_encode($sideMenuConfig));
        }

        //隐藏菜单
        if(isset($sideDisabledMenuConfig[$type])){
            foreach($menu as $key => $item){
                if(in_array($item['title'],$sideDisabledMenuConfig[$type])){
                    unset($menu[$key]);
                }
            }
        }
        return $menu;
    });


    Hook::addRoute(function () {
        Route::namespace('HidePartialMenu')
            ->prefix('admin/plugins/hide-partial-menu/api')
            ->middleware(['web', 'auth', 'role:admin'])
            ->group(function () {
                Route::get('getmenu', 'ConfigController@list');
                Route::post('changemenu', 'ConfigController@changemenu');
            });
    });
};
