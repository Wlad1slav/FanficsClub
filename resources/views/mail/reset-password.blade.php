<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Відновлення Пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #555;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 2px dashed rgb(30,30,30);
        }
        .button {
            display: inline-block;
            background-color: rgb(165, 60, 60);
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        p, a {
            font-size: 16px;
        }

        h1 {
            padding: 10px;
            background: rgb(165, 60, 60);
        }

        h2 {
            border-bottom: 4px solid rgb(30,30,30);
        }

    </style>
</head>
<body>

<div class="container">
    <h1 style="color: #ffffff;">Фанфіки українською мовою</h1>
    <h2>Відновлення Пароля</h2>
    <p>Ви отримали цей лист, тому що ми отримали запит на відновлення пароля для вашого акаунта.</p>
    <a style="color: #ffffff;" href="{{ route('password.reset', ['token'=>$token, 'email'=>$email]) }}" class="button">> Відновити Пароль</a>
    <p>Якщо ви не запитували відновлення пароля, будь ласка, ігноруйте цей лист.</p>

</div>

</body>
</html>
