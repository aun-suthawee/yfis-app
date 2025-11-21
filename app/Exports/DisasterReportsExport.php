<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DisasterReportsExport implements FromCollection, WithHeadings
{
    use Exportable;

    /**
     * @var \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private Collection $rows;

    /**
     * @param Collection<int, array<string, mixed>> $rows
     */
    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return array_keys($this->rows->first() ?? []);
    }
}
