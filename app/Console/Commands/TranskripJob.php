<?php

namespace App\Console\Commands;

use App\Jobs\TranscribeFileSound;
use App\Services\TranscriptionService;
use Illuminate\Console\Command;

class TranskripJob extends Command
{
    protected $signature = 'transkrip:job {--limit=50 : Jumlah file per eksekusi} {--sync : Jalankan langsung tanpa queue}';

    protected $description = 'Kirim file sound yang belum ditranskrip (transkrip null/0) ke Whisper';

    public function handle(TranscriptionService $service): int
    {
        $ids = $service->pendingIds((int) $this->option('limit'));
        $this->info('File sound belum ditranskrip: ' . count($ids));

        foreach ($ids as $id) {
            if ($this->option('sync')) {
                try {
                    $service->transcribe($id);
                    $this->line("OK   {$id}");
                } catch (\Throwable $e) {
                    $this->error("FAIL {$id}: " . $e->getMessage());
                }
            } else {
                TranscribeFileSound::dispatch($id);
            }
        }

        return self::SUCCESS;
    }
}
