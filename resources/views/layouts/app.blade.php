<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fuel System') }} - @yield('title', 'Dashboard')</title>

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
            --primary-light: #dbeafe;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --content-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.15);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--content-bg);
            color: var(--text-dark);
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        /* LAYOUT */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: white;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
            transition: transform var(--transition), width var(--transition);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Sidebar Brand */
        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
        }

        .sidebar-brand-icon {
            font-size: 2rem;
            line-height: 1;
            filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.2));
        }

        .sidebar-brand-text {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sidebar Nav */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav-section {
            padding: 1.25rem 1.5rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-nav-section::before {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
        }

        .sidebar-nav-section:first-of-type {
            margin-top: 0.5rem;
        }

        .sidebar-nav-item {
            position: relative;
            margin: 0.25rem 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.75);
            border-radius: 0.6rem;
            text-decoration: none;
            transition: all var(--transition);
            border-left: 3px solid transparent;
            margin-left: 0;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: white;
            border-left-color: rgba(59, 130, 246, 0.5);
            transform: translateX(2px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, transparent 100%);
            color: #3b82f6;
            border-left-color: #3b82f6;
            font-weight: 600;
            box-shadow: inset -2px 0 8px rgba(59, 130, 246, 0.1);
        }

        .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
            opacity: 0.9;
        }

        .nav-link.active i {
            opacity: 1;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
            backdrop-filter: blur(10px);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            text-decoration: none;
            transition: all var(--transition);
            padding: 0.75rem;
            border-radius: 0.6rem;
            cursor: pointer;
        }

        .sidebar-user:hover {
            background-color: rgba(59, 130, 246, 0.1);
            transform: translateX(2px);
        }

        .sidebar-user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.6rem;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 0.85rem;
            font-weight: 600;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
            font-weight: 500;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        /* TOPBAR */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .topbar-icon-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            border: none;
            background-color: var(--content-bg);
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            font-size: 1.1rem;
        }

        .topbar-icon-btn:hover {
            background-color: var(--border-color);
            transform: scale(1.05);
        }

        .topbar-user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .topbar-user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 0.85rem;
        }

        /* CONTENT AREA */
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* CARDS */
        .card {
            border: none;
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* STAT CARDS */
        .stat-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary);
            transition: all var(--transition);
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .stat-card.primary { border-left-color: #3b82f6; }
        .stat-card.success { border-left-color: #22c55e; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #8b5cf6; }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            margin-left: 1rem;
        }

        .stat-card.primary .stat-icon { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-card.success .stat-icon { background-color: rgba(34, 197, 94, 0.1); color: #22c55e; }
        .stat-card.warning .stat-icon { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .stat-card.danger .stat-icon { background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

        /* BUTTONS */
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: var(--content-bg);
            border-color: var(--text-light);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-icon {
            width: 2rem;
            height: 2rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.4rem;
            font-size: 0.9rem;
        }

        .btn-edit {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .btn-edit:hover {
            background-color: #3b82f6;
            color: white;
        }

        .btn-delete {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .btn-delete:hover {
            background-color: #ef4444;
            color: white;
        }

        .btn-view {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .btn-view:hover {
            background-color: #22c55e;
            color: white;
        }

        /* FORMS */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.65rem 0.875rem;
            font-size: 0.9rem;
            color: var(--text-dark);
            background-color: white;
            transition: all var(--transition);
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.65rem 2.5rem 0.65rem 0.875rem;
            font-size: 0.9rem;
            color: var(--text-dark);
            background-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231e293b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
            transition: all var(--transition);
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* TABLES */
        .table {
            margin: 0;
            background-color: var(--card-bg);
        }

        .table thead th {
            background-color: var(--content-bg);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-weight: 700;
            padding: 1rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-top: none;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            color: var(--text-dark);
        }

        .table tbody tr {
            transition: background-color var(--transition);
        }

        .table tbody tr:hover {
            background-color: var(--content-bg);
        }

        /* BADGES */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-admin {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .badge-manager {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .badge-supervisor {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .badge-attendant {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .badge-active {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .badge-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* PAGINATION */
        .pagination {
            gap: 0.25rem;
        }

        .page-link {
            border: 1px solid var(--border-color);
            border-radius: 0.4rem;
            color: var(--primary);
            background-color: white;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            transition: all var(--transition);
        }

        .page-link:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .page-item.disabled .page-link {
            color: var(--text-light);
            background-color: var(--content-bg);
            border-color: var(--border-color);
        }

        /* ALERTS & TOASTS */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #7c2d12;
            border-left: 4px solid #f59e0b;
        }

        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content {
                padding: 1rem;
            }

            .topbar {
                padding: 1rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .topbar-title {
                font-size: 1rem;
            }

            .topbar-actions {
                gap: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar-brand-text {
                display: none;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.25rem;
                margin-left: 0.75rem;
            }

            .btn-group {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .form-control,
            .form-select,
            .btn {
                width: 100%;
            }

            .btn-group-sm {
                flex-direction: column;
            }

            .btn-group-sm .btn-icon {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <span class="sidebar-brand-icon">⛽</span>
                <span class="sidebar-brand-text">FuelHub</span>
            </div>

            <nav class="sidebar-nav">
                <!-- Management Section -->
                <div class="sidebar-nav-section">Management</div>
                <div class="sidebar-nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="sidebar-nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Users</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('fuel-products.index') }}" class="nav-link {{ request()->routeIs('fuel-products.*') ? 'active' : '' }}">
                                <i class="bi bi-droplet-fill"></i>
                                <span>Fuel Products</span>
                            </a>
                        </div>
                    @endif

                    <!-- Operations Section -->
                    <div class="sidebar-nav-section">Operations</div>

                    @if(auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('tanks.index') }}" class="nav-link {{ request()->routeIs('tanks.*') ? 'active' : '' }}">
                                <i class="bi bi-database"></i>
                                <span>Tanks</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <div class="sidebar-nav-item">
                            <a href="{{ route('pumps.index') }}" class="nav-link {{ request()->routeIs('pumps.*') ? 'active' : '' }}">
                                <i class="bi bi-lightning-fill"></i>
                                <span>Pumps</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                                <i class="bi bi-clock"></i>
                                <span>Shifts</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('attendant'))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i>
                                <span>Record Sale</span>
                            </a>
                        </div>
                        <div class="sidebar-nav-item">
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                                <i class="bi bi-receipt"></i>
                                <span>My Sales</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i>
                                <span>Inventory</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') && !request()->routeIs('sales.create') ? 'active' : '' }}">
                                <i class="bi bi-graph-up"></i>
                                <span>Sales</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('reconciliations.index') }}" class="nav-link {{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">
                                <i class="bi bi-calculator"></i>
                                <span>Reconciliation</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('deliveries.index') }}" class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
                                <i class="bi bi-truck"></i>
                                <span>Deliveries</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('credit-customers.index') }}" class="nav-link {{ request()->routeIs('credit-customers.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Credit Customers</span>
                            </a>
                        </div>
                    @endif

                    <!-- System Section -->
                    <div class="sidebar-nav-section">System</div>

                    @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                        <div class="sidebar-nav-item">
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Reports</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <div class="sidebar-nav-item">
                            <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                                <i class="bi bi-shield-check"></i>
                                <span>Audit Logs</span>
                            </a>
                        </div>
                        <div class="sidebar-nav-item">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>

            <!-- Sidebar Footer -->
            @auth
                <div class="sidebar-footer">
                    <div class="sidebar-user">
                        <div class="sidebar-user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="sidebar-user-info">
                            <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                            <span class="sidebar-user-role">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                </div>
            @endauth
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOPBAR -->
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn-icon d-md-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="topbar-title">@yield('topbar_title', 'Dashboard')</h1>
                </div>
                <div class="topbar-actions">
                    <button class="topbar-icon-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="dropdown">
                        <div class="topbar-user-menu" data-bs-toggle="dropdown">
                            <div class="topbar-user-avatar">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 150px;">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; cursor: pointer; width: 100%; text-align: left;">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- FLASH MESSAGES -->
            <div style="padding: 1rem 2rem 0 2rem;">
                @if ($message = session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-2"></i>
                            <div><strong>Success!</strong> {{ $message }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <div><strong>Error!</strong> {{ $message }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div><strong>Warning!</strong> {{ $message }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle me-2" style="margin-top: 2px;"></i>
                            <div>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- CONTENT -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !sidebarToggle?.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 4000);
        });
    </script>
</body>
</html>
