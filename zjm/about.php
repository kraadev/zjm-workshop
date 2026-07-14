<?php
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Kami - Zara Jaya Motor</title>
  
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

    /* About Content */
    .about-content {
      padding: 80px 20px;
      background: white;
    }

    .about-container {
      max-width: 1200px;
      margin: 0 auto;
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

    .about-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      margin-bottom: 60px;
    }

    .about-card {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .about-card::before {
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

    .about-card:hover::before {
      transform: scaleX(1);
    }

    .about-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
      border-color: var(--primary-color);
    }

    .about-card h3 {
      color: var(--secondary-color);
      font-size: 1.8em;
      margin-bottom: 20px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .about-card h3 i {
      color: var(--primary-color);
    }

    .about-card p {
      margin-bottom: 15px;
      color: #666;
      line-height: 1.8;
    }

    .about-card ul {
      padding-left: 25px;
      margin: 15px 0;
    }

    .about-card li {
      margin-bottom: 10px;
      color: #666;
    }

    /* Vision Mission */
    .vision-mission {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      margin-bottom: 60px;
    }

    .vision-card, .mission-card {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .vision-card::before, .mission-card::before {
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

    .vision-card:hover::before, .mission-card:hover::before {
      transform: scaleX(1);
    }

    .vision-card:hover, .mission-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
      border-color: var(--primary-color);
    }

    .vision-card h3, .mission-card h3 {
      color: var(--secondary-color);
      font-size: 1.8em;
      margin-bottom: 20px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .vision-card h3 i {
      color: var(--primary-color);
    }

    .mission-card h3 i {
      color: var(--primary-color);
    }

    .vision-card p, .mission-card p {
      margin-bottom: 15px;
      color: #666;
      line-height: 1.8;
    }

    /* Advantages Section */
    .advantages-section {
      padding: 80px 20px;
      background: linear-gradient(180deg, var(--bg-light), white);
    }

    .advantages-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .advantage-item {
      background: white;
      padding: 35px 25px;
      border-radius: 20px;
      text-align: center;
      transition: all 0.4s;
      border: 2px solid transparent;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .advantage-item:hover {
      transform: translateY(-10px);
      border-color: var(--primary-color);
      box-shadow: 0 15px 40px var(--shadow);
    }

    .advantage-icon {
      font-size: 3em;
      color: var(--primary-color);
      margin-bottom: 20px;
      transition: all 0.4s;
    }

    .advantage-item:hover .advantage-icon {
      transform: scale(1.2) rotateY(360deg);
    }

    .advantage-item h4 {
      color: var(--secondary-color);
      margin-bottom: 15px;
      font-size: 1.4em;
      font-weight: 700;
    }

    .advantage-item p {
      font-size: 0.95em;
      color: #666;
      line-height: 1.6;
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

      .hero h2 {
        font-size: 2.2em;
      }

      .section-title {
        font-size: 2em;
      }

      .about-grid, .vision-mission {
        grid-template-columns: 1fr;
      }

      .advantages-grid {
        grid-template-columns: 1fr;
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
          <a href="about.php" class="active"><i class="fas fa-info-circle"></i> Tentang Kami</a>
          <a href="kontak.php"><i class="fas fa-phone"></i> Kontak</a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Hero Banner -->
  <section class="hero">
    <div class="hero-content">
      <h2><span>Tentang</span> Zara Jaya Motor</h2>
      <p>Lebih dari sekadar bengkel, kami adalah mitra perawatan motor Anda</p>
    </div>
  </section>

<!-- About Content -->
<section class="about-content">
  <div class="about-container">
    <h2 class="section-title">Profil Usaha</h2>
    <div class="about-grid">
      <?php
      // Ambil data untuk bagian Sejarah dan Lokasi
      $query = "SELECT * FROM profil_usaha WHERE kategori IN ('sejarah', 'lokasi')";
      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()) {
        echo '
        <div class="about-card">
          <h3><i class="fas ' . $row['ikon'] . '"></i> ' . $row['judul'] . '</h3>
          <p>' . nl2br($row['isi']) . '</p>
        </div>';
      }
      ?>
    </div>

    <h2 class="section-title">Visi & Misi</h2>
    <div class="vision-mission">
      <?php
      // Ambil data Visi
      $visi = $conn->query("SELECT * FROM profil_usaha WHERE kategori = 'visi'")->fetch_assoc();
      echo '
      <div class="vision-card">
        <h3><i class="fas ' . $visi['ikon'] . '"></i> ' . $visi['judul'] . '</h3>
        <p>' . nl2br($visi['isi']) . '</p>
      </div>';

      // Ambil data Misi
      $misi = $conn->query("SELECT * FROM profil_usaha WHERE kategori = 'misi'")->fetch_assoc();

      // Pisahkan isi misi jadi list
      $misi_list = explode(';', $misi['isi']);
      echo '
      <div class="mission-card">
        <h3><i class="fas ' . $misi['ikon'] . '"></i> ' . $misi['judul'] . '</h3>
        <ul>';
        foreach ($misi_list as $m) {
          echo '<li>' . trim($m) . '</li>';
        }
        echo '</ul>
      </div>';
      ?>
    </div>
  </div>
</section>

  <!-- Advantages Section -->
  <section class="advantages-section">
    <h2 class="section-title">Keunggulan Kami</h2>
    <div class="advantages-grid">
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-user-tie"></i></div>
        <h4>Teknisi Bersertifikat</h4>
        <p>Semua teknisi kami telah melalui pelatihan intensif dan memiliki sertifikasi resmi dari produsen motor ternama.</p>
      </div>
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-tools"></i></div>
        <h4>Peralatan Modern</h4>
        <p>Kami menggunakan peralatan dan mesin diagnosa terkini yang memungkinkan deteksi masalah secara akurat dan efisien.</p>
      </div>
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-shield-alt"></i></div>
        <h4>Garansi Jasa</h4>
        <p>Setiap pekerjaan servis dilengkapi dengan garansi jasa hingga 30 hari untuk memberikan ketenangan pikiran kepada pelanggan.</p>
      </div>
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-tag"></i></div>
        <h4>Harga Transparan</h4>
        <p>Kami menerapkan sistem harga transparan tanpa biaya tersembunyi, dengan rincian biaya yang jelas sebelum pekerjaan dimulai.</p>
      </div>
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-clock"></i></div>
        <h4>Waktu Pengerjaan Cepat</h4>
        <p>Dengan sistem kerja yang efisien, kami mampu menyelesaikan servis rutin dalam waktu 1-2 jam tanpa mengurangi kualitas.</p>
      </div>
      <div class="advantage-item">
        <div class="advantage-icon"><i class="fas fa-star"></i></div>
        <h4>Sparepart Original</h4>
        <p>Kami hanya menggunakan sparepart original atau berkualitas OEM untuk menjamin performa dan keamanan kendaraan Anda.</p>
      </div>
    </div>
  </section>

  <!-- Call To Action -->
  <section class="cta-section">
    <h3>Percayakan Motor Anda Kepada Kami</h3>
    <p>Kami siap memberikan layanan terbaik untuk menjaga performa motor Anda</p>
    <a href="kontak.php" class="cta-button"><i class="fas fa-phone-alt"></i> Hubungi Kami Sekarang</a>
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

    document.querySelectorAll('.about-card, .vision-card, .mission-card, .advantage-item').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>