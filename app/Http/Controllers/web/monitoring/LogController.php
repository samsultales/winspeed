<?php

namespace App\Http\Controllers\web\monitoring;

use App\Http\Controllers\Controller;
use App\Models\Logger;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Helpers\appHelper;

class LogController extends Controller
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
     */
    public function index()
    {
        //
        $form = array(
            'title' => 'Log History',
            'sub_title' => 'Monitoring'
        );

        $data = session()->get('auth');
        $units = appHelper::options($data['roles']);
        $unit = $units['unit'];
        $unitId = $data['key_id_unit_kerja'];
        $collection = collect(['1', '0']);
        if (request()->ajax()) {
            if ($collection->contains($unitId)) {
                $unit = UnitKerja::query()->where('flag_aktif', '1')->whereIn('key_id', DB::table('trn_file_sound')->pluck('key_id_unit_kerja'))->orderBy('key_id', 'ASC');
            } else {
                $unit = UnitKerja::query()->where('key_id', $unitId)->where('flag_aktif', '1')->whereIn('key_id', DB::table('trn_file_sound')->pluck('key_id_unit_kerja'))->orderBy('key_id', 'ASC');
            }
            return DataTables::of($unit)->make();
        }
        return view('logger.index', compact('form', 'unit', 'unitId'));
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
                $unit = DB::select("select * from mst_unit_kerja where flag_aktif = 1");
            } else {
                $unit = DB::select("select * from mst_unit_kerja where key_id = '" . $ids . "' and flag_aktif = 1");
            }
        } else {
            $unit = UnitKerja::query()->where('key_id', $unitId)->where('flag_aktif', '1')->whereIn('key_id', DB::table('trn_file_sound')->pluck('key_id_unit_kerja'))->orderBy('key_id', 'ASC');
        }
        return DataTables::of($unit)->make();
    }

    public function GetDetaillogHistory(Request $request)
    {
        $thang = $request->input('tgl');
        $id    = $request->input('id');

        // var_dump($thang); exit;

        if ($thang !== null) {
            $query = DB::select("select * from trn_logger where key_id_unit_kerja ='" . $id . "'  and to_char(date_pesan,'dd/mm/yyyy') = '" . $thang . "' order by key_id desc");
        } else {
            $query = DB::select("select * from trn_logger where key_id_unit_kerja ='" . $id . "' order by key_id desc");
        }

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
            $unit = DB::select("select * from mst_unit_kerja where flag_aktif = 1");
        } else {
            $unit = DB::select("select * from mst_unit_kerja where key_id = '" . $ids . "' and flag_aktif = 1");
        }
        return DataTables::of($unit)->make();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Logger $logger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logger $logger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logger $logger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logger $logger)
    {
        //
    }
}
