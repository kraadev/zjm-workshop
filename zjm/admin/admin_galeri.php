<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle Create
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/img/galeri/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $gambar = $new_filename;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $new_filename)) {
            $stmt = $conn->prepare("INSERT INTO galeri (judul, gambar) VALUES (?, ?)");
            $stmt->bind_param("ss", $judul, $gambar);
            $stmt->execute();
            header('Location: admin_galeri.php?status=success');
            exit;
        }
    }
}

// Handle Update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $gambar_lama = $_POST['gambar_lama'];
    $gambar = $gambar_lama;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/img/galeri/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $gambar = $new_filename;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $new_filename)) {
            if (file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE galeri SET judul=?, gambar=? WHERE id=?");
    $stmt->bind_param("ssi", $judul, $gambar, $id);
    $stmt->execute();
    header('Location: admin_galeri.php?status=updated');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $result = $conn->query("SELECT gambar FROM galeri WHERE id=$id");
    $data = $result->fetch_assoc();
    
    if ($data['gambar'] && file_exists('assets/img/galeri/' . $data['gambar'])) {
        unlink('assets/img/galeri/' . $data['gambar']);
    }
    
    $conn->query("DELETE FROM galeri WHERE id=$id");
    header('Location: admin_galeri.php?status=deleted');
    exit;
}

// Get all galeri
$galeri = $conn->query("SELECT * FROM galeri ORDER BY tanggal_upload DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - Zara Jaya Motor</title>
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

        .content-box h2 {
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            background: white;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0, 255, 136, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-info {
            padding: 15px;
            background: white;
        }

        .gallery-info h3 {
            font-size: 1em;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .gallery-info p {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 12px;
        }

        .gallery-actions {
            display: flex;
            gap: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4em;
            color: var(--primary-color);
            opacity: 0.3;
            margin-bottom: 20px;
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
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: zoomIn 0.3s ease-out;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
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
            transition: color 0.3s;
        }

        .close:hover {
            color: var(--primary-color);
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

        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .img-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
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

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 15px;
            }

            .gallery-item img {
                height: 150px;
            }

            .gallery-info {
                padding: 10px;
            }

            .gallery-info h3 {
                font-size: 0.9em;
            }

            .gallery-info p {
                font-size: 0.8em;
            }

            .gallery-actions {
                flex-direction: column;
                gap: 5px;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
                font-size: 0.85em;
                padding: 8px;
            }

            .modal-content {
                padding: 20px;
                margin: 10px;
            }

            .form-group input[type="text"],
            .form-group input[type="file"] {
                padding: 10px;
                font-size: 0.95em;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
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
            <a href="admin_layanan.php" class="menu-item">
                <i class="fas fa-wrench"></i>
                <span>Kelola Layanan</span>
            </a>
            <a href="admin_produk.php" class="menu-item">
                <i class="fas fa-box"></i>
                <span>Kelola Produk</span>
            </a>
            <a href="admin_galeri.php" class="menu-item active">
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
            <h1><i class="fas fa-images"></i> Kelola Galeri</h1>
            <button class="btn btn-primary" onclick="openModal('tambahModal')">
                <i class="fas fa-plus"></i> Upload Foto
            </button>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'success') echo '<i class="fas fa-check-circle"></i> Foto berhasil diupload!';
                if ($_GET['status'] == 'updated') echo '<i class="fas fa-check-circle"></i> Foto berhasil diupdate!';
                if ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Foto berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <h2>Galeri Foto</h2>
            
            <?php if ($galeri->num_rows > 0): ?>
                <div class="gallery-grid">
                    <?php while ($row = $galeri->fetch_assoc()): ?>
                    <div class="gallery-item">
                        <img src="assets/img/galeri/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                        <div class="gallery-info">
                            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <p><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($row['tanggal_upload'])); ?></p>
                            <div class="gallery-actions">
                                <button class="btn btn-warning btn-sm" onclick='editGaleri(<?php echo json_encode($row); ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>Belum ada foto di galeri. Klik tombol "Upload Foto" untuk menambahkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Tambah -->
    <div id="tambahModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Upload Foto Baru</h2>
                <span class="close" onclick="closeModal('tambahModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Judul Foto</label>
                    <input type="text" name="judul" required placeholder="Masukkan judul foto">
                </div>
                <div class="form-group">
                    <label>Pilih Gambar</label>
                    <input type="file" name="gambar" accept="image/*" required onchange="previewImage(event, 'preview1')">
                    <img id="preview1" class="img-preview">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('tambahModal')">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Foto</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                
                <div class="form-group">
                    <label>Judul Foto</label>
                    <input type="text" name="judul" id="edit_judul" required>
                </div>
                
                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <img id="edit_current_image" class="img-preview" style="display: block;">
                </div>
                
                <div class="form-group">
                    <label>Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" accept="image/*" onchange="previewImage(event, 'preview2')">
                    <img id="preview2" class="img-preview">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" name="edit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
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
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Reset form
            const modal = document.getElementById(modalId);
            const form = modal.querySelector('form');
            if (form) form.reset();
            
            // Hide previews
            modal.querySelectorAll('.img-preview').forEach(img => img.style.display = 'none');
        }

        function editGaleri(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_judul').value = data.judul;
            document.getElementById('edit_gambar_lama').value = data.gambar;
            document.getElementById('edit_current_image').src = 'assets/img/galeri/' + data.gambar;
            openModal('editModal');
        }

        function previewImage(event, previewId) {
            const preview = document.getElementById(previewId);
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
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

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Auto hide alert after 3 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            }
        }, 3000);
    </script>
</body>
</html>