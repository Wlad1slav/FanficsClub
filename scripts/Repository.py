import mysql.connector
import json
import os
import numpy as np


def convert_types(row):
    # Перетворює типи numpy на рідні типи Python
    for key, value in row.items():
        if isinstance(value, np.integer):
            row[key] = int(value)
        elif isinstance(value, np.floating):
            row[key] = float(value)
        elif isinstance(value, np.ndarray):
            row[key] = value.tolist()
    return row


class Repository:
    config_path = os.path.join(os.path.dirname(__file__), 'config_db.json')

    def __init__(self, table):
        # Отримання параметрів підключення
        with open(self.config_path, 'r') as file:
            data = json.load(file)
        # Встановлення параметрів підключення
        config = {
            'host': data['host'],
            'user': data['user'],
            'password': data['pass'],
            'database': data['dbname']
        }

        self.table = table  # Таблиця, з якою будуть проводитися маніпуляції

        # Підключення до бази даних
        self.conn = self.connect(config)

    def connect(self, config):
        # Створення з'єднання
        try:
            connect = mysql.connector.connect(**config)
            if connect.is_connected():
                print(f'Connected to database ({self.table})')
                return connect

        except mysql.connector.Error as err:
            print(f'Error: {err}')

    def close_connect(self):
        # Закриття з'єднання
        if self.conn.is_connected():
            self.conn.close()
            print(f'The connection is closed ({self.table})')

    def get_all(self):
        # Повертає усі рядки бд
        cursor = self.conn.cursor()
        try:
            query = f'SELECT * FROM {self.table}'  # SQL запит для отримання рядків
            cursor.execute(query)
            rows = cursor.fetchall()  # Виконання SQL запиту
            return rows
        except mysql.connector.Error as err:
            print(f'Помилка запиту: {err}')
            return False
        finally:
            cursor.close()

    def get_all_in_dic(self):
        # Повертає усі рядки бази даних у якості словнику, де ключ є id
        rows = self.get_all()
        dic = {}
        for row in rows:
            dic[row[0]] = row
        return dic

    def get_row(self, value, column='id'):
        # Метод для отримання змісту рядку з бд

        cursor = self.conn.cursor()
        try:
            query = f'SELECT * FROM {self.table} WHERE {column} = %s'  # SQL запит для отримання рядка
            cursor.execute(query,
                           (value,))  # встановлення value в SQL запит через метод execute (захист від SQL ін'єкції)
            row = cursor.fetchone()  # Виконання SQL запиту
            return row
        except mysql.connector.Error as err:
            print(f'Помилка запиту: {err}')
            return False
        finally:
            cursor.close()

    def create_row(self, data):
        # Метод, для додавання рядку в бд

        columns = ', '.join([f'`{column}`' for column in data.keys()])  # Отримання колонок, в які треба додати данні
        placeholders = ', '.join(['%s' for _ in data.values()])  # Отримання даних, які треба додати в колонки

        query = f'INSERT INTO `{self.table}` ({columns}) VALUES ({placeholders})'  # SQL запит для створення рядку

        cursor = self.conn.cursor()

        try:
            cursor.execute(query, tuple(data.values()))  # Виконання SQL запиту
            self.conn.commit()  # Збереження результатів
            return True
        except mysql.connector.Error as err:
            print(f'Помилка запиту: {err}')
            return False
        finally:
            cursor.close()
