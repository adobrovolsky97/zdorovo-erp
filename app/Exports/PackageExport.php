<?php

namespace App\Exports;

use App\Models\Package\Package;
use App\Models\Package\PackageProduct;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class PackageExport
 */
class PackageExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var Package
     */
    private Package $package;

    /**
     * @param Package $package
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->package->packageProducts;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Назва',
            'К-ть',
            'Ціна закупки'
        ];
    }

    /**
     * @param PackageProduct $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->product->external_id,
            null,
            $row->product->name,
            $row->quantity,
            $row->product->bimpsoft_price ?? $row->product->price
        ];
    }
}
