<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\RedirectResponse;
use UnitEnum;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //
        $form = array(
            'title' => 'Unit Kerja',
            'sub_title' => 'Pengaturan'
        );

        // $units = UnitKerja::orderBy('key_id', 'ASC')->paginate(8);
        $units = UnitKerja::orderBy('key_id', 'ASC')->get();

        return view('unit.index', compact('units', 'form'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $form = array(
            'title' => 'New Unit Kerja',
            'sub_title' => 'Pengaturan'
        );


        $units = UnitKerja::get();
        $regional = DB::table('mst_wilayah')->pluck('wilayah','kd_wilayah')->all();
        $wilayah = DB::table('vlist_unit_kerja')->pluck('regional','kd_regional')->all();

        return view('unit.create', compact('units', 'regional', 'wilayah','form'));
    }

    public function getListUnitKerja()
    {
        $chiper = bin2hex(random_bytes(16 / 2));

        $find = UnitKerja::where('key', $chiper);
        if (! $find) {
            return $chiper;
        } else {
            $chiper = bin2hex(random_bytes(16 / 2));
        }
        return $chiper;
    }

    public function getRegionalList(Request $request)
    {
        $id = $request['id']; 

        // $sql = "select * from trn_file_sound where key_id_unit_kerja = $id and to_char(date_intg, 'dd-mm-yyyy') = '" . $tgl . "'";
        $sql = "select * from mst_regional where kd_wilayah = $id ";
        $regionallist =  DB::select($sql);

        if (!$regionallist) {
            $data[] = array(
                'regional' => 'Data tidak Ditemukan',
                'kd_regional' => '0',
            );

            return $data;
        }

        return  $regionallist;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $input['key'] = $this->getListUnitKerja();

        // var_dump($input); exit;
        // $input['key_id_unit_kerja'] = ((int)$input['unit']) == null ? 0 : (int)$input['unit'];
    
        // var_dump( $input); exit;
        
        $user = UnitKerja::create($input);
        // $user->assignRole($request->input('roles'));

        $notification = array(
            'message' => 'Unit Kerja created successfully!', 
            'alert_type' => 'success',
        );
    
        return redirect()->route('units.index')
                        // ->with('success','User created successfully');
                        ->with('notification', $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitKerja $unitKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        //
        $units = UnitKerja::find($id); 
        $regional = DB::table('mst_wilayah')->pluck('wilayah','kd_wilayah')->all();
        $wilayah = DB::table('mst_regional')->pluck('regional','kd_regional')->all();

        $owilayah  =  DB::table('mst_wilayah')->where('kd_wilayah',  $units["kd_wilayah"])->pluck('wilayah','kd_wilayah')->all();
        $oregional =  DB::table('mst_regional')->where('kd_regional',  $units["kd_regional"])->pluck('regional','kd_regional')->all();

        $form = array(
            'title' => 'Edit Unit Kerja',
            'sub_title' => 'Pengaturan'
        );

        return view('unit.edit', compact('units', 'regional','owilayah', 'oregional', 'wilayah','form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
        $input = $request->all();
        $unit = UnitKerja::find($id);
        $unit->update($input);
      
        $notification = array(
            'message' => 'User updated successfully!', 
            'alert_type' => 'success',
        );

    
        return redirect()->route('units.index')
                        // ->with('success','User updated successfully');
                        ->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {

        // var_dump('delete'); exit;

        $count = DB::table('mst_unit_kerja')->where('key_id', $id)->where('flag_aktif', '1')->count();

        $notification = array(
            'message' => 'Unit Kerja is Active , Cannot deleted !',
            'alert_type' => 'error',
        );

        if ($count  == 0) {
            $notification = array(
                'message' => 'Unit Kerja deleted successfully!', 
                'alert_type' => 'success',
            );
            
            $unit = UnitKerja::find($id); 
            UnitKerja::find($id)->delete();
        }
       
        return redirect()->route('units.index')
                        // ->with('success','User deleted successfully');
                        ->with('notification', $notification);
    }

    

    
}
