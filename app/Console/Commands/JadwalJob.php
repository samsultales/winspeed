<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Events\AlatRusakNotifikasi;
use App\Events\AlatRusakNotifikasi2;
use App\Models\User;

class JadwalJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Log::info('Cron Job Gen Notifikasi running at ' . now());
        try {
            $query = "CALL p_gen_notif();";
            DB::statement($query);

            $count = DB::table('notifikasi')->where('flag', 'true')->count    ();
            $count2 = DB::table('notifikasi')->where('flag', 'true')->where('id_notif', '2')->count();
            $users = DB::select("select * from notifikasi where flag = true order by coalesce(updated_at, created_at) desc NULLS LAST limit 5 ");
            Log::info('Jumlah Data Noitif :  ' . $count);
            
            if ($count > 0) {
                if ($count2 > 0) { 
                    $message = array(
                        'success' => true,
                        'message' => 'Ada Alat (Radio) yang belum mengirimkan rekaman data percakapan.',
                        'message2' => 'Ada Kendala Alat, Mohon cek alat (Soundcard, Koneksi Internet, dan Radio).',
                        'data' => $users,
                        'count' => $count,
                    );
                } else {
                    $message = array(
                        'success' => true,
                        'message' => 'Ada Alat (Radio) yang belum mengirimkan rekaman data percakapan.',
                        'message2' => '',
                        'data' => $users,
                        'count' => $count,
                    );
                }
                event(new AlatRusakNotifikasi($message));
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        //  DB::select('EXEC p_gen_notif');
    }
}
