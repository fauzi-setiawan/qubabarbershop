<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * DocumentRagService
 *
 * Mengimplementasikan Simple RAG (Retrieval-Augmented Generation) dengan membaca
 * dokumen .docx dari folder document_urs, mengekstrak teksnya, memecahnya menjadi
 * chunk, lalu mengambil chunk yang paling relevan berdasarkan query pengguna
 * menggunakan TF-IDF scoring sederhana.
 */
class DocumentRagService
{
    /** Direktori tempat dokumen URS/FSD disimpan */
    protected string $documentDir;

    /** Ukuran maksimum setiap chunk dalam karakter */
    protected int $chunkSize = 1500;

    /** Tumpang tindih antar chunk (context overlap) dalam karakter */
    protected int $chunkOverlap = 200;

    /** Jumlah chunk teratas yang dikembalikan */
    protected int $topK = 4;

    /** Durasi cache teks dokumen (dalam detik) */
    protected int $cacheTtl = 3600;

    public function __construct()
    {
        $this->documentDir = resource_path('document_urs');
    }

    // ─── Public API ───────────────────────────────────────────────────────────

    /**
     * Ambil konteks yang relevan dari dokumen di folder document_urs
     * berdasarkan query yang diberikan.
     *
     * @param  string      $query           Teks deskripsi/modul dari pengguna
     * @param  string|null $documentFilter  Nama file dokumen spesifik untuk membatasi ruang pencarian
     * @return string                       Konteks relevan siap disuntikkan ke prompt AI
     */
    public function getRelevantContext(string $query, ?string $documentFilter = null): string
    {
        $allChunks = $this->getAllChunks();

        if (empty($allChunks)) {
            return '';
        }

        // Batasi pencarian pada dokumen spesifik jika filter disediakan
        if ($documentFilter) {
            $allChunks = array_filter($allChunks, function ($chunk) use ($documentFilter) {
                return strcasecmp($chunk['source'], $documentFilter) === 0;
            });
        }

        $scoredChunks = $this->scoreChunks($allChunks, $query);

        // Ambil top-K chunk dengan skor tertinggi
        $topChunks = array_slice($scoredChunks, 0, $this->topK);

        if (empty($topChunks)) {
            return '';
        }

        return $this->formatContext($topChunks);
    }

    /**
     * Daftar semua dokumen knowledge yang tersedia di folder document_urs.
     *
     * @return array  Daftar dokumen dengan nama, ukuran, tipe, dan tanggal modifikasi
     */
    public function listDocuments(): array
    {
        $documents = [];

        if (!is_dir($this->documentDir)) {
            return $documents;
        }

        $extensions = ['docx', 'md'];

        foreach ($extensions as $ext) {
            $files = glob($this->documentDir . "/*.{$ext}");
            foreach ($files as $filePath) {
                $documents[] = [
                    'name'      => basename($filePath),
                    'size'      => filesize($filePath),
                    'type'      => $ext === 'md' ? 'Markdown' : 'Word Document',
                    'extension' => $ext,
                    'modified'  => date('Y-m-d H:i:s', filemtime($filePath)),
                    'chunks'    => $this->countChunksForFile(basename($filePath)),
                ];
            }
        }

        return $documents;
    }

