<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TranscriptionService
{
    private const ENCRYPTION_ALGORITHM = 'AES-128-CBC';

    /**
     * Ambil data file sound beserta key AES unit kerjanya.
     */
    public function findFileSound(int $id): ?object
    {
        return DB::table('trn_file_sound as fs')
            ->join('trn_unit_kerja_aktif as uka', 'uka.key_id', '=', 'fs.id_unit_kerja_aktif')
            ->join('mst_unit_kerja as uk', 'uk.key_id', '=', 'uka.key_id_unit_kerja')
            ->where('fs.key_id', $id)
            ->select('fs.key_id', 'fs.nm_file_sound', 'fs.path', 'fs.transkrip', 'uk.key')
            ->first();
    }

    /**
     * Daftar key_id file sound yang belum ditranskrip (transkrip null / 0).
     */
    public function pendingIds(int $limit): array
    {
        return DB::table('trn_file_sound')
            ->where(function ($q) {
                $q->whereNull('transkrip')->orWhere('transkrip', 0);
            })
            ->orderBy('key_id')
            ->limit($limit)
            ->pluck('key_id')
            ->all();
    }

    /**
     * Dekripsi file, kirim ke Whisper, lalu simpan hasilnya.
     */
    public function transcribe(int $id): string
    {
        $row = $this->findFileSound($id);
        if (!$row) {
            throw new RuntimeException("trn_file_sound {$id} atau key unit kerja tidak ditemukan");
        }

        $filePath = storage_path(rtrim($row->path, '/') . '/' . $row->nm_file_sound);
        if (!is_file($filePath)) {
            throw new RuntimeException("File sound tidak ditemukan: {$filePath}");
        }

        $decrypted = openssl_decrypt(
            file_get_contents($filePath),
            self::ENCRYPTION_ALGORITHM,
            $row->key,
            OPENSSL_RAW_DATA,
            $row->key
        );
        if ($decrypted === false) {
            throw new RuntimeException("Gagal dekripsi file {$row->nm_file_sound}");
        }

        // format=srt -> response text/plain berisi subtitle bertimestamp (bisa kosong bila audio hening)
        $response = Http::timeout(config('services.whisper.timeout'))
            ->attach('file', $decrypted, $row->nm_file_sound, ['Content-Type' => 'audio/mpeg'])
            ->post(config('services.whisper.url'), array_filter([
                'format' => config('services.whisper.format'),
                'language' => config('services.whisper.language'),
                'task' => config('services.whisper.task'),
                'channels' => config('services.whisper.channels'),
                'model' => config('services.whisper.model'),
            ]));

        if ($response->failed()) {
            throw new RuntimeException("Whisper API error {$response->status()}: " . $response->body());
        }

        $text = $response->body();

        DB::table('trn_file_sound')
            ->where('key_id', $id)
            ->update([
                'transkrip' => 1,
                'transkrip_text' => trim($text),
            ]);

        Log::info("Transkrip trn_file_sound {$id} berhasil");

        return $text;
    }
}
