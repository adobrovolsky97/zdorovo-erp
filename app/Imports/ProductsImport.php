<?php

namespace App\Imports;

use App\Enum\Product\Label;
use App\Enum\Product\Pack;
use App\Models\Product\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection, WithChunkReading, WithStartRow
{
    public function chunkSize(): int
    {
        return 300;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            if (empty($row[0])) {
                continue;
            }

            $product = Product::query()->where('barcode', $row[0])->first();

            if (empty($product)) {
                continue;
            }

            $label = null;
            if (!empty($row[2])) {
                $label = Label::getByName($row[2]);
            }

            $pack = null;

            if (!empty($row[3])) {
                $pack = Pack::getByName($row[3]);
            }

            $product->updateQuietly([
                'label'        => $label,
                'pack'         => $pack,
                'safety_stock' => $row[4] ?? null,
            ]);
        }
    }
}
