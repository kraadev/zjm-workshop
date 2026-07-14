<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle Create
if (isset($_POST['tambah'])) {
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $ikon = $_POST['ikon'];
    $isi = $_POST['isi'];
    $link = !empty($_POST['link']) ? $_POST['link'] : null;
    
    $stmt = $conn->prepare("INSERT INTO kontak_info (kategori, judul, ikon, isi, link) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kategori, $judul, $ikon, $isi, $link);
    $stmt->execute();
    header('Location: admin_kontak.php?status=success');
    exit;
}

// Handle Update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $ikon = $_POST['ikon'];
    $isi = $_POST['isi'];
    $link = !empty($_POST['link']) ? $_POST['link'] : null;
    
    $stmt = $conn->prepare("UPDATE kontak_info SET kategori=?, judul=?, ikon=?, isi=?, link=? WHERE id=?");
    $stmt->bind_param("sssssi", $kategori, $judul, $ikon, $isi, $link, $id);
    $stmt->execute();
    header('Location: admin_kontak.php?status=updated');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM kontak_info WHERE id = $id");
    header('Location: admin_kontak.php?status=deleted');
    exit;
}

// Get all kontak info
$kontak = $conn->query("SELECT * FROM kontak_info ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kontak Info - Zara Jaya Motor</title>
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
            overflow-x: hidden;
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 2000;
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 1.2em;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #0d2738 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
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
            transition: margin-left 0.3s ease;
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
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 1.6em;
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
            white-space: nowrap;
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
            padding: 8px 12px;
            font-size: 0.85em;
        }

        .content-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95em;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .info-card {
            background: var(--bg-light);
            border-left: 5px solid var(--primary-color);
            padding: 16px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 255, 136, 0.2);
        }

        .info-header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: var(--secondary-color);
            flex-shrink: 0;
        }

        .info-title h3 {
            color: var(--secondary-color);
            font-size: 1em;
            margin-bottom: 2px;
        }

        .info-category {
            display: inline-block;
            padding: 2px 8px;
            background: white;
            border-radius: 20px;
            font-size: 0.7em;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .info-body {
            color: #333;
            line-height: 1.5;
            margin-bottom: 12px;
            font-size: 0.9em;
        }

        .info-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
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
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 15px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .modal-header h2 {
            color: var(--secondary-color);
            font-size: 1.4em;
        }

        .close {
            font-size: 1.8em;
            cursor: pointer;
            color: #999;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.95em;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
            font-size: 0.95em;
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

        .form-hint {
            font-size: 0.8em;
            color: #666;
            margin-top: 4px;
        }

        .icon-preview {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: var(--bg-light);
            border-radius: 8px;
            margin-top: 8px;
            font-size: 0.9em;
        }

        .icon-preview i {
            font-size: 1.3em;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }

            .header {
                padding: 16px;
            }

            .header h1 {
                font-size: 1.4em;
            }

            .btn {
                padding: 10px 16px;
                font-size: 0.9em;
            }

            .content-box {
                padding: 16px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                padding: 16px;
            }

            .modal-header h2 {
                font-size: 1.3em;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

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
            <a href="admin_pesan.php" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span>Pesan Pengunjung</span>
            </a>
            <a href="admin_kontak.php" class="menu-item active">
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
            <h1><i class="fas fa-address-book"></i> Kelola Kontak Info</h1>
            <button class="btn btn-primary" onclick="openModal('tambahModal')">
                <i class="fas fa-plus"></i> Tambah Info Kontak
            </button>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'success') echo '<i class="fas fa-check-circle"></i> Info kontak berhasil ditambahkan!';
                elseif ($_GET['status'] == 'updated') echo '<i class="fas fa-check-circle"></i> Info kontak berhasil diupdate!';
                elseif ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Info kontak berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <h2>Informasi Kontak Bengkel</h2>
            <div class="info-grid">
                <?php while ($row = $kontak->fetch_assoc()): ?>
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon">
                            <i class="fas <?php echo htmlspecialchars($row['ikon']); ?>"></i>
                        </div>
                        <div class="info-title">
                            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <span class="info-category"><?php echo strtoupper(htmlspecialchars($row['kategori'])); ?></span>
                        </div>
                    </div>
                    <div class="info-body">
                        <?php echo htmlspecialchars_decode(nl2br(htmlspecialchars($row['isi']))); ?>
                    </div>
                    <?php if (!empty($row['link'])): ?>
                        <div style="margin-bottom: 12px;">
                            <small style="color: #666;">
                                <i class="fas fa-link"></i> Link: 
                                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" style="color: var(--primary-dark);">
                                    <?php echo substr(htmlspecialchars($row['link']), 0, 35); ?>...
                                </a>
                            </small>
                        </div>
                    <?php endif; ?>
                    <div class="info-actions">
                        <button class="btn btn-warning btn-sm" onclick='editKontak(<?php echo htmlspecialchars(json_encode($row, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="?hapus=<?php echo (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <!-- Modal Tambah -->
    <div id="tambahModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Info Kontak</h2>
                <span class="close" onclick="closeModal('tambahModal')">&times;</span>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="alamat">Alamat</option>
                        <option value="telepon">Telepon</option>
                        <option value="email">Email</option>
                        <option value="jam">Jam Operasional</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" placeholder="Contoh: Alamat Bengkel" required>
                </div>
                <div class="form-group">
                    <label>Ikon FontAwesome</label>
                    <input type="text" name="ikon" id="ikon_input" placeholder="Contoh: fa-map-marker-alt" required>
                    <div class="form-hint">Gunakan kode ikon dari FontAwesome (tanpa 'fas')</div>
                    <div class="icon-preview" id="icon_preview_add" style="display: none;">
                        <i class="fas fa-question"></i>
                        <span>Preview Ikon</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Isi / Konten</label>
                    <textarea name="isi" placeholder="Isi informasi kontak (bisa menggunakan HTML)" required></textarea>
                    <div class="form-hint">Anda bisa menggunakan tag HTML seperti <br>, <strong>, <a>, dll.</div>
                </div>
                <div class="form-group">
                    <label>Link (Opsional)</label>
                    <input type="url" name="link" placeholder="https://wa.me/628123456789">
                    <div class="form-hint">Link eksternal (WhatsApp, Maps, dll) - kosongkan jika tidak ada</div>
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
                <h2>Edit Info Kontak</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" id="edit_kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="alamat">Alamat</option>
                        <option value="telepon">Telepon</option>
                        <option value="email">Email</option>
                        <option value="jam">Jam Operasional</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="edit_judul" required>
                </div>
                <div class="form-group">
                    <label>Ikon FontAwesome</label>
                    <input type="text" name="ikon" id="edit_ikon" required>
                    <div class="form-hint">Gunakan kode ikon dari FontAwesome (tanpa 'fas')</div>
                    <div class="icon-preview" id="icon_preview_edit">
                        <i class="fas fa-question" id="preview_icon_edit"></i>
                        <span>Preview Ikon</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Isi / Konten</label>
                    <textarea name="isi" id="edit_isi" required></textarea>
                    <div class="form-hint">Anda bisa menggunakan tag HTML</div>
                </div>
                <div class="form-group">
                    <label>Link (Opsional)</label>
                    <input type="url" name="link" id="edit_link">
                </div>
                <button type="submit" name="edit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function editKontak(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_kategori').value = data.kategori;
            document.getElementById('edit_judul').value = data.judul || '';
            document.getElementById('edit_ikon').value = data.ikon || '';
            document.getElementById('edit_isi').value = data.isi || '';
            document.getElementById('edit_link').value = data.link || '';
            const iconEl = document.getElementById('preview_icon_edit');
            if (data.ikon) {
                iconEl.className = 'fas ' + data.ikon;
            } else {
                iconEl.className = 'fas fa-question';
            }
            openModal('editModal');
        }

        document.getElementById('ikon_input').addEventListener('input', function(e) {
            const iconClass = e.target.value.trim();
            const preview = document.getElementById('icon_preview_add');
            const icon = preview.querySelector('i');
            if (iconClass) {
                icon.className = 'fas ' + iconClass;
                preview.style.display = 'inline-flex';
            } else {
                preview.style.display = 'none';
            }
        });

        document.getElementById('edit_ikon').addEventListener('input', function(e) {
            const iconClass = e.target.value.trim();
            const icon = document.getElementById('preview_icon_edit');
            if (iconClass) {
                icon.className = 'fas ' + iconClass;
            } else {
                icon.className = 'fas fa-question';
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