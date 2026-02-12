<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScanSipDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sip:scan-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan SIP Record folder and import documents into database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting SIP document scan...');
        
        // Check multiple possible locations for SIP Record folder
        $possiblePaths = [
            public_path('SIPRecord'),
            public_path('SIP Record'),
            base_path('SIPRecord'),
            base_path('SIP Record'),
            storage_path('app/public/SIPRecord'),
            storage_path('app/public/SIP Record'),
            'E:/xampp/htdocs/sip_form_laravel/SIPRecord', // Direct path as fallback
            'E:/xampp/htdocs/sip_form_laravel/SIP Record', // Direct path as fallback
        ];
        
        $baseFolder = null;
        foreach ($possiblePaths as $path) {
            if (is_dir($path)) {
                $baseFolder = $path;
                $this->info("Found SIP Record folder at: {$path}");
                break;
            }
        }
        
        if (!$baseFolder) {
            $this->error("SIP Record folder not found in any of these locations:");
            foreach ($possiblePaths as $path) {
                $this->error("  - {$path}");
            }
            $this->info("\nPlease create the SIP Record folder and place your SIP documents inside it.");
            $this->info("Example structure:");
            $this->info("  SIP Record/");
            $this->info("  ├── 500_CompanyName/");
            $this->info("  │   └── scanned_document/");
            $this->info("  │       ├── document1.pdf");
            $this->info("  │       └── document2.jpg");
            $this->info("  └── 501_OtherCompany/");
            $this->info("      └── scanned_document/");
            $this->info("          └── document3.pdf");
            return 1;
        }

        $this->scanFolder($baseFolder);
        
        $this->info('Document scan completed successfully!');
        return 0;
    }

    private function scanFolder($folder)
    {
        $folders = array_diff(scandir($folder), ['.', '..']);
        
        foreach ($folders as $f) {
            $path = $folder . '/' . $f;
            
            if (is_dir($path)) {
                $this->processSipFolder($path, $f);
            }
        }
    }

    private function processSipFolder($path, $folderName)
    {
        // Extract SIP number and company name from folder name
        $parts = explode('_', $folderName);
        $sipNumber = $parts[0] ?? '';
        $companyName = str_replace($sipNumber . '_', '', $folderName);
        
        $this->info("Processing SIP: {$sipNumber} - Company: {$companyName}");
        
        // Check for scanned_document subfolder
        $scannedFolder = $path . '/scanned_document';
        
        if (is_dir($scannedFolder)) {
            $this->processScannedDocuments($scannedFolder, $sipNumber, $companyName);
        } else {
            // Also check if there are documents directly in the SIP folder
            $this->processScannedDocuments($path, $sipNumber, $companyName);
        }
    }

    private function processScannedDocuments($folder, $sipNumber, $companyName)
    {
        $files = array_diff(scandir($folder), ['.', '..']);
        
        foreach ($files as $file) {
            $filePath = $folder . '/' . $file;
            
            if (is_file($filePath)) {
                $this->importDocument($filePath, $sipNumber, $companyName);
            }
        }
    }

    private function importDocument($filePath, $sipNumber, $companyName)
    {
        $fileName = basename($filePath);
        
        // Check if document already exists
        $existing = DB::table('uploaded_files')
            ->where('sip_number', $sipNumber)
            ->where('file_name', $fileName)
            ->first();
        
        if ($existing) {
            $this->line("  - Skipping existing: {$fileName}");
            return;
        }
        
        // Convert full path to relative path for web access
        $relativePath = str_replace(public_path(), '', $filePath);
        $relativePath = ltrim($relativePath, '/\\');
        
        // Insert new document (matching your table structure)
        DB::table('uploaded_files')->insert([
            'sip_number' => $sipNumber,
            'file_name' => $fileName,
            'file_path' => $relativePath,
            'uploaded_at' => now(),
        ]);
        
        $this->line("  + Imported: {$fileName} (Company: {$companyName})");
        $this->line("    Path: {$relativePath}");
    }
}
