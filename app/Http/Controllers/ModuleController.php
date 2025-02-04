<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Auth;

class ModuleController extends Controller
{

    public function __construct(){
        $this->menu = 'module';
    }

    public function index(){
        $user = Auth::user();
        return view('module.sql_home', [
            'user' => $user,
            'menu' => $this->menu,
        ]);
    }

    public function getMenu(){
        $menuArr = $this->getMenuParents();
        $menuBuild = $this->menuBuilder($menuArr);

        $formattedMenu = [];
        $response = [
            'success' => false,
            'message' => 'UNKOWN ERROR',
            'data' => [],
        ];
        $response['success'] = true;
        $response['message'] = 'Success';
        $response['data'] = $menuBuild;
        return response()->json($response);
    }

    private function menuBuilder($array, $indent = ''){
        $menuArray = $array;
        $stringArray = '';
        foreach($menuArray as $arr){
            if($arr['menu_url'] == ''){
                $stringArray .= '<a href="'. $arr['menu_url'] .'" class="list-group-item py-2 ripple" aria-current="true">
                                    <i class="'. $arr['menu_icon'] .' fa-fw me-3"></i>
                                    <span>'. $indent . $arr['menu_name'] .'</span>
                                </a>';
            }
            else{
                $stringArray .= '<a href="'. $arr['menu_url'] .'" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
                                    <i class="'. $arr['menu_icon'] .' fa-fw me-3"></i>
                                    <span>'. $indent . $arr['menu_name'] .'</span>
                                </a>';
            }
            if(count($arr['children']) > 0){
                $tempChild = $this->menuBuilder($arr['children'], $indent . '&nbsp;&nbsp;&nbsp;&nbsp;');
                $stringArray .= $tempChild;
            }
        }
        return $stringArray;
    }
    
    public function getMenuParents($id = null) {
        $parentList = [];
    
        if($id == null){
            $menus = Menu::where('menu_parent_id', null)->get();
            foreach($menus as $menu){
                $parentList[] = $this->getMenuParents($menu->id);
            }
        } else {
            $menuParent = Menu::where('id', $id)->first();
            $menusChild = Menu::where('menu_parent_id', $id)->get();
    
            $children = [];
            foreach($menusChild as $child){
                $childList = $this->getMenuParents($child->id);
                if(!empty($childList)){
                    $children[] = $childList;
                }
            }
    
            if($menuParent != null){
                $parentList = [
                    'id' => $menuParent->id, 
                    'menu_name' => $menuParent->menu_name, 
                    'menu_url' => $menuParent->menu_url,
                    'menu_icon' => $menuParent->menu_icon,
                    'children' => $children,
                ];
            }
        }
    
        return $parentList;
    }

    // public function sqlHome(){
    //     $user = Auth::user();
    //     return view('module.home', [
    //         'user' => $user,
    //         'menu' => 'module',
    //     ]);
    // }
    
    // public function sqlIntro(){
    //     $user = Auth::user();
    //     return view('module.intro', [
    //         'user' => $user,
    //         'menu' => 'module',
    //     ]);
    // }

    public function sqlModule($sqlModule){
        $user = Auth::user();
        if($sqlModule == ''){
            $sqlModule = 'sql_home';
        }
        return view('module.'.$sqlModule, [
            'user' => $user,
            'menu' => 'module',
        ]);
    }
    
    

}
