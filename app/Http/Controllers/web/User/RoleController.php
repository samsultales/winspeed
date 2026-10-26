<?php

namespace App\Http\Controllers\web\User;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {

        $form = array(
            'title' => 'Roles Management',
            'sub_title' => 'Pengaturan'
        );

        // $roles = Role::orderBy('id', 'ASC')->whereNotIn('roles.id', [1])->paginate(5);
        $roles = Role::orderBy('id', 'ASC')->whereNotIn('roles.id', [3])->paginate(8);

        return view('roles.index', compact('roles', 'form'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {

        $form = array(
            'title' => 'Add New Roles',
            'sub_title' => 'Pengaturan'
        );


        $permission = Permission::get();
        return view('roles.create', compact('permission', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            $request->input('permission')
        );

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);

        $notification = array(
            'message' => 'Roles created successfully!',
            'alert_type' => 'success',
        );

        return redirect()->route('roles.index')
            ->with('notification', $notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        $form = array(
            'title' => 'Add New Roles',
            'sub_title' => 'Pengaturan'
        );
        return view('roles.show', compact('role', 'rolePermissions', 'form'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $form = array(
            'title' => 'Edit Roles',
            'sub_title' => 'Pengaturan'
        );

        return view('roles.edit', compact('role', 'permission', 'rolePermissions', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            $request->input('permission')
        );

        $role->syncPermissions($permissionsID);

        $notification = array(
            'message' => 'Roles Updated successfully!',
            'alert_type' => 'success',
        );
        return redirect()->route('roles.index')
            ->with('notification', $notification);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {

        $item = Role::withCount('users')->find($id);

        // var_dump($item->users_count); 

        $notification = array(
            'message' => 'Roles in Use , Cannot deleted !',
            'alert_type' => 'error',
        );

        if ($item->users_count == 0) {

            DB::table("roles")->where('id', $id)->delete();

            $notification = array(
                'message' => 'Roles deleted successfully!',
                'alert_type' => 'success',
            );
        }


        return redirect()->route('roles.index')
            ->with('notification', $notification);
    }


    public function GenRolePermision()
    {
        DB::statement('TRUNCATE TABLE roles, permissions, model_has_permissions , model_has_roles , role_has_permissions RESTART IDENTITY');
        // create permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        foreach ($permissions as $permission) {
            // Permission::create(['name' => $permission]);
            Permission::firstOrCreate(['name' => $permission]);
        }
        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create([
            'name' => 'Administrator',
        ]);

        $role->givePermissionTo(Permission::all());

        $role = Role::create([
            'name' => 'Kantor Pusat',
        ]);

        $role->givePermissionTo([
            'role-list',
            'role-create',
            'role-edit',
        ]);

        $role = Role::create([
            'name' => 'Installer',
        ]);

        $role->givePermissionTo('role-list');

        $user1 = User::find(1);
        $user1->assignRole('Administrator');

        $user2 = User::find(2);
        $user2->assignRole('Installer');

        $user3 = User::find(3);
        $user3->assignRole('Kantor Pusat');
    }
}
