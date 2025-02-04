<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Carbon\Carbon;

class ReportSummaryExport extends DefaultValueBinder implements WithCustomValueBinder, FromView, ShouldAutoSize, WithTitle, WithEvents
{
    use Exportable;

    public function __construct($items = [])
    {
        $this->items = $items;
        $this->countRow = count($items) + 4;
        return $this;
    }
    
    public function bindValue(Cell $cell, $value)
    {
        $columns = [];
        foreach($columns as $item){
            if ($cell->getColumn()==$item) {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
                return true;
            }
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:B1');
                $event->sheet->mergeCells('C1:F1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('C2:F2');
                $event->sheet->mergeCells('A3:B3');
                $event->sheet->mergeCells('C3:F3');
                $event->sheet->getStyle('A4:E' . $this->countRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
    public function title(): string
    {
        return ucwords(__('Report Summary'));
    }
    public function view(): View
    {
        $auth = Auth::user();
        $date = Carbon::now()->format('d/m/Y H:i:s');

        return view('admin.export.export_summary', ['items' => $this->items, 'auth' => $auth, 'date' => $date]);
    }
}