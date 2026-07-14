<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_nama = $_SESSION['admin_nama'] ?? $_SESSION['admin_username'];

// Handle Create
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $detail = $_POST['detail_pelayanan'];
    $harga = $_POST['harga'];
    
    $gambar = 'default.jpg';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "image/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = $target_dir . uniqid() . '.' . $file_extension;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }
    
    $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, deskripsi, detail_pelayanan, harga, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $nama, $deskripsi, $detail, $harga, $gambar);
    $stmt->execute();
    header('Location: admin_layanan.php?status=success');
    exit;
}

// Handle Update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $detail = $_POST['detail_pelayanan'];
    $harga = $_POST['harga'];
    
    $gambar_lama = $_POST['gambar_lama'];
    $gambar = $gambar_lama;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "image/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = $target_dir . uniqid() . '.' . $file_extension;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
        
        if ($gambar_lama != 'default.jpg' && file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }
    }
    
    $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, deskripsi=?, detail_pelayanan=?, harga=?, gambar=? WHERE id=?");
    $stmt->bind_param("sssisi", $nama, $deskripsi, $detail, $harga, $gambar, $id);
    $stmt->execute();
    header('Location: admin_layanan.php?status=updated');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $result = $conn->query("SELECT gambar FROM layanan WHERE id=$id");
    $data = $result->fetch_assoc();
    
    if ($data['gambar'] != 'default.jpg' && file_exists($data['gambar'])) {
        unlink($data['gambar']);
    }
    
    $conn->query("DELETE FROM layanan WHERE id=$id");
    header('Location: admin_layanan.php?status=deleted');
    exit;
}

// Get all layanan
$layanan = $conn->query("SELECT * FROM layanan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan - Zara Jaya Motor</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8em;
            color: var(--secondary-color);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--secondary-color);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
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

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 600px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: var(--bg-light);
            color: var(--secondary-color);
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
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
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            color: var(--secondary-color);
        }

        .close {
            font-size: 2em;
            cursor: pointer;
            color: #999;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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

        .img-preview {
            max-width: 100px;
            max-height: 100px;
            border-radius: 8px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
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

        /* Responsive untuk mobile */
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
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 15px;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .content-box {
                padding: 15px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.9em;
            }

            .img-preview {
                max-width: 60px;
                max-height: 60px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
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
            <a href="admin_layanan.php" class="menu-item active">
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

    <main class="main-content">
        <div class="header">
            <h1><i class="fas fa-wrench"></i> Kelola Layanan</h1>
            <button class="btn btn-primary" onclick="openModal('tambahModal')">
                <i class="fas fa-plus"></i> Tambah Layanan
            </button>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'success') echo '<i class="fas fa-check-circle"></i> Layanan berhasil ditambahkan!';
                if ($_GET['status'] == 'updated') echo '<i class="fas fa-check-circle"></i> Layanan berhasil diupdate!';
                if ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Layanan berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <h2>Daftar Layanan</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Layanan</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $layanan->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($row['gambar']); ?>" class="img-preview" alt="<?php echo htmlspecialchars($row['nama_layanan']); ?>">
                            </td>
                            <td><?php echo htmlspecialchars($row['nama_layanan']); ?></td>
                            <td><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...</td>
                            <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning btn-sm" onclick='editLayanan(<?php echo json_encode($row); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Tambah -->
    <div id="tambahModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Layanan</h2>
                <span class="close" onclick="closeModal('tambahModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="nama_layanan" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Detail Pelayanan</label>
                    <textarea name="detail_pelayanan" required></textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" required>
                </div>
                <div class="form-group">
                    <label>Gambar</label>
                    <input type="file" name="gambar" accept="image/*">
                </div>
                <button type="submit" name="tambah" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Layanan</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data" id="editForm">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="nama_layanan" id="edit_nama" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Detail Pelayanan</label>
                    <textarea name="detail_pelayanan" id="edit_detail" required></textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" id="edit_harga" required>
                </div>
                <div class="form-group">
                    <label>Gambar (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" name="gambar" accept="image/*">
                    <img id="current_image" class="img-preview" style="margin-top: 10px;">
                </div>
                <button type="submit" name="edit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function editLayanan(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nama').value = data.nama_layanan;
            document.getElementById('edit_deskripsi').value = data.deskripsi;
            document.getElementById('edit_detail').value = data.detail_pelayanan;
            document.getElementById('edit_harga').value = data.harga;
            document.getElementById('edit_gambar_lama').value = data.gambar;
            document.getElementById('current_image').src = data.gambar;
            openModal('editModal');
        }

        // Tutup sidebar saat klik di luar (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-toggle');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = toggleBtn && toggleBtn.contains(event.target);

            if (window.innerWidth <= 768 && !isClickInsideSidebar && !isClickOnToggle) {
                sidebar.classList.remove('active');
            }
        });

        // Tutup modal saat klik di luar konten
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>