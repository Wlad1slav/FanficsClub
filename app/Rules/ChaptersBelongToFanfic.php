<?php

namespace App\Rules;

use App\Models\Chapter;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class ChaptersBelongToFanfic implements Rule
{

    private array $sequence;
    private Fanfiction $fanfic;

    /**
     * @return void
     */
    public function __construct(array $sequence, Fanfiction $fanfic)
    {
        $this->sequence = array_keys($sequence);
        sort($this->sequence);

        $this->fanfic = $fanfic;
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   // Перевіряє, чи усі розділи належать певному фанфіку

        $trueChapters = Cache::remember("chapters_ff_{$this->fanfic->id}_all_ids_array", 60*60, function () {
            // При створенні нового розділу чи оновленні існуючого кеш повинен оновлюватися

            return Chapter::where('fanfiction_id', $this->fanfic->id)->pluck('id')->toArray();
        });

        return $trueChapters == $this->sequence;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Перевірте, чи усі введені вами розділи належать цьому твору (адміністрація щиро захоплюється вашою маленькою витівкою).';
    }
}
