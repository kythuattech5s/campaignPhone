<?php
namespace vanhenry\manager\helpers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportView implements FromView, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;

    public $data;
    public $fields;
    public $notes;
    public function __construct($data, $fields, $notes){
        $this->data = $data;
        $this->fields = $fields;
        $this->notes = $notes;
    }

    public function view(): View
    {
        return view('vh::table_export', [
            'data' => $this->data,
            'fields' => $this->fields,
            'notes' => $this->notes
        ]);
    }

    public static function afterSheet(AfterSheet $event)
    {
         $default_font_style = [
            'font' => ['name' => 'Arial', 'size' => 12]
        ];

        $heading = [
            'font' => ['bold' => true]
        ];

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border::lookup(0x01),
                ]
            ],
        ];

        $active_sheet = $event->sheet->getDelegate();
        $column = $active_sheet->getColumnDimensions();
        $columnHeading = "A1:".end($column)->getColumnIndex().'1';
        $cells = $active_sheet->getActiveCell().':'.$active_sheet->getCellCollection()->getCurrentCoordinate();
        $active_sheet->getParent()->getDefaultStyle()->applyFromArray($default_font_style);
        $active_sheet->getStyle($cells)->applyFromArray($styleArray);
        $active_sheet->getStyle($columnHeading)->applyFromArray($heading);
    }
}
