<?php

namespace App\Exports;

use App\Models\Product\Product;
use App\Services\Product\Contracts\ProductServiceInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Class ConsumptionsExport
 */
class ProductsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithColumnFormatting
{
    protected array $searchParams;

    public function __construct(array $searchParams)
    {
        $this->searchParams = $searchParams;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $service = app(ProductServiceInterface::class);
        $dataCollection = collect();

        $page = 1;

        do {
            $paginator = $service->getAllPaginated(array_merge($this->searchParams, ['page' => $page]));

            /** @var Product $product */
            foreach ($paginator->items() as $product) {
                $dataCollection->push([
                    ' ' . (string)$product->barcode,
                    (string)$product->name,
                    (string)$product->label?->title() ?? ' ',
                    (string)$product->pack?->title() ?? ' ',
                    (string)$product->qty_in_stock ?? '0',
                    (string)$product->ordered_qty ?? '0',
                    (string)$product->daily_demand ?? ' ',
                    (string)$product->safety_stock ?? ' ',
                    (string)$product->qty_to_process ?? ' ',
                ]);
            }

            $lastPage = $paginator->lastPage();
            $page++;
        } while ($page <= $lastPage);

        return $dataCollection;
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Штрих-код',
            'Назва товару',
            'Ярлик',
            'Упаковка',
            'Залишок на складі',
            'Кількість у замовленнях',
            'Денний попит',
            'Резерв',
            'Потрібно нафасувати',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT
        ];
    }
}
