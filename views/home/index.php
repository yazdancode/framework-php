<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خوش آمدید | صفحه اصلی</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        /* Container styling */
        .container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }

        /* Header styling */
        h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: #2c3e50;
            position: relative;
            padding-bottom: 15px;
            font-weight: 700;
        }

        h1:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 2px;
        }

        /* Paragraph styling */
        p {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 25px;
            color: #555;
            text-align: justify;
            text-justify: inter-word;
        }

        /* Features section */
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        .feature {
            flex: 1;
            min-width: 200px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .feature h3 {
            color: #2980b9;
            margin-bottom: 10px;
        }

        /* Button styling */
        .cta-button {
            display: inline-block;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            margin-top: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        /* Footer styling */
        footer {
            margin-top: 40px;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 25px;
            }

            h1 {
                font-size: 2.2rem;
            }

            .features {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>به سایت ما خوش آمدید</h1>
    <p>از اینکه ما را انتخاب کرده‌اید بسیار خوشحالیم. اینجا می‌توانید جدیدترین خدمات و محصولات ما را کشف کنید و از تجربه‌ای لذت بخش در فضایی امن و کاربرپسند بهره‌مند شوید.</p>

    <div class="features">
        <div class="feature">
            <h3>طراحی مدرن</h3>
            <p>طراحی زیبا و واکنش‌گرا که در تمام دستگاه‌ها به بهترین شکل نمایش داده می‌شود.</p>
        </div>
        <div class="feature">
            <h3>کاربری آسان</h3>
            <p>رابط کاربری intuitive که با توجه به نیاز کاربران طراحی شده است.</p>
        </div>
        <div class="feature">
            <h3>امنیت بالا</h3>
            <p>سیستم‌های امنیتی پیشرفته برای حفاظت از اطلاعات شما.</p>
        </div>
    </div>

    <a href="#" class="cta-button">شروع کنید</a>
</div>

<footer>
    <p>© ۱۴۰۲ نام شرکت. تمامی حقوق محفوظ است.</p>
</footer>
</body>
</html>