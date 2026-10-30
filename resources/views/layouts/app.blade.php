<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CLINIC SYSTEM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        html, body { height: 100vh; width: 100vw; overflow: hidden; background-color: #ededed; }
        body { display: flex; flex-direction: column; padding: 10px; }
        
        /* App Window Frame */
        .app-window { flex: 1; display: flex; flex-direction: column; border: 3px solid #4a4c8c; background: #f0efea; border-radius: 4px; overflow: hidden; width: 100%; height: 100%; }
        
        /* Window Titlebar */
        .window-bar { background-color: #4a4c8c; color: white; padding: 6px 12px; font-weight: bold; font-size: 14px; flex-shrink: 0; }

        /* Main Workspace Body */
        .app-body { flex: 1; display: flex; height: calc(100% - 33px); overflow: hidden; }

        /* Sidebar Navigation */
        .sidebar { width: 180px; border-right: 3px solid #4a4c8c; padding: 20px 12px; display: flex; flex-direction: column; gap: 12px; background: #f0efea; flex-shrink: 0; }
        .nav-btn { display: block; text-align: center; padding: 8px 4px; border: 2px solid #4a4c8c; background: #ffffff; color: #4a4c8c; text-decoration: none; font-weight: bold; font-size: 14px; border-radius: 4px; }
        .nav-btn-submit { width: 100%; cursor: pointer; }
        .nav-btn.active { background: #4a4c8c; color: white; }

        /* Page Content Area */
        .content-area { flex: 1; padding: 24px; overflow-y: auto; height: 100%; background: #f0efea; }
        .page-title { font-size: 22px; font-weight: bold; color: #4a4c8c; margin-bottom: 20px; text-transform: uppercase; }

        /* Global UI Controls */
        .btn-primary { background: #4a4c8c; color: white; border: none; padding: 8px 16px; font-weight: bold; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .input-box { border: 2px solid #4a4c8c; padding: 6px 10px; border-radius: 4px; background: white; font-size: 14px; outline: none; }
        
        /* Data Tables */
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 15px; }
        .data-table th { text-align: left; padding: 10px; border-bottom: 2px solid #4a4c8c; color: #4a4c8c; font-size: 14px; }
        .data-table td { padding: 10px; border-bottom: 1px solid #ccc; font-size: 14px; }

    </style>
</head>
<body>
    <div class="app-window">
        <div class="window-bar">
            CLINIC SYSTEM
        </div>
        
        <div class="app-body">
            <div class="sidebar">
                <a href="{{ route('dashboard') }}" class="nav-btn {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('patients.index') }}" class="nav-btn {{ request()->routeIs('patients.*') ? 'active' : '' }}">Patients</a>
                <a href="{{ route('appointments.index') }}" class="nav-btn {{ request()->routeIs('appointments.*') ? 'active' : '' }}">Appointments</a>
                <a href="{{ route('daily.index') }}" class="nav-btn {{ request()->routeIs('daily.*') ? 'active' : '' }}">Daily Sched</a>
                <a href="{{ route('billing.index') }}" class="nav-btn {{ request()->routeIs('billing.*') ? 'active' : '' }}">Billing</a>
                <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                    @csrf
                    <button type="submit" class="nav-btn nav-btn-submit">Log out</button>
                </form>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>