import Repository as rep
from slug_generate import slug_generate

# Читання розділів фанфіку і перетворення їх на словник
from create_dic_of_chapters import read_epub_content_to_dict
from datetime import datetime

# Шлях до EPUB файлу
fanfic_original_path = 'to_import_ffs/hpimr1.epub'
fanfic_id = 1
is_draft = 0

# Контент фанфіку
content_dict = read_epub_content_to_dict(fanfic_original_path)

chapters_table = rep.Repository('chapters')

for title, content in content_dict.items():
    # print(f"Chapter: {title}\nContent: {content}\n\n")
    print(f"Розділ {title} ({slug_generate(title)}) згенерований")

    chapters_table.create_row(
        {
            'fanfiction_id': fanfic_id,
            'title': str(title),
            'slug': slug_generate(title),
            'content': str(content),
            'is_draft': is_draft,
            'additional_descriptions': '{"notes": null, "notify": null}',
            'created_at': datetime.now()
        }
    )

