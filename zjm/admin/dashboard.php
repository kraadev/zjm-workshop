<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_nama = $_SESSION['admin_nama'] ?? $_SESSION['admin_username'];

// Ambil statistik
$total_layanan = $conn->query("SELECT COUNT(*) as total FROM layanan")->fetch_assoc()['total'] ?? 0;
$total_produk = $conn->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'] ?? 0;
$total_galeri = $conn->query("SELECT COUNT(*) as total FROM galeri")->fetch_assoc()['total'] ?? 0;
$total_pesan = $conn->query("SELECT COUNT(*) as total FROM pesan_pengunjung")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Zara Jaya Motor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #00ff88;
            --primary-dark: #00cc6a;
            --primary-light: #66ffbb;
            --secondary-color: #0a1929;
            --text-dark: #1a1a1a;
            --bg-light: #f0fff6;
            --shadow: rgba(0, 255, 136, 0.3);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #0d2738 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 2px solid rgba(0, 255, 136, 0.2);
        }

        .sidebar-header i {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 10px;
            animation: rotate 3s ease-in-out infinite;
        }

        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
        }

        .sidebar-header h2 {
            font-size: 1.3em;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(0, 255, 136, 0.1);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .menu-item i {
            font-size: 1.2em;
            width: 25px;
        }

        .logout-section {
            position: absolute;
            bottom: 20px;
            width: 100%;
            padding: 0 20px;
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            background: rgba(255, 59, 48, 0.1);
            color: #ff3b30;
            border: 2px solid #ff3b30;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-logout:hover {
            background: #ff3b30;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .top-bar {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            font-size: 1.8em;
            color: var(--secondary-color);
            font-weight: 700;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.95em;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            color: var(--secondary-color);
            font-weight: 700;
        }

        .user-details h3 {
            font-size: 1em;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .user-details p {
            font-size: 0.85em;
            color: #666;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--shadow);
        }

        .stat-icon {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stat-info h3 {
            font-size: 2em;
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.95em;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .quick-actions h2 {
            font-size: 1.5em;
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--bg-light), white);
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .action-btn i {
            font-size: 1.5em;
            color: var(--primary-color);
        }

        .action-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px var(--shadow);
        }

        .action-btn:hover i {
            color: white;
        }

        /* Recent Activity */
        .recent-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .recent-section h2 {
            font-size: 1.5em;
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .info-box {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .info-box p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .info-box p:last-child {
            margin-bottom: 0;
        }

        .info-box i {
            margin-right: 10px;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2em;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 80px 20px 30px;
            }

            .top-bar {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-tools"></i>
            <h2>Zara Jaya Motor</h2>
        </div>
        
        <nav class="sidebar-menu">
            <a href="dashboard.php" class="menu-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="admin_layanan.php" class="menu-item">
                <i class="fas fa-wrench"></i>
                <span>Kelola Layanan</span>
            </a>
            <a href="admin_produk.php" class="menu-item">
                <i class="fas fa-box"></i>
                <span>Kelola Produk</span>
            </a>
            <a href="admin_galeri.php" class="menu-item">
                <i class="fas fa-images"></i>
                <span>Kelola Galeri</span>
            </a>
            <a href="admin_pesan.php" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span>Pesan Pengunjung</span>
            </a>
            <a href="admin_kontak.php" class="menu-item">
                <i class="fas fa-address-book"></i>
                <span>Kontak Info</span>
            </a>
            <a href="admin_profil.php" class="menu-item">
                <i class="fas fa-building"></i>
                <span>Profil Usaha</span>
            </a>
        </nav>

        <div class="logout-section">
            <form action="logout.php" method="POST">
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="welcome-text">
                <h1>Selamat Datang, <?php echo htmlspecialchars($admin_nama); ?>!</h1>
                <p>Kelola sistem bengkel Anda dengan mudah</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($admin_nama, 0, 1)); ?>
                </div>
                <div class="user-details">
                    <h3><?php echo htmlspecialchars($admin_nama); ?></h3>
                    <p>Administrator</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_layanan; ?></h3>
                    <p>Total Layanan</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_produk; ?></h3>
                    <p>Total Produk</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_galeri; ?></h3>
                    <p>Total Galeri</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_pesan; ?></h3>
                    <p>Pesan Masuk</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Aksi Cepat</h2>
            <div class="actions-grid">
                <a href="admin_layanan.php" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Layanan</span>
                </a>
                <a href="admin_produk.php" class="action-btn">
                    <i class="fas fa-box-open"></i>
                    <span>Tambah Produk</span>
                </a>
                <a href="admin_galeri.php" class="action-btn">
                    <i class="fas fa-image"></i>
                    <span>Upload Galeri</span>
                </a>
                <a href="admin_pesan.php" class="action-btn">
                    <i class="fas fa-comments"></i>
                    <span>Lihat Pesan</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-section">
            <h2>Informasi Sistem</h2>
            <div class="info-box">
                <p><i class="fas fa-check-circle" style="color: var(--primary-color);"></i> Sistem berjalan dengan baik</p>
                <p><i class="fas fa-database" style="color: var(--primary-color);"></i> Database terhubung</p>
                <p><i class="fas fa-clock" style="color: var(--primary-color);"></i> Terakhir login: <?php echo date('d M Y H:i'); ?></p>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>