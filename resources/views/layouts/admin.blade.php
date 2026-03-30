<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel — HiperBlog</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #1e293b;
            color: white;
            z-index: 1000;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-link i {
            font-size: 1.25rem;
        }

        .admin-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .badge-published {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-draft {
            background-color: #f1f5f9;
            color: #475569;
        }

        .badge-scheduled {
            background-color: #fef9c3;
            color: #854d0e;
        }

        @media (max-width: 990px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="d-flex align-items-center gap-2 mb-5 px-2">
            <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center"
                style="width: 32px; height: 32px;">
                <i class="bi bi-lightning-fill text-white"></i>
            </div>
            <h5 class="fw-bold mb-0">HiperAdmin</h5>
        </div>

        <nav>
            <a href="{{ route('admin.posts.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Articles</span>
            </a>
            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Live Site</span>
            </a>
        </nav>

        <div class="mt-auto pt-5">
            <div class="bg-white bg-opacity-10 rounded-4 p-3 mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-circle bg-primary" style="width: 8px; height: 8px;"></div>
                    <small class="fw-bold text-white">{{ auth()->user()->name }}</small>
                </div>
                <small
                    class="text-secondary d-block">{{ auth()->user()->role == 'admin' ? 'Administrator' : 'Editor' }}</small>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 btn-sm rounded-3">
                    <i class="bi bi-power me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>