<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Jobs\TranscribeFileSound;

class IntegrasiController extends Controller
{
    /**
    *    @OA\Get(
    *       path="/api/integrasi/check",
    *       tags={"General"},
    *       operationId="check",
    *       summary="Cek Server Web API",
    *       description="Menguji integrasi dengan server web API",
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Cek integrasi API berhasil"
    *          }),
    *      ),
    *  )
    */
    public function check()
    {
        try {
            $response['success'] = true;
            $response['message'] = "Cek integrasi API berhasil";
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Get(
    *       path="/api/integrasi/getlistunitkerja",
    *       tags={"Unit Kerja"},
    *       operationId="listUnitKerja",
    *       summary="Get List Unit Kerja",
    *       description="Mendapatkan list unit kerja",
      *      security={{"bearerAuth":{}}},
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Get list unit kerja berhasil",
    *               "data": {
    *                   {
    *                   "id": 0,
    *                   "kode_unit_kerja": "",
   *                   "nama_unit_kerja": "",
   *                   "flag_aktif": 0,
   *                   "seq_server": 0,
   *                   "key": "",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function getListUnitKerja()
    {
        try {
            $unitkerja = DB::select("SELECT key_id id, kd_unit_kerja kode_unit_kerja,nm_unit_kerja nama_unit_kerja,flag_aktif,seq_server,key FROM mst_unit_kerja ORDER BY key_id");

            DB::beginTransaction();
            try {
                $digit = 16;
                foreach ($unitkerja as $row) {
                    if (is_null($row->key) || strlen($row->key) < 16) {
                        $randomkey = bin2hex(random_bytes($digit / 2));
                        $row->key = $randomkey;
                        // update key
                        DB::table('mst_unit_kerja')->where('key_id', $row->id)->update(['key' => $randomkey]);
                    }
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
            }

            $response['success'] = true;
            $response['message'] = "Get list unit kerja berhasil";
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Post(
    *       path="/api/integrasi/setflagunitkerja",
    *       tags={"Unit Kerja"},
    *       operationId="flagUnitKerja",
    *       summary="Set Flag Unit Kerja",
    *       description="Update flag unit kerja",
      *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_server",
 *                     type="integer"
 *                 ),
 *                 example={"id_unit_kerja": 0, "seq_server": 0}
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": 1
    *          }),
    *      ),
    *  )
    */
    public function setFlagUnitKerja(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
            // 'flag_aktif'  => 'required|integer',
            'seq_server'  => 'required|integer'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $unitkerja = DB::table('mst_unit_kerja')
                ->where('key_id', $request->id_unit_kerja)
                ->update([
                    'flag_aktif' => 1, //$request->flag_aktif,
                    'seq_server' => $request->seq_server,
                    'updated_at' => DB::raw('now()'),
                    'updated_by' => 'API-'.__METHOD__
                ]);

            DB::commit();

            if ($unitkerja == 1) {
                $message = 'Data berhasil disimpan';
            } else {
                $message = 'Tidak ada data yang tersimpan. Cek lagi id unit kerja yang dikirim';
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Post(
    *       path="/api/integrasi/upsertunitkerjaaktif",
    *       tags={"Unit Kerja"},
    *       operationId="insUnitKerjaAktif",
    *       summary="Insert or Update Unit Kerja Aktif",
    *       description="insert unit kerja aktif jika belum ada atau update unit kerja aktif jika sudah ada",
      *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_server",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="ip_server_site",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="nama_server_site",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="serial_server_site",
 *                     type="string"
 *                 ),
 *                 example={"id_unit_kerja": 0, "seq_server": 0, "ip_server_site": "", "nama_server_site": "", "serial_server_site": ""}
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": true
    *          }),
    *      ),
    *  )
    */
    public function insUnitKerjaAktif(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
            'seq_server'  => 'required|integer',
            'ip_server_site'  => 'required|string',
            'nama_server_site'  => 'required|string',
            'serial_server_site'  => 'required|string'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $where = [
                'key_id_unit_kerja' => $request->id_unit_kerja,
                'seq_server' => $request->seq_server,
                'serialnumber' => $request->serial_server_site
            ];
            // check if exist just update else insert
            if (DB::table('trn_unit_kerja_aktif')->where($where)->exists()) {
                $unitkerja = DB::table('trn_unit_kerja_aktif')
                    ->where($where)
                    ->update([
                        'ip_server' => $request->ip_server_site,
                        'nm_server' => $request->nama_server_site,
                        'updated_at' => DB::raw('now()'),
                        'updated_by' => 'API-'.__METHOD__
                    ]);
            } else {
                $unitkerja = DB::table('trn_unit_kerja_aktif')
                    ->insert([
                        'key_id_unit_kerja' => $request->id_unit_kerja,
                        'ip_server' => $request->ip_server_site,
                        'nm_server' => $request->nama_server_site,
                        'seq_server' => $request->seq_server,
                        'serialnumber' => $request->serial_server_site,
                        'created_at' => DB::raw('now()'),
                        'created_by' => 'API-'.__METHOD__
                    ]);
            }

            DB::commit();

            if ($unitkerja) {
                $message = 'Data berhasil disimpan';
            } else {
                $message = 'Tidak ada data yang tersimpan';
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Put(
    *       path="/api/integrasi/upduserlocalinfo",
    *       tags={"Unit Kerja"},
    *       operationId="userUnitKerja",
    *       summary="Update Info User Local Unit Kerja",
    *       description="Update info user local unit kerja",
      *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_server",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="user_local",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password_local",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="email_local",
 *                     type="string"
 *                 ),
 *                 example={"id_unit_kerja": 0, "seq_server": 0, "user_local": "", "password_local": "", "email_local": ""}
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": 1
    *          }),
    *      ),
    *  )
    */
    public function updUserLocalInfo(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
            // 'flag_aktif'  => 'required|integer',
            'seq_server'  => 'required|integer',
            'user_local' => 'required|string',
            'password_local' => 'required|string',
            'email_local' => 'required|string'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $unitkerja = DB::table('trn_unit_kerja_aktif')
                ->where([
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                    'seq_server' => $request->seq_server
                ])
                ->update([
                    'user_local' => $request->user_local,
                    'password_local' => $request->password_local,
                    'email_local' => $request->email_local,
                    'updated_at' => DB::raw('now()'),
                    'updated_by' => 'API-'.__METHOD__
                ]);

            DB::commit();

            if ($unitkerja == 1) {
                $message = 'Data berhasil disimpan';
            } else {
                $message = 'Tidak ada data yang tersimpan. Cek lagi id unit kerja yang dikirim';
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Post(
    *       path="/api/integrasi/insloggerlocal",
    *       tags={"Unit Kerja"},
    *       operationId="insLoggerLocal",
    *       summary="Insert Logger Local",
    *       description="Insert Logger Local",
      *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_server",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="pesan",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="date_pesan",
 *                     type="string"
 *                 ),
 *                 example={"id_unit_kerja": 0, "seq_server": 0, "pesan": "", "date_pesan": ""}
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": true
    *          }),
    *      ),
    *  )
    */
    public function insLoggerLocal(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
            'seq_server'  => 'required|integer',
            'pesan'  => 'required|string',
            'date_pesan'  => 'required|string'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            // get unit kerja aktif
            $unitkerjaaktif = DB::table("trn_unit_kerja_aktif")
                ->where([
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                    'seq_server' => $request->seq_server
                ])
                ->select('key_id')
                ->first();

            $unitkerja = DB::table('trn_logger')
                ->insert([
                    'id_unit_kerja_aktif' => $unitkerjaaktif->key_id,
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                    'pesan' => $request->pesan,
                    'date_pesan' => DB::raw("to_timestamp('$request->date_pesan', 'DD Month YYYY hh24:mi')"),
                    'date_intg' => DB::raw('now()')
                ]);

            DB::commit();

            if ($unitkerja == 1) {
                $message = 'Data berhasil disimpan';
            } else {
                $message = 'Tidak ada data yang tersimpan';
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
 * @OA\Post(
 *   path="/api/integrasi/sendfilesound",
 *   tags={"Recording"},
    *       operationId="storeFile",
 *   summary="Kirim File Recording",
 *   description="Kirim File Recording",
     *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(property="filesound", type="file"),
 *                 @OA\Property(
 *                     property="filesize",
 *                     type="string",
 *                     description="ukuran dalam MegaBytes (MB)"
 *                 ),
 *                 @OA\Property(
 *                     property="created_file",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="start_time",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="end_time",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="nm_channel",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="nm_sound_card",
 *                     type="string"
 *                 ),
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": ""
    *          }),
    *      ),
    *  )
 * )
 */
    public function storefile(Request $request)
    {
        \Log::info('sendfilesound', $request->except('filesound'));
        //set ini
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '125M');

        // validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja' => 'required|integer',
            'seq_unit_kerja' => 'required|integer',
            'filesound' => 'required|file',
            'filesize' => 'required|string',
            'created_file' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'nm_channel' => 'required|string',
            'nm_sound_card' => 'required|string',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            //upload file
            $image = $request->filesound;
            $nmfile = $image->getClientOriginalName();

            // simpan di db
            // get unit kerja aktif
            $unitkerjaaktif = DB::table("trn_unit_kerja_aktif")
                ->where([
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                    'seq_server' => $request->seq_unit_kerja
                ])
                ->select('key_id')
                ->first();
            
            $lokasifile = DB::table("trn_lokasi_file")
            ->where([
                'flag_aktif' => 1
            ])
            ->select('lokasi_file')
            ->first();

            $pathfile = substr($lokasifile->lokasi_file,0,1)=='/'?substr($lokasifile->lokasi_file,1,strlen($lokasifile->lokasi_file)-1):$lokasifile->lokasi_file;

            // $image->storeAs('', $image->getClientOriginalName(), 'voice-data');
            $newdisk = Storage::build([
                'driver' => 'local',
                'root' => storage_path($lokasifile->lokasi_file),
                'throw' => true,
            ]);
            // $newdisk->putFileAs('', $image, $image->getClientOriginalName());
            if (!file_exists(storage_path("{$pathfile}/{$image->getClientOriginalName()}"))) {
                $newdisk->putFileAs('', $image, $image->getClientOriginalName());
            }
            $data = []; //$image->getClientOriginalName();

            $response['success'] = true;
            $response['message'] = 'Data berhasil disimpan';
            $response['data'] = true;

            if (file_exists(storage_path("{$pathfile}/{$image->getClientOriginalName()}"))) {
                $fileSoundId = DB::table('trn_file_sound')
                    ->insertGetId([
                        'id_unit_kerja_aktif' => $unitkerjaaktif->key_id,
                        'nm_file_sound' => $nmfile,
                        'created_file' => DB::raw("to_timestamp('$request->created_file', 'YYYY-MM-DD hh24:mi')"),
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'path' => $lokasifile->lokasi_file,
                        'key_id_unit_kerja' => $request->id_unit_kerja,
                        'nm_sound_card' => $request->nm_sound_card,
                        'chanel' => $request->nm_channel,
                        'ukuran' => $request->filesize,
                        'date_intg' => DB::raw('now()'),
                        'created_at' => DB::raw('now()'),
                        'created_by' => 'API-'.__METHOD__
                    ], 'key_id');

                DB::commit();
                $data = true;

                // antrikan transkrip Whisper; gagal antri tidak membatalkan upload (diambil ulang oleh transkrip:job)
                try {
                    TranscribeFileSound::dispatch((int) $fileSoundId);
                } catch (\Throwable $e) {
                    \Log::error("Gagal antri transkrip trn_file_sound {$fileSoundId}: " . $e->getMessage());
                }
                // $data = $pathfile;
                // $data = storage_path("{$pathfile}/{$image->getClientOriginalName()}");

                $response['success'] = true;
                $response['message'] = 'Data berhasil disimpan';
                $response['data'] = true;
            } else {
                $data = false;
                $response['success'] = false;
                $response['message'] = 'Data gagal disimpan. File sound tidak ditemukan';
                $response['data'] = false;
            }

        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
            $response['data'] = false;
        }
        return response()->json($response); 
    }

    /**
    *    @OA\Get(
    *       path="/api/integrasi/getintervalrecording",
    *       tags={"Recording"},
    *       operationId="intervalRecording",
    *       summary="Get Interval Recording",
    *       description="Mendapatkan interval recording",
      *      security={{"bearerAuth":{}}},
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Get interval recording berhasil",
    *               "data": {
    *                   "interval": 0
    *                  }
    *          }),
    *      ),
    *  )
    */
    public function getIntervalRecording()
    {
        try {
            $unitkerja = DB::table('mst_interval_sound')
                ->select('interval')
                ->first();

            $response['success'] = true;
            $response['message'] = "Get interval recording berhasil";
            $response['data'] = $unitkerja;
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Get(
    *       path="/api/integrasi/getlocalpassword",
    *       tags={"General"},
    *       operationId="lupapassword",
    *       summary="Kirim email permintaan lupa password",
    *       description="Kirim email permintaan lupa password",
      *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id_unit_kerja",
     *          description="id unit kerja",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="seq_unit_kerja",
     *          description="sequence unit kerja",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Kirim email local password berhasil",
    *               "data": {
    *              }
    *          }),
    *      ),
    *  )
    */
    public function getLocalPassword(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
            'seq_unit_kerja'  => 'required|integer',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            // get unit kerja aktif
            $unitkerjaaktif = DB::table("trn_unit_kerja_aktif")
                ->where([
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                    'seq_server' => $request->seq_unit_kerja
                ])
                ->select('password_local', 'email_local', 'user_local')
                ->first();

            $title = 'Permintaan Lupa Password';
            $body = 'Permintaan lupa password untuk User: '.$unitkerjaaktif->user_local;
            $password = 'Password: '.$unitkerjaaktif->password_local;
            $data = [
                'subject' => $title,
                'title' => $title,
                'maincontent' => $body,
                'passwordcontent' => $password
            ];
            
            Mail::to($unitkerjaaktif->email_local)->send(new SendEmail($data));
            
            $response['success'] = true;
            $response['message'] = "Kirim email local password berhasil";
            $response['data'] = [];
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Get(
    *       path="/api/integrasi/getlistserialnumber",
    *       tags={"Unit Kerja"},
    *       operationId="listserialnumber",
    *       summary="Mendapatkan list serial number pada unit kerja",
    *       description="Mendapatkan list serial number pada unit kerja",
      *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id_unit_kerja",
     *          description="id unit kerja",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Get List serial number berhasil",
    *               "data": {
    *              }
    *          }),
    *      ),
    *  )
    */
    public function getListSerialNumber(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id_unit_kerja'  => 'required|integer',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            // get unit kerja aktif
            $unitkerjaaktif = DB::table("trn_unit_kerja_aktif")
                ->where([
                    'key_id_unit_kerja' => $request->id_unit_kerja,
                ])
                ->select('key_id_unit_kerja', 'seq_server', 'serialnumber')
                ->get();
            
            $response['success'] = true;
            $response['message'] = "Get List serial number berhasil";
            $response['data'] = $unitkerjaaktif;
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    }

    /**
    *    @OA\Post(
    *       path="/api/integrasi/insstatusserverlocal",
    *       tags={"Unit Kerja"},
    *       operationId="insStsSrvLocal",
    *       summary="Insert Status Server Local",
    *       description="Insert Status Server Local",
      *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_unit_kerja",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="seq_server",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="pesan",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="date_pesan",
 *                     type="string"
 *                 ),
 *                 example={"id_unit_kerja": 0, "seq_server": 0, "pesan": "", "date_pesan": ""}
 *             )
 *         )
 *     ),
   *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "success": true,
    *               "message": "Data berhasil disimpan",
    *               "data": true
    *          }),
    *      ),
    *  )
    */
    public function insStsSrvLocal(Request $request)
    {
        //set validation
        $format = '';
        $validator = Validator::make($request->all(), [
            'data.*.id_unit_kerja'  => 'required|integer',
            'data.*.seq_server'  => 'required|integer',
            'data.*.chanel_nama'  => 'required|string',
            'data.*.sound_card_nama'  => 'required|string',
            'data.*.status'  => 'required|integer',
            'data.*.last_check_tgl'  => 'required|date_format:Y-m-d H:i',
            'data.*.last_recording_tgl'  => 'nullable|date_format:Y-m-d H:i',
            'data.*.last_file_nama'  => 'nullable|string',
        ], [
            'data.*.last_check_tgl.date_format' => 'The :attribute field must match the format YYYY-MM-DD HH24:MI:SS (ex. 2024-12-01 19:50).',
            'data.*.last_recording_tgl.date_format' => 'The :attribute field must match the format YYYY-MM-DD HH24:MI:SS (ex. 2024-12-01 19:50).'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            //delete dulu
            $data = $request['data'];
            $deleted = DB::table('trn_check')
                ->where('key_id_unit_kerja', $data[0]['id_unit_kerja'])
                ->where('seq_server', $data[0]['seq_server'])
                ->delete();

            // insert data array
            foreach ($data as $row) {
                $unitkerja = DB::table('trn_check')
                    ->insert([
                        'key_id_unit_kerja' => $row['id_unit_kerja'],
                        'seq_server' => $row['seq_server'],
                        'chanel' => $row['chanel_nama'],
                        'sound_card' => $row['sound_card_nama'],
                        'status' => $row['status'],
                        'last_file_sound' => $row['last_file_nama'],
                        'last_check' => DB::raw("to_timestamp('".$row['last_check_tgl']."', 'YYYY-MM-DD hh24:mi')"),
                        'last_recording' => is_null($row['last_recording_tgl'])?$row['last_recording_tgl']:DB::raw("to_timestamp('".$row['last_recording_tgl']."', 'YYYY-MM-DD hh24:mi')"),
                    ]);
            }

            DB::commit();

            if ($unitkerja == 1) {
                $message = 'Data berhasil disimpan';
            } else {
                $message = 'Tidak ada data yang tersimpan';
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['data'] = true;
        } catch (\Throwable $th) {
            DB::rollback();

            $response['success'] = false;
            $response['message'] = $th->getMessage();
            $response['success'] = false;
        }
        return response()->json($response);
    }

}