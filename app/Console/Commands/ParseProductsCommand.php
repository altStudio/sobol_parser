<?php

namespace App\Console\Commands;

use App\Exports\ProductExport;
use App\Models\Product;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel as ExcelConstants;
use Maatwebsite\Excel\Facades\Excel;

class ParseProductsCommand extends Command
{
    private int $counter = 100;

    private array $productTemplate = [
        "Артикул" => "0",
        "Название" => "0",
        "Активность" => "0",
        "Slug" => "0",
        "Описание" => "0",
        "Родительская категория товара" => "0",
        "В каких категориях показывать товар" => "0",
        "Ссылки на изображения" => "0",
        "Новинка" => "0",
        "Популярный" => "0",
        "Особый" => "0",
        "Тип меха" => "0",
        "Размеры" => "0",
        "Длина" => "0",
        "Воротник/капюшон" => "0",
        "Капюшон" => "0",
        "Отделка" => "0",
        "Силуэт" => "0",
        "Застежка" => "0",
        "Рукав" => "0",
        "Без утеплителя" => "0",
        "С утеплителем" => "0",
        "Цвет" => "0",
        "Цена" => "0",
        "Цена со скидкой" => "0"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $export = [];
        $products = Product::all();

        foreach ($products as $product) {
            if (strlen($product->title) > 0) {
                //Создаем новый продукт для экспорта
                $this->createProduct($product);

                //Добавляем новый продукт в экспорт
                $export[] = $this->productTemplate;
            }
        }

        Excel::store(new ProductExport($export), 'products1.xlsx', 'public', ExcelConstants::XLSX);

        return Command::SUCCESS;
    }

    /**
     * @param Product $product
     * @return void
     */
    private function createProduct(Product $product): void
    {
        $this->info("Parsing: " . $product->title);

        //Поля, заполняемые сразу
        $this->productTemplate["Название"] = $product->title;
        $this->productTemplate["Slug"] = $product->slug;
        $this->productTemplate["Новинка"] = $product->is_new;
        $this->productTemplate["Популярный"] = $product->is_hit;
        $this->productTemplate["Длина"] = $product->length;
        $this->productTemplate["Цена"] = $product->price;
        $this->productTemplate["Цена со скидкой"] = $product->price_sale;

        //Поля, заполняемые чуть сложнее
        $this->productTemplate["Описание"] = $product->getDescription();
        $this->productTemplate["Активность"] = $product->isActive();
        $this->productTemplate["Воротник/капюшон"] = $product->hasHood();
        $this->productTemplate["Капюшон"] = $product->hood();
        $this->productTemplate["Артикул"] = $this->counter++;

        //Сложные поля
        $this->productTemplate["Ссылки на изображения"] = $product->getImagesLinks();
        $this->productTemplate["Тип меха"] = $product->getFur();
        $this->productTemplate["Отделка"] = $product->getTrim();
        $this->productTemplate["Цвет"] = $product->getColors();
        $this->productTemplate["Размеры"] = $product->getSizes();
        $this->productTemplate["В каких категориях показывать товар"] = $product->getCategory();
        $this->productTemplate["Родительская категория товара"] = $product->getGender();
    }
}
