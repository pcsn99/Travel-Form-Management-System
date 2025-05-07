<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TestImageExportController extends Controller
{
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Test Image Embedding');

        $imagePath = public_path('shared/signatures/j9lqKf8ZdwPWqW3eZCOsZTmuKcVNPsc1BJddzIUY.png'); // Adjust to your actual test image path

        if (file_exists($imagePath)) {
            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('Signature Image');
            $drawing->setPath($imagePath);
            $drawing->setHeight(80);
            $drawing->setCoordinates('B3');
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A3', 'Image not found: ' . $imagePath);
        }

        $filename = 'test-image.xlsx';
        $path = storage_path('app/public/' . $filename);
        (new Xlsx($spreadsheet))->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
