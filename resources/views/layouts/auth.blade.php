<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fuel System') }} - @yield('title', 'Login')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #3b82f6;
            --primary-dark: #1e40af;
            --sidebar-bg: #0f172a;
            --content-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--text-dark);
            font-size: 0.9375rem;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        /* ANIMATED BACKGROUND */
        .auth-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            z-index: -1;
            overflow: hidden;
        }

        .auth-bg::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            animation: float 20s infinite ease-in-out;
        }

        .auth-bg::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            bottom: -300px;
            right: -300px;
            animation: float 25s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(30px, -30px);
            }
        }

        /* CONTAINER */
        .auth-container {
            position: relative;
            z-index: 1;
            max-width: 450px;
            width: 100%;
            padding: 1rem;
        }

        /* CARD */
        .auth-card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .auth-card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .auth-card-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
            font-weight: 500;
        }

        .auth-card-body {
            padding: 2rem;
        }

        /* FORM ELEMENTS */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
            display: block;
            letter-spacing: -0.3px;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.6rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all var(--transition);
            background-color: #f8fafc;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* ERROR MESSAGES */
        .error-message {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: block;
            font-weight: 500;
        }

        .alert {
            border-radius: 0.6rem;
            border: none;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #991b1b;
        }

        /* CHECKBOX */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            margin-top: 0.2rem;
            border: 1px solid var(--border-color);
            border-radius: 0.4rem;
            cursor: pointer;
            transition: all var(--transition);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            user-select: none;
            margin: 0;
        }

        /* BUTTONS */
        .btn {
            border-radius: 0.6rem;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            transition: all var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            width: 100%;
            letter-spacing: -0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* LINKS */
        .text-center-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition);
            position: relative;
        }

        .auth-link:hover {
            color: var(--primary-dark);
        }

        .auth-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform var(--transition);
        }

        .auth-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* DEMO CREDENTIALS */
        .auth-footer {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .auth-footer p {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .demo-credentials {
            background-color: #f8fafc;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-family: 'Monaco', 'Courier New', monospace;
            color: #0f172a;
            border-left: 3px solid var(--primary);
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .auth-container {
                max-width: 100%;
            }

            .auth-card-header {
                padding: 2rem 1.5rem;
            }

            .auth-card-header h2 {
                font-size: 1.5rem;
            }

            .auth-card-body {
                padding: 1.5rem;
            }

            .btn {
                padding: 0.7rem 1.25rem;
                font-size: 0.9rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-bg"></div>
    
    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.querySelector('.btn-close').onclick) {
                    setTimeout(() => {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }, 5000);
                }
            });

            // Add focus effects
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
