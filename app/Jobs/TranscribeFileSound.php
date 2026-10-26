<?php

namespace App\Jobs;

use App\Services\TranscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class TranscribeFileSound implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 900;

    // Lock unik dilepas otomatis jika job macet lebih lama dari ini
    public int $uniqueFor = 3600;

    public function __construct(public int $fileSoundId)
    {
        $this->onQueue('transkrip');
    }

    public function uniqueId(): string
    {
        return (string) $this->fileSoundId;
    }

    public function handle(TranscriptionService $service): void
    {
        $service->transcribe($this->fileSoundId);
    }

    public function failed(Throwable $e): void
    {
        Log::error("Transkrip trn_file_sound {$this->fileSoundId} gagal: " . $e->getMessage());
    }
}
