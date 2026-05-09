<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Stock Update System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- INTERNAL CSS (Modern Corporate Theme) -->
    <style>
        :root {
            --primary: #2563eb; /* Blue for trust/navigation */
            --primary-hover: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981; /* Green for growth/profit */
            --warning: #f59e0b; /* Yellow/Orange for alerts */
            --danger: #dc2626; /* Red for loss/low stock */
            --info: #0ea5e9;
            --bg-color: #f8fafc; /* Neutral light */
            --card-bg: #ffffff;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            --sidebar-hover: #475569;
            --sidebar-active: #64748b;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e5e7eb;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius: 12px;
            --radius-sm: 8px;
        }
        body { 
            font-family: 'Consolas', monospace; 
            margin: 0; 
            padding: 0; 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); 
            color: #1e293b; 
            -webkit-font-smoothing: antialiased; 
            min-height: 100vh;
            line-height: 1.6;
        }
        .wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: 280px; 
            background: var(--sidebar-bg); 
            color: #fff; 
            padding-top: 30px; 
            flex-shrink: 0; 
            display: flex; 
            flex-direction: column; 
            box-shadow: var(--shadow-lg);
            position: relative;
        }
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--success));
        }
        .sidebar h3 { 
            text-align: center; 
            color: #fff; 
            font-weight: 700; 
            letter-spacing: 1px; 
            margin-bottom: 40px; 
            font-size: 1.4rem; 
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .sidebar a { 
            display: flex; 
            align-items: center;
            padding: 16px 28px; 
            color: #cbd5e1; 
            text-decoration: none; 
            font-weight: 500; 
            font-size: 0.95rem; 
            border-left: 4px solid transparent; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative;
        }
        .sidebar a::before {
            content: '';
            width: 20px;
            height: 20px;
            margin-right: 12px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover { 
            background-color: var(--sidebar-hover); 
            color: #fff; 
            transform: translateX(4px);
        }
        .sidebar a.active { 
            background-color: var(--sidebar-active); 
            color: var(--info); 
            border-left-color: var(--info);
            box-shadow: inset 0 0 0 1px rgba(14, 165, 233, 0.2);
        }
        .sidebar a.active::before {
            background: var(--info);
        }
        .logout-container { margin-top: auto; padding-bottom: 20px; }
        .logout-btn { 
            background: none; 
            border: none; 
            color: #f87171; 
            padding: 16px 28px; 
            cursor: pointer; 
            text-align: left; 
            width: 100%; 
            font-size: 0.95rem; 
            font-weight: 500; 
            font-family: 'Inter', sans-serif; 
            transition: all 0.3s ease; 
            display: flex;
            align-items: center;
        }
        .logout-btn::before {
            content: '🚪';
            margin-right: 12px;
            font-size: 1rem;
        }
        .logout-btn:hover { 
            background-color: var(--sidebar-hover); 
            color: #ef4444; 
            transform: translateX(4px);
        }

        /* Main Content & Layout */
        .main-content { 
            flex-grow: 1; 
            padding: 40px 50px; 
            overflow-y: auto; 
            background: transparent;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid var(--border-color); 
            padding-bottom: 25px; 
            margin-bottom: 35px; 
            background: var(--card-bg);
            padding: 25px 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .header h2 { 
            margin: 0; 
            font-size: 1.8rem; 
            font-weight: 700; 
            color: var(--text-main); 
            background: linear-gradient(135deg, var(--primary), var(--success));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card { 
            background: var(--card-bg); 
            border-radius: var(--radius); 
            box-shadow: var(--shadow); 
            padding: 30px; 
            margin-bottom: 30px; 
            border: 1px solid var(--border-color); 
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--success));
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 25px; 
            margin-bottom: 35px; 
        }
        .card-stat { 
            padding: 30px; 
            border-radius: var(--radius); 
            color: #fff; 
            box-shadow: var(--shadow); 
            position: relative; 
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .card-stat h3 { 
            margin: 0 0 15px 0; 
            font-size: 0.9rem; 
            font-weight: 600; 
            opacity: 0.9; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .card-stat p { 
            margin: 0; 
            font-size: 2.5rem; 
            font-weight: 800; 
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Gradients for stats */
        .bg-primary { background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%); }
        .bg-success { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); }
        .bg-warning { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); }
        .bg-danger  { background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%); }

        /* Table */
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 0; 
            font-size: 0.95rem; 
            border-radius: var(--radius-sm);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .table th, .table td { 
            padding: 18px 15px; 
            text-align: left; 
            vertical-align: middle; 
        }
        .table th { 
            background: linear-gradient(135deg, var(--bg-color) 0%, #e2e8f0 100%); 
            color: var(--text-muted); 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 0.8rem; 
            letter-spacing: 0.5px; 
            border-bottom: 2px solid var(--border-color); 
        }
        .table td { 
            border-bottom: 1px solid var(--border-color); 
            color: var(--text-main); 
        }
        .table tr:nth-child(even) td { background-color: #f9fafb; }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { 
            background-color: #f3f4f6; 
            transition: background-color 0.2s ease;
        }

        /* Buttons */
        .btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            padding: 12px 20px; 
            font-size: 0.9rem; 
            font-weight: 600; 
            cursor: pointer; 
            border-radius: var(--radius-sm); 
            text-decoration: none; 
            border: none; 
            color: #fff; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            font-family: 'Inter', sans-serif; 
            position: relative;
            overflow: hidden;
        }
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn:hover::before {
            left: 100%;
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: var(--shadow-lg); 
        }
        .btn:active { transform: translateY(0); }
        .btn-primary { background-color: var(--primary); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-success { background-color: var(--success); }
        .btn-warning { background-color: var(--warning); color: #fff; }
        .btn-danger { background-color: var(--danger); }
        .btn-info { background-color: var(--info); }
        .btn-secondary { background-color: var(--secondary); }
        .btn-sm { padding: 8px 16px; font-size: 0.8rem; border-radius: 6px; }

        /* Forms */
        .form-group { margin-bottom: 24px; }
        .form-group label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: 600; 
            color: var(--text-main); 
            font-size: 0.9rem; 
            letter-spacing: 0.3px;
        }
        .form-control { 
            width: 100%; 
            padding: 14px 16px; 
            border: 2px solid var(--border-color); 
            border-radius: var(--radius-sm); 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif; 
            font-size: 0.95rem; 
            color: var(--text-main); 
            transition: all 0.3s ease; 
            background-color: #fff; 
        }
        .form-control:focus { 
            outline: none; 
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1); 
            transform: translateY(-1px);
        }
        
        /* Alerts & Badges */
        .alert { 
            padding: 18px 24px; 
            margin-bottom: 28px; 
            border-radius: var(--radius-sm); 
            border-left: 5px solid; 
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: currentColor;
            opacity: 0.3;
        }
        .alert-success { 
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); 
            color: #065f46; 
            border-left-color: var(--success); 
        }
        .alert-danger { 
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); 
            color: #991b1b; 
            border-left-color: var(--danger); 
        }
        .alert-info { 
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); 
            color: #075985; 
            border-left-color: var(--info); 
        }
        
        .badge { 
            display: inline-flex; 
            align-items: center;
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            box-shadow: var(--shadow);
        }

        /* Modals */
        .modal { 
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(15, 23, 42, 0.7); 
            backdrop-filter: blur(8px); 
        }
        .modal-content { 
            background-color: #fff; 
            margin: 5% auto; 
            padding: 35px; 
            border: none; 
            width: 90%; 
            max-width: 550px; 
            border-radius: var(--radius); 
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25); 
            position: relative; 
            animation: modalFadeIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); 
        }
        @keyframes modalFadeIn { 
            from { opacity: 0; transform: translateY(-30px) scale(0.95); } 
            to { opacity: 1; transform: translateY(0) scale(1); } 
        }
        .close { 
            color: var(--text-muted); 
            position: absolute; 
            right: 25px; 
            top: 20px; 
            font-size: 28px; 
            font-weight: 400; 
            cursor: pointer; 
            transition: all 0.2s ease; 
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close:hover { 
            color: var(--danger); 
            background-color: rgba(220, 38, 38, 0.1);
        }

        /* Pagination */
        nav[aria-label="Pagination Navigation"] { 
            display: flex; 
            justify-content: center; 
            margin-top: 25px; 
            font-size: 0.9rem; 
        }
        nav[aria-label="Pagination Navigation"] .page-link {
            padding: 10px 15px;
            margin: 0 2px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        nav[aria-label="Pagination Navigation"] .page-link:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-1px);
        }
        nav[aria-label="Pagination Navigation"] .active .page-link {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        /* Stock Arrows Animation */
        .stock-arrow {
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 1.2em;
        }
        .stock-arrow.up { color: var(--success); }
        .stock-arrow.down { color: var(--danger); }
        .stock-arrow:hover { transform: scale(1.2); }

        /* Accessibility: High contrast focus */
        .btn:focus, .form-control:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Color-blind friendly indicators */
        .low-stock { 
            background: repeating-linear-gradient(45deg, #fef3c7, #fef3c7 10px, #f59e0b 10px, #f59e0b 20px);
            border-left: 4px solid var(--warning);
        }
        .profit { color: var(--success); font-weight: bold; }
        .loss { color: var(--danger); font-weight: bold; }

        /* Data Visualization Enhancements */
        .chart-container { position: relative; height: 300px; }
        .badge-alert { 
            background: var(--warning); 
            color: #92400e; 
            padding: 4px 8px; 
            border-radius: 12px; 
            font-size: 0.75rem; 
            font-weight: bold; 
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h3>Stock System</h3>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
            <a href="{{ route('branches.index') }}" class="{{ request()->routeIs('branches.*') ? 'active' : '' }}">Branches</a>
            @endif
            <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}">Inventory</a>
            <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">Sales</a>
            
            <div style="position: absolute; bottom: 20px; width: 250px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div id="realtime-alert" class="alert alert-info" style="display: none;">
                <span id="alert-message"></span>
            </div>

            @yield('dashboard_content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="http://127.0.0.1:5173/resources/js/echo.js"></script>
</body>
</html>
