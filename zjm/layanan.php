<?php include 'config/koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Layanan - Zara Jaya Motor</title>
  
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

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, rgba(10, 25, 41, 0.95), rgba(13, 39, 56, 0.95)), 
                  url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=1200') center/cover;
      color: white;
      text-align: center;
      padding: 100px 20px 80px;
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
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

    .page-header-content {
      position: relative;
      z-index: 1;
      max-width: 800px;
      margin: 0 auto;
    }

    .page-header h2 {
      font-size: 3.2em;
      margin-bottom: 20px;
      text-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
      font-weight: 800;
    }

    .page-header h2 i {
      color: var(--primary-color);
      margin-right: 15px;
    }

    .page-header p {
      font-size: 1.3em;
      opacity: 0.9;
      line-height: 1.8;
    }

    /* Stats Section */
    .stats-section {
      background: white;
      padding: 50px 20px;
      margin-top: -40px;
      position: relative;
      z-index: 10;
    }

    .stats-container {
      max-width: 1000px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
    }

    .stat-card {
      background: linear-gradient(135deg, var(--bg-light), white);
      padding: 30px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      border: 2px solid transparent;
      transition: all 0.4s;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
      transform: scaleX(0);
      transition: transform 0.4s;
    }

    .stat-card:hover::before {
      transform: scaleX(1);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px var(--shadow);
      border-color: var(--primary-color);
    }

    .stat-icon {
      font-size: 3em;
      color: var(--primary-color);
      margin-bottom: 15px;
    }

    .stat-number {
      font-size: 2.5em;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
    }

    .stat-label {
      color: #666;
      font-weight: 600;
      font-size: 1.1em;
    }

    /* Services Section */
    .layanan-section {
      padding: 80px 20px;
      background: var(--bg-light);
    }

    .section-header {
      text-align: center;
      max-width: 700px;
      margin: 0 auto 60px;
    }

    .section-title {
      font-size: 2.8em;
      color: var(--secondary-color);
      margin-bottom: 20px;
      font-weight: 800;
      position: relative;
      display: inline-block;
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

    .section-description {
      font-size: 1.2em;
      color: #666;
      line-height: 1.8;
    }

    .grid-layanan {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 35px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card-layanan {
      background: white;
      border-radius: 25px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      text-decoration: none;
      color: inherit;
      display: block;
      position: relative;
    }

    .card-layanan::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
      transform: scaleX(0);
      transition: transform 0.4s;
      z-index: 2;
    }

    .card-layanan:hover::before {
      transform: scaleX(1);
    }

    .card-layanan:hover {
      transform: translateY(-12px);
      box-shadow: 0 15px 45px var(--shadow);
      border-color: var(--primary-color);
    }

    .card-image-wrapper {
      position: relative;
      overflow: hidden;
      height: 220px;
    }

    .card-layanan img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }

    .card-layanan:hover img {
      transform: scale(1.15);
    }

    .image-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(to bottom, transparent, rgba(10, 25, 41, 0.7));
      opacity: 0;
      transition: opacity 0.4s;
      display: flex;
      align-items: flex-end;
      padding: 20px;
    }

    .card-layanan:hover .image-overlay {
      opacity: 1;
    }

    .overlay-icon {
      color: var(--primary-color);
      font-size: 2em;
      transform: translateY(20px);
      transition: transform 0.4s;
    }

    .card-layanan:hover .overlay-icon {
      transform: translateY(0);
    }

    .card-content {
      padding: 30px;
    }

    .card-layanan h3 {
      font-size: 1.5em;
      color: var(--secondary-color);
      margin-bottom: 15px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-layanan h3::before {
      content: '\f0ad';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: var(--primary-color);
      font-size: 0.7em;
    }

    .card-layanan p {
      font-size: 1em;
      line-height: 1.7;
      color: #666;
      margin-bottom: 20px;
    }

    .card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 20px;
      border-top: 2px solid var(--bg-light);
    }

    .harga {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 12px 25px;
      border-radius: 50px;
      font-weight: 800;
      font-size: 1.2em;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 5px 15px var(--shadow);
      transition: all 0.3s;
    }

    .card-layanan:hover .harga {
      transform: scale(1.05);
      box-shadow: 0 8px 20px var(--shadow);
    }

    .harga i {
      font-size: 0.9em;
    }

    .view-detail {
      color: var(--primary-dark);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
    }

    .view-detail i {
      transition: transform 0.3s;
    }

    .card-layanan:hover .view-detail {
      color: var(--primary-color);
    }

    .card-layanan:hover .view-detail i {
      transform: translateX(5px);
    }

    /* No Services Message */
    .no-services {
      text-align: center;
      padding: 80px 20px;
    }

    .no-services i {
      font-size: 5em;
      color: var(--primary-color);
      margin-bottom: 30px;
      opacity: 0.5;
    }

    .no-services h3 {
      font-size: 2em;
      color: var(--secondary-color);
      margin-bottom: 15px;
    }

    .no-services p {
      font-size: 1.2em;
      color: #666;
    }

    /* Call to Action */
    .cta-section {
      background: linear-gradient(135deg, var(--secondary-color), #0d2738);
      padding: 80px 20px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cta-section::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
      top: -250px;
      right: -250px;
      animation: float 6s ease-in-out infinite;
    }

    .cta-section::after {
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

    .cta-content {
      position: relative;
      z-index: 1;
      max-width: 800px;
      margin: 0 auto;
    }

    .cta-section h3 {
      font-size: 2.5em;
      color: white;
      margin-bottom: 20px;
      font-weight: 800;
    }

    .cta-section p {
      font-size: 1.2em;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 35px;
      line-height: 1.8;
    }

    .cta-button {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 18px 45px;
      border-radius: 50px;
      text-decoration: none;
      font-size: 1.2em;
      font-weight: 700;
      transition: all 0.4s;
      box-shadow: 0 10px 30px var(--shadow);
      position: relative;
      overflow: hidden;
    }

    .cta-button::before {
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

    .cta-button:hover::before {
      width: 300px;
      height: 300px;
    }

    .cta-button:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px var(--shadow);
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

      .page-header h2 {
        font-size: 2em;
      }

      .page-header p {
        font-size: 1.1em;
      }

      .section-title {
        font-size: 2em;
      }

      .grid-layanan {
        grid-template-columns: 1fr;
      }

      .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
      }

      .cta-section h3 {
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

  <!-- Page Header -->
  <section class="page-header">
    <div class="page-header-content">
      <h2><i class="fas fa-cogs"></i> Layanan Kami</h2>
      <p>Kami menyediakan berbagai jenis layanan profesional untuk menjaga performa motor Anda tetap optimal dan aman di perjalanan</p>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-motorcycle"></i></div>
        <div class="stat-number">1000+</div>
        <div class="stat-label">Motor Dilayani</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-number">500+</div>
        <div class="stat-label">Pelanggan Setia</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-award"></i></div>
        <div class="stat-number">10+</div>
        <div class="stat-label">Tahun Pengalaman</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div class="stat-number">4.9</div>
        <div class="stat-label">Rating Pelanggan</div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="layanan-section">
    <div class="section-header">
      <h2 class="section-title">Daftar Layanan Bengkel</h2>
      <p class="section-description">Pilih layanan yang sesuai dengan kebutuhan motor Anda</p>
    </div>

    <?php
    $query = "SELECT * FROM layanan ORDER BY id ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<div class="grid-layanan">';

        while ($row = $result->fetch_assoc()) {
            // Sanitasi data untuk keamanan & tampilan
            $id = (int)$row['id'];
            $nama_layanan = htmlspecialchars($row['nama_layanan'] ?? '');
            $gambar = htmlspecialchars($row['gambar'] ?? 'default.jpg');
            $deskripsi = htmlspecialchars($row['deskripsi'] ?? '');
            $harga = number_format((float)$row['harga'], 0, ',', '.');
            
            // Placeholder image jika gambar tidak ditemukan
            $placeholder = 'https://via.placeholder.com/300x220/00ff88/0a1929?text=' . urlencode($nama_layanan);

            // Tampilkan setiap kartu layanan
            echo "
            <a href='detail_layanan.php?id={$id}' class='card-layanan'>
              <div class='card-image-wrapper'>
                <img src='{$gambar}' 
                     alt='{$nama_layanan}' 
                     onerror=\"this.onerror=null;this.src='{$placeholder}';\">
                <div class='image-overlay'>
                  <i class='fas fa-arrow-right overlay-icon'></i>
                </div>
              </div>
              <div class='card-content'>
                <h3>{$nama_layanan}</h3>
                <p>{$deskripsi}</p>
                <div class='card-footer'>
                  <span class='harga'><i class='fas fa-tag'></i> Rp {$harga}</span>
                  <span class='view-detail'>Detail <i class='fas fa-arrow-right'></i></span>
                </div>
              </div>
            </a>
            ";
        }

        echo '</div>'; // Tutup grid-layanan
    } else {
        // Pesan jika belum ada layanan
        echo "
        <div class='no-services'>
          <i class='fas fa-tools'></i>
          <h3>Belum Ada Layanan</h3>
          <p>Layanan sedang dalam tahap persiapan. Silakan hubungi kami untuk informasi lebih lanjut.</p>
        </div>
        ";
    }
    ?>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="cta-content">
      <h3>Butuh Konsultasi untuk Motor Anda?</h3>
      <p>Tim mekanik profesional kami siap membantu Anda menentukan layanan yang paling tepat untuk kondisi motor Anda</p>
      <a href="kontak.php" class="cta-button">
        <i class="fas fa-phone-alt"></i> Hubungi Kami Sekarang
      </a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Zara Jaya Motor. All rights reserved.</p>
  </footer>

  <script>
    // Counter animation for stats
    const animateCounter = (element, target) => {
      let count = 0;
      const increment = target / 100;
      const timer = setInterval(() => {
        count += increment;
        if (count >= target) {
          element.textContent = target + (target < 100 ? '' : '+');
          clearInterval(timer);
        } else {
          element.textContent = Math.floor(count) + (target < 100 ? '' : '+');
        }
      }, 20);
    };

    // Intersection Observer for animations
    const observerOptions = {
      threshold: 0.2,
      rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Animate stats
          if (entry.target.classList.contains('stat-card')) {
            const numberEl = entry.target.querySelector('.stat-number');
            const text = numberEl.textContent;
            const number = parseFloat(text.replace('+', ''));
            animateCounter(numberEl, number);
            observer.unobserve(entry.target);
          }
          
          // Fade in cards
          if (entry.target.classList.contains('card-layanan')) {
            entry.target.style.opacity = '0';
            entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
          }
        }
      });
    }, observerOptions);

    // Observe elements
    document.querySelectorAll('.stat-card, .card-layanan').forEach(el => {
      observer.observe(el);
    });

    // Add fadeInUp animation
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>