<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LocalTravelForm;
use App\Models\OverseasTravelForm;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;


class TravelFormExportController extends Controller
{
    public function exportLocal($id)
    {
        $form = LocalTravelForm::with(['request.user', 'answers.question'])->findOrFail($id);

        $templatePath = storage_path('app/templates/local_travel_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('B2', $form->request->user->name);
        $sheet->setCellValue('B3', $form->request->intended_departure_date);
        $sheet->setCellValue('B4', $form->request->intended_return_date);

        $row = 6;
        foreach ($form->answers as $answer) {
            $sheet->setCellValue("A{$row}", $answer->question->question);
            $sheet->setCellValue("B{$row}", $answer->answer);
            $row++;
        }

        $filename = 'local_travel_form_' . $form->id . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        (new Xlsx($spreadsheet))->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }


    public function exportOverseas($id)
    {
        $form = OverseasTravelForm::with(['request.user', 'answers.question'])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        

        // Basic info
        $sheet->setCellValue('A2', $form->request->user->name);
        $sheet->setCellValue('E2', $form->request->updated_at);
        $sheet->setCellValue('A11', $form->request->intended_departure_date);
        $sheet->setCellValue('D11', $form->request->intended_return_date);

        // Answers start from A6
        $row = 13;
        foreach ($form->answers as $answer) {
            $sheet->setCellValue("C{$row}", $answer->answer);
            $row++;
        }

        // Signature
        $signaturePath = public_path('shared/' . $form->request->user->signature);
        if (file_exists($signaturePath)) {
            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('User Signature');
            $drawing->setPath($signaturePath);
            $drawing->setHeight(80);
            $drawing->setCoordinates('E20');
            $drawing->setWorksheet($sheet);
        }

        $filename = 'overseas_travel_form_' . $form->id . '_clean.xlsx';
        $path = storage_path('app/public/' . $filename);

        (new Xlsx($spreadsheet))->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
