<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function sideMenu()
    {
        // $role_id = session('auth.role_id') ?? 0;
        $role_id = session('auth.roles')[0]['id'] ?? 0;
        Cache::forget('sidemenu_'.$role_id);
        $treemenu = Cache::rememberForever('sidemenu_'.$role_id, function () use ($role_id) {
            $rows = $this->join('role_menu', 'role_menu.menu_id', '=', 'id')
                ->where('role_id', '=', $role_id)
                ->where('is_active', 1)->get();
            $treemenu = '';
            $this->treeSideMenu($treemenu, $rows);
            return $treemenu;
        });
        
        return $treemenu;
    }
    
    public function treeSideMenu(&$return, $array, $pid=0, $level=0)
    {   
        $filtered = $array->filter(function ($value, $key) use($pid) {
            if ($value->parent_id==$pid) return $value;
        });

       
        foreach ($filtered->sortBy('sequence') as $item) {
            
            $nextlevel = $level+1;
            $children = '';
            $this->treeSideMenu($children, $array, $item->id, $nextlevel);
            $url = (!empty($children)) ? 'javascript:;' : url($item->url.'?mid='.$item->id);
            
            $li = '';
            if (empty($item->icon)) $item->icon = 'bx bx-radio-circle';
            if (empty($children)) {
                if (empty($item->route)) $item->route = '404';
                $li .= '<li class="nav-item">
                        <a href="'.url($item->route).'" class="nav-link">
                            <i class="'. $item->icon.'"></i>
                            <span>&nbsp;&nbsp;'.$item->name.'</span>
                        </a>
                    </li>';
            } else {
                $li .= '<li class="nav-item">
                <a href="javascript: void(0);" class="nav-link">
                    <i class="'. $item->icon.'"></i>
                    <span>&nbsp;&nbsp;'.$item->name.'</span>
                    <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                    '.$children.'
                </ul>
            </li>';
            }
            $return .= $li;
        }
    }

     public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id');
    }
}
