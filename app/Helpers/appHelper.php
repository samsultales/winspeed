<?php

	namespace App\Helpers;

    use DB;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Session\Session;

    class appHelper
	{
        private const ENCRYPTION_ALGORITHM = 'AES-128-CBC';

        public static function uprofiles($id) {

            $user = DB::select("select distinct id, username, name, role_id, key_id_grp_mon from v_users where id = '".$id."'");
            $array = $user[0];
            // $array = $user[0]->key_id_grp_mon;
            return $array;
        }

        public static function grupalat ($id, $userId) {

            if ($id !== 0) {
                $unit = DB::select("select distinct coalesce(a.key_id_unit_kerja,'0') key_id_unit_kerja from (
                                    select distinct key_id_unit_kerja from users where id = ({$userId})
                                    union all
                                    select distinct a.key_id_unit_kerja from v_group_alat a where a.key_id in ({$id}) ) a");

                foreach($unit as $r){
                    $array [] = $r->key_id_unit_kerja;
                }
            } else {
                $qcek = DB::select("select count(1) ada from v_users where id = '".$userId."' and key_id_grp_mon = 0 limit 1");

                $role = array("1", "11");

                if ( $qcek[0]->ada > 0 ) {
                    
                    // apabila user tidak set group monitoring maka akan masuk ke unit kerja sendiri 
                    // apabila admin dan kanpus maka akan muncul semua....
                    // setting nya disini .. query dibawah cek apakah role masuk ke 1 ata 11 untuk admin dan kanpus

                    $qcek2 = DB::select("select distinct coalesce(role_id, 0) role_id from v_users where id = '".$userId."'  limit 1 ");

                    if (!in_array($qcek2[0]->role_id, $role)) { 
                        $unit = DB::select("select distinct coalesce(a.key_id_unit_kerja,'0') key_id_unit_kerja from (
                                            select distinct key_id_unit_kerja from users where id = ({$userId}) ) a");
                    } else {
                        $unit = DB::select("select distinct a.key_id_unit_kerja from v_mst_unit_alat a");
                    }
                } else {
                    if ($id == 0) {
                        $unit = DB::select("select distinct key_id_unit_kerja from v_users_alat where key_id_users = '". $userId ."'");
                    } 
                    else {
                          $unit = DB::select("select distinct coalesce(a.key_id_unit_kerja,'0') key_id_unit_kerja from (
                                    select distinct key_id_unit_kerja from users where id = ({$userId})
                                    union all
                                    select distinct a.key_id_unit_kerja from v_group_alat a where a.key_id in ({$id}) ) a");
                    }
                   
                }

                foreach($unit as $r){
                    $array [] = $r->key_id_unit_kerja;
                }
            }

            // Response buat where clause query  --
            return implode("','", $array) ;

        }

        public static function options($id) { 
    
            $data = session()->get('auth');
            // var_dump($data['key_id_unit_kerja'] ); exit;

            if ($data['key_id_unit_kerja'] == '0') {
                $qunit = DB::select("select distinct key_id, nm_unit_kerja from vlist_unit_kerja_aktif order by 2, 1");
            } else {
                $qunit = DB::select("select distinct key_id, nm_unit_kerja from vlist_unit_kerja_aktif where key_id = '".$data['key_id_unit_kerja']."' order by 2, 1");
            }

            $selected = 0; 

            $unit = '';
            // $unit .= "<option value=''></option>";
            foreach ($qunit as $row) {
                $unit .= "<option value='$row->key_id'>$row->nm_unit_kerja</option>";
            }  

            $data['unit'] = $unit;

            return  $data; //response()->json($data, 200);
        }

        public static function optsound($id) { 
            $qfilesound = DB::select("select * from trn_file_sound where id_unit_kerja_aktif = {{$id}} order by 3, 1");
            $selected = 0; 

            $filesound = '';
            $filesound .= "<option value=''></option>";
            foreach ($qfilesound as $row) {
                $filesound .= "<option value='$row->path'>$row->nm_file_sound</option>";
            }  

            $data['filesound'] = $filesound;

            return  $data; //response()->json($data, 200);
        }

        public static function getSoundFile($id) {
            $data = DB::select(" select a.* from trn_file_sound a where a.key_id = $id");

            if (count($data) == 0) {
                // $response['message'] = 'Unknown';
                // return response()->json($response);
                return response()->file(storage_path("sounds/error_callback.mp3"), [
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            } else {
                $filePath = storage_path("app/public/sounds/". $data[0]->nm_file_sound . ".mp3");
                return response()->file($filePath, [
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }
        }

        public static function getSoundFileUrl($id) {
            $data = DB::select(" select a.* from trn_file_sound a where a.key_id = $id");

            if (!count($data) == 0) {
                $filePath = url("storage/sounds/". $data[0]->nm_file_sound . ".mp3");
                return $filePath;
            }  
        }

        public static function decryptAES($key, $inputfile)
        {
            try {
                Log::info('decrypt file START');

                $content = file_get_contents($inputfile);
                $encrypted = openssl_decrypt($content, self::ENCRYPTION_ALGORITHM, $key, OPENSSL_RAW_DATA, $key);
                // file_put_contents($outputfile, $encrypted);

                Log::info("File decrypted!");
                Log::info('Memory usage: ' . round(memory_get_usage() / 1048576, 2) . "M");
                return base64_encode($encrypted);
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                // return 'error: '.$th->getMessage();
                return false;
            }
        }

        public static function decryptFileAES($key, $inputfile, $outputfile)
        {
            try {
                Log::info('decrypt file START');

                $content = file_get_contents($inputfile);
                $encrypted = openssl_decrypt($content, self::ENCRYPTION_ALGORITHM, $key, OPENSSL_RAW_DATA, $key);
                file_put_contents($outputfile, $encrypted);

                Log::info("File decrypted!");
                Log::info('Memory usage: ' . round(memory_get_usage() / 1048576, 2) . "M");
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
        }

        public static function encryptFileAES($key, $inputfile, $outputfile)
        {
            try {
                Log::info('encrypt file START');

                $content = file_get_contents($inputfile);
                $encrypted = openssl_encrypt($content, self::ENCRYPTION_ALGORITHM, $key, OPENSSL_RAW_DATA, $key);
                file_put_contents($outputfile, $encrypted);

                Log::info("File encrypted!");
                Log::info('Memory usage: ' . round(memory_get_usage() / 1048576, 2) . "M");
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
        }
    }
