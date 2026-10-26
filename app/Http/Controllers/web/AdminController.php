<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Helpers\appHelper;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon;
use Illuminate\View\View;
use App\Events\AlatRusakNotifikasi;

class AdminController extends Controller
{
    //
    public function index(Request $request)
    {
        if (Auth::check()) {
            $data = $request->session()->all();

            // $session = json_encode($data['auth']);
            $session = json_encode($data['auth']);

            $units = appHelper::options($data['auth']['roles']);

            $notifs = DB::select('select * from notifikasi where flag = true');
            $count = DB::table('notifikasi')->where('flag', 'true')->count();

            $form = array(
                'title' => 'Playback Mode',
                'sub_title' => 'Beranda'
            );

            $unit = $units['unit'];

            $count = DB::table('notifikasi')->where('flag', 'true')->count();
            $users = DB::select("select * from notifikasi where flag = true order by coalesce(updated_at, created_at) desc NULLS LAST limit 5 ");
            $unitaktif = DB::select("select key_id from vlist_unit_kerja_aktif order by 1 asc limit 1");

            if ($count > 0) {
                $message = array(
                    'success' => true,
                    'message' => 'Ada Alat (Radio) yang belum mengirimkan rekaman data percakapan.',
                    'data' => $users,
                    'count' => $count,
                );

                // event(new AlatRusakNotifikasi($message));
            }


            if ($data['auth']['key_id_unit_kerja'] == '0') {
                $unitId = $unitaktif[0]->key_id;
            } else {
                $unitId = $data['auth']['key_id_unit_kerja'];
            }


            return view('dashboard', compact('session', 'form', 'unit', 'notifs', 'count', 'unitId'));
        } else {
            return view('signin');
        }
    }

    public function getSoundfile(Request $request)
    {
        $id = $request['id'];
        $tgl = $request['tgl'];
        // startd: _startd,
        //         endd: _endd,
        $startd = $request['startd'];
        $endd = $request['endd'];

        

        // $sql = "select * from trn_file_sound where key_id_unit_kerja = $id and to_char(date_intg, 'dd-mm-yyyy') = '" . $tgl . "'";
        // $sql = "select * from trn_file_sound where key_id_unit_kerja = $id and to_char(created_file, 'dd/mm/yyyy') = '" . $tgl . "'";
        $sql = "select * from trn_file_sound where key_id_unit_kerja = $id and to_char(created_file, 'yyyymmddHH24MI') between '" . $startd . "' and '".$endd."' order by key_id desc";
        $soundlist =  DB::select($sql);

        // var_dump($sql); exit;

        if (!$soundlist) {
            $data[] = array(
                'nm_file_sound' => 'Data tidak Ditemukan',
                'key_id' => '0',
                'start_time' => '00:00',
                'end_time' => '00:00'
            );

            return $data;
        }

        return  $soundlist;
    }

    public function playSoundLokasi(Request $request)
    {
        $id = $request['id'];
        $data = DB::select(" select a.* from trn_file_sound a where a.key_id = $id");
        $fileloc = DB::select(" select a.* from trn_lokasi_file a where a.flag_aktif = '1'");

        $unitkerja = DB::table("mst_unit_kerja")
            ->where([
                'key_id' => $request->id
            ])
            ->select('key')
            ->first();

        if (!count($data) == 0 && !count($fileloc) == 0) {
            $filePath = storage_path($fileloc[0]->lokasi_file . $data[0]->nm_file_sound);
            $outputfile = storage_path($fileloc[0]->lokasi_file . str_replace('.mp3', '', $data[0]->nm_file_sound) . '_decrypted.mp3');
            $dekrip = appHelper::decryptFileAES($unitkerja->key, $filePath, $outputfile);

            return response()->file($outputfile, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }
    }

    public function playSoundData(Request $request)
    {
        $id = $request['id'];
        $data = DB::select(" select a.* from trn_file_sound a where a.key_id = $id");
        $fileloc = DB::select(" select a.* from trn_lokasi_file a where a.flag_aktif = '1'");

        $unitkerja = DB::table("mst_unit_kerja")
            ->where([
                'key_id' => $data[0]->key_id_unit_kerja
            ])
            ->select('key')
            ->first();

        if (!count($data) == 0 && !count($fileloc) == 0) {
            // $filePath = storage_path($fileloc[0]->lokasi_file . $data[0]->nm_file_sound);
            $filePath = storage_path($data[0]->path .'/'.$data[0]->nm_file_sound);
            $outputfile = storage_path($fileloc[0]->lokasi_file . str_replace('.mp3', '', $data[0]->nm_file_sound) . '_decrypted.mp3');
            $dekrip = appHelper::decryptAES($unitkerja->key, $filePath);

            if (!$dekrip) {
                return response()->json([
                    'success' => false,
                    'message' => 'file data audio tidak ditemukan!',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'get data audio berhasil',
                'data' => $dekrip,
            ], 200);
        }
    }

    public function getTagname(Request $request)
    {
        $id = $request['id'];
        $data = DB::select(" select distinct a.ip_server,a.nm_server, b.nm_unit_kerja , coalesce(b.timezone, 'WIB') timezone, c.*
                                from trn_unit_kerja_aktif a, mst_unit_kerja b, trn_file_sound c
                                where a.key_id in(select id_unit_kerja_aktif from trn_file_sound where key_id=$id)
                                and a.key_id = c.id_unit_kerja_aktif
                                and c.key_id = $id
                                and a.key_id_unit_kerja=b.key_id");
        $fileloc = DB::select(" select a.* from trn_lokasi_file a where a.flag_aktif = '1'");

        $unitkerja = DB::table("mst_unit_kerja")
            ->where([
                'key_id' => $request->id
            ])
            ->select('key')
            ->first();

        if (!count($data) == 0) {
            return $data;
        }
    }

    public function getDownload(Request $request)
    {
        //PDF file is stored under project/public/download/info.pdf
        $id = $request['id'];
        $data = DB::select(" select a.* from trn_file_sound a where a.key_id = $id");

        if (count($data) == 0) {
            $response['status'] = 'error';
            return $response;
        }

        $response['status'] = 'success'; 
        // $response['filename '] = $data[0]->nm_file_sound ;
        return json_encode($response);
    }

    public function getNotif(Request $request)
    {
        $data = $request->session()->all();

        $temp0_ = DB::select("select count(1) row_ from user_role where user_id = '" . $data['auth']['id'] . "' and role_id = 1");
        $users = [];
        $count = 0;
        $isAdmin = false;
        if ($temp0_[0]->row_ > 0) {
            // admin
            $count = DB::table('notifikasi')->where('flag', 'true')->count();
            $users = DB::select("select * from notifikasi where flag = true order by coalesce(updated_at, created_at) desc NULLS LAST limit 5 ");
            $isAdmin = true;
        } else {
            // non-admin

        }

        return response()->json([
            'success' => true,
            'message' => 'Ada Notifikasi Keterlambatan Pengiriman Audio..!',
            'data' => $users,
            'count' => $count,
            'isAdmin' => $isAdmin
        ], 200);
    }

    public function showNotif(Request $request): View
    {
        //
        $form = array(
            'title' => 'Notifikasi',
            'sub_title' => 'Pengaturan'
        );

        $notifs = DB::table('notifikasi')->orderBy('key_id', 'DESC')->paginate(10);

        //var_dump($notifs); exit;

        return view('notif.index', compact('notifs', 'form'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}
