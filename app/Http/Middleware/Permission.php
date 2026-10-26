<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $role_id = session('auth.role_id');

        var_dump($role_id);
        var_dump(session());
        exit;
        $existmenu = \App\Models\Menu::where('route','like',"%{$path}")->first();
        if (!empty($existmenu)) {
            $row = \App\Models\Role::withCount(['menu' => function($query) use($path) {
                $query->where('route','like',"%{$path}");
            }])
            ->find($role_id);
            if (!$row) abort(403, 'Access denied');//return redirect('404');
            if ($row->menu_count == 0) abort(403, 'Access denied');// return redirect('404');
        }
        return $next($request);
    }
}
