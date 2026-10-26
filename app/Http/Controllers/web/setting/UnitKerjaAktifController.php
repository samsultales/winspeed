<?php

namespace App\Http\Controllers\web\setting;

use App\Http\Controllers\Controller;
use App\Models\UnitKerjaAktir;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Helpers\appHelper;
use Yajra\DataTables\Facades\DataTables;

class UnitKerjaAktifController extends Controller
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

    public function index(Request $request): View
    {
        //
        $form = array(
            'title' => 'Server Unit Kerja',
            'sub_title' => 'Monitoring'
        );

        // $units = UnitKerjaAktir::orderBy('key_id', 'DESC')->paginate(8);
        /*
        Country::join('state', 'state.country_id', '=', 'country.country_id')
              		->join('city', 'city.state_id', '=', 'state.state_id')
              		->get(['country.country_name', 'state.state_name', 'city.city_name']);
        */
        
        $data = session()->get('auth');
        $units = appHelper::options($data['roles']);
        $unit = $units['unit'];
        $unitId = $data['key_id_unit_kerja'];
        $collection = collect(['1', '0']);
        // $units =  DB::table('trn_unit_kerja_aktif')
        // ->join('mst_unit_kerja', 'trn_unit_kerja_aktif.key_id_unit_kerja', '=', 'mst_unit_kerja.key_id')
        // // ->join('departments', 'users.dpt_id', '=', 'departments.id')
        // ->select('trn_unit_kerja_aktif.*', 'mst_unit_kerja.nm_unit_kerja')
        // ->paginate(8);

        // return view('server.index', compact('units', 'form'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
        return view('server.index', compact('form', 'unit', 'unitId'));
    }

    public function get_datatable(Request $request)
    {
        $data = session()->get('auth');
        $units = appHelper::options($data['roles']);
        $unit = $units['unit'];
        $unitId = $data['key_id_unit_kerja'];
        $collection = collect(['1', '0']);
        $ids = $request->input('unitid');
        $tgl = $request->input('tgl');


        if ($collection->contains($unitId)) {
            // $unit = UnitKerja::query()->where('flag_aktif', '1')->whereIn('key_id', DB::table('trn_file_sound')->pluck('key_id_unit_kerja'))->orderBy('key_id', 'ASC');
            if ($ids == '0') {
                // $unit = DB::select("select * from mst_unit_kerja where flag_aktif = 1");
                //  $unit =  DB::table('trn_unit_kerja_aktif')
                //             ->join('mst_unit_kerja', 'trn_unit_kerja_aktif.key_id_unit_kerja', '=', 'mst_unit_kerja.key_id') 
                //             ->select('trn_unit_kerja_aktif.*', 'mst_unit_kerja.nm_unit_kerja', ' null pwd');
                 $unit = DB::select("select distinct a.*, b.nm_unit_kerja, replace(password_local, password_local, '*******') pwd
                                        from trn_unit_kerja_aktif a
                                        join mst_unit_kerja b on b.key_id = a.key_id_unit_kerja
                                        left join trn_check c on c.key_id_unit_kerja = b.key_id
                                        where  b.flag_aktif = 1 and a.user_local is not null  and a.password_local is not null
                                        order by 2 asc");
            } else {
                $unit = DB::select("select distinct a.*, b.nm_unit_kerja, replace(password_local, password_local, '*******') pwd
                                        from trn_unit_kerja_aktif a
                                        join mst_unit_kerja b on b.key_id = a.key_id_unit_kerja
                                        left join trn_check c on c.key_id_unit_kerja = b.key_id
                                        where b.key_id = '" . $ids . "' and b.flag_aktif = 1 and a.user_local is not null  and a.password_local is not null
                                        order by 2 asc");
            }
        } else {
            // $unit = UnitKerja::query()->where('key_id', $unitId)->where('flag_aktif', '1')->whereIn('key_id', DB::table('trn_file_sound')->pluck('key_id_unit_kerja'))->orderBy('key_id', 'ASC');
            $unit = DB::select("select distinct a.*, b.nm_unit_kerja, replace(password_local, password_local, '*******') pwd
                                        from trn_unit_kerja_aktif a
                                        join mst_unit_kerja b on b.key_id = a.key_id_unit_kerja
                                        left join trn_check c on c.key_id_unit_kerja = b.key_id
                                        where b.key_id = '" . $ids . "' and b.flag_aktif = 1 and a.user_local is not null  and a.password_local is not null
                                        order by 2 asc");
        }
        return DataTables::of($unit)->make();
    }


    public function GetDetailUnit(Request $request)
    {
        $thang = null; //$request->input('tgl');
        $id    = $request->input('id');

        // var_dump($thang);
        // $query = DB::select("select * from trn_check where key_id_unit_kerja ='" . $id . "' order by key_id desc");
        if ($thang !== null) {
            $query = DB::select("select * from trn_check where key_id_unit_kerja ='" . $id . "'  and to_char(date_pesan,'dd/mm/yyyy') = '" . $thang . "' order by key_id desc");
        } else {
            $query = DB::select("select * from trn_check where key_id_unit_kerja ='" . $id . "' order by key_id desc");
        }

        // var_dump("select * from trn_check where key_id_unit_kerja ='" . $id . "' order by key_id desc");
        // var_dump( $thang);
        // var_dump( $id);
        // var_dump( $query); exit;

        return Datatables::of($query)->make(true);
    }

    public function refreshgrid(Request $request)
    {
        $data = session()->get('auth');
        $units = appHelper::options($data['roles']);
        $unit = $units['unit'];
        $unitId = $data['key_id_unit_kerja'];
        $ids = $request->input('unitids');
        $tgl = $request->input('tgls');

        // var_dump($ids); exit;
        if ($ids == '0') {
            // $unit = DB::select("select * from mst_unit_kerja where flag_aktif = 1");
            $unit = DB::select("select distinct a.*, b.nm_unit_kerja, replace(password_local, password_local, '*******') pwd
                                        from trn_unit_kerja_aktif a
                                        join mst_unit_kerja b on b.key_id = a.key_id_unit_kerja
                                        left join trn_check c on c.key_id_unit_kerja = b.key_id
                                        where  b.flag_aktif = 1 and a.user_local is not null  and a.password_local is not null
                                        order by 1 desc");
        } else {
            // $unit = DB::select("select * from mst_unit_kerja where key_id = '" . $ids . "' and flag_aktif = 1");
            $unit = DB::select("select distinct a.*, b.nm_unit_kerja, replace(password_local, password_local, '*******') pwd
                                        from trn_unit_kerja_aktif a
                                        join mst_unit_kerja b on b.key_id = a.key_id_unit_kerja
                                        left join trn_check c on c.key_id_unit_kerja = b.key_id
                                        where b.key_id = '" . $ids . "' and b.flag_aktif = 1 and a.user_local is not null  and a.password_local is not null");
        }
        return DataTables::of($unit)->make();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         //
         $form = array(
            'title' => 'New Unit Kerja Aktif',
            'sub_title' => 'Pengaturan'
        );


        $units = UnitKerja::get()->pluck('nm_unit_kerja','key_id')->all();
        // $unitKerja = DB::table('mst_unit_kerja')->pluck('nm_unit_kerja','kd_unit_kerja')->all();
        $regional = DB::table('mst_wilayah')->pluck('wilayah','kd_wilayah')->all();
        $wilayah = DB::table('vlist_unit_kerja')->pluck('regional','kd_regional')->all();

        return view('server.create', compact('units', 'regional', 'wilayah','form'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         //
         $input = $request->all();
         //  var_dump($input); exit;         
         $unit = UnitKerjaAktir::create($input);
         
 
         $notification = array(
             'message' => 'Unit Kerja created successfully!', 
             'alert_type' => 'success',
             "data" => $unit
         );
     
         return redirect()->route('server.index') 
                         ->with('notification', $notification);
     }

    /**
     * Display the specified resource.
     */
    public function show(UnitKerjaAktir $unitKerjaAktir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKerjaAktir $unitKerjaAktir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitKerjaAktir $unitKerjaAktir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        //
         $notification = array(
            'message' => 'Unit Kerja Aktif deleted successfully!', 
            'alert_type' => 'success',
        );
        
        $unit = UnitKerjaAktir::find($id); 

        UnitKerjaAktir::find($id)->delete();
        return redirect()->route('server.index') 
                        ->with('notification', $notification);
    }
}
