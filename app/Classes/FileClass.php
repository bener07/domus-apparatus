<?php

namespace App\Classes;
use Illuminate\Support\Facades\Storage;

class FileClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    

    public static function fileExists($file, $disk = 'public', $folder = 'products')
    {
        $hash = md5_file($file->getPathname()); // Generate file hash
        $files = Storage::disk($disk)->allFiles($folder); // Get all stored files in the folder

        foreach ($files as $storedFile) {
            if ($hash === md5_file(Storage::disk($disk)->path($storedFile))) {
                return "/storage/".$storedFile; // Return the existing file path
            }
        }

        return false; // File does not exist
    }


}
