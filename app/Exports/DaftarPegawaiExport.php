<?php

namespace App\Exports;

use App\Services\DaftarPegawai\DaftarPegawaiService;
use Generator;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DaftarPegawaiExport extends DefaultValueBinder implements FromGenerator, WithCustomValueBinder, WithEvents, WithHeadings
{
    protected $params;

    protected $daftarPegawaiService;

    public function __construct(array $params, DaftarPegawaiService $daftarPegawaiService)
    {
        $this->params = $params;
        $this->daftarPegawaiService = $daftarPegawaiService;

    }

    public function generator(): Generator
    {
        $timestamp = time();
        $no = 1;

        $additional = [
            'page' => 1,
            'limit' => 200,
        ];

        do {
            $all_params = array_merge($this->params, $additional);
            ksort($all_params);
            $signature = create_signature($all_params, $timestamp);
            $response = Http::withHeaders([
                'X-Signature' => $signature,
                'X-Timestamp' => $timestamp,
                'Content-Type' => 'application/json',
            ])->get('http://127.0.0.1:9000/api/employees', $all_params);

            if (! $response->successful()) {
                break;
            }

            $data = $response->json();

            foreach ($data as $item) {

                unset($item['id_pegawai'], $item['kode_skpd']);
                yield array_merge(['No' => $no++], $item);

            }

            $hasMore = count($data) === $additional['limit'];
            $additional['page'] += 1;

        } while ($hasMore);

    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Pegawai',
            'NIP',
            'Status Kepegawaian',
            'Jenis Pengadaan PPPK',
            'Pangkat/Gol Ruang',
            'TMT Pangkat',
            'No SK CPNS',
            'Tgl SK CPNS',
            'TMT CPNS',
            'No SK PNS',
            'Tgl SK PNS',
            'TMT PNS',
            'NIK',
            'NPWP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Jenis Kelamin',
            'Agama',
            'Telepon',
            'Tipe Jabatan',
            'Eselon',
            'Jabatan',
            'TMT Jabatan',
            'Jenis Jenjang Jabatan',
            'Jenjang Jabatan',
            'SKPD',
            'Pendidikan Terakhir',
            'Jurusan',
            'Nama Sekolah',
            'Alamat',
            'Email',
            'Status Pernikahan',
            'Nama Suami/Istri',
            'Pekerjaan Pasangan',
            'Tanggal Nikah',
        ];
    }

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         1 => [
    //             'font' => ['bold' => true],
    //         ],
    //     ];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'DAFTAR PEGAWAI DENGAN KRITERIA TERTENTU');

                $sheet->mergeCells('A1:AL1');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getRowDimension(2)->setRowHeight(20);
                for ($row = 3; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }

                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A2:'.$highestColumn.$highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    );

                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }
            },
        ];
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        if (in_array($cell->getColumn(), ['N', 'U', 'O'])) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'N' => '@',
            'U' => '@',
            'O' => '@',
        ];
    }
}
