<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Sparepart - Zara Jaya Motor</title>
  
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

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, rgba(10, 25, 41, 0.95), rgba(13, 39, 56, 0.95)), 
                  url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=1200') center/cover;
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

    /* Filter Section */
    .filter {
      text-align: center;
      padding: 40px 20px;
      background: white;
    }

    .filter h3 {
      font-size: 1.5em;
      color: var(--secondary-color);
      margin-bottom: 20px;
      font-weight: 700;
    }

    .filter h3 i {
      color: var(--primary-color);
      margin-right: 10px;
    }

    .filter-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .filter .btn {
      display: inline-block;
      padding: 12px 30px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      border-radius: 50px;
      text-decoration: none;
      font-weight: 700;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(0, 255, 136, 0.2);
      position: relative;
      overflow: hidden;
      border: none;
      cursor: pointer;
    }

    .filter .btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.4s, height 0.4s;
    }

    .filter .btn:hover::before {
      width: 300px;
      height: 300px;
    }

    .filter .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px var(--shadow);
    }

    .filter .btn i {
      margin-right: 8px;
    }

    /* Dropdown Styles */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background: white;
      min-width: 200px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      border-radius: 15px;
      z-index: 1000;
      overflow: hidden;
      margin-top: 10px;
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .dropdown-content a {
      color: var(--secondary-color);
      padding: 12px 20px;
      text-decoration: none;
      display: block;
      transition: all 0.3s;
      font-weight: 600;
    }

    .dropdown-content a:hover {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      color: var(--secondary-color);
      padding-left: 30px;
    }

    .dropdown-content a i {
      margin-right: 10px;
      color: var(--primary-color);
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown .btn i.fa-chevron-down {
      margin-left: 5px;
      transition: transform 0.3s;
    }

    .dropdown:hover .btn i.fa-chevron-down {
      transform: rotate(180deg);
    }

    /* Stock Table */
    .stock-section {
      padding: 40px 20px;
      background: linear-gradient(180deg, white, var(--bg-light));
    }

    .stock-section h3 {
      text-align: center;
      font-size: 2em;
      color: var(--secondary-color);
      margin-bottom: 30px;
      font-weight: 800;
    }

    .stock-section h3 i {
      color: var(--primary-color);
      margin-right: 10px;
    }

    .tabel-stok {
      margin: 0 auto;
      border-collapse: collapse;
      width: 90%;
      max-width: 800px;
      background: white;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
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

    .tabel-stok th, .tabel-stok td {
      padding: 18px 20px;
      text-align: center;
    }

    .tabel-stok th {
      background: linear-gradient(135deg, var(--secondary-color), #0d2738);
      color: white;
      font-weight: 700;
      font-size: 1.1em;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .tabel-stok tr {
      transition: all 0.3s;
    }

    .tabel-stok tr:nth-child(even) {
      background-color: var(--bg-light);
    }

    .tabel-stok tr:hover {
      background: rgba(0, 255, 136, 0.1);
      transform: scale(1.02);
    }

    .tabel-stok td {
      color: #555;
      font-weight: 500;
    }

    .tabel-stok td:last-child {
      color: var(--primary-dark);
      font-weight: 700;
      font-size: 1.1em;
    }

    /* Product Section */
    .produk {
      padding: 60px 20px;
      background: var(--bg-light);
    }

    .produk .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .produk .card {
      background: white;
      border-radius: 20px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      border: 2px solid transparent;
      position: relative;
    }

    .produk .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
      transform: scaleX(0);
      transition: transform 0.4s;
      z-index: 1;
    }

    .produk .card:hover::before {
      transform: scaleX(1);
    }

    .produk .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px var(--shadow);
      border-color: var(--primary-color);
    }

    .produk .card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: transform 0.4s;
    }

    .produk .card:hover img {
      transform: scale(1.1);
    }

    .card-content {
      padding: 25px;
    }

    .produk .card h3 {
      margin: 0 0 15px 0;
      font-size: 1.3em;
      font-weight: 700;
    }

    .produk .card h3 a {
      color: var(--secondary-color);
      text-decoration: none;
      transition: color 0.3s;
    }

    .produk .card h3 a:hover {
      color: var(--primary-dark);
    }

    .produk .card p {
      margin: 10px 0;
      color: #666;
      font-size: 0.95em;
    }

    .produk .card .badge {
      display: inline-block;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--secondary-color);
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: 700;
      margin: 5px 5px 5px 0;
    }

    .produk .card .price {
      font-size: 1.4em;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 15px 0 10px 0;
    }

    .produk .card .stock {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #555;
      font-weight: 600;
    }

    .produk .card .stock i {
      color: var(--primary-color);
    }

    .no-products {
      text-align: center;
      padding: 60px 20px;
      font-size: 1.2em;
      color: #666;
    }

    .no-products i {
      font-size: 4em;
      color: var(--primary-color);
      margin-bottom: 20px;
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

      .filter-buttons {
        flex-direction: column;
        align-items: center;
      }

      .filter .btn {
        width: 100%;
        max-width: 250px;
      }

      .dropdown-content {
        position: relative;
        box-shadow: none;
        margin-top: 5px;
      }

      .tabel-stok {
        width: 100%;
      }

      .tabel-stok th, .tabel-stok td {
        padding: 12px 10px;
        font-size: 0.9em;
      }

      .produk .grid {
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
          <a href="about.php"><i class="fas fa-info-circle"></i> Tentang Kami</a>
          <a href="kontak.php"><i class="fas fa-phone"></i> Kontak</a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Page Header -->
  <section class="page-header">
    <h2><i class="fas fa-box-open"></i> Produk Sparepart & Oli</h2>
    <p>Sparepart berkualitas untuk performa motor terbaik Anda</p>
  </section>

  <!-- Filter Kategori -->
  <section class="filter">
    <h3><i class="fas fa-filter"></i> Filter Kategori</h3>
    <div class="filter-buttons">
      <a href="produk.php" class="btn"><i class="fas fa-th"></i> Semua Produk</a>
      <a href="produk.php?kategori=Oli" class="btn"><i class="fas fa-oil-can"></i> Oli</a>
      <a href="produk.php?kategori=Kampas Rem" class="btn"><i class="fas fa-compact-disc"></i> Kampas Rem</a>
      <a href="produk.php?kategori=Busi" class="btn"><i class="fas fa-bolt"></i> Busi</a>
      
      <!-- Dropdown Kategori Lainnya -->
      <div class="dropdown">
        <button class="btn">
          <i class="fas fa-ellipsis-h"></i> Lainnya
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-content">
          <?php
            // Ambil semua kategori yang tidak termasuk dalam tombol utama
            $mainCategories = ['Oli', 'Kampas Rem', 'Busi'];
            $placeholders = "'" . implode("','", $mainCategories) . "'";
            
            $otherCategoriesQuery = $conn->query("
              SELECT DISTINCT kategori 
              FROM produk 
              WHERE kategori NOT IN ($placeholders) 
              ORDER BY kategori ASC
            ");
            
            if ($otherCategoriesQuery->num_rows > 0) {
              while ($cat = $otherCategoriesQuery->fetch_assoc()) {
                // Tentukan icon berdasarkan kategori
                $icon = 'fa-box';
                $kategoriLower = strtolower($cat['kategori']);
                
                if (strpos($kategoriLower, 'ban') !== false) $icon = 'fa-circle';
                elseif (strpos($kategoriLower, 'rantai') !== false) $icon = 'fa-link';
                elseif (strpos($kategoriLower, 'lampu') !== false) $icon = 'fa-lightbulb';
                elseif (strpos($kategoriLower, 'filter') !== false) $icon = 'fa-filter';
                elseif (strpos($kategoriLower, 'aki') !== false) $icon = 'fa-battery-full';
                elseif (strpos($kategoriLower, 'spion') !== false) $icon = 'fa-eye';
                
                echo "<a href='produk.php?kategori=" . urlencode($cat['kategori']) . "'>
                        <i class='fas {$icon}'></i> {$cat['kategori']}
                      </a>";
              }
            } else {
              echo "<a href='#' style='pointer-events: none; opacity: 0.6;'>
                      <i class='fas fa-info-circle'></i> Tidak ada kategori lain
                    </a>";
            }
          ?>
        </div>
      </div>
    </div>
  </section>

  <?php
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

    // Tampilkan tabel stok untuk kategori Oli
    if ($kategori == 'Oli') {
      echo "<section class='stock-section'>
              <h3><i class='fas fa-chart-bar'></i> Stok Oli per Merek</h3>
              <table class='tabel-stok'>
                <tr>
                  <th>Merek</th>
                  <th>Total Stok</th>
                </tr>";

      $stokQuery = $conn->query("SELECT merek, SUM(stok) AS total_stok FROM produk WHERE kategori='Oli' GROUP BY merek");
      while ($stok = $stokQuery->fetch_assoc()) {
        echo "<tr>
                <td><i class='fas fa-tag'></i> {$stok['merek']}</td>
                <td>{$stok['total_stok']} botol</td>
              </tr>";
      }
      echo "</table></section>";
    }
  ?>

  <!-- Produk Section -->
  <section class="produk">
    <div class="grid">
      <?php
        if ($kategori != '') {
          $stmt = $conn->prepare("SELECT * FROM produk WHERE kategori = ? ORDER BY id DESC");
          $stmt->bind_param("s", $kategori);
          $stmt->execute();
          $hasil = $stmt->get_result();
        } else {
          $hasil = $conn->query("SELECT * FROM produk ORDER BY id DESC");
        }

        if ($hasil->num_rows > 0) {
          while ($row = $hasil->fetch_assoc()) {
            // Tentukan path gambar
            $imagePath = '';
            if (!empty($row['gambar'])) {
              // Cek apakah path sudah lengkap atau hanya nama file
              if (strpos($row['gambar'], '/') !== false || strpos($row['gambar'], '\\') !== false) {
                // Jika sudah ada path (seperti 'image/kampas.jpeg' atau 'assets/img/oli.jpg')
                $imagePath = $row['gambar'];
              } else {
                // Jika hanya nama file, tambahkan path default
                $imagePath = 'assets/img/' . $row['gambar'];
              }
            }
            
            echo "
              <div class='card'>
                <img src='{$imagePath}' alt='{$row['nama']}' onerror=\"this.src='https://via.placeholder.com/280x220/00ff88/0a1929?text=No+Image'\">
                <div class='card-content'>
                  <h3><a href='detail_produk.php?id={$row['id']}'>{$row['nama']}</a></h3>
                  <div>
                    <span class='badge'><i class='fas fa-layer-group'></i> {$row['kategori']}</span>
                    <span class='badge'><i class='fas fa-copyright'></i> {$row['merek']}</span>
                  </div>
                  <p>{$row['deskripsi']}</p>
                  <p class='price'>Rp " . number_format($row['harga'], 0, ',', '.') . "</p>
                  <p class='stock'><i class='fas fa-boxes'></i> Stok: {$row['stok']} unit</p>
                </div>
              </div>
            ";
          }
        } else {
          echo "<div class='no-products'>
                  <i class='fas fa-box-open'></i>
                  <p>Tidak ada produk dalam kategori ini.</p>
                </div>";
        }
      ?>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Zara Jaya Motor. All rights reserved.</p>
  </footer>

  <script>
    // Intersection Observer untuk animasi saat scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '0';
          entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.card').forEach(el => {
      observer.observe(el);
    });

    // Highlight active filter
    const urlParams = new URLSearchParams(window.location.search);
    const activeKategori = urlParams.get('kategori');
    
    document.querySelectorAll('.filter .btn').forEach(btn => {
      const btnHref = btn.getAttribute('href');
      if (btnHref && ((activeKategori && btnHref.includes(activeKategori)) || 
          (!activeKategori && btnHref === 'produk.php'))) {
        btn.style.background = 'linear-gradient(135deg, var(--primary-light), var(--primary-color))';
        btn.style.boxShadow = '0 8px 25px var(--shadow)';
      }
    });

    // Highlight dropdown items
    if (activeKategori) {
      document.querySelectorAll('.dropdown-content a').forEach(link => {
        if (link.getAttribute('href').includes(activeKategori)) {
          link.style.background = 'linear-gradient(135deg, var(--primary-color), var(--primary-light))';
        }
      });
    }
  </script>
</body>
</html>