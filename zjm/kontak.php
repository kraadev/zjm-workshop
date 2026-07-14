<?php include 'config/koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami - Zara Jaya Motor</title>
  
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
                  url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1200') center/cover;
      color: white;
      text-align: center;
      padding: 80px 20px 60px;
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

    .page-header h2 {
      font-size: 3em;
      margin-bottom: 15px;
      text-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
      font-weight: 800;
      position: relative;
      z-index: 1;
    }

    .page-header h2 i {
      color: var(--primary-color);
    }

    .page-header p {
      font-size: 1.2em;
      position: relative;
      z-index: 1;
      opacity: 0.9;
    }

    /* Contact Section */
    .contact-section {
      padding: 60px 20px;
    }

    .contact-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }

    /* Contact Info Cards */
    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .info-card {
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .info-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
      background: linear-gradient(180deg, var(--primary-color), var(--primary-dark));
      transform: scaleY(0);
      transition: transform 0.4s;
    }

    .info-card:hover::before {
      transform: scaleY(1);
    }

    .info-card:hover {
      transform: translateX(10px);
      box-shadow: 0 10px 30px var(--shadow);
      border-color: var(--primary-color);
    }

    .info-card-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 15px;
    }

    .info-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8em;
      color: var(--secondary-color);
      box-shadow: 0 5px 15px var(--shadow);
    }

    .info-card h3 {
      color: var(--secondary-color);
      font-size: 1.3em;
      font-weight: 700;
    }

    .info-card p {
      color: #555;
      line-height: 1.8;
      margin: 5px 0;
    }

    .info-card a {
      color: var(--primary-dark);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }

    .info-card a:hover {
      color: var(--primary-color);
    }

    .whatsapp-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(135deg, #25d366, #128c7e);
      color: white;
      padding: 12px 25px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 700;
      margin-top: 10px;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
    }

    .whatsapp-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
    }

    /* Contact Form */
    .contact-form-wrapper {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      border: 2px solid transparent;
      transition: all 0.4s;
      position: relative;
      overflow: hidden;
    }

    .contact-form-wrapper::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    }

    .contact-form-wrapper h3 {
      color: var(--secondary-color);
      font-size: 1.8em;
      margin-bottom: 25px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .contact-form-wrapper h3 i {
      color: var(--primary-color);
    }

    .form-kontak {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label {
      color: var(--secondary-color);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group label i {
      color: var(--primary-color);
      font-size: 0.9em;
    }

    .form-kontak input,
    .form-kontak textarea {
      padding: 15px 18px;
      border-radius: 12px;
      border: 2px solid #e0e0e0;
      font-size: 1em;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s;
      background: var(--bg-light);
    }

    .form-kontak input:focus,
    .form-kontak textarea:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
      background: white;
    }

    .form-kontak textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-kontak button {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      border: none;
      padding: 16px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 1.1em;
      font-weight: 700;
      transition: all 0.4s;
      box-shadow: 0 5px 15px var(--shadow);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
    }

    .form-kontak button::before {
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

    .form-kontak button:hover::before {
      width: 300px;
      height: 300px;
    }

    .form-kontak button:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px var(--shadow);
    }

    /* Notification Messages */
    .sukses,
    .error {
      padding: 18px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 600;
      animation: slideDown 0.5s ease-out;
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

    .sukses {
      background: linear-gradient(135deg, #d4edda, #c3e6cb);
      color: #155724;
      border-left: 5px solid #28a745;
    }

    .error {
      background: linear-gradient(135deg, #f8d7da, #f5c6cb);
      color: #721c24;
      border-left: 5px solid #dc3545;
    }

    .sukses i,
    .error i {
      font-size: 1.5em;
    }

    /* Map Section */
    .map-section {
      padding: 60px 20px;
      background: white;
    }

    .map-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .map-container h3 {
      text-align: center;
      font-size: 2.5em;
      color: var(--secondary-color);
      margin-bottom: 40px;
      font-weight: 800;
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .map-container h3::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }

    .map-container h3 i {
      color: var(--primary-color);
      margin-right: 10px;
    }

    .map-wrapper {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      border: 5px solid white;
      position: relative;
    }

    .map-wrapper::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border: 3px solid var(--primary-color);
      border-radius: 15px;
      pointer-events: none;
      z-index: 1;
    }

    .map-wrapper iframe {
      width: 100%;
      height: 450px;
      border: 0;
      display: block;
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
      .contact-wrapper {
        grid-template-columns: 1fr;
      }

      .contact-form-wrapper {
        order: -1;
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

      .page-header h2 {
        font-size: 2em;
      }

      .contact-form-wrapper,
      .info-card {
        padding: 25px;
      }

      .map-wrapper iframe {
        height: 300px;
      }

      .map-container h3 {
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
          <a href="kontak.php" class="active"><i class="fas fa-phone"></i> Kontak</a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Page Header -->
  <section class="page-header">
    <h2><i class="fas fa-phone-volume"></i> Hubungi Kami</h2>
    <p>Kami siap membantu Anda dengan layanan terbaik</p>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="contact-wrapper">
      <!-- Contact Form -->
      <div class="contact-form-wrapper">
        <h3><i class="fas fa-envelope"></i> Kirim Pesan</h3>
        <p style="color: #666; margin-bottom: 25px;">Jika Anda memiliki pertanyaan tentang layanan atau produk, kirimkan pesan melalui formulir di bawah ini.</p>

        <?php
          if (isset($_POST['kirim'])) {
            $nama = htmlspecialchars($_POST['nama']);
            $email = htmlspecialchars($_POST['email']);
            $pesan = htmlspecialchars($_POST['pesan']);

            $stmt = $conn->prepare("INSERT INTO pesan_pengunjung (nama, email, pesan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $pesan);

            if ($stmt->execute()) {
              echo "<div class='sukses'>
                      <i class='fas fa-check-circle'></i>
                      <span>Pesan Anda telah terkirim! Kami akan segera menghubungi Anda.</span>
                    </div>";
            } else {
              echo "<div class='error'>
                      <i class='fas fa-exclamation-circle'></i>
                      <span>Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.</span>
                    </div>";
            }
          }
        ?>

        <form method="POST" class="form-kontak">
          <div class="form-group">
            <label><i class="fas fa-user"></i> Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama lengkap Anda" required>
          </div>

          <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" name="email" placeholder="example@email.com" required>
          </div>

          <div class="form-group">
            <label><i class="fas fa-comment-dots"></i> Pesan</label>
            <textarea name="pesan" placeholder="Tulis pesan Anda di sini..." required></textarea>
          </div>

          <button type="submit" name="kirim">
            <i class="fas fa-paper-plane"></i> Kirim Pesan
          </button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="contact-info">
        <?php
        $query = "SELECT * FROM kontak_info ORDER BY id ASC";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '
            <div class="info-card">
              <div class="info-card-header">
                <div class="info-icon">
                  <i class="fas ' . $row['ikon'] . '"></i>
                </div>
                <h3>' . $row['judul'] . '</h3>
              </div>
              <p>' . $row['isi'] . '</p>';
            
            // Jika ada link tambahan (misalnya WhatsApp)
            if (!empty($row['link'])) {
              echo '<a href="' . $row['link'] . '" target="_blank" class="whatsapp-btn">
                      <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                    </a>';
            }

            echo '</div>';
          }
        } else {
          echo '<p><em>Data kontak belum tersedia.</em></p>';
        }
        ?>
      </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <div class="map-container">
      <h3><i class="fas fa-map-marked-alt"></i> Temukan di Peta </h3>
      <div class="map-wrapper">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0073120325094!2d107.0003982!3d-6.2493656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698d66197d35e1%3A0x73baaae2a97066ff!2sZara%20Jaya%20Motor!5e0!3m2!1sid!2sid!4v1733960000000!5m2!1sid!2sid"
          allowfullscreen="" 
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Zara Jaya Motor. All rights reserved.</p>
  </footer>

  <script>
    // Form validation animation
    const form = document.querySelector('.form-kontak');
    const inputs = form.querySelectorAll('input, textarea');

    inputs.forEach(input => {
      input.addEventListener('invalid', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dc3545';
        setTimeout(() => {
          this.style.borderColor = '#e0e0e0';
        }, 2000);
      });

      input.addEventListener('input', function() {
        if (this.validity.valid) {
          this.style.borderColor = 'var(--primary-color)';
        }
      });
    });

    // Smooth scroll for internal links
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
          entry.target.style.animation = 'slideDown 0.6s ease-out forwards';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.info-card, .contact-form-wrapper').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>