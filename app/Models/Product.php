<?php

namespace App\Models;

use App\Constants\DictTypes;
use App\Constants\GendersByCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property bool $has_hood
 * @property int $id
 * @property bool $active
 * @property string $title
 * @property int $is_new
 * @property int $is_hit
 * @property int $length
 * @property string $code
 * @property string $description
 * @property int $category_id
 * @property string $slug
 * @property float $price
 * @property float $price_sale
 */
class Product extends Model
{
    use HasFactory;

    protected $table = "product";

    /**
     * @return int
     */
    public function isActive(): int
    {
        return (int)$this->active;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return Str::replace(["<p>", "</p>"], "", $this->description);
    }

    /**
     * @return int
     */
    public function hasHood(): int
    {
        return $this->has_hood ? 1 : 0;
    }

    /**
     * @return int
     */
    public function hood(): int
    {
        return $this->has_hood == 1 ? 1 : 0;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        if ($this->code !== "") {
            return Str::replace("old:model:", "", $this->code);
        }
        else {
            $article = "";
            $name = trim($this->title);

            for ($i = Str::length($name) - 1; mb_substr($name, $i, 1, 'utf-8') !== " " || mb_substr($name, $i, 1, 'utf-8') !== " "; $i--) {
                $article .= mb_substr($name, $i, 1, 'utf-8');
            }

            return strrev(trim($article));
        }
    }

    /**
     * @return string
     */
    public function getImagesLinks(): string
    {
        $images = Image::where('owner_type', 2)->where('owner_id', $this->id)->get();

        $imagesString = "";

        foreach ($images as $image) {
            $imagesString .= "https://sobol-mex.ru/images/fcrp-w800" . $image->filename . "|";
        }

        return $this->substrLastSymbols($imagesString);
    }

    /**
     * @return string
     */
    public function getFur(): string
    {
        return $this->getAttributeByType(DictTypes::FUR);
    }

    /**
     * @return string
     */
    public function getTrim(): string
    {
        return $this->getAttributeByType(DictTypes::TRIM);
    }

    /**
     * @return string
     */
    public function getColors(): string
    {
        return $this->getAttributeByType(DictTypes::COLOR);
    }

    /**
     * @return string
     */
    public function getSizes(): string
    {
        return $this->getAttributeByType(DictTypes::SIZE);
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        $category = Dictionary::where('id', $this->category_id)->where('type', DictTypes::CATEGORY)->first();

        return $category ? $category->title : "0";
    }

    /**
     * @return int|string
     */
    public function getGender(): int|string
    {
        return array_key_exists($this->category_id, GendersByCategory::IDS) ? GendersByCategory::IDS[$this->category_id] : "0";
    }

    /**
     * @param int $type
     * @return string
     */
    private function getAttributeByType(int $type): string
    {
        $result = "0";

        //Для начала ищем все атрибуты по заданному типу
        $attrs = ProductTag::where('type', $type)->where('product_id', $this->id)->select('dictionary_id')->get();

        if ($attrs->isNotEmpty()) {
            $result = "";

            //если они есть - удаляем дубликаты и берем их названия из словаря
            $attrs = $attrs->pluck('dictionary_id')->unique();

            foreach ($attrs as $attr) {
                $result .= DB::table('dictionary')->find($attr)->title . "|";
            }

            //Удаляем последние ' | '
            $result = $this->substrLastSymbols($result);
        }

        return $result;
    }

    /**
     * @param string $string
     * @return string
     */
    private function substrLastSymbols(string $string): string
    {
        return Str::substr($string, 0, Str::length($string) - 1);
    }

}
