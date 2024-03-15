<?php

namespace HidePartialMenu;

use App\Services\Hook;
use App\Services\Facades\Option;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConfigController extends Controller
{
    public function render()
    {
        Hook::addScriptFileToPage(plugin('hide-partial-menu')->assets('config.js'));

        return view('HidePartialMenu::config');
    }

    public function list()
    {
        $sideMenuConfig = Option::get('hide-partial-menu.side_menu','{}');
        $sideDisabledMenuConfig = Option::get('hide-partial-menu.disable_side_menu','{}');

        $menuData = json_decode($sideMenuConfig,true);
        $disabledmenuData = json_decode($sideDisabledMenuConfig,true);

        $translates = [];
        foreach($menuData as $type => $items){
            $translatedItems = [];
            foreach($items as $item){
                $translates[$type][$item] = trans($item);
            }

        }

        $newConfig = array(
            "menuData" => $menuData,
            "disabledMenuData" => $disabledmenuData,
            "translatesData" => $translates,
        );

        return($newConfig);
    }

    public function disabledlist()
    {
        $sideMenuConfig = Option::get('hide-partial-menu.disable_side_menu','{}');
        return($sideMenuConfig);
    }

    public function changemenu(Request $request)
    {
        $type = $request->input('type');
        $value = $request->input('value');
        $disabled = $request->input('disabled');
        $sideDisabledMenuConfig = json_decode(Option::get('hide-partial-menu.disable_side_menu','{}'),true);
        if(!isset($sideDisabledMenuConfig[$type])){
            $sideDisabledMenuConfig[$type] = [];
        }

        // 如果 disabled = true, 添加项目
        if($disabled){
            //不在数组里
            if(!in_array($value,$sideDisabledMenuConfig[$type])){
                $sideDisabledMenuConfig[$type][] = $value;
            }
        }else{
            $sideDisabledMenuConfig[$type] = array_diff($sideDisabledMenuConfig[$type], [$value]);
        }

        //保存更新后的$sideDisabledMenuConfig
        Option::set('hide-partial-menu.disable_side_menu',json_encode($sideDisabledMenuConfig));
        //返回ok
        return response()->json(['message' => 'OK'], 200);
    }
}
