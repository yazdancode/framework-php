<?php
// ارسال هدر HTTP 404
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>صفحه پیدا نشد - 404</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f8f8f8;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 48px;
            color: #e74c3c;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>404 - صفحه پیدا نشد</h1>
    <p>متأسفیم، صفحه‌ای که دنبال آن بودید وجود ندارد.</p>
    <p><a href="/">بازگشت به صفحه اصلی</a></p>
</body>
</html>
