<?php

namespace App\Exports;

use App\Models\LocalTravelForm;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LocalTravelFormExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    protected $form;

    public function __construct(LocalTravelForm $form)
    {
        $this->form = $form->load([
            'answers.question',
            'request.user',
            'request.answers.question', 
            'supervisor'
        ]);
    }



    public function array(): array
    {
        $rows = [];

        $rows[] = ['Application for Local Travel'];
        $rows[] = [''];

        $rows[] = ['-- Applicant Info --'];
        $rows[] = ['Name', $this->form->request->user->name];
        $rows[] = ['Type', $this->form->request->type];
        $rows[] = ['Departure Date', $this->form->request->intended_departure_date];
        $rows[] = ['Return Date', $this->form->request->intended_return_date];
        $rows[] = ['Status', ucfirst($this->form->status)];


        $rows[] = ['Travel Request Answers'];
        foreach ($this->form->request->answers as $reqAnswer) {
            $rows[] = [$reqAnswer->question->question, $reqAnswer->answer];
        }
        $rows[] = [''];

        $rows[] = ['-- Questionnaire --'];
        foreach ($this->form->answers as $answer) {
            $rows[] = [$answer->question->question, $answer->answer];
        }

        $rows[] = [''];
        $rows[] = ['-- Remarks --'];
        $rows[] = ['Admin Comment', $this->form->admin_comment ?? ''];

        $rows[] = [''];
        $rows[] = ['-- Signatures --'];
        $rows[] = ['Applicant', '________________________'];
        $rows[] = ['Director of Work', '________________________'];
        $rows[] = ['Local Superior', '________________________'];
        $rows[] = ['Provincial', '________________________'];

        return $rows;
    }

    public function title(): string
    {
        return 'Local Travel Form';
    }

    public function styles(Worksheet $sheet)
    {
        // Apply general styles
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:B1')->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Bold section titles
        foreach ([3, 4, 10, 13, 18] as $row) {
            $sheet->getStyle("A$row")->getFont()->setBold(true);
        }

        // Enable word wrap in all columns
        $sheet->getStyle('A1:B50')->getAlignment()->setWrapText(true);

        // Optional: Auto width
        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function drawings(): array
    {
        $drawings = [];
    
        if ($this->form->local_supervisor && $this->form->supervisor && $this->form->supervisor->signature) {
            $path = public_path('storage/' . $this->form->supervisor->signature);
    
            if (file_exists($path)) {
                $signature = new Drawing();
                $signature->setName('Supervisor Signature');
                $signature->setDescription('Supervisor Signature');
                $signature->setPath($path);
                $signature->setHeight(50);
                $signature->setCoordinates('B28'); // Adjust row/column as needed
                $signature->setOffsetX(10);
    
                $drawings[] = $signature;
            }
        }
    
        return $drawings;
    }
}
