<?php

namespace App\Imports;

use App\Models\OrderedProduct\OrderedProduct;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     * @return OrderedProduct
     */
    public function model(array $row): ?OrderedProduct
    {
        if(empty($row['nomer_zaiavki']) || empty($row['data_stvorennia']) || empty($row['id_tovariposlugi']) || empty($row['k_t_tovariposlugi'])) {
            return null; // Skip rows with missing required fields
        }

        $rawDate = $row['data_stvorennia'];
        $createdAt = is_numeric($rawDate)
            ? Carbon::instance(Date::excelToDateTimeObject($rawDate))
            : Carbon::parse($rawDate);

        return new OrderedProduct([
            'order_id' => $row['nomer_zaiavki'],
            'product_external_id' => $row['id_tovariposlugi'],
            'quantity' => $row['k_t_tovariposlugi'],
            'order_date' => $createdAt->format('Y-m-d'),
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
