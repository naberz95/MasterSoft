<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'MASTERSOFT') }} - Autenticación</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .auth-header {
            background: linear-gradient(135deg, #3c6bd3 0%, #2851b8 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .auth-header .logo-container {
            margin-bottom: 20px;
        }
        
        .auth-header .logo-container i {
            font-size: 3.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .auth-header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .auth-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 300;
        }
        
        .auth-body {
            padding: 40px 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3c6bd3;
            box-shadow: 0 0 0 0.2rem rgba(60, 107, 211, 0.25);
            transform: translateY(-1px);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3c6bd3 0%, #2851b8 100%);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
            border-radius: 8px;
            font-size: 1.1rem;
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(60, 107, 211, 0.3);
            background: linear-gradient(135deg, #2851b8 0%, #1a3d8a 100%);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .auth-footer {
            text-align: center;
            padding: 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .btn-link {
            color: #3c6bd3;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-link:hover {
            color: #2851b8;
            text-decoration: underline;
            transform: translateY(-1px);
        }
        
        .form-check-input:checked {
            background-color: #3c6bd3;
            border-color: #3c6bd3;
        }
        
        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @media (max-width: 576px) {
            .auth-card {
                margin: 10px;
                border-radius: 10px;
            }
            
            .auth-header, .auth-body {
                padding: 30px 20px;
            }
            
            .auth-header h1 {
                font-size: 1.8rem;
            }
            
            .auth-header .logo-container i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>