<?php
namespace App\Library;
use Illuminate\Support\Facades\Storage;

/**
 * Handles uploaded files
 */

class FileManager
{
    static function store($file_object)
    {
        $old_file_name = $file_object->getClientOriginalName();
        $new_file_name = self::sanitizeFileName($old_file_name);

        $random_code = time();
        $new_file_name = $random_code . '_' . $new_file_name;

//        $s3 = Storage::disk('s3');
//        $s3->put('text.txt', 'This is my dummy file storage', 'public');
        Storage::disk('s3')->put($new_file_name, file_get_contents($file_object), 'public');

        return $new_file_name;
    }

    static function sanitizeFileName($name)
    {
        $new_name = preg_replace("/[^A-Za-z0-9.]/", "_", $name);
        return $new_name;
    }
}