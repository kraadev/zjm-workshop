<?php
include 'config/koneksi.php';

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query produk berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

// Ambil produk terkait (kategori yang sama)
if ($produk) {
  $stmtRelated = $conn->prepare("SELECT * FROM produk WHERE kategori = ? AND id != ? LIMIT 3");
  $stmtRelated->bind_param("si", $produk['kategori'], $id);
  $stmtRelated->execute();
  $relatedProducts = $stmtRelated->get_result();
}

// Fungsi untuk mendapatkan path gambar yang benar
function getImagePath($gambar) {
  if (empty($gambar)) {
    return '';
  }
  
  // Cek apakah path sudah lengkap
  if (strpos($gambar, '/') !== false || strpos($gambar, '\\') !== false) {
    return $gambar;
  }
  
  // Jika hanya nama file, tambahkan path default
  return 'assets/img/' . $gambar;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Produk - <?= $produk ? $produk['nama'] : 'Tidak Ditemukan'; ?> - Zara Jaya Motor</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
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
      --text-light: #ffffff;
      --bg-light: #f0fff6;
      --shadow: rgba(0, 255, 136, 0.3);
    }

    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: var(--bg-light);
      color: var(--text-dark);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* Animated Background */
    body::before {
      content: '';
      position: fixed;
      width: 300%;
      height: 300%;
      top: -100%;
      left: -100%;
      z-index: -1;
      background: radial-gradient(circle, rgba(0, 255, 136, 0.05) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveBackground 20s linear infinite;
    }

    @keyframes moveBackground {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }

    /* Header */
    header {
      background: linear-gradient(135deg, var(--secondary-color) 0%, #0d2738 100%);
      color: white;
      padding: 20px 0;
      box-shadow: 0 5px 30px rgba(0, 255, 136, 0.2);
      position: sticky;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(10px);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      font-size: 1.8em;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    header h1 i {
      color: var(--primary-color);
      animation: rotate 3s ease-in-out infinite;
      -webkit-text-fill-color: var(--primary-color);
    }

    @keyframes rotate {
      0%, 100% { transform: rotate(0deg); }
      50% { transform: rotate(15deg); }
    }

    nav a {
      color: white;
      text-decoration: none;
      margin-left: 25px;
      font-weight: 600;
      transition: all 0.3s;
      position: relative;
      padding: 5px 0;
    }

    nav a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary-color);
      transition: width 0.3s;
    }

    nav a:hover::after {
      width: 100%;
    }

    nav a:hover {
      color: var(--primary-color);
    }

    nav a i {
      margin-right: 5px;
    }

    /* Breadcrumb */
    .breadcrumb {
      background: white;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .breadcrumb .container {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.95em;
    }

    .breadcrumb a {
      color: var(--primary-dark);
      text-decoration: none;
      transition: color 0.3s;
    }

    .breadcrumb a:hover {
      color: var(--primary-color);
    }

    .breadcrumb i {
      color: #999;
      font-size: 0.8em;
    }

    .breadcrumb span {
      color: #666;
    }

    /* Detail Section */
    .detail-section {
      padding: 60px 20px;
    }

    .detail-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      max-width: 1100px;
      margin: 0 auto;
      background: white;
      border-radius: 25px;
      padding: 50px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .detail-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    }

    .image-section {
      position: relative;
    }

    .detail-img {
      width: 100%;
      height: 450px;
      object-fit: cover;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: transform 0.4s;
    }

    .detail-img:hover {
      transform: scale(1.05);
    }

    .category-badge {
      position: absolute;
      top: 20px;
      left: 20px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 10px 20px;
      border-radius: 30px;
      font-weight: 700;
      box-shadow: 0 5px 15px var(--shadow);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-info h2 {
      font-size: 2.5em;
      color: var(--secondary-color);
      margin-bottom: 20px;
      font-weight: 800;
      line-height: 1.2;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin: 30px 0;
    }

    .info-item {
      background: var(--bg-light);
      padding: 20px;
      border-radius: 15px;
      border-left: 4px solid var(--primary-color);
      transition: all 0.3s;
    }

    .info-item:hover {
      transform: translateX(5px);
      box-shadow: 0 5px 15px rgba(0, 255, 136, 0.2);
    }

    .info-item i {
      color: var(--primary-color);
      margin-right: 10px;
      font-size: 1.2em;
    }

    .info-item label {
      display: block;
      color: #666;
      font-size: 0.9em;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .info-item .value {
      color: var(--secondary-color);
      font-size: 1.2em;
      font-weight: 700;
    }

    .price-section {
      background: linear-gradient(135deg, var(--secondary-color), #0d2738);
      padding: 30px;
      border-radius: 20px;
      margin: 30px 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .price-section::before {
      content: '';
      position: absolute;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
      top: -100px;
      right: -100px;
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(20px, 20px); }
    }

    .price-label {
      color: rgba(255, 255, 255, 0.7);
      font-size: 1em;
      margin-bottom: 10px;
    }

    .price-value {
      font-size: 3em;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .description-section {
      margin: 30px 0;
    }

    .description-section h3 {
      color: var(--secondary-color);
      font-size: 1.5em;
      margin-bottom: 15px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .description-section h3 i {
      color: var(--primary-color);
    }

    .description-section p {
      color: #555;
      line-height: 1.8;
      font-size: 1.05em;
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }

    .btn {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 18px 35px;
      text-decoration: none;
      border-radius: 50px;
      font-size: 1.1em;
      font-weight: 700;
      transition: all 0.4s;
      position: relative;
      overflow: hidden;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      box-shadow: 0 10px 30px var(--shadow);
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-primary:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-primary:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px var(--shadow);
    }

    .btn-secondary {
      background: transparent;
      color: var(--secondary-color);
      border: 3px solid var(--primary-color);
    }

    .btn-secondary:hover {
      background: var(--primary-color);
      transform: translateY(-5px);
      box-shadow: 0 10px 30px var(--shadow);
    }

    /* Related Products */
    .related-section {
      padding: 60px 20px;
      background: white;
    }

    .section-title {
      text-align: center;
      font-size: 2.5em;
      color: var(--secondary-color);
      margin-bottom: 50px;
      font-weight: 800;
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .related-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
    }

    .related-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
      border-color: var(--primary-color);
    }

    .related-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: transform 0.4s;
    }

    .related-card:hover img {
      transform: scale(1.1);
    }

    .related-card-content {
      padding: 25px;
    }

    .related-card h4 {
      color: var(--secondary-color);
      font-size: 1.2em;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .related-card h4 a {
      color: var(--secondary-color);
      text-decoration: none;
      transition: color 0.3s;
    }

    .related-card h4 a:hover {
      color: var(--primary-dark);
    }

    .related-card .price {
      font-size: 1.3em;
      font-weight: 700;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-top: 10px;
    }

    /* Not Found */
    .not-found {
      text-align: center;
      padding: 100px 20px;
    }

    .not-found i {
      font-size: 6em;
      color: var(--primary-color);
      margin-bottom: 30px;
    }

    .not-found h2 {
      font-size: 2.5em;
      color: var(--secondary-color);
      margin-bottom: 20px;
    }

    .not-found p {
      font-size: 1.2em;
      color: #666;
      margin-bottom: 30px;
    }

    /* Footer */
    footer {
      background: var(--secondary-color);
      color: white;
      text-align: center;
      padding: 40px 20px;
      position: relative;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }

    footer p {
      margin: 0;
      font-weight: 300;
    }

    /* Responsiveness */
    @media (max-width: 968px) {
      .detail-container {
        grid-template-columns: 1fr;
        padding: 30px;
      }

      .detail-img {
        height: 350px;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .action-buttons {
        flex-direction: column;
      }

      .related-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        text-align: center;
      }

      nav {
        margin-top: 20px;
      }

      nav a {
        margin: 0 10px;
        font-size: 0.9em;
      }

      .detail-info h2 {
        font-size: 1.8em;
      }

      .price-value {
        font-size: 2.2em;
      }

      .section-title {
        font-size: 1.8em;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <div class="header-content">
        <a href="login.php" style="text-decoration: none; color: inherit;"><h1><i class="fas fa-tools"></i> Zara Jaya Motor</h1></a>
        <nav>
          <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
          <a href="layanan.php"><i class="fas fa-wrench"></i> Layanan</a>
          <a href="produk.php"><i class="fas fa-box"></i> Sparepart</a>
          <a href="about.php"><i class="fas fa-info-circle"></i> Tentang Kami</a>
          <a href="kontak.php"><i class="fas fa-phone"></i> Kontak</a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
      <i class="fas fa-chevron-right"></i>
      <a href="produk.php">Produk</a>
      <?php if ($produk): ?>
        <i class="fas fa-chevron-right"></i>
        <a href="produk.php?kategori=<?= urlencode($produk['kategori']); ?>"><?= $produk['kategori']; ?></a>
        <i class="fas fa-chevron-right"></i>
        <span><?= $produk['nama']; ?></span>
      <?php endif; ?>
    </div>
  </section>

  <?php if ($produk): ?>
    <!-- Detail Section -->
    <section class="detail-section">
      <div class="detail-container">
        <!-- Image Section -->
        <div class="image-section">
          <div class="category-badge">
            <i class="fas fa-tag"></i>
            <?= $produk['kategori']; ?>
          </div>
          <?php
            $imagePath = getImagePath($produk['gambar']);
            $placeholderUrl = 'https://via.placeholder.com/500x450/00ff88/0a1929?text=' . urlencode($produk['nama']);
          ?>
          <img src="<?= $imagePath; ?>" alt="<?= $produk['nama']; ?>" class="detail-img" onerror="this.src='<?= $placeholderUrl; ?>'">
        </div>

        <!-- Info Section -->
        <div class="detail-info">
          <h2><?= $produk['nama']; ?></h2>

          <!-- Info Grid -->
          <div class="info-grid">
            <div class="info-item">
              <label><i class="fas fa-copyright"></i> Merek</label>
              <div class="value"><?= $produk['merek']; ?></div>
            </div>
            <div class="info-item">
              <label><i class="fas fa-boxes"></i> Stok Tersedia</label>
              <div class="value"><?= $produk['stok']; ?> unit</div>
            </div>
          </div>

          <!-- Price Section -->
          <div class="price-section">
            <div class="price-label">Harga Produk</div>
            <div class="price-value">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></div>
          </div>

          <!-- Description -->
          <div class="description-section">
            <h3><i class="fas fa-info-circle"></i> Deskripsi Produk</h3>
            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
          </div>

         <!-- Action Buttons -->
          <div class="action-buttons">
            <?php if (!empty($produk['link_pemesanan'])): ?>
              <a href="<?= htmlspecialchars($produk['link_pemesanan']); ?>" target="_blank" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Pesan Sekarang
              </a>
            <?php else: ?>
              <a href="kontak.php" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Pesan Sekarang
              </a>
            <?php endif; ?>

            <a href="produk.php?kategori=<?= urlencode($produk['kategori']); ?>" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Related Products -->
    <?php if ($relatedProducts && $relatedProducts->num_rows > 0): ?>
      <section class="related-section">
        <h3 class="section-title">Produk Terkait</h3>
        <div class="related-grid">
          <?php while ($related = $relatedProducts->fetch_assoc()): ?>
            <?php
              $relatedImagePath = getImagePath($related['gambar']);
            ?>
            <div class="related-card">
              <img src="<?= $relatedImagePath; ?>" alt="<?= $related['nama']; ?>" onerror="this.src='https://via.placeholder.com/280x200/00ff88/0a1929?text=No+Image'">
              <div class="related-card-content">
                <h4><a href="detail_produk.php?id=<?= $related['id']; ?>"><?= $related['nama']; ?></a></h4>
                <p class="price">Rp <?= number_format($related['harga'], 0, ',', '.'); ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </section>
    <?php endif; ?>

  <?php else: ?>
    <!-- Not Found -->
    <section class="not-found">
      <i class="fas fa-box-open"></i>
      <h2>Produk Tidak Ditemukan</h2>
      <p>Maaf, produk yang Anda cari tidak tersedia atau sudah dihapus.</p>
      <a href="produk.php" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali ke Produk
      </a>
    </section>
  <?php endif; ?>

  <footer>
    <p>&copy; 2025 Zara Jaya Motor. All rights reserved.</p>
  </footer>

  <script>
    // Image zoom on click
    const detailImg = document.querySelector('.detail-img');
    if (detailImg) {
      detailImg.addEventListener('click', function() {
        if (this.style.transform === 'scale(1.5)') {
          this.style.transform = 'scale(1)';
          this.style.cursor = 'zoom-in';
        } else {
          this.style.transform = 'scale(1.5)';
          this.style.cursor = 'zoom-out';
        }
      });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  </script>
</body>
</html>