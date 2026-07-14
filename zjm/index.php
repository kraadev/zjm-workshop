<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zara Jaya Motor - Solusi Terbaik Untuk Motor Anda</title>
  
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

    /* Hero Banner */
    .hero {
      background: linear-gradient(135deg, rgba(10, 25, 41, 0.95), rgba(13, 39, 56, 0.95)), 
                  url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200') center/cover;
      color: white;
      text-align: center;
      padding: 120px 20px;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.1), transparent);
      animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero h2 {
      font-size: 3.5em;
      margin-bottom: 20px;
      text-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
      font-weight: 800;
      animation: fadeInDown 1s ease-out;
    }

    .hero h2 span {
      color: var(--primary-color);
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hero p {
      font-size: 1.3em;
      margin-bottom: 35px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
      animation: fadeInUp 1s ease-out 0.3s backwards;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .cta-button {
      display: inline-block;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 18px 45px;
      text-decoration: none;
      border-radius: 50px;
      font-size: 1.1em;
      font-weight: 700;
      transition: all 0.4s;
      box-shadow: 0 10px 30px var(--shadow);
      position: relative;
      overflow: hidden;
      animation: fadeInUp 1s ease-out 0.6s backwards;
    }

    .cta-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .cta-button:hover::before {
      left: 100%;
    }

    .cta-button:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px var(--shadow);
    }

    /* About Section */
    .about-section {
      padding: 80px 20px;
      background: white;
      position: relative;
    }

    .about-content {
      max-width: 1000px;
      margin: 0 auto;
      text-align: center;
    }

    .about-content h3 {
      font-size: 2.8em;
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 30px;
      font-weight: 800;
      position: relative;
      display: inline-block;
    }

    .about-content h3::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }

    .about-content p {
      font-size: 1.15em;
      line-height: 1.9;
      margin-bottom: 20px;
      color: #555;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      margin-top: 60px;
    }

    .feature-item {
      background: linear-gradient(135deg, #ffffff, var(--bg-light));
      padding: 40px 25px;
      border-radius: 20px;
      text-align: center;
      transition: all 0.4s;
      border: 2px solid transparent;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .feature-item:hover {
      transform: translateY(-10px);
      border-color: var(--primary-color);
      box-shadow: 0 15px 40px var(--shadow);
    }

    .feature-icon {
      font-size: 3.5em;
      color: var(--primary-color);
      margin-bottom: 20px;
      transition: all 0.4s;
    }

    .feature-item:hover .feature-icon {
      transform: scale(1.2) rotateY(360deg);
    }

    .feature-item h4 {
      color: var(--secondary-color);
      margin-bottom: 15px;
      font-size: 1.3em;
      font-weight: 700;
    }

    .feature-item p {
      font-size: 0.95em;
      color: #666;
    }

    /* Layanan Section */
    .layanan-section {
      padding: 80px 20px;
      background: linear-gradient(180deg, var(--bg-light), white);
    }

    .section-title {
      text-align: center;
      font-size: 2.8em;
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
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

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: white;
      padding: 35px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
      transform: scaleX(0);
      transition: transform 0.4s;
    }

    .card:hover::before {
      transform: scaleX(1);
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
      border-color: var(--primary-color);
    }

    .card h4 {
      color: var(--secondary-color);
      font-size: 1.5em;
      margin-bottom: 15px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card h4::before {
      content: '\f1b9';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: var(--primary-color);
      font-size: 0.8em;
    }

    .card p {
      margin: 15px 0;
      color: #666;
      line-height: 1.7;
    }

    .card .harga {
      font-size: 1.5em;
      font-weight: 700;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-top: 20px;
    }

    /* Promo Section */
    .promo-section {
      padding: 80px 20px;
      background: linear-gradient(135deg, var(--secondary-color), #0d2738);
      color: white;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .promo-section::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
      top: -250px;
      right: -250px;
      animation: float 6s ease-in-out infinite;
    }

    .promo-section::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
      bottom: -200px;
      left: -200px;
      animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(30px, 30px); }
    }

    .promo-section h3 {
      font-size: 2.8em;
      margin-bottom: 30px;
      font-weight: 800;
      position: relative;
      z-index: 1;
    }

    .promo-section h3 i {
      color: var(--primary-color);
      animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .promo-section p {
      font-size: 1.2em;
      margin-bottom: 30px;
      position: relative;
      z-index: 1;
    }

    .promo-box {
      background: rgba(0, 255, 136, 0.1);
      padding: 40px;
      border-radius: 20px;
      max-width: 700px;
      margin: 0 auto 30px;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(0, 255, 136, 0.3);
      position: relative;
      z-index: 1;
      transition: all 0.4s;
    }

    .promo-box:hover {
      transform: scale(1.05);
      box-shadow: 0 20px 50px var(--shadow);
    }

    .promo-box h4 {
      font-size: 2em;
      margin-bottom: 15px;
      color: var(--primary-color);
      font-weight: 700;
    }

    /* CTA Section */
    .cta-section {
      padding: 100px 20px;
      background: linear-gradient(135deg, var(--bg-light), white);
      text-align: center;
    }

    .cta-section h3 {
      font-size: 2.8em;
      margin-bottom: 25px;
      color: var(--secondary-color);
      font-weight: 800;
    }

    .cta-section p {
      font-size: 1.2em;
      margin-bottom: 40px;
      color: #555;
    }

    .cta-buttons {
      display: flex;
      justify-content: center;
      gap: 25px;
      flex-wrap: wrap;
    }

    .btn-primary, .btn-secondary {
      padding: 18px 45px;
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

    /* Loading Animation */
    @keyframes shimmer {
      0% { background-position: -1000px 0; }
      100% { background-position: 1000px 0; }
    }

    /* Responsiveness */
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

      .hero h2 {
        font-size: 2.2em;
      }

      .hero p {
        font-size: 1.1em;
      }

      .about-content h3, .section-title, .cta-section h3, .promo-section h3 {
        font-size: 2em;
      }

      .features {
        grid-template-columns: 1fr;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn-primary, .btn-secondary {
        width: 100%;
        max-width: 300px;
      }
    }

        /* Galeri Section */
    .galeri-section {
      padding: 80px 20px;
      background: white;
    }

    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .galeri-item {
      position: relative;
      overflow: hidden;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.4s;
      cursor: pointer;
      aspect-ratio: 4/3;
    }

    .galeri-item:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
    }

    .galeri-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }

    .galeri-item:hover img {
      transform: scale(1.1);
    }

    .galeri-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to top, rgba(10, 25, 41, 0.95), transparent);
      padding: 25px;
      transform: translateY(100%);
      transition: transform 0.4s;
    }

    .galeri-item:hover .galeri-overlay {
      transform: translateY(0);
    }

    .galeri-overlay h4 {
      color: white;
      font-size: 1.2em;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .galeri-overlay p {
      color: var(--primary-color);
      font-size: 0.9em;
    }

    .galeri-empty {
      text-align: center;
      grid-column: 1/-1;
      padding: 60px 20px;
      color: #999;
    }

    .galeri-empty i {
      font-size: 4em;
      color: var(--primary-color);
      margin-bottom: 20px;
      opacity: 0.3;
    }

        /* Lightbox Modal */
    .lightbox {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.95);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .lightbox.active {
      display: flex;
    }

    .lightbox-content {
      max-width: 90%;
      max-height: 90%;
      position: relative;
      animation: zoomIn 0.3s ease-out;
    }

    @keyframes zoomIn {
      from {
        transform: scale(0.8);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .lightbox-content img {
      max-width: 100%;
      max-height: 80vh;
      border-radius: 10px;
      box-shadow: 0 10px 50px rgba(0, 255, 136, 0.3);
    }

    .lightbox-close {
      position: absolute;
      top: -40px;
      right: 0;
      color: white;
      font-size: 2em;
      cursor: pointer;
      transition: all 0.3s;
    }

    .lightbox-close:hover {
      color: var(--primary-color);
      transform: rotate(90deg);
    }

    .lightbox-caption {
      text-align: center;
      color: white;
      margin-top: 20px;
      font-size: 1.2em;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <div class="header-content">
        <a href="admin/login.php" style="text-decoration: none; color: inherit;"><h1><i class="fas fa-tools"></i> Zara Jaya Motor</h1></a>
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

  <!-- Hero Banner -->
  <section class="hero">
    <div class="hero-content">
      <h2>Solusi <span>Terbaik</span> Untuk Motor Anda</h2>
      <p>Servis cepat, sparepart lengkap, harga bersahabat</p>
      <a href="kontak.php" class="cta-button"><i class="fas fa-phone-alt"></i> Hubungi Kami Sekarang</a>
    </div>
  </section>

  <!-- Tentang Bengkel -->
  <section class="about-section">
    <div class="about-content">
      <h3>Selamat Datang di Zara Jaya Motor</h3>
      <p>
        Zara Jaya Motor adalah bengkel motor terpercaya dengan pengalaman lebih dari 10 tahun melayani perawatan dan perbaikan motor Anda. Kami berkomitmen memberikan layanan terbaik dengan teknisi profesional dan peralatan modern.
      </p>
      <p>
        Kepuasan pelanggan adalah prioritas kami. Setiap motor yang masuk ditangani dengan cermat dan profesional untuk memastikan performa optimal kendaraan Anda.
      </p>

      <div class="features">
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-user-cog"></i></div>
          <h4>Teknisi Ahli</h4>
          <p>Tim mekanik berpengalaman dan tersertifikasi</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-bolt"></i></div>
          <h4>Servis Cepat</h4>
          <p>Pengerjaan efisien tanpa mengurangi kualitas</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-tags"></i></div>
          <h4>Harga Terjangkau</h4>
          <p>Tarif kompetitif dengan hasil maksimal</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-certificate"></i></div>
          <h4>Garansi Jasa</h4>
          <p>Jaminan kualitas untuk ketenangan Anda</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Layanan Unggulan -->
  <section class="layanan-section">
    <h3 class="section-title">Layanan Unggulan Kami</h3>
    <div class="grid">
      <?php
      $query = "SELECT * FROM layanan ORDER BY id ASC LIMIT 6";
      $hasil = $conn->query($query);

      if ($hasil && $hasil->num_rows > 0) {
        while ($row = $hasil->fetch_assoc()) {
          // Sanitasi data agar aman dari XSS
          $id         = (int)$row['id'];
          $nama       = htmlspecialchars($row['nama'] ?? '');
          $deskripsi  = htmlspecialchars($row['deskripsi'] ?? '');
          $harga      = number_format((float)$row['harga'], 0, ',', '.');
          $gambar     = htmlspecialchars($row['gambar'] ?? '');
          $placeholder = 'https://via.placeholder.com/300x220/00ff88/0a1929?text=' . urlencode($nama);

          echo "
            <a href='detail_layanan.php?id={$id}' class='card'>
              <div class='card-image'>
                <img src='assets/img/{$gambar}' 
                    alt='{$nama}' 
                    onerror=\"this.onerror=null;this.src='{$placeholder}';\">
              </div>
              <div class='card-body'>
                <h4>{$nama}</h4>
                <p>{$deskripsi}</p>
                <p class='harga'>Rp {$harga}</p>
              </div>
            </a>
          ";
        }
      } else {
        echo "<p style='text-align:center; grid-column: 1/-1;'>Belum ada layanan tersedia</p>";
      }
      ?>
    </div>

    <div style="text-align: center; margin-top: 50px;">
      <a href="layanan.php" class="cta-button">
        <i class="fas fa-arrow-right"></i> Lihat Semua Layanan
      </a>
    </div>
  </section>


  <!-- Promo Terbaru -->
  <section class="promo-section">
    <h3><i class="fas fa-gift"></i> Promo Spesial Bulan Ini!</h3>
    <div class="promo-box">
      <h4>Diskon 20% Service Berkala</h4>
      <p>Dapatkan diskon spesial untuk paket servis berkala. Penawaran terbatas!</p>
    </div>
    <p>Jangan lewatkan kesempatan emas ini. Hubungi kami untuk informasi lebih lanjut!</p>
  </section>

   <!-- Galeri Section -->
  <section class="galeri-section">
    <h3 class="section-title"><i class="fas fa-images"></i> Galeri Kami</h3>
    <div class="galeri-grid">
      <?php
      $query_galeri = "SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT 6";
      $hasil_galeri = $conn->query($query_galeri);

      if ($hasil_galeri && $hasil_galeri->num_rows > 0) {
        while ($row = $hasil_galeri->fetch_assoc()) {
          $id_galeri = (int)$row['id'];
          $judul = htmlspecialchars($row['judul'] ?? '');
          $gambar_galeri = htmlspecialchars($row['gambar'] ?? '');
          $tanggal = date('d M Y', strtotime($row['tanggal_upload']));
          $placeholder_galeri = 'https://via.placeholder.com/400x300/00ff88/0a1929?text=' . urlencode($judul);

          echo "
            <div class='galeri-item' onclick='openLightbox(\"{$gambar_galeri}\", \"{$judul}\")'>
              <img src='assets/img/galeri/{$gambar_galeri}' 
                   alt='{$judul}' 
                   onerror=\"this.onerror=null;this.src='{$placeholder_galeri}';\">
              <div class='galeri-overlay'>
                <h4>{$judul}</h4>
                <p><i class='far fa-calendar-alt'></i> {$tanggal}</p>
              </div>
            </div>
          ";
        }
      } else {
        echo "
          <div class='galeri-empty'>
            <i class='fas fa-camera'></i>
            <p>Belum ada foto di galeri</p>
          </div>
        ";
      }
      ?>
    </div>
  </section>

   <!-- Lightbox Modal -->
  <div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <div class="lightbox-content" onclick="event.stopPropagation()">
      <span class="lightbox-close" onclick="closeLightbox()">
        <i class="fas fa-times"></i>
      </span>
      <img id="lightbox-img" src="" alt="">
      <div class="lightbox-caption" id="lightbox-caption"></div>
    </div>
  </div>

  <!-- Call To Action -->
  <section class="cta-section">
    <h3>Siap Merawat Motor Anda?</h3>
    <p>Hubungi kami sekarang untuk konsultasi gratis atau booking servis</p>
    <div class="cta-buttons">
      <a href="kontak.php" class="btn-primary"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
      <a href="layanan.php" class="btn-secondary"><i class="fas fa-list-ul"></i> Lihat Layanan</a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Zara Jaya Motor. All rights reserved.</p>
  </footer>

  <script>
    // Smooth scroll untuk link internal
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });

    // Intersection Observer untuk animasi saat scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.card, .feature-item, .promo-box').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>