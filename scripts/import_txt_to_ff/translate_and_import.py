import Repository as rep
from slug_generate import slug_generate

from create_arr_of_chapters import read_txt_content_to_arr as content_to_arr
from translate import translate_text

from datetime import datetime
import time, random

fanfic_original_path = 'to_import_ffs/uninterestingguy__Legenda_o_Bleke._Kubok_Ognya_(SI)_Readli.Net_614205_original_0bc7e.txt'
fanfic_id = 1
is_draft = 0
language = 'ru'

# Використання поточного часу як сід для генератора випадкових чисел
random.seed(int(time.time()))


fanfic = content_to_arr('========== Глава', fanfic_original_path)


chapters_table = rep.Repository('chapters')

chapter_id = random.randint(100000, 999999)
chapter_num = 1

for chapter in fanfic:
    content = translate_text(chapter, language)

    chapters_table.create_row(
        {
            'id': chapter_id,
            'fanfiction_id': fanfic_id,
            'title': f'Розділ {chapter_num}',
            'slug': slug_generate(f'лоб келих вогню Розділ {chapter_num}'),
            'content': str(content),
            'is_draft': is_draft,
            'additional_descriptions': '{"notes": null, "notify": null}',
            'created_at': datetime.now()
        }
    )

    chapter_id += 1
    chapter_num += 1
