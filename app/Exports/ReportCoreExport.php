<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportCoreExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $dataSummary;
    private $dataDetail;

    public function __construct($data, $title)
    {
        $this->dataSummary = $data['summary'];
        $this->dataDetail = $data['detail'];
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[0] = new ReportSummaryExport($this->dataSummary);
        $sheets[1] = new ReportDetailExport($this->dataDetail);
        return $sheets;
    }
}