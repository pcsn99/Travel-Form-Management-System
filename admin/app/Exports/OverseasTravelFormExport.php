<?php

namespace App\Exports;

use App\Models\OverseasTravelForm;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OverseasTravelFormExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    protected $form;

    public function __construct(OverseasTravelForm $form)
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
        $f = $this->form;
        $user = $f->request->user;
    
        $rows = [];
    
        // Title + Applicant Info
        $rows[] = ['Application v2023-1', '', 'Application Date:'];
        $rows[] = ["Applicant's Name: {$user->name}", '', ''];
        $rows[] = ['For Overseas Travel', '', ''];
    
        // Instructions
        $rows[] = ['[a] Accomplish this form electronically. Then submit this form with a scan of your passport page.'];
        $rows[] = ['[b] For trips that require Nuncio’s endorsement, submit this form at least 2 months ahead.'];
        $rows[] = ['[c] Your Director of Work (if applicable) and local Superior must sign this before it is sent to the Provincial.'];
        $rows[] = ['[d] Procure travel insurance online or through your travel agent.'];
        $rows[] = ['[e] For trips to be financed by the Province, submit a budget to the Provincial for approval.'];
    
        $rows[] = ['Departure Date:', $f->request->intended_departure_date, 'Return Date: ' . $f->request->intended_return_date];
    
        // Travel Request Questions (formatted like Itinerary, Purpose, etc.)
        foreach ($f->request->answers as $a) {
            $rows[] = [$a->question->question, $a->answer];
        }
    
        $rows[] = ['']; // Spacer
    
        // Form Answers
        foreach ($f->answers as $a) {
            $rows[] = [$a->question->question, $a->answer];
        }
    
        $rows[] = [''];
        $rows[] = ['Applicant Signature', '______________________________'];
        $rows[] = ['']; // Spacer
    
        // Director of Work Remarks
        $rows[] = ["Director of Work’s Remarks:"];
        $rows[] = ['☐ APPROVED   ☐ DISAPPROVED', '(Name and Signature)'];
        $rows[] = [''];
    
        // Local Superior Remarks
        $rows[] = ["Local Superior’s Remarks: (Also indicate if there are any health factors to be considered)"];
        $rows[] = ['☐ APPROVED   ☐ DISAPPROVED', '(Name and Signature)'];
        $rows[] = [''];
    
        // Provincial Remarks
        $rows[] = ['Provincial’s Remarks:'];
        $rows[] = ['☐ APPROVED   ☐ DISAPPROVED', '(Name and Signature)'];
    
        $rows[] = [''];
        $rows[] = ['Once approved by the Provincial, a copy of the approved form will be emailed to the Applicant, Director of Work, Local Superior, Province Treasurer and Socius.'];
    
        return $rows;
    }
    

    public function title(): string
    {
        return 'Overseas Travel Form';
    }

    public function styles(Worksheet $sheet)
    {
        // Title styling
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    
        // Wrap text for full range
        $sheet->getStyle('A1:C50')->getAlignment()->setWrapText(true);
    
        // Bold section titles (example: Director, Local Superior, etc.)
        foreach ([2, 3, 16, 20, 24, 27] as $row) {
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        }
    
        // Column width
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(30);
    
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
                $signature->setCoordinates('B40'); // Adjust depending on your content
                $signature->setOffsetX(10);
    
                $drawings[] = $signature;
            }
        }
    
        return $drawings;
    }
}

