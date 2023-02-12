<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Config;

class FileService  implements WithStartRow
{
    public function saveFileToS3($filePath, $exportedObject)
    {
        $csvContent = Excel::raw($exportedObject, \Maatwebsite\Excel\Excel::XLSX);
        // $csvContent = mb_convert_encoding($csvContent, 'SJIS', 'auto');
        // $csvContent = iconv(mb_detect_encoding($csvContent, mb_detect_order(), true), "UTF-8", $csvContent);
        $s3 = Storage::disk('s3');
        $s3->put($filePath, $csvContent);
        $url = $s3->temporaryUrl(
            $filePath,
            now()->addMinutes(10)
        );
        return $url;
    }

    public function saveFileImageToS3($filePath, $exportedObject)
    {
        $s3 = Storage::disk('s3');
        $s3->put($filePath, $exportedObject);
        $url = $s3->temporaryUrl(
            $filePath,
            now()->addMinutes(10)
        );
        return $url;
    }

    public function uploadFileToS3($file, $filePath)
    {
        $fileName = $file->getClientOriginalName();
        $fileName = preg_replace('/\s+/', '_', $fileName);
        $randomStr = Str::random(10);
        $filePath = $filePath . '/' . $randomStr;
        return $file->storeAs($filePath, $fileName, 's3');
    }

    public function deleteFileS3($fileUrl)
    {
        $filePath = parse_url($fileUrl)['path'];
        Storage::disk('s3')->delete($filePath);
    }

    function setInputEncoding($file)
    {
        $fileContent = file_get_contents($file);
        $enc = mb_detect_encoding($fileContent, mb_list_encodings(), true);
        Config::set('excel.imports.csv.input_encoding', $enc);
    }

    // import file
    public function importFile($request, $validator, $fileImport)
    {
        $this->setInputEncoding($request->file('file'));
        Excel::import($validator, $request->file('file'));
        if (!empty($validator->errors)) {
            return _error($validator->errors, __('error'), HTTP_BAD_REQUEST);
        } else {
            Excel::import($fileImport, $request->file('file'));
            return _success(null, __('messages.import_success'), HTTP_SUCCESS);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
