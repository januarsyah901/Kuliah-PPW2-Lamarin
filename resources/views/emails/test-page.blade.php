<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SMTP Email - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .test-card {
            max-width: 600px;
            width: 100%;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #f0f0f0;
            border-radius: 15px 15px 0 0 !important;
            padding: 30px;
        }
        .card-body {
            padding: 30px;
        }
        .email-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .config-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .config-info table {
            margin: 0;
        }
        .config-info td {
            padding: 8px 0;
        }
        .config-info td:first-child {
            font-weight: 600;
            color: #495057;
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <div class="card">
            <div class="card-header text-center">
                <div class="email-icon">📧</div>
                <h2 class="mb-2">Test SMTP Email</h2>
                <p class="text-muted mb-0">Test konfigurasi Gmail SMTP Anda</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>✓ Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>✗ Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('test-email.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email Tujuan</label>
                        <input
                            type="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="contoh@gmail.com"
                            value="{{ old('email', 'januarsyahakbar791@gmail.com') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan email yang ingin menerima email test</div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <strong>Kirim Email Test</strong>
                    </button>
                </form>

                <div class="config-info">
                    <h6 class="mb-3"><strong>📋 Konfigurasi Saat Ini:</strong></h6>
                    <table class="w-100">
                        <tr>
                            <td>SMTP Host</td>
                            <td>{{ config('mail.mailers.smtp.host') }}</td>
                        </tr>
                        <tr>
                            <td>SMTP Port</td>
                            <td>{{ config('mail.mailers.smtp.port') }}</td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{ config('mail.mailers.smtp.username') }}</td>
                        </tr>
                        <tr>
                            <td>From Address</td>
                            <td>{{ config('mail.from.address') }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                @if(config('mail.default') === 'smtp')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

