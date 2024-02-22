from deep_translator import GoogleTranslator
import time


def split_text(text, chunk_size=3000):
    return [text[i:i + chunk_size] for i in range(0, len(text), chunk_size)]


def translate_text(text, source_lang, target_lang='uk'):
    chunks = split_text(text)
    # for chunk in chunks:
    #     print(GoogleTranslator(source=source_lang, target=target_lang).translate(chunk))
    translated_chunks = [GoogleTranslator(source=source_lang, target=target_lang).translate(chunk) for chunk in chunks]
    return ''.join(translated_chunks)


def translate(chapters, source_lang):
    translated = []
    # Перекладає масив розділів на укр мову
    for chapter in chapters:
        t = translate_text(chapter, source_lang)
        translated.append(t)
        print(t)

    return translated
