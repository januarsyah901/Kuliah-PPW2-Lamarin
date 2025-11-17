<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .success-message {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
            color: #155724;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-box td:first-child {
            font-weight: 600;
            color: #495057;
            width: 40%;
        }
        .info-box td:last-child {
            color: #6c757d;
        }
        .info-box tr:last-child td {
            border-bottom: none;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">✉️</div>
            <h1>SMTP Test Berhasil!</h1>
            <span class="badge">{{ $testData['app_name'] }}</span>
        </div>

        <div class="email-body">
            <div class="success-message">
                <strong>🎉 Selamat!</strong><br>
                {{ $testData['message'] }}
            </div>

            <p>Email ini dikirim untuk memastikan bahwa konfigurasi SMTP Gmail Anda sudah berjalan dengan baik.</p>

            <div class="info-box">
                <table>
                    <tr>
                        <td>Aplikasi</td>
                        <td>{{ $testData['app_name'] }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Pengiriman</td>
                        <td>{{ $testData['date'] }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><strong style="color: #28a745;">✓ Berhasil</strong></td>
                    </tr>
                    <tr>
                        <td>SMTP Server</td>
                        <td>Gmail (smtp.gmail.com)</td>
                    </tr>
                </table>
            </div>

            <p style="color: #6c757d; font-size: 14px; margin-top: 30px;">
                Jika Anda menerima email ini, berarti konfigurasi SMTP sudah benar dan siap digunakan untuk mengirim email dari aplikasi Anda.
            </p>
        </div>

        <div class="email-footer">
            <p>Email ini dikirim secara otomatis dari sistem testing<br>
            <strong>{{ $testData['app_name'] }}</strong></p>
        </div>
    </div>
</body>
</html>

