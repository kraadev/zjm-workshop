-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jul 2026 pada 16.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `nama_lengkap`, `dibuat_pada`) VALUES
(1, 'admin', 'admin@zarajaya.com', 'ZJMadmin123', 'Administrator', '2025-10-22 14:56:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak_info`
--

CREATE TABLE `kontak_info` (
  `id` int(11) NOT NULL,
  `kategori` enum('alamat','telepon','email','jam') NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `ikon` varchar(50) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontak_info`
--

INSERT INTO `kontak_info` (`id`, `kategori`, `judul`, `ikon`, `isi`, `link`) VALUES
(1, 'alamat', 'Alamat', 'fa-map-marker-alt', '<strong>Zara Jaya Motor</strong><br>\r\nJl. Mayor Madmuin Hasibuan No.12 A, RT.003/RW.024, Margahayu Timur<br>\r\nKota Bekasi, Jawa Barat, 17113', NULL),
(2, 'telepon', 'Telepon & WhatsApp', 'fa-phone-alt', '<i class=\"fas fa-phone\"></i> <a href=\"tel:081289035251\">081289035251</a><br>\r\n<i class=\"fas fa-mobile-alt\"></i> <a href=\"tel:081289035251\">081289035251</a>', 'https://wa.me/6281289035251?text=Halo%20Zara%20Jaya%20Motor%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20Anda'),
(3, 'email', 'Email', 'fa-envelope', '<i class=\"fas fa-at\"></i> <a href=\"mailto:zarajayamotor@gmail.com\">zarajayamotor@gmail.com</a><br>\r\n<i class=\"fas fa-clock\"></i> Kami akan membalas dalam 1x24 jam', NULL),
(4, 'jam', 'Jam Operasional', 'fa-clock', '<strong>Senin - Kamis:</strong> 09:00 - 20:30 WIB<br>\r\n<strong>Sabtu - Minggu:</strong> 09:00 - 21:00 WIB<br>\r\n<strong>Jum\'at & Hari Libur:</strong> Tutup', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `detail_pelayanan` text DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `deskripsi`, `detail_pelayanan`, `harga`, `gambar`) VALUES
(1, 'Ganti Oli', 'Penggantian oli mesin dengan oli pilihan berkualitas tinggi.', 'Ganti oli mesin & bersihkan filter\r\n\r\nCek tekanan ban dan rem\r\n\r\nPeriksa rantai & kelistrikan\r\n\r\nCek busi dan air radiator\r\n\r\nPembersihan area mesin\r\n\r\nReset indikator oli\r\n\r\nCatatan servis & pengingat berikutnya\r\n\r\nPelayanan cepat dan ramah', 50000, 'image/68f8f677b835a.jpg'),
(2, 'Servis Ringan', 'Pemeriksaan umum, penyetelan, dan pembersihan komponen utama.', 'Cek dan setel karburator / injeksi\r\n\r\nCek busi dan ganti jika diperlukan\r\n\r\nBersihkan filter udara\r\n\r\nPeriksa dan setel rantai\r\n\r\nCek tekanan & kondisi ban\r\n\r\nPeriksa kampas rem depan & belakang\r\n\r\nCek oli mesin dan sistem pelumasan\r\n\r\nPeriksa aki & sistem kelistrikan\r\n\r\nKencangkan baut dan mur penting\r\n\r\nUji performa mesin setelah servis', 75000, 'image/servis.jpg'),
(3, 'Tune Up Lengkap', 'Servis lengkap termasuk penyetelan karburator, busi, dan saringan udara.', 'Pemeriksaan dan penyetelan karburator / injektor\r\n\r\nPembersihan ruang bakar & throttle body\r\n\r\nCek dan ganti busi jika diperlukan\r\n\r\nPembersihan filter udara\r\n\r\nCek tekanan dan kondisi ban\r\n\r\nPeriksa serta setel klep (katup)\r\n\r\nCek rantai & pelumasan ulang\r\n\r\nPemeriksaan sistem kelistrikan dan aki\r\n\r\nCek kampas rem depan & belakang\r\n\r\nGanti oli mesin bila diperlukan', 150000, 'image/tuneup.jpg'),
(4, 'Spooring Motor', 'Penyetelan keseimbangan roda dan suspensi untuk kenyamanan berkendara.', 'Pemeriksaan keseimbangan roda depan & belakang\r\n\r\nPenyetelan sudut kemudi (caster, camber, toe)\r\n\r\nPengecekan dan penyetelan komstir (steering head)\r\n\r\nPemeriksaan kondisi shock absorber dan suspensi\r\n\r\nCek tekanan angin & kondisi ban\r\n\r\nPengecekan velg dan kesejajaran rangka\r\n\r\nPengetesan stabilitas motor setelah penyetelan\r\n\r\nPembersihan area roda & pelumas ulang bagian terkait', 150000, 'image/spooring.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_pengunjung`
--

CREATE TABLE `pesan_pengunjung` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `gambar` varchar(200) DEFAULT NULL,
  `link_pemesanan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `merek`, `kategori`, `deskripsi`, `harga`, `stok`, `gambar`, `link_pemesanan`) VALUES
(1, 'Oli Federal Matic 1L', 'Federal Oil', 'Oli', 'Oli mesin khusus motor matic 1 liter, menjaga performa mesin tetap halus.', 48000.00, 25, 'image/federal.jpeg', 'https://color-detection-lyart.vercel.app/'),
(2, 'Oli Yamalube 1L', 'Yamaha', 'Oli', 'Oli resmi Yamaha Yamalube 1 liter untuk motor bebek dan matic.', 52000.00, 20, 'image/yamalube.jpeg', 'https://shopee.co.id/'),
(3, 'Kampas Rem Depan', 'Nissin', 'Kampas Rem', 'Kampas rem depan Nissin, cocok untuk motor bebek dan matic.', 75000.00, 15, 'image/68fb919846b73.jpeg', 'https://shopee.co.id/'),
(4, 'Busi NGK CR7', 'NGK', 'Busi', 'Busi motor tipe CR7 dari NGK, performa tinggi dan tahan lama.', 35000.00, 40, 'image/68fb951c9bb9e.png', 'https://shopee.co.id/');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_usaha`
--

CREATE TABLE `profil_usaha` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `ikon` varchar(50) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `kategori` enum('sejarah','lokasi','visi','misi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profil_usaha`
--

INSERT INTO `profil_usaha` (`id`, `judul`, `ikon`, `isi`, `kategori`) VALUES
(1, 'Sejarah Singkat', 'fa-history', 'Zara Jaya Motor didirikan pada tahun 2013 oleh Bapak Reynaldi, seorang mekanik berpengalaman selama 15 tahun di industri otomotif roda dua. Berawal dari sebuah bengkel kecil di pinggir jalan, kini Zara Jaya Motor telah berkembang menjadi bengkel bersertifikat dan melayani lebih dari 500 pelanggan setiap bulannya. Komitmen kami terhadap kualitas layanan dan kepuasan pelanggan telah membuat kami menjadi salah satu bengkel motor terpercaya di wilayah Bekasi selama lebih dari 10 tahun.', 'sejarah'),
(2, 'Lokasi Strategis', 'fa-map-marker-alt', 'Berlokasi di pusat kota Bekasi, tepatnya di Jl. Mayor Madmuin Hasibuan No.12 A, kami mudah diakses dari berbagai penjuru kota. Fasilitas bengkel kami dilengkapi dengan area parkir, ruang tunggu yang nyaman, serta sistem antrian dan proses pembayaran yang transparan dengan mengutamakan komunikasi dengan pelanggan. Kami buka setiap hari Senin hingga Minggu, pukul 08.00 - 20.00 WIB, siap melayani kebutuhan perawatan motor Anda kapan saja.', 'lokasi'),
(3, 'Visi', 'fa-eye', 'Menjadi bengkel motor terdepan di Indonesia yang memberikan layanan terbaik dengan teknologi modern dan SDM profesional, sehingga menjadi pilihan utama masyarakat dalam merawat kendaraan roda dua mereka.', 'visi'),
(4, 'Misi', 'fa-bullseye', 'Memberikan layanan perawatan dan perbaikan motor berkualitas tinggi dengan harga terjangkau; Menggunakan peralatan dan teknologi terkini untuk menjamin hasil kerja yang optimal; Mengembangkan kompetensi teknisi secara berkelanjutan melalui pelatihan dan sertifikasi; Membangun hubungan jangka panjang dengan pelanggan berdasarkan kepercayaan dan kepuasan; Berkontribusi positif terhadap lingkungan dengan menerapkan praktik ramah lingkungan dalam operasional bengkel.', 'misi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak_info`
--
ALTER TABLE `kontak_info`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan_pengunjung`
--
ALTER TABLE `pesan_pengunjung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil_usaha`
--
ALTER TABLE `profil_usaha`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kontak_info`
--
ALTER TABLE `kontak_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pesan_pengunjung`
--
ALTER TABLE `pesan_pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `profil_usaha`
--
ALTER TABLE `profil_usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
