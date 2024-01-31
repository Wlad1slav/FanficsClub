<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

trait BaseGenerationTrait
{
    use SlugGenerationTrait;

    // private array $BASE_ROWS; // Базові рядки таблиці
    // private bool $hasSlug; // Чи є в таблиці моделі slug?

    public function generate(): void
    {
        // Метод, що генерує рядки в таблицю

        if (!isset($this->hasSlug) or !isset($this->BASE_ROWS))
            throw new \InvalidArgumentException
                ('BaseGenerationTrait->generate(): Властивість hasSlug чи BASE_ROWS не встановленні.');

        if ($this->hasSlug)
            // Якщо в таблиці є slug,
            // то відбувається перевірка,
            // чи в усіх рядках майбутніх масивів він заданий
            foreach ($this->BASE_ROWS as $num => $category) {
                // Якщо в якомусь з масивів не задан slug,
                // то він генерується
                if (!isset($category['slug']))
                    $category['slug'] = self::getSlug($category['name']);
                $this->BASE_ROWS[$num] = $category;
            }

        // Після перевірки коректності масиву, дані заносяться в таблицю
        $db = DB::table($this->getTable());
        $db->insert($this->BASE_ROWS);
    }

    public function getSlugStatus(): bool
    {
        return $this->hasSlug;
    }

    public function getBaseRows(): array
    {
        return $this->BASE_ROWS;
    }
}
