<?php

namespace App\Console\Commands;

use App\Services\DocumentRagService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TestRagCommand extends Command
{
    protected $signature   = 'rag:test {query? : Query untuk diuji} {--clear-cache : Bersihkan cache dokumen}';
    protected $description = 'Uji ekstraksi dokumen RAG dari folder document_urs';

    public function handle(): void
    {
        if ($this->option('clear-cache')) {
            Cache::forget('rag_document_chunks');
            $this->info('✅ Cache dokumen RAG berhasil dibersihkan.');
        }

        $query = $this->argument('query') ?? 'login registrasi pengguna autentikasi';
        $this->info("🔍 Query: \"{$query}\"");
        $this->newLine();

        $service = new DocumentRagService();

        $this->info('📄 Mengekstrak dokumen dari folder document_urs...');
        $context = $service->getRelevantContext($query);

        if (empty($context)) {
            $this->warn('⚠️  Tidak ada konteks relevan ditemukan. Periksa:');
            $this->line('   • Apakah ada file .docx di resources/document_urs/?');
            $this->line('   • Apakah phpoffice/phpword sudah terinstall?');
            return;
        }

        $this->info('✅ Konteks berhasil diekstrak:');
        $this->newLine();
        $this->line(str_repeat('─', 60));
        $this->line($context);
        $this->line(str_repeat('─', 60));
        $this->newLine();
        $this->info('RAG siap digunakan oleh AI Generate Test Case.');
    }
}
