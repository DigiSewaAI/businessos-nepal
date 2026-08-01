<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Artisan;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Storage::disk('backups')->files();
        $backupInfo = [];
        foreach ($backups as $file) {
            $backupInfo[] = [
                'name' => basename($file),
                'size' => Storage::disk('backups')->size($file),
                'modified' => Storage::disk('backups')->lastModified($file),
            ];
        }
        // Sort by modified time descending
        usort($backupInfo, fn($a, $b) => $b['modified'] - $a['modified']);

        return view('admin.backups.index', compact('backupInfo'));
    }

    public function create()
    {
        Artisan::call('backup:run --only-db');
        return redirect()->route('admin.backups.index')->with('success', 'Backup created successfully.');
    }

    public function download($filename)
    {
        $path = "backups/{$filename}";
        if (!Storage::disk('backups')->exists($filename)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }
        return Storage::disk('backups')->download($filename);
    }

    public function destroy($filename)
    {
        if (!Storage::disk('backups')->exists($filename)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }
        Storage::disk('backups')->delete($filename);
        return redirect()->route('admin.backups.index')->with('success', 'Backup deleted.');
    }
}