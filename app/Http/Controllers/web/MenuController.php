<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;
// use Illuminate\Container\Attributes\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Menu;
use Auth;

class MenuController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $form = array(
            'title' => 'Menu Management',
            'sub_title' => 'Pengaturan'
        );
 
        $icon = DB::table('icon')->pluck('icon_name','icon_name')->all();

        return view('menu.index', compact('form', 'icon'));
    }

    public function data(Request $request)
    {
        $model = new Menu();
        $menurows = $model->all();
        if ($request->root == 1) {
            $treemenu = [];
            $item = [];
            $item['title'] = 'Root';
            $item['key'] = '';
            $item['expanded'] = true;
            $item['folder'] = true;
            $item['children'] = $this->_treemenu($menurows);
            $item['level'] = 0;
            $item['text'] = 'Root';
            $treemenu[] = $item;
        } else {
            $treemenu = $this->_treemenu($menurows);
        }
        return response()->json($treemenu);
    }

    private function _treemenu($arraymenu, $parent_id = 0, $level = 1)
    {
        $arrayresult = [];
        $filtered = $arraymenu->where('parent_id', $parent_id)->sortBy('sequence')->toArray();
        foreach ($filtered as $row) {
            $hasChildren = $arraymenu->where('parent_id', $row['id'])->count() > 0 ? true : false;
            $item = [];
            if (empty($row['icon'])) {
                if ($hasChildren) {
                    $icon = 'bx bx-folder';
                } else {
                    $icon = 'bx bx-radio-circle';
                }
            } else {
                $icon = $row['icon'];
            }
            $icon = '<i class="' . $icon . '"></i>&nbsp;';
            $item['title'] = $row['name'];
            $item['key'] = $row['id'];
            $item['expanded'] = $hasChildren;
            $item['folder'] = $hasChildren;
            $item['icon'] = $row['icon'];
            $item['route'] = $row['route'];
            $item['sequence'] = $row['sequence'];
            $item['is_active'] = $row['is_active'];
            $item['is_default'] = $row['is_default'];
            $item['level'] = $level;
            // $item['text'] = $row['name'];
            if ($hasChildren) {
                $item['children'] = $this->_treemenu($arraymenu, $row['id'], $level + 1);
            }
            $arrayresult[] = $item;
        }
        return $arrayresult;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //
        $req = $request->all();
        $permissions = collect($request->permissions)->pluck('permission');

        //debug($permissions); exit;
        $vrules = [
            'name' => 'required',
            // 'route' => 'required',
        ];

        $vmessages = [
            'name.required' => 'Nama Menu harus diisi.',
            // 'route.required' => 'URL Menu harus diisi.',
        ];
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                // $this->output->message = $error;
                DB::rollBack();
                // return response()->json($this->output);
                return response()->json([
                    'success' => false,
                    'message' => $error,
                ], 200);
            }

            $data = [
                'name' => $request->name,
                'route' => $request->route,
                'parent_id' => $request->parent_id ?? 0,
                'icon' => $request->icon,
                'sequence' => $request->sequence ?? 1,
                'is_default' => $request->is_active ? 1 : 0,
                'is_active' => $request->is_active ? 1 : 0
            ];
            $model = new Menu();
            if (empty($request->id)) {
                $data['created_by'] = Auth::user()->username;
                $row = $model->create($data);
            } else {
                $data['updated_by'] = Auth::user()->username;
                $row = $model->where('id', '=', $request->id)->update($data);
            }

            //

            DB::commit();
            
            $output = array(
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $row,
            );
        } catch (\Exception $e) {
            // $this->output->success = false;
            // $this->output->message = $e->getMessage();
            $output = array(
                'success' => false,
                'message' => $e->getMessage(),
            );
            DB::rollBack();
        }
        return response()->json($output, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DB::beginTransaction();
        try {
            
            $model = new Menu();
            $model->where('id','=',$id)->delete();
            $model->where('parent_id','=',$id)->update(['parent_id'=>0]);
            DB::commit();
            $output = array(
                'success' => true,
                'message' => 'Data berhasil dihapus.', 
            );
        } catch (\Exception $e) {
            $output = array(
                'success' => false,
                'message' => $e->getMessage(), 
            );
            DB::rollBack();
        }
        return response()->json($output);
    }
}
