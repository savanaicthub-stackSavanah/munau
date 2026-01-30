<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal | Munau College')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #059669;
            --light: #f3f4f6;
            --text-dark: #1f2937;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light);
        }

        .sidebar {
            background-color: var(--primary);
            color: white;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .sidebar a, .sidebar button {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .sidebar a:hover, .sidebar button:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
        }

        .badge-active { background-color: #d1fae5; color: var(--secondary); }
        .badge-pending { background-color: #fef3c7; color: #d97706; }
        .badge-completed { background-color: #dbeafe; color: #1e40af; }
        .badge-overdue { background-color: #fee2e2; color: #dc2626; }

        .nav-pills .nav-link {
            color: var(--text-dark);
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary);
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .stat-card.success { background: linear-gradient(135deg, #00c4a8 0%, #00a879 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #fbb040 0%, #fb8500 100%); }
        .stat-card.danger { background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -300px;
                width: 250px;
                top: 0;
                z-index: 1000;
                transition: left 0.3s;
            }

            .sidebar.show {
                left: 0;
            }
        }
    </style>

    @yield('additional-css')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar col-md-3 col-lg-2" id="sidebar">
            <div class="mb-4 text-center">
                <h5 class="mb-1">{{ Auth::user()->full_name }}</h5>
                <small class="text-muted">Student Portal</small>
            </div>

            <div class="mb-4 pb-4 border-bottom border-secondary">
                <img src="https://via.placeholder.com/80" alt="Profile" class="img-fluid rounded-circle w-100" style="max-width: 80px;">
            </div>

            <h6 class="text-uppercase text-muted mb-3 small">Navigation</h6>
            
            <a href="{{ route('student.dashboard') }}" class="nav-link @if(Route::currentRouteName() == 'student.dashboard') active @endif">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            
            <a href="{{ route('student.profile') }}" class="nav-link @if(Route::currentRouteName() == 'student.profile') active @endif">
                <i class="fas fa-user me-2"></i> My Profile
            </a>

            <h6 class="text-uppercase text-muted mb-3 small mt-4">Academics</h6>
            
            <a href="{{ route('student.courses') }}" class="nav-link @if(Route::currentRouteName() == 'student.courses') active @endif">
                <i class="fas fa-book me-2"></i> Courses
            </a>
            
            <a href="{{ route('student.timetable') }}" class="nav-link @if(Route::currentRouteName() == 'student.timetable') active @endif">
                <i class="fas fa-calendar me-2"></i> Timetable
            </a>
            
            <a href="{{ route('student.exam-schedule') }}" class="nav-link @if(Route::currentRouteName() == 'student.exam-schedule') active @endif">
                <i class="fas fa-clipboard-list me-2"></i> Exams
            </a>
            
            <a href="{{ route('student.results') }}" class="nav-link @if(Route::currentRouteName() == 'student.results') active @endif">
                <i class="fas fa-chart-bar me-2"></i> Results
            </a>
            
            <a href="{{ route('student.transcript') }}" class="nav-link @if(Route::currentRouteName() == 'student.transcript') active @endif">
                <i class="fas fa-file-alt me-2"></i> Transcript
            </a>

            <h6 class="text-uppercase text-muted mb-3 small mt-4">Finance</h6>
            
            <a href="{{ route('student.finances') }}" class="nav-link @if(Route::currentRouteName() == 'student.finances') active @endif">
                <i class="fas fa-wallet me-2"></i> Finance
            </a>
            
            <a href="{{ route('student.receipts') }}" class="nav-link @if(Route::currentRouteName() == 'student.receipts') active @endif">
                <i class="fas fa-receipt me-2"></i> Receipts
            </a>

            <h6 class="text-uppercase text-muted mb-3 small mt-4">Facilities</h6>
            
            <a href="{{ route('student.hostel') }}" class="nav-link @if(Route::currentRouteName() == 'student.hostel') active @endif">
                <i class="fas fa-bed me-2"></i> Hostel
            </a>
            
            <a href="{{ route('student.id-card') }}" class="nav-link @if(Route::currentRouteName() == 'student.id-card') active @endif">
                <i class="fas fa-id-card me-2"></i> ID Card
            </a>

            <h6 class="text-uppercase text-muted mb-3 small mt-4">Other</h6>
            
            <a href="{{ route('student.notifications') }}" class="nav-link @if(Route::currentRouteName() == 'student.notifications') active @endif">
                <i class="fas fa-bell me-2"></i> Notifications
                @if(Auth::user()->notifications->where('status', 'unread')->count() > 0)
                    <span class="badge bg-danger ms-2">{{ Auth::user()->notifications->where('status', 'unread')->count() }}</span>
                @endif
            </a>

            <hr class="border-secondary">

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="nav-link w-100 text-start btn btn-link">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </nav>

        <!-- Main Content -->
        <main class="main-content col-md-9 col-lg-10 ms-md-auto">
            <!-- Top Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <h1 class="h3">@yield('page-title', 'Dashboard')</h1>
                <div>
                    <span class="text-muted me-3">{{ date('l, F j, Y') }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Error</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

    @yield('additional-js')
</body>
</html>
