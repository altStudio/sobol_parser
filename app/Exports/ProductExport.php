<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class ProductExport extends StringValueBinder implements FromArray, WithHeadings, WithStrictNullComparison
{
    public function __construct(
        protected array $products
    )
    { }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            "Артикул",
            "Название",
            "Активность",
            "Slug",
            "Описание",
            "Родительская категория товара",
            "В каких категориях показывать товар",
            "Ссылки на изображения",
            "Новинка",
            "Популярный",
            "Особый",
            "Тип меха",
            "Размеры",
            "Длина",
            "Воротник/капюшон",
            "Капюшон",
            "Отделка",
            "Силуэт",
            "Застежка",
            "Рукав",
            "Без утеплителя",
            "С утеплителем",
            "Цвет",
            "Цена",
            "Цена со скидкой"
        ];
    }
}
