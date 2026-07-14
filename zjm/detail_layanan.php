<?php
include 'config/koneksi.php';

// Ambil ID layanan dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query data layanan berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM layanan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$layanan = $result->fetch_assoc();

// Ambil layanan terkait (3 layanan lainnya)
if ($layanan) {
  $stmtRelated = $conn->prepare("SELECT * FROM layanan WHERE id != ? ORDER BY RAND() LIMIT 3");
  $stmtRelated->bind_param("i", $id);
  $stmtRelated->execute();
  $relatedServices = $stmtRelated->get_result();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Layanan - <?= $layanan ? $layanan['nama_layanan'] : 'Layanan Tidak Ditemukan'; ?> - Zara Jaya Motor</title>
  
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

    nav a:hover::after,
    nav a.active::after {
      width: 100%;
    }

    nav a:hover,
    nav a.active {
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
      grid-template-columns: 1fr 1.2fr;
      gap: 50px;
      max-width: 1200px;
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
      height: 500px;
      object-fit: cover;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: transform 0.4s;
      cursor: zoom-in;
    }

    .detail-img:hover {
      transform: scale(1.05);
    }

    .service-badge {
      position: absolute;
      top: 20px;
      left: 20px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 12px 25px;
      border-radius: 30px;
      font-weight: 700;
      box-shadow: 0 5px 15px var(--shadow);
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.1em;
    }

    .detail-info h2 {
      font-size: 2.8em;
      color: var(--secondary-color);
      margin-bottom: 25px;
      font-weight: 800;
      line-height: 1.2;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .detail-info h2 i {
      color: var(--primary-color);
      font-size: 0.9em;
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
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
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
      font-size: 1.6em;
      margin-bottom: 20px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .description-section h3 i {
      color: var(--primary-color);
    }

    .description-section p {
      color: #555;
      line-height: 1.9;
      font-size: 1.05em;
      margin-bottom: 25px;
    }

    /* Service Details List */
    .service-details {
      background: var(--bg-light);
      padding: 30px;
      border-radius: 20px;
      margin: 30px 0;
      border-left: 5px solid var(--primary-color);
    }

    .service-details h3 {
      color: var(--secondary-color);
      font-size: 1.5em;
      margin-bottom: 20px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .service-details h3 i {
      color: var(--primary-color);
    }

    .service-details ul {
      list-style: none;
      padding: 0;
    }

    .service-details ul li {
      color: #555;
      padding: 12px 0;
      border-bottom: 1px solid rgba(0, 255, 136, 0.1);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      font-size: 1.05em;
      line-height: 1.7;
      transition: all 0.3s;
    }

    .service-details ul li:last-child {
      border-bottom: none;
    }

    .service-details ul li:hover {
      padding-left: 10px;
      color: var(--secondary-color);
    }

    .service-details ul li::before {
      content: '\f00c';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: var(--primary-color);
      font-size: 1.1em;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 40px;
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

    /* Features Grid */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin: 30px 0;
    }

    .feature-item {
      background: white;
      padding: 25px;
      border-radius: 15px;
      text-align: center;
      border: 2px solid var(--bg-light);
      transition: all 0.3s;
    }

    .feature-item:hover {
      border-color: var(--primary-color);
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 255, 136, 0.15);
    }

    .feature-item i {
      font-size: 2.5em;
      color: var(--primary-color);
      margin-bottom: 15px;
    }

    .feature-item h4 {
      color: var(--secondary-color);
      font-weight: 700;
      margin-bottom: 8px;
    }

    .feature-item p {
      color: #666;
      font-size: 0.9em;
    }

    /* Related Services */
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
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
      text-decoration: none;
      color: inherit;
      display: block;
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
      font-size: 1.3em;
      margin-bottom: 10px;
      font-weight: 700;
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

      .action-buttons {
        flex-direction: column;
      }

      .features-grid {
        grid-template-columns: 1fr;
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
        font-size: 2em;
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
          <a href="layanan.php" class="active"><i class="fas fa-wrench"></i> Layanan</a>
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
      <a href="layanan.php">Layanan</a>
      <?php if ($layanan): ?>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($layanan['nama_layanan']); ?></span>
      <?php endif; ?>
    </div>
  </section>

  <?php if ($layanan): ?>
    <!-- Detail Section -->
    <section class="detail-section">
      <div class="detail-container">
        <!-- Image Section -->
        <div class="image-section">
          <div class="service-badge">
            <i class="fas fa-cog"></i>
            Layanan Profesional
          </div>
          <?php
            $gambar = htmlspecialchars($layanan['gambar'] ?? 'default.jpg');
            $placeholder = 'https://via.placeholder.com/500x500/00ff88/0a1929?text=' . urlencode($layanan['nama_layanan']);
          ?>
          <img src="<?= $gambar; ?>" alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>" class="detail-img" onerror="this.onerror=null;this.src='<?= $placeholder; ?>'">
        </div>

        <!-- Info Section -->
        <div class="detail-info">
          <h2>
            <i class="fas fa-wrench"></i>
            <?= htmlspecialchars($layanan['nama_layanan']); ?>
          </h2>

          <!-- Description -->
          <div class="description-section">
            <h3><i class="fas fa-info-circle"></i> Deskripsi Layanan</h3>
            <p><?= nl2br(htmlspecialchars($layanan['deskripsi'])); ?></p>
          </div>

          <!-- Price Section -->
          <div class="price-section">
            <div class="price-label">
              <i class="fas fa-tag"></i>
              Harga Layanan
            </div>
            <div class="price-value">Rp <?= number_format($layanan['harga'], 0, ',', '.'); ?></div>
          </div>

          <!-- Service Details -->
          <?php if (!empty($layanan['detail_pelayanan'])): ?>
            <div class="service-details">
              <h3><i class="fas fa-clipboard-list"></i> Detail Pelayanan yang Didapatkan</h3>
              <ul>
                <?php
                // Pisahkan baris berdasarkan tanda <br> atau newline
                $details = preg_split("/<br>|\\r?\\n/", $layanan['detail_pelayanan']);
                foreach ($details as $d) {
                    $trimmed = trim($d);
                    if (!empty($trimmed)) echo "<li>" . htmlspecialchars($trimmed) . "</li>";
                }
                ?>
              </ul>
            </div>
          <?php endif; ?>

          <!-- Features Grid -->
          <div class="features-grid">
            <div class="feature-item">
              <i class="fas fa-clock"></i>
              <h4>Cepat</h4>
              <p>Pengerjaan efisien</p>
            </div>
            <div class="feature-item">
              <i class="fas fa-shield-alt"></i>
              <h4>Garansi</h4>
              <p>Jaminan kualitas</p>
            </div>
            <div class="feature-item">
              <i class="fas fa-user-check"></i>
              <h4>Profesional</h4>
              <p>Teknisi ahli</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="action-buttons">
            <a href="kontak.php" class="btn btn-primary">
              <i class="fas fa-calendar-check"></i> Booking Sekarang
            </a>
            <a href="layanan.php" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Related Services -->
    <?php if ($relatedServices && $relatedServices->num_rows > 0): ?>
      <section class="related-section">
        <h3 class="section-title">Layanan Lainnya</h3>
        <div class="related-grid">
          <?php while ($related = $relatedServices->fetch_assoc()): ?>
            <a href="detail_layanan.php?id=<?= $related['id']; ?>" class="related-card">
              <img src="assets/img/<?= htmlspecialchars($related['gambar']); ?>" alt="<?= htmlspecialchars($related['nama_layanan']); ?>" onerror="this.src='https://via.placeholder.com/300x200/00ff88/0a1929?text=No+Image'">
              <div class="related-card-content">
                <h4><?= htmlspecialchars($related['nama_layanan']); ?></h4>
                <p><?= htmlspecialchars(substr($related['deskripsi'], 0, 80)); ?>...</p>
                <p class="price">Rp <?= number_format($related['harga'], 0, ',', '.'); ?></p>
              </div>
            </a>
          <?php endwhile; ?>
        </div>
      </section>
    <?php endif; ?>

  <?php else: ?>
    <!-- Not Found -->
    <section class="not-found">
      <i class="fas fa-exclamation-triangle"></i>
      <h2>Layanan Tidak Ditemukan</h2>
      <p>Maaf, layanan yang Anda cari tidak tersedia atau sudah dihapus.</p>
      <a href="layanan.php" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali ke Layanan
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

    // Animation on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '0';
          entry.target.style.animation = 'fadeIn 0.6s ease-out forwards';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.service-details, .features-grid, .related-card').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>