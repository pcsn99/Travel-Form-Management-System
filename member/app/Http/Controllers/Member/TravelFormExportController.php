<?php

namespace App\Http\Controllers\Member;

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
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class TravelFormExportController extends Controller
{
    public function exportLocal(Request $request, $id)
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


    public function exportOverseas(Request $request, $id)
    {
        $form = OverseasTravelForm::with(['request.user', 'answers.question'])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //default setup

        // Set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);
        $sheet->getDefaultColumnDimension()->setWidth(17);



        //
        $sheet->mergeCells('A1:B1'); 
        $sheet->mergeCells('A2:B2'); 
        $sheet->getStyle('A1:B2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM); 
        $sheet->getStyle('A1:B2')->applyFromArray([
            'font' => [
                'bold' => false,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->setCellValue('A1', "Applicant's Name:");
        //

        $sheet->mergeCells('C1:D2');
        $sheet->getStyle('C1:D2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM); 
        $sheet->getStyle('C1:D2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->setCellValue('C1', "Application v2023-1 for\nOverseas Travel");
        //
        $sheet->mergeCells('E1:F1');
        $sheet->mergeCells('E2:F2');
        $sheet->getStyle('E1:F2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('E1:F2')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->setCellValue('E1', 'Application Date:');
        //


        $sheet->getStyle('E2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
        ]);

        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('E2')->getFont()->setBold(true);

        $sheet->mergeCells('A4:F4');
        $sheet->mergeCells('A5:F5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->mergeCells('A6:F6');
        $sheet->mergeCells('A7:F7');
        $sheet->mergeCells('A8:F8');

        $sheet->getStyle('A9:F9')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);



        // Instructions
        $instructions = [
            '[a] Accomplish this form electronically. Then submit this form with a scan of your passport page.',
            '[b] For trips that require Nuncio’s endorsement, submit this form at least 2 months ahead.',
            '[c] Your Director of Work (if applicable) and local Superior must sign this before it is sent to the Provincial.',
            '[d] Procure travel insurance online or through your travel agent.',
            '[e] For trips to be financed by the Province, submit a budget to the Provincial for approval.'
        ];
        $row = 4;
        foreach ($instructions as $text) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", $text);
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true);
            $row++;
        }

        $sheet->getStyle('A10:F43')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A10:F43')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getRowDimension(11)->setRowHeight(20);

        // Travel dates
        $sheet->setCellValue('A10', 'Departure Date:');
        $sheet->setCellValue('D10', 'Return Date:');

        $sheet->getRowDimension(12)->setRowHeight(28);
        $sheet->getRowDimension(13)->setRowHeight(17);
        $sheet->getRowDimension(14)->setRowHeight(17);
        $sheet->getRowDimension(15)->setRowHeight(28);
        $sheet->getRowDimension(16)->setRowHeight(28);
        $sheet->getRowDimension(17)->setRowHeight(28);
        $sheet->getStyle('A12:B17')->getFont()->setBold(true);

        // Questions
        $questions = [
            "Itinerary form the Philippines and\nback (pls list cities)",
            'Purpose',
            'Who will finance?',
            "Who will arrange to procure the\noverseas health insurance?",
            "Who will pay for the overseas health\ninsurance?",
            "How can you be contacted abroad?\n(phone,email)",
        ];
        $sheet->getRowDimension(18)->setRowHeight(4);
        $sheet->getRowDimension(19)->setRowHeight(4);
        $sheet->getRowDimension(20)->setRowHeight(40);

        $startRow = 12;
        foreach ($questions as $index => $question) {
            $sheet->mergeCells("A" . ($startRow + $index) . ":B" . ($startRow + $index));
            $sheet->mergeCells("C" . ($startRow + $index) . ":F" . ($startRow + $index));
            $sheet->setCellValue("A" . ($startRow + $index), $question);

            // Borders
            $sheet->getStyle("A" . ($startRow + $index) . ":F" . ($startRow + $index))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->mergeCells('E21:F21');
        $sheet->getStyle('E21:F21')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->setCellValue('E21', "Applicant Signature:   ");
        $sheet->getStyle('E21:F21')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);



     
        $sheet->mergeCells('A22:C22');
        $sheet->getStyle('A22:F22')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->setCellValue('A22', "Director of Work’s Remarks:");


        $sheet->mergeCells('A23:B25');
        $sheet->setCellValue('A26', '☐ APPROVED');
        $sheet->setCellValue('B26', '☐ DISAPPROVED');
        $sheet->mergeCells('A27:B27');
        $sheet->getStyle('A27:B27')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A27')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A27')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A27', '(Name and Signature)');

        //
        $sheet->mergeCells('A29:C29');
        $sheet->getStyle('A29:F29')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->setCellValue('A29', "Local Superior’s Remarks: (Also indicate if there are any health factors to be considered)");


        $sheet->mergeCells('A30:B32');
        $sheet->setCellValue('A33', '☐ APPROVED');
        $sheet->setCellValue('B33', '☐ DISAPPROVED');
        $sheet->mergeCells('A34:B34');
        $sheet->getStyle('A34:B34')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A34')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A34')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A34', '(Name and Signature)');

        //
        $sheet->mergeCells('A36:C36');
        $sheet->getStyle('A36:F36')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->setCellValue('A36', "Provincial’s Remarks:");


        $sheet->mergeCells('A37:B39');
        $sheet->setCellValue('A40', '☐ APPROVED');
        $sheet->setCellValue('B40', '☐ DISAPPROVED');
        $sheet->mergeCells('A41:B41');
        $sheet->getStyle('A41:B41')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A41')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A41')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A41', '(Name and Signature)');

        $sheet->mergeCells('A43:F43');
        $sheet->getRowDimension(43)->setRowHeight(50);
        $sheet->getStyle('A43')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A43')->applyFromArray([
            'font' => [
                'italic'=>true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->setCellValue('A43', "Once approved by the Provincial, a copy of the approved form will be emailed to the Applicant, Director of Work,\nLocal Superior, Province Treasurer and Socius.");

        //static layouts




        // Basic info
        $sheet->setCellValue('A2', $form->request->user->name);
        $sheet->setCellValue('E2', $form->request->updated_at);
        $sheet->setCellValue('A11', $form->request->intended_departure_date);
        $sheet->setCellValue('D11', $form->request->intended_return_date);

        // Answers start from A6
        $row = 12;
        foreach ($form->answers as $answer) {
            $sheet->setCellValue("C{$row}", $answer->answer);
            $row++;
        }


        $sheet->mergeCells('E18:F20');
        // Signature
        $signature = $form->request->user->signature ?? null;

        if (is_null($signature)) {
            if (!$request->has('force')) {
                return redirect()->back()->with('warning', 'The user has not submitted a signature. Do you still want to proceed with the export? 
                    <a href="' . route('travel-forms.export', ['id' => $id, 'force' => 1]) . '" class="btn btn-sm btn-warning ms-2">Yes, proceed</a>');
            }
        }

        // safe to use signature or not
        if (!is_null($signature)) {
            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('User Signature');
            $drawing->setPath(public_path('shared/' . $signature));
            $drawing->setHeight(60);
            $drawing->setCoordinates('E18');
            $drawing->setWorksheet($sheet);
        }

        $filename = 'overseas_travel_form_' . $form->id . '_clean.xlsx';
        $path = storage_path('app/public/' . $filename);

        (new Xlsx($spreadsheet))->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
