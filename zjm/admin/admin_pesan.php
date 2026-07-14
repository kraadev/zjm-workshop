<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM pesan_pengunjung WHERE id=$id");
    header('Location: admin_pesan.php?status=deleted');
    exit;
}

// Handle Mark as Read (optional - you can add a 'status' column to track this)
if (isset($_GET['tandai'])) {
    $id = $_GET['tandai'];
    // If you have a status column: $conn->query("UPDATE pesan_pengunjung SET status='dibaca' WHERE id=$id");
    header('Location: admin_pesan.php?status=marked');
    exit;
}

// Get all messages with pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = $conn->query("SELECT COUNT(*) as total FROM pesan_pengunjung");
$total_row = $total_query->fetch_assoc();
$total_messages = $total_row['total'];
$total_pages = ceil($total_messages / $limit);

$pesan = $conn->query("SELECT * FROM pesan_pengunjung ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Pengunjung - Zara Jaya Motor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 1.2em;
            z-index: 2000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 1.8em;
            color: var(--secondary-color);
        }

        .header p {
            color: #666;
            margin-top: 8px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9em;
        }

        .content-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-card {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .message-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .message-info h3 {
            color: var(--secondary-color);
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .message-info p {
            color: #666;
            font-size: 0.9em;
        }

        .message-meta {
            text-align: right;
            color: #999;
            font-size: 0.85em;
        }

        .message-body {
            color: #333;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        .message-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--secondary-color);
            background: white;
            border: 2px solid var(--primary-color);
            transition: all 0.3s;
            min-width: 40px;
            text-align: center;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: var(--secondary-color);
        }

        .pagination .active {
            background: var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #ddd;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
        }

        .modal-header h2 {
            color: var(--secondary-color);
        }

        .close {
            font-size: 2em;
            cursor: pointer;
            color: #999;
        }

        .detail-item {
            margin-bottom: 20px;
        }

        .detail-item label {
            display: block;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .detail-item p {
            color: #666;
            padding: 12px;
            background: var(--bg-light);
            border-radius: 8px;
            line-height: 1.6;
        }

        /* ✅ RESPONSIVE UNTUK MOBILE */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                height: 100vh;
                width: 260px;
                transition: transform 0.3s ease;
                z-index: 1500;
                overflow-y: auto;
            }

            .sidebar.active {
                transform: translateX(260px);
            }

            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }

            .header {
                padding: 15px;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .content-box {
                padding: 15px;
            }

            .message-card {
                padding: 15px;
            }

            .message-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .message-meta {
                text-align: left;
                width: 100%;
            }

            .message-body {
                padding: 12px;
                font-size: 0.95em;
            }

            .message-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
                font-size: 0.85em;
                padding: 10px;
                margin-bottom: 5px;
            }

            .pagination {
                justify-content: center;
            }

            .pagination a, .pagination span {
                padding: 8px 12px;
                font-size: 0.9em;
                min-width: 36px;
            }

            .modal-content {
                padding: 20px;
                margin: 10px;
            }

            .detail-item p {
                padding: 10px;
                font-size: 0.95em;
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
            <a href="dashboard.php" class="menu-item">
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
            <a href="admin_pesan.php" class="menu-item active">
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

    <main class="main-content">
        <div class="header">
            <h1><i class="fas fa-envelope"></i> Pesan Pengunjung</h1>
            <p>Total <?php echo $total_messages; ?> pesan diterima</p>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Pesan berhasil dihapus!';
                if ($_GET['status'] == 'marked') echo '<i class="fas fa-check-circle"></i> Pesan ditandai!';
                ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <?php if ($total_messages > 0): ?>
                <?php while ($row = $pesan->fetch_assoc()): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div class="message-info">
                            <h3><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['nama']); ?></h3>
                            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></p>
                        </div>
                        <div class="message-meta">
                            <i class="fas fa-clock"></i>
                            <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?>
                        </div>
                    </div>
                    <div class="message-body">
                        <?php echo nl2br(htmlspecialchars($row['pesan'])); ?>
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-info btn-sm" onclick='viewMessage(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i> Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>">Next <i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum ada pesan</h3>
                    <p>Pesan dari pengunjung akan muncul di sini</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-envelope-open"></i> Detail Pesan</h2>
                <span class="close" onclick="closeModal('detailModal')">&times;</span>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-user"></i> Nama Pengirim</label>
                <p id="detail_nama"></p>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-envelope"></i> Email</label>
                <p id="detail_email"></p>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-clock"></i> Tanggal Dikirim</label>
                <p id="detail_tanggal"></p>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-comment"></i> Isi Pesan</label>
                <p id="detail_pesan"></p>
            </div>
        </div>
    </div>

    <script>
        function viewMessage(data) {
            document.getElementById('detail_nama').textContent = data.nama;
            document.getElementById('detail_email').textContent = data.email;
            document.getElementById('detail_pesan').textContent = data.pesan;
            
            const tanggal = new Date(data.tanggal);
            document.getElementById('detail_tanggal').textContent = tanggal.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            openModal('detailModal');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-toggle');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = toggleBtn && toggleBtn.contains(event.target);

            if (window.innerWidth <= 768 && !isClickInsideSidebar && !isClickOnToggle) {
                sidebar.classList.remove('active');
            }
        });

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>