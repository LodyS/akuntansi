<?php

namespace App;

use Cache;
use Laratrust\Laratrust;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ShowMenu2
{
    protected $items;

    public function __construct()
    {
    }

    public static function menu()
    {
        $menu = new ShowMenu2();
        return $menu;
    }

    public function render()
    {
        $refs = array();
        $list = array();
        $html = "";

        $allMenu = Menu::all();
        foreach ($allMenu as $menu) {
            if (\Laratrust::can($menu->permission->name)) {
                $thisref = &$refs[$menu->id];
                $thisref['parent_id'] = $menu->parent_id;
                $thisref['name'] = $menu->name;
                $thisref['url'] = $menu->url;
                $thisref['icon'] = $menu->icon;
                $thisref['parent_status'] = $menu->parent_status;
                $thisref['ordinal'] = $menu->ordinal;

                if ($menu->parent_id == null) {
                    $list[$menu->id] = &$thisref;
                } else {
                    $refs[$menu->parent_id]['children'][$menu->id] = &$thisref;
                }
            }
        }
        $html .= $this->create_list($list);
        return $html;
    }

    public function create_list($listMenu)
    {
        $html = "";

        foreach ($listMenu as $menuArray) {
            $menu = (object) $menuArray;

            $isActive = "";
            if (isset($menu->children)) {
                $isActive = $this->isChildActive($menu->children, 'active');
            } else {
                $RequestUrlSegment = \Request::segment(1);
                $MenuUrlSegment = explode('/', $menu->url)[0];
                $isActive = $MenuUrlSegment == $RequestUrlSegment ? 'active' : '';
            }

            $hasSub = ($menu->parent_status == 'Y') ? 'has-sub' : '';
            $menuUrl = ($menu->parent_status == 'Y') ? 'javascript:;' : url($menu->url);

            $html .= '<li class="site-menu-item ' . $hasSub . ' ' . $isActive . '">';
            $html .= '<a href="' . $menuUrl . '" data-content="' . $menu->name . '" data-trigger="hover" data-toggle="popover" data-original-title="" tabindex="0" title=""  data-delay=\'{"show":"1500", "hide":"0"}\'>';

            if ($menu->ordinal == 1) {
                $html .= '<i class="site-menu-icon ' . $menu->icon . '" aria-hidden="true"></i>';
            }
            $html .= '<span class="site-menu-title">' . $menu->name . '</span>';
            if ($menu->parent_status == 'Y') {
                $html .= '<span class="site-menu-arrow"></span>';
            }
            $html .= '</a>';
            if (isset($menu->children)) {
                $html .= '<ul class="site-menu-sub">';
                $html .= $this->create_list($menu->children);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }


    public function isChildActive($childsArr, $is)
    {
        $hasil = '';
        foreach ($childsArr as $key => $menu) {
            if (array_key_exists('children', $menu)) {
                $hasil = $this->isChildActive($menu['children'], $is);
            } else {
                $RequestUrlSegment = \Request::segment(1);
                $MenuUrlSegment = explode('/', $menu['url'])[0];
                if ($MenuUrlSegment == $RequestUrlSegment) {
                    $hasil = 'active open';
                }
            }
        }

        return $hasil;
    }



    public function setCache()
    {
        Cache::put('user_menu_' . Auth::id(), $this->render(), 120);
    }

    public function getCache()
    {
        $value = Cache::remember('user_menu_' . Auth::id(), 120, function () {
            return $this->render();
        });

        return $value;
    }

    public function clearCache()
    {
        Cache::forget('user_menu_' . Auth::id());
    }
}
