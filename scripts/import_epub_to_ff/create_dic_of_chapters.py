import ebooklib
from ebooklib import epub
from bs4 import BeautifulSoup

def read_epub_content_to_dict(epub_path):
    book = epub.read_epub(epub_path)

    # Словник для зберігання назви розділу та його вмісту
    chapters_content = {}

    for item in book.get_items():
        if item.get_type() == ebooklib.ITEM_DOCUMENT:
            soup = BeautifulSoup(item.content, 'html.parser')
            # Використання назви розділу як ключа в словнику
            # Перевірка, чи існує title, інакше використовується ідентифікатор елемента
            chapter_title = soup.title.string if soup.title else item.get_name()
            # Збереження вмісту розділу за назвою розділу
            chapters_content[chapter_title] = soup.get_text()

    return chapters_content
