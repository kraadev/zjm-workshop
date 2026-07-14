<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle Create
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $merek = $_POST['merek'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $link_pemesanan = $_POST['link_pemesanan'];
    
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "image/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = $target_dir . uniqid() . '.' . $file_extension;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }
    
    $stmt = $conn->prepare("INSERT INTO produk (nama, merek, kategori, deskripsi, harga, stok, gambar, link_pemesanan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdis", $nama, $merek, $kategori, $deskripsi, $harga, $stok, $gambar, $link_pemesanan);
    $stmt->execute();
    header('Location: admin_produk.php?status=success');
    exit;
}

// Handle Update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $merek = $_POST['merek'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $link_pemesanan = $_POST['link_pemesanan'];
    
    $gambar_lama = $_POST['gambar_lama'];
    $gambar = $gambar_lama;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "image/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = $target_dir . uniqid() . '.' . $file_extension;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
        
        if ($gambar_lama && file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }
    }
    
    $stmt = $conn->prepare("UPDATE produk SET nama=?, merek=?, kategori=?, deskripsi=?, harga=?, stok=?, gambar=?, link_pemesanan=? WHERE id=?");
    $stmt->bind_param("ssssdissi", $nama, $merek, $kategori, $deskripsi, $harga, $stok, $gambar, $link_pemesanan, $id);
    $stmt->execute();
    header('Location: admin_produk.php?status=updated');
    exit;
}

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $result = $conn->query("SELECT gambar FROM produk WHERE id=$id");
    $data = $result->fetch_assoc();
    
    if ($data['gambar'] && file_exists($data['gambar'])) {
        unlink($data['gambar']);
    }
    
    $conn->query("DELETE FROM produk WHERE id=$id");
    header('Location: admin_produk.php?status=deleted');
    exit;
}

// Get all produk
$produk = $conn->query("SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Zara Jaya Motor</title>
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

        /* Mobile Toggle */
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
            padding: 10px;
            font-size: 1.2em;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
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
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
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
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            font-size: 0.95em;
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
            padding: 6px 10px;
            font-size: 0.8em;
        }

        .content-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.9em;
        }

        th {
            background: var(--bg-light);
            color: var(--secondary-color);
            font-weight: 600;
            white-space: nowrap;
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
            padding: 15px;
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

        .img-preview {
            max-width: 60px;
            max-height: 60px;
            border-radius: 6px;
            display: block;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Mobile Responsive */
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
                padding: 8px 14px;
                font-size: 0.9em;
            }

            .content-box {
                padding: 16px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.85em;
            }

            .img-preview {
                max-width: 50px;
                max-height: 50px;
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
            <a href="admin_produk.php" class="menu-item active">
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
            <h1><i class="fas fa-box"></i> Kelola Produk</h1>
            <button class="btn btn-primary" onclick="openModal('tambahModal')">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'success') echo '<i class="fas fa-check-circle"></i> Produk berhasil ditambahkan!';
                elseif ($_GET['status'] == 'updated') echo '<i class="fas fa-check-circle"></i> Produk berhasil diupdate!';
                elseif ($_GET['status'] == 'deleted') echo '<i class="fas fa-check-circle"></i> Produk berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <h2>Daftar Produk</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Merek</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Link</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $produk->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td>
                                <?php if ($row['gambar']): ?>
                                    <img src="<?php echo htmlspecialchars($row['gambar']); ?>" class="img-preview" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                                <?php else: ?>
                                    <span style="color: #999; font-size: 0.8em;">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['merek']); ?></td>
                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                            <td>Rp <?php echo number_format((float)$row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php 
                                $stok = (int)$row['stok'];
                                if ($stok > 10) {
                                    echo "<span class='badge badge-success'>$stok</span>";
                                } elseif ($stok > 0) {
                                    echo "<span class='badge badge-warning'>$stok</span>";
                                } else {
                                    echo "<span class='badge badge-danger'>Habis</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (!empty($row['link_pemesanan'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['link_pemesanan']); ?>" target="_blank" style="color: var(--primary-dark); text-decoration: none; font-size: 0.85em;">
                                        <i class="fas fa-external-link-alt"></i> Link
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999; font-size: 0.85em;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning btn-sm" onclick='editProduk(<?php echo htmlspecialchars(json_encode($row, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS), ENT_QUOTES, 'UTF-8'); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?hapus=<?php echo (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
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
                <h2>Tambah Produk</h2>
                <span class="close" onclick="closeModal('tambahModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Merek</label>
                    <input type="text" name="merek" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Oli">Oli</option>
                        <option value="Kampas Rem">Kampas Rem</option>
                        <option value="Busi">Busi</option>
                        <option value="Ban">Ban</option>
                        <option value="Aki">Aki</option>
                        <option value="Rantai">Rantai</option>
                        <option value="Filter">Filter</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" required>
                </div>
                <div class="form-group">
                    <label>Link Pemesanan (Opsional)</label>
                    <input type="url" name="link_pemesanan" placeholder="https://shopee.co.id/...">
                    <small style="color: #666; font-size: 0.8em; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> Link untuk pemesanan online (Shopee, Tokopedia, WhatsApp, dll)
                    </small>
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
                <h2>Edit Produk</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" id="edit_nama" required>
                </div>
                <div class="form-group">
                    <label>Merek</label>
                    <input type="text" name="merek" id="edit_merek" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" id="edit_kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Oli">Oli</option>
                        <option value="Kampas Rem">Kampas Rem</option>
                        <option value="Busi">Busi</option>
                        <option value="Ban">Ban</option>
                        <option value="Aki">Aki</option>
                        <option value="Rantai">Rantai</option>
                        <option value="Filter">Filter</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" id="edit_harga" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" id="edit_stok" required>
                </div>
                <div class="form-group">
                    <label>Link Pemesanan (Opsional)</label>
                    <input type="url" name="link_pemesanan" id="edit_link_pemesanan" placeholder="https://shopee.co.id/...">
                    <small style="color: #666; font-size: 0.8em; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> Link untuk pemesanan online (Shopee, Tokopedia, WhatsApp, dll)
                    </small>
                </div>
                <div class="form-group">
                    <label>Gambar (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" name="gambar" accept="image/*">
                    <img id="current_image" class="img-preview" style="margin-top: 10px; display: none;">
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

        function editProduk(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nama').value = data.nama || '';
            document.getElementById('edit_merek').value = data.merek || '';
            document.getElementById('edit_kategori').value = data.kategori || '';
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';
            document.getElementById('edit_harga').value = data.harga || '';
            document.getElementById('edit_stok').value = data.stok || '';
            document.getElementById('edit_link_pemesanan').value = data.link_pemesanan || '';
            document.getElementById('edit_gambar_lama').value = data.gambar || '';
            
            const img = document.getElementById('current_image');
            if (data.gambar) {
                img.src = data.gambar;
                img.style.display = 'block';
            } else {
                img.style.display = 'none';
            }
            openModal('editModal');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>