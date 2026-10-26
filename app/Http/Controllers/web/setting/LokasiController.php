<?php

namespace App\Http\Controllers\web\setting;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// use File;
use Illuminate\Support\Facades\File;

class LokasiController extends Controller
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
            'title' => 'Lokasi File',
            'sub_title' => 'Pengaturan'
        );

        
        $lokasi = Lokasi::orderBy('key_id', 'DESC')->paginate(8);
       
        // var_dump($filePath); exit;
        
        foreach ($lokasi as $key => $p) {
            $filePath = storage_path($p->lokasi_file );
            $file_size = 0; 
            $file_sizes = 0; 
            $prosen = 0;
            $file_sizes_ori = 0;
            $ds = disk_total_space("/");
            if (file_exists( $filePath)) { 
                foreach (File::allFiles( $filePath  ) as $file) {
                    $file_size += $file->getSize();
                }
                $file_sizes = number_format($file_size / 1048576, 2);
                $file_sizes_ori = $file_size;
                $prosen = $file_sizes_ori / $ds * 100 ;
                // $prosen =$file_size / $file_sizes_ori * 100; 
            }   
            
            $files[$key]['file_sizes'] =  $file_sizes;
            $files[$key]['total_sizes'] =  number_format($ds);
            $files[$key]['file_sizes_ori'] =  $file_sizes_ori;
            $files[$key]['total_sizes_ori'] =  $ds;
            $files[$key]['prosen'] =  $prosen.'%';
        }

        // var_dump($files); exit;

        return view('lokasi.index', compact('lokasi', 'form', 'files'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $form = array(
            'title' => 'Add New Roles',
            'sub_title' => 'Pengaturan'
        );

        $lokasi = Lokasi::get();
        return view('lokasi.create', compact('lokasi', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $session = $request->session()->all();

        $input = $request->all();
        $input['flag_aktif'] = '1';
        $input['created_at'] = now();
        $input['created_by'] = $session['auth']['name'];

        DB::statement('update trn_lokasi_file set flag_aktif = 0');

        // var_dump( $input); exit;

        $lokasi = Lokasi::create($input);
        $mypath = '';
        $result = '';

        // var_dump(storage_path($input['lokasi_file'])); exit;
        $mypath = storage_path($input['lokasi_file']);
        
        if (! File::exists($mypath)) {
            // File::makeDirectory($mypath);
            $result = File::makeDirectory($mypath, 0775, true);
        }

        $notification = array(
            'message' => 'Lokasi File created successfully!',
            'alert_type' => 'success',
            'lokasi' => $lokasi,
        );

        return redirect()->route('lokasi.index')
            ->with('notification', $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lokasi $lokasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lokasi $lokasi)
    {
        //
    }


    public function testSize()
    {
        $file_size = 0;

        $filePath = storage_path();

        foreach (File::allFiles($filePath) as $file) {
            $file_size += $file->getSize();
        }
        // echo number_format($file_size / 1048576, 2);
        $ds = disk_total_space("/");

        echo number_format($ds);
    }
}
