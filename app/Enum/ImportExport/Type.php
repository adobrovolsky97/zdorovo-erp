<?php

namespace App\Enum\ImportExport;


use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

/**
 * Class Type
 */
enum Type: string
{
    case PRODUCTS = 'products';

    /**
     * @return string
     */
    public function getExportClass(): string
    {
        return match ($this) {
            self::PRODUCTS => ProductsExport::class,
        };
    }

    public function getImportClass(): string
    {
        return match ($this) {
            self::PRODUCTS => ProductsImport::class,
        };
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getName(array $params): string
    {
        $today = date('d.m.Y');

        return match ($this) {
            self::PRODUCTS => "Товари_за_$today.xlsx",
        };
    }
}
