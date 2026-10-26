<?php
    
namespace App\Http\Controllers\web\User;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Auth;
    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        // $data = User::latest()->paginate(5);
        $data = User::join('vlist_unit_kerja', 'vlist_unit_kerja.key_id', '=', 'users.key_id_unit_kerja')->whereNotIn('users.id', [1])->paginate(10);
        // $data = User::with('vlist_unit_kerja')->latest()
        // ->where('id','<>', session('auth.id'))
        // ->paginate(5);

        // ::with('roles')
        //     ->where('id','<>', session('auth.id'));

        $Userid = session()->get('auth')['id'];
        $Roles = session()->get('auth')['roles'];

        $userRoles = User::find($Userid)->roles->pluck('name')->first();
        
        $form = array (
            'title' => 'Users Management',
            'sub_title' => 'Pengaturan'
        );
  
        return view('users.index',compact('data', 'form'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

  
    public function showUserDatatable()
    {
         $users = DB::select("select * from users");

            return response()->json([
                'success' => true,
                'message' => 'List Data Post',
                'data' => $users
            ], 200);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
         // var_dump($data); exit;
         $form = array (
            'title' => 'Tambah User Baru',
            'sub_title' => 'Beranda'
        );

        $roles = Role::where('id','<>','3')->pluck('name','name')->all(); 
        $units = DB::table('vlist_unit_kerja')->pluck('nm_unit_kerja','key_id')->all();

        return view('users.create',compact('roles', 'units', 'form'));
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
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['key_id_unit_kerja'] = ((int)$input['unit']) == null ? 0 : (int)$input['unit'];
    
        // var_dump( $input); exit;
        
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $notification = array(
            'message' => 'User created successfully!', 
            'alert_type' => 'success',
        );
    
        return redirect()->route('users.index')
                        // ->with('success','User created successfully');
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
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $userUnit =  DB::table('vlist_unit_kerja')->where('key_id',  $user["key_id_unit_kerja"])->pluck('nm_unit_kerja','key_id')->all();
        $units = DB::table('vlist_unit_kerja')->pluck('nm_unit_kerja','key_id')->all(); 

        $form = array (
            'title' => 'Ubah user baru',
            'sub_title' => 'Beranda'
        );
        return view('users.show',compact('user','roles','userRole','units', 'userUnit','form'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::where('id','<>','3')->pluck('name','name')->all(); 
        $userRole = $user->roles->pluck('name','name')->all();
        // $userRole = Role::where('id','<>','3')->pluck('name','name')->all(); 

        

        $userUnit =  DB::table('vlist_unit_kerja')->where('key_id',  $user["key_id_unit_kerja"])->pluck('nm_unit_kerja','key_id')->all();
        
        // var_dump($userRole); 
        // var_dump($userUnit); 
        // exit;
        $units = DB::table('vlist_unit_kerja')->pluck('nm_unit_kerja','key_id')->all(); 

        $form = array (
            'title' => 'Ubah user baru',
            'sub_title' => 'Beranda'
        );
    
        return view('users.edit',compact('user','roles','userRole','units', 'userUnit','form'));
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        
        $user->assignRole($request->input('roles'));
        $notification = array(
            'message' => 'User updated successfully!', 
            'alert_type' => 'success',
        );

    
        return redirect()->route('users.index')
                        // ->with('success','User updated successfully');
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

        $notification = array(
            'message' => 'User deleted successfully!', 
            'alert_type' => 'success',
        );

        User::find($id)->delete();
        return redirect()->route('users.index')
                        // ->with('success','User deleted successfully');
                        ->with('notification', $notification);
    }
}