    /**
     * Baca konten mentah dari dokumen knowledge berdasarkan nama file.
     *
     * @param  string $filename  Nama file dokumen
     * @return string|null       Konten teks dokumen atau null jika tidak ditemukan
     */
    public function readDocument(string $filename): ?string
    {
        $filePath = $this->documentDir . '/' . basename($filename);

        if (!file_exists($filePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'md') {
            return file_get_contents($filePath);
        }

        if ($ext === 'docx') {
            return $this->extractTextFromDocx($filePath);
        }

        return null;
    }

    /**
     * Hitung jumlah chunk untuk sebuah file tertentu.
     */
    protected function countChunksForFile(string $filename): int
    {
        $allChunks = $this->getAllChunks();
        return count(array_filter($allChunks, fn($c) => $c['source'] === $filename));
    }

    // ─── Dokumen Loader ───────────────────────────────────────────────────────

    /**
     * Membaca semua dokumen .docx dari folder document_urs dan memecahnya menjadi chunk.
     * Hasil di-cache selama $cacheTtl detik.
     */
    protected function getAllChunks(): array
    {
        return Cache::remember('rag_document_chunks', $this->cacheTtl, function () {
            $chunks = [];

            if (!is_dir($this->documentDir)) {
                return $chunks;
            }

            // Proses file .docx
            $docxFiles = glob($this->documentDir . '/*.docx');
            foreach ($docxFiles as $filePath) {
                try {
                    $text = $this->extractTextFromDocx($filePath);
                    $text = $this->sanitizeUtf8($text);
                    if (!empty(trim($text))) {
                        $fileChunks = $this->chunkText($text, basename($filePath));
                        $chunks = array_merge($chunks, $fileChunks);
                    }
                } catch (\Throwable $e) {
                    \Log::warning("RAG: Gagal membaca dokumen [{$filePath}]: " . $e->getMessage());
                }
            }

            // Proses file .md (Markdown)
            $mdFiles = glob($this->documentDir . '/*.md');
            foreach ($mdFiles as $filePath) {
                try {
                    $text = $this->extractTextFromMarkdown($filePath);
                    $text = $this->sanitizeUtf8($text);
                    if (!empty(trim($text))) {
                        $fileChunks = $this->chunkText($text, basename($filePath));
                        $chunks = array_merge($chunks, $fileChunks);
                    }
                } catch (\Throwable $e) {
                    \Log::warning("RAG: Gagal membaca markdown [{$filePath}]: " . $e->getMessage());
                }
            }

            return $chunks;
        });
    }

    /**
     * Mengekstrak teks dari file .docx menggunakan PHPWord.
     * Teks dari setiap paragraf digabungkan dengan newline.
     */
    protected function extractTextFromDocx(string $filePath): string
    {
        $phpWord   = \PhpOffice\PhpWord\IOFactory::load($filePath);
        $sections  = $phpWord->getSections();
        $textParts = [];

        foreach ($sections as $section) {
            foreach ($section->getElements() as $element) {
                $elementText = $this->extractTextFromElement($element);
                if (!empty(trim($elementText))) {
                    $textParts[] = trim($elementText);
                }
            }
        }

        return implode("\n", $textParts);
    }

    /**
     * Mengekstrak teks dari file Markdown (.md).
     * Menghapus sintaks Markdown untuk mendapatkan teks bersih yang bisa di-chunk.
     */
    protected function extractTextFromMarkdown(string $filePath): string
    {
        $content = file_get_contents($filePath);

        // Bersihkan sintaks Markdown agar teks lebih bersih untuk chunking
        // Hapus header markers tapi pertahankan teksnya
        $content = preg_replace('/^#{1,6}\s+/m', '', $content);

        // Hapus bold/italic markers tapi pertahankan teks
        $content = preg_replace('/\*{1,3}(.*?)\*{1,3}/', '$1', $content);
        $content = preg_replace('/_{1,3}(.*?)_{1,3}/', '$1', $content);

        // Hapus inline code backticks tapi pertahankan konten
        $content = preg_replace('/`([^`]+)`/', '$1', $content);

        // Hapus link syntax tapi pertahankan teks
        $content = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $content);

        // Hapus horizontal rules
        $content = preg_replace('/^[-*_]{3,}$/m', '', $content);

        // Hapus tabel dividers tapi pertahankan konten baris tabel
        $content = preg_replace('/^\|?[-:|\s]+\|?$/m', '', $content);

        // Bersihkan pipe characters di tabel menjadi spasi
        $content = preg_replace('/\|/', ' | ', $content);

        // Hapus image syntax
        $content = preg_replace('/!\[([^\]]*)\]\([^)]+\)/', '$1', $content);

        // Normalisasi whitespace berlebih
        $content = preg_replace('/\n{3,}/', "\n\n", trim($content));

        return $content;
    }

    /**
     * Mengekstrak teks secara rekursif dari elemen PHPWord.
     * Menangani semua tipe elemen: Text, TextRun, Paragraph, Table, Row, Cell, dll.
     */
    protected function extractTextFromElement($element): string
    {
        // Elemen teks dasar
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            return (string) $element->getText();
        }

        // TextRun berisi beberapa Text/TextRun
        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                $t = $this->extractTextFromElement($child);
                if (!empty($t)) $parts[] = $t;
            }
            return implode('', $parts);
        }

        // Table → iterasi Row → Cell → elemen
        if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
            $rows = [];
            foreach ($element->getRows() as $row) {
                $cells = [];
                foreach ($row->getCells() as $cell) {
                    $cellText = '';
                    foreach ($cell->getElements() as $cellEl) {
                        $cellText .= $this->extractTextFromElement($cellEl) . ' ';
                    }
                    $cellText = trim($cellText);
                    if (!empty($cellText)) $cells[] = $cellText;
                }
                if (!empty($cells)) $rows[] = implode(' | ', $cells);
            }
            return implode("\n", $rows);
        }

        // AbstractContainer mencakup: ListItem, ListItemRun, Footer, Header, dll.
        if ($element instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                $t = $this->extractTextFromElement($child);
                if (!empty($t)) $parts[] = $t;
            }
            return implode(' ', $parts);
        }

        // Fallback: coba getText() jika ada
        if (method_exists($element, 'getText')) {
            $val = $element->getText();
            return is_string($val) ? $val : '';
        }

        return '';
    }


    // ─── Chunking ─────────────────────────────────────────────────────────────

    /**
     * Memecah teks panjang menjadi chunk-chunk kecil dengan overlap.
     * Pemotongan dilakukan pada batas kalimat/paragraf untuk menjaga koherensi.
     *
     * @return array<int, array{text: string, source: string, index: int}>
     */
    protected function chunkText(string $text, string $sourceName): array
    {
        // Normalisasi whitespace
        $text = preg_replace('/\n{3,}/', "\n\n", trim($text));

        $chunks = [];
        $offset = 0;
        $length = mb_strlen($text, 'UTF-8');
        $index  = 0;

        while ($offset < $length) {
            $end = min($offset + $this->chunkSize, $length);

            // Potong pada batas paragraf atau kalimat terdekat
            if ($end < $length) {
                $window     = mb_substr($text, $offset, $end - $offset, 'UTF-8');
                $windowLen  = mb_strlen($window, 'UTF-8');
                $halfLen    = (int) ($this->chunkSize * 0.5);

                $breakAt = false;
                foreach (["\n\n", ". ", "\n"] as $sep) {
                    $pos = mb_strrpos($window, $sep, 0, 'UTF-8');
                    if ($pos !== false && $pos > $halfLen) {
                        $breakAt = $pos + mb_strlen($sep, 'UTF-8');
                        break;
                    }
                }
                if ($breakAt !== false) {
                    $end = $offset + $breakAt;
                }
            }

            $chunkText = trim(mb_substr($text, $offset, $end - $offset, 'UTF-8'));
            if (!empty($chunkText)) {
                $chunks[] = [
                    'text'   => $chunkText,
                    'source' => $sourceName,
                    'index'  => $index++,
                ];
            }

            // Maju dengan overlap untuk menjaga konteks antar chunk
            $newOffset = $end - $this->chunkOverlap;
            if ($newOffset <= $offset) {
                $newOffset = $end; // Hindari infinite loop
            }
            $offset = $newOffset;
        }

        return $chunks;
    }


    // ─── Scoring (TF-IDF Sederhana) ───────────────────────────────────────────

    /**
     * Menghitung skor relevansi setiap chunk terhadap query menggunakan
     * pendekatan TF-IDF sederhana berbasis kata kunci.
     *
     * @param  array  $chunks  Array chunk dokumen
     * @param  string $query   Query pengguna
     * @return array           Chunk yang sudah diurutkan berdasarkan skor
     */
    protected function scoreChunks(array $chunks, string $query): array
    {
        $queryTokens = $this->tokenize($query);

        if (empty($queryTokens)) {
            return $chunks;
        }

        $scoredChunks = [];

        foreach ($chunks as $chunk) {
            $chunkTokens = $this->tokenize($chunk['text']);
            $score       = $this->computeTfIdfScore($queryTokens, $chunkTokens);

            $scoredChunks[] = array_merge($chunk, ['score' => $score]);
        }

        // Urutkan berdasarkan skor tertinggi
        usort($scoredChunks, fn($a, $b) => $b['score'] <=> $a['score']);

        // Filter chunk dengan skor 0 (tidak relevan sama sekali)
        return array_filter($scoredChunks, fn($c) => $c['score'] > 0);
    }

    /**
     * Tokenisasi teks: lowercase, hapus stopword umum, ambil kata unik.
     *
     * @return string[]
     */
    protected function tokenize(string $text): array
    {
        // Ubah ke lowercase dan ekstrak kata
        $text   = Str::lower($text);
        $words  = preg_split('/[\s\W]+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Stopword Bahasa Indonesia & Inggris
        $stopwords = [
            'yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'dengan', 'untuk',
            'pada', 'adalah', 'akan', 'dapat', 'dalam', 'tidak', 'atau', 'juga',
            'sudah', 'saat', 'ada', 'oleh', 'lebih', 'bila', 'maka', 'sehingga',
            'the', 'a', 'an', 'is', 'in', 'of', 'to', 'and', 'for', 'or', 'be',
            'as', 'at', 'by', 'it', 'its', 'if', 'on', 'we', 'he', 'she',
        ];

        return array_values(array_filter($words, fn($w) =>
            strlen($w) > 2 && !in_array($w, $stopwords)
        ));
    }

    /**
     * Hitung skor TF-IDF sederhana: frekuensi kata query yang cocok di chunk
     * dibagi panjang chunk (normalisasi).
     */
    protected function computeTfIdfScore(array $queryTokens, array $chunkTokens): float
    {
        if (empty($chunkTokens)) return 0.0;

        $score = 0.0;
        $chunkTokenFreq = array_count_values($chunkTokens);
        $chunkLen       = count($chunkTokens);

        foreach ($queryTokens as $token) {
            if (isset($chunkTokenFreq[$token])) {
                // TF: frekuensi relatif token dalam chunk
                $tf = $chunkTokenFreq[$token] / $chunkLen;
                $score += $tf;
            }
            // Partial match untuk kata majemuk (substring)
            foreach ($chunkTokens as $ct) {
                if ($ct !== $token && (str_contains($ct, $token) || str_contains($token, $ct)) && strlen($token) > 3) {
                    $score += 0.1 / $chunkLen;
                }
            }
        }

        return $score;
    }

    // ─── Formatter ────────────────────────────────────────────────────────────

    /**
     * Format chunk terpilih menjadi blok teks konteks untuk prompt AI.
     */
    protected function formatContext(array $topChunks): string
    {
        $parts = [];
        foreach ($topChunks as $i => $chunk) {
            $num    = $i + 1;
            $source = $chunk['source'];
            $parts[] = "[Referensi #{$num} — Sumber: {$source}]\n{$chunk['text']}";
        }

        return implode("\n\n---\n\n", $parts);
    }

    /**
     * Sanitasi teks agar selalu bertipe UTF-8 yang valid untuk menghindari database encoding error.
     */
    protected function sanitizeUtf8(string $text): string
    {
        if (!mb_check_encoding($text, 'UTF-8')) {
            // Coba konversi dari Windows-1252 (CP1252) ke UTF-8 jika terdeteksi encoding lama
            $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        }

        // Bersihkan atau hilangkan karakter UTF-8 yang tidak valid/rusak jika ada
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }
}
