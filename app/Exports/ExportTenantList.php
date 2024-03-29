<?php

namespace App\Exports;

use App\Models\Tenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTenantList implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tenants = Tenant::where('user_id', auth()->user()->id)->get();

        $data = [];

        foreach ($tenants as $index => $tenant) {
            $data[] = [
                'No.' => $index + 1,
                'Tenant Name' => $tenant->fullname,
                'Gender' => $tenant->gender,
                'Date of Birth' => $tenant->dob,
                'ID Card number' => $tenant->id_card,
                'Phone number' => $tenant->phone_number,
                'Email' => $tenant->email,
                'Hometown' => $tenant->hometown,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No.',
            'Full Name',
            'Gender',
            'Date of birth',
            'ID Card number',
            'Phone number',
            'Email',
            'Hometown',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply bold font style to the first row (i.e., the heading row)
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Set the border style for the entire sheet
        $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}
