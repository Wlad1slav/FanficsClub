
def read_txt_content_to_arr(separator, txt_path):
    # Читає txt файл і перетворює його на масив розділів

    # Відкриття та читання файлу
    with open(txt_path, 'r', encoding='utf-8') as file:
        file_content = file.read()

    return file_content.split(separator)
