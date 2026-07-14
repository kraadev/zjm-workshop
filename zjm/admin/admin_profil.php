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
    
    $stmt = $conn->prepare("INSERT INTO profil_usaha (kategori, judul, ikon, isi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $kategori, $judul, $ikon, $isi);
    $stmt->execute();
    header('Location: admin_profil.php?status=success');
    exit;
}

// Handle Update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $ikon = $_POST['ikon'];
    $isi = $_POST['isi'];
    
    $stmt = $conn->prepare("UPDATE profil_usaha SET kategori=?, judul=?, ikon=?, isi=? WHERE id=?");
    $stmt->bind_param("ssssi", $kategori, $judul, $ikon, $isi, $id);
    $stmt->execute();
    header('Location: admin_profil.php?status=updated');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM profil_usaha WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: admin_profil.php?status=deleted');
    exit;
}

// Get all profil usaha grouped by category
$profil = $conn->query("SELECT * FROM profil_usaha ORDER BY 
    FIELD(kategori, 'sejarah', 'lokasi', 'visi', 'misi'), id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil Usaha - Zara Jaya Motor</title>
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
            overflow-x: hidden;
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
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .profil-section {
            margin-bottom: 30px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-color);
        }

        .section-title i {
            font-size: 1.8em;
            color: var(--primary-color);
        }

        .section-title h2 {
            color: var(--secondary-color);
            font-size: 1.4em;
        }

        .profil-grid {
            display: grid;
            gap: 20px;
        }

        .profil-card {
            background: linear-gradient(135deg, var(--bg-light) 0%, white 100%);
            border-left: 5px solid var(--primary-color);
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .profil-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
            border-radius: 50%;
        }

        .profil-card:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.15);
        }

        .profil-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .profil-title {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 200px;
        }

        .profil-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            color: var(--secondary-color);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        .profil-title-text h3 {
            color: var(--secondary-color);
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .profil-badge {
            padding: 4px 10px;
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 20px;
            font-size: 0.7em;
            font-weight: 700;
            color: var(--primary-dark);
            text-transform: uppercase;
        }

        .profil-content {
            color: #333;
            line-height: 1.7;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            text-align: justify;
            font-size: 0.95em;
        }

        .profil-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 15px;
            max-width: 650px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid var(--primary-color);
        }

        .modal-header h2 {
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.4em;
        }

        .modal-header h2 i {
            color: var(--primary-color);
        }

        .close {
            font-size: 1.8em;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .close:hover {
            color: #dc3545;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.95em;
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
            font-size: 1em;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        .form-hint {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }

        .icon-preview {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: var(--bg-light);
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid var(--primary-color);
        }

        .icon-preview i {
            font-size: 1.6em;
            color: var(--primary-color);
        }

        .icon-preview span {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.9em;
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

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.5em;
            z-index: 2001;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
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
                padding: 15px;
                flex-direction: column;
                align-items: stretch;
            }

            .header h1 {
                font-size: 1.4em;
            }

            .profil-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profil-actions {
                justify-content: flex-start;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .modal-content {
                padding: 20px;
                margin: 10px 0;
            }

            .profil-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3em;
            }

            .profil-title-text h3 {
                font-size: 1.1em;
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
            <a href="admin_pesan.php" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span>Pesan Pengunjung</span>
            </a>
            <a href="admin_kontak.php" class="menu-item">
                <i class="fas fa-address-book"></i>
                <span>Kontak Info</span>
            </a>
            <a href="admin_profil.php" class="menu-item active">
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
            <h1><i class="fas fa-building"></i> Kelola Profil Usaha</h1>
            <button class="btn btn-primary" onclick="openModal('tambahModal')">
                <i class="fas fa-plus"></i> Tambah Profil
            </button>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'success') echo '<i class="fas fa-check-circle"></i> Profil usaha berhasil ditambahkan!';
                if ($_GET['status'] == 'updated') echo '<i class="fas fa-check-circle"></i> Profil usaha berhasil diupdate!';
                if ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Profil usaha berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="profil-section">
            <div class="profil-grid">
                <?php 
                if ($profil->num_rows === 0): ?>
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <h3>Belum ada data profil usaha</h3>
                        <p>Tambahkan profil usaha pertama Anda!</p>
                    </div>
                <?php else:
                    $current_kategori = '';
                    while ($row = $profil->fetch_assoc()): 
                        if ($current_kategori != $row['kategori']) {
                            if ($current_kategori != '') echo '</div></div>';
                            $current_kategori = $row['kategori'];
                            
                            $kategori_icons = [
                                'sejarah' => 'fa-history',
                                'lokasi' => 'fa-map-marker-alt',
                                'visi' => 'fa-eye',
                                'misi' => 'fa-bullseye'
                            ];
                            
                            $kategori_names = [
                                'sejarah' => 'Sejarah',
                                'lokasi' => 'Lokasi',
                                'visi' => 'Visi',
                                'misi' => 'Misi'
                            ];
                            
                            echo '<div class="content-box">';
                            echo '<div class="section-title">';
                            echo '<i class="fas ' . $kategori_icons[$current_kategori] . '"></i>';
                            echo '<h2>' . $kategori_names[$current_kategori] . '</h2>';
                            echo '</div>';
                        }
                ?>
                <div class="profil-card">
                    <div class="profil-header">
                        <div class="profil-title">
                            <div class="profil-icon">
                                <i class="fas <?php echo htmlspecialchars($row['ikon']); ?>"></i>
                            </div>
                            <div class="profil-title-text">
                                <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                                <span class="profil-badge"><?php echo strtoupper($row['kategori']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="profil-content">
                        <?php echo nl2br(htmlspecialchars($row['isi'])); ?>
                    </div>
                    <div class="profil-actions">
                        <button class="btn btn-warning btn-sm" onclick='editProfil(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus profil ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                <?php 
                    endwhile; 
                    if ($current_kategori != '') echo '</div>';
                endif;
                ?>
            </div>
        </div>
    </main>

    <!-- Modal Tambah -->
    <div id="tambahModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-plus-circle"></i> Tambah Profil Usaha</h2>
                <span class="close" onclick="closeModal('tambahModal')">&times;</span>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Kategori</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="sejarah">Sejarah</option>
                        <option value="lokasi">Lokasi</option>
                        <option value="visi">Visi</option>
                        <option value="misi">Misi</option>
                    </select>
                    <div class="form-hint">Pilih kategori profil usaha</div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Judul</label>
                    <input type="text" name="judul" placeholder="Contoh: Sejarah Singkat" required>
                    <div class="form-hint">Masukkan judul profil</div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-icons"></i> Ikon FontAwesome</label>
                    <input type="text" name="ikon" id="ikon_input" placeholder="Contoh: fa-history" required>
                    <div class="form-hint">Kode ikon dari FontAwesome (tanpa 'fas')</div>
                    <div class="icon-preview" id="icon_preview_add" style="display: none;">
                        <i class="fas fa-question"></i>
                        <span>Preview Ikon</span>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Isi / Konten</label>
                    <textarea name="isi" placeholder="Tulis isi profil usaha..." required></textarea>
                    <div class="form-hint">Tulis deskripsi lengkap profil usaha</div>
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
                <h2><i class="fas fa-edit"></i> Edit Profil Usaha</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Kategori</label>
                    <select name="kategori" id="edit_kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="sejarah">Sejarah</option>
                        <option value="lokasi">Lokasi</option>
                        <option value="visi">Visi</option>
                        <option value="misi">Misi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Judul</label>
                    <input type="text" name="judul" id="edit_judul" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-icons"></i> Ikon FontAwesome</label>
                    <input type="text" name="ikon" id="edit_ikon" required>
                    <div class="form-hint">Kode ikon dari FontAwesome (tanpa 'fas')</div>
                    <div class="icon-preview" id="icon_preview_edit">
                        <i class="fas fa-question" id="preview_icon_edit"></i>
                        <span>Preview Ikon</span>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Isi / Konten</label>
                    <textarea name="isi" id="edit_isi" required></textarea>
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

        function editProfil(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_kategori').value = data.kategori;
            document.getElementById('edit_judul').value = data.judul;
            document.getElementById('edit_ikon').value = data.ikon;
            document.getElementById('edit_isi').value = data.isi;
            
            // Update preview icon
            document.getElementById('preview_icon_edit').className = 'fas ' + data.ikon;
            
            openModal('editModal');
        }

        // Icon preview for add form
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

        // Icon preview for edit form
        document.getElementById('edit_ikon').addEventListener('input', function(e) {
            const iconClass = e.target.value.trim();
            const icon = document.getElementById('preview_icon_edit');
            if (iconClass) {
                icon.className = 'fas ' + iconClass;
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>