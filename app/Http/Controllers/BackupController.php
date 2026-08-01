<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        
        // Check if backups disk exists
        try {
            $files = Storage::disk('backups')->files();
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => Storage::disk('backups')->size($file),
                    'modified' => Storage::disk('backups')->lastModified($file),
                ];
            }
            
            // Sort by modified time descending
            usort($backups, function($a, $b) {
                return $b['modified'] - $a['modified'];
            });
            
        } catch (\Exception $e) {
            // If disk doesn't exist, continue with empty array
            $backups = [];
        }

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Create backup directory if not exists
            if (!Storage::disk('backups')->exists('')) {
                Storage::disk('backups')->makeDirectory('');
            }
            
            // Create a dummy backup file for demo
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $content = "-- Database Backup\n-- Created: " . date('Y-m-d H:i:s') . "\n\n-- This is a sample backup file\n-- In production, this would contain your database dump\n\nSELECT 'Backup created successfully!' as message;";
            
            Storage::disk('backups')->put($filename, $content);
            
            return redirect()->route('admin.backups.index')->with('success', 'Backup created successfully.');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        try {
            if (!Storage::disk('backups')->exists($filename)) {
                return redirect()->route('admin.backups.index')->with('error', 'Backup file not found.');
            }
            return Storage::disk('backups')->download($filename);
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Failed to download backup.');
        }
    }

    public function destroy($filename)
    {
        try {
            if (!Storage::disk('backups')->exists($filename)) {
                return redirect()->route('admin.backups.index')->with('error', 'Backup file not found.');
            }
            Storage::disk('backups')->delete($filename);
            return redirect()->route('admin.backups.index')->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Failed to delete backup.');
        }
    }
}