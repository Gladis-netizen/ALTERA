
<?php
session_start();

$namaPengguna = $_SESSION['nama'] ?? null;

require_once "config/database.php";

$database = new Database();
$db = $database->getConnection();

// Ambil produk aktif dari database
$query = "SELECT * FROM produk
          WHERE status = 'aktif'
          ORDER BY id ASC";

$stmt = $db->prepare($query);
$stmt->execute();

$dataProduk = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sesuaikan data database dengan dashboard
$produk = [];

foreach ($dataProduk as $row) {
    $produk[] = [
        "atelier" => $row["nama_atelier"],
        "nama" => $row["nama_produk"],
        "lokasi" => $row["lokasi"],
        "harga" => number_format(
            (float) $row["harga"],
            0,
            ",",
            "."
        ),
        "gambar" => $row["gambar"],
        "label" => $row["kategori"]
    ];
}

// Data Koleksi Populer
$koleksi = [];

if (count($produk) >= 8) {
    $koleksi = [
        [
            "nama" => "Gaun Pesta Malam",
            "atelier" => "Atelier Sari",
            "harga" => "285.000",
            "rating" => "4.8",
            "terjual" => "128",
            "gambar" => $produk[0]["gambar"]
        ],
        [
            "nama" => "Jas Formal Navy Set",
            "atelier" => "Royal Tailor",
            "harga" => "520.000",
            "rating" => "4.9",
            "terjual" => "67",
            "gambar" => $produk[7]["gambar"]
        ],
        [
            "nama" => "Batik Tulis Premium",
            "atelier" => "Studio Mode",
            "harga" => "168.000",
            "rating" => "4.7",
            "terjual" => "89",
            "gambar" => $produk[4]["gambar"]
        ],
        [
            "nama" => "Batik Tulis Premium",
            "atelier" => "Studio Mode",
            "harga" => "168.000",
            "rating" => "4.7",
            "terjual" => "89",
            "gambar" => $produk[5]["gambar"]
        ],
        [
            "nama" => "Batik Tulis Premium",
            "atelier" => "Studio Mode",
            "harga" => "168.000",
            "rating" => "4.7",
            "terjual" => "89",
            "gambar" => $produk[6]["gambar"]
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ALTERA — Dashboard Customer</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>

<body>
    <!-- HEADER -->
    <header class="topbar">
        <a href="index.php" class="brand">
            <span class="brand-mark">A</span>
            <span>ALTERA</span>
        </a>

        <form class="search" id="searchForm">
            <span aria-hidden="true">⌕</span>
            <input
                type="search"
                id="searchInput"
                placeholder="Cari tailor, koleksi, kota..."
                aria-label="Cari tailor, koleksi, kota"
            >
        </form>

        <nav class="top-actions" aria-label="Menu akun">
            <button
                class="icon-button"
                type="button"
                aria-label="Notifikasi"
                data-message="Fitur notifikasi akan dibuat pada tahap berikutnya."
            >♧</button>

            <button
                class="icon-button"
                type="button"
                aria-label="Keranjang"
                data-message="Fitur keranjang akan dibuat pada tahap berikutnya."
            >🛒</button>

            <?php if ($namaPengguna): ?>
                <div class="profile-button">
                    <span class="profile-dot">
                        <?= htmlspecialchars(
                            strtoupper(substr($namaPengguna, 0, 1)),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars(
                            $namaPengguna,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <a href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <a class="profile-button" href="login.php">
                    <span class="profile-dot">I</span>
                    <span>Login</span>
                </a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="page">

        <!-- PROMO UTAMA -->
        <section class="hero">
            <div class="hero-copy">
                <span class="eyebrow">PROMO HARI INI</span>

                <h1>
                    Diskon 30% untuk<br>
                    Pesanan Pertama!
                </h1>

                <p>
                    Gunakan kode ALTERA30 saat checkout.
                    Berlaku s/d 31 Mei 2025.
                </p>

                <button
                    class="button button-outline"
                    type="button"
                    data-message="Kode promo ALTERA30 siap digunakan saat fitur checkout tersedia."
                >
                    Klaim Sekarang
                </button>
            </div>

            <div class="hero-shape" aria-hidden="true"></div>
        </section>

        <!-- MENU -->
        <section class="section menu-section">
            <h2>Menu</h2>

            <div class="menu-grid">
                <button class="menu-item" data-message="Kamu memilih Beranda." aria-label="Beranda">
                    <span>⌂</span>
                </button>

                <button class="menu-item" data-message="Fitur pesan akan dibuat berikutnya." aria-label="Pesan">
                    <span>▱</span>
                </button>

                <button class="menu-item" data-message="Fitur rekomendasi akan dibuat berikutnya." aria-label="Rekomendasi">
                    <span>✧</span>
                </button>

                <button class="menu-item" data-message="Fitur pesanan akan dibuat berikutnya." aria-label="Pesanan">
                    <span>⬡</span>
                </button>

                <button class="menu-item" data-message="Fitur panduan ukuran akan dibuat berikutnya." aria-label="Panduan ukuran">
                    <span>◫</span>
                </button>

                <button class="menu-item" data-message="Fitur pengiriman akan dibuat berikutnya." aria-label="Pengiriman">
                    <span>♧</span>
                </button>

                <button class="menu-item" data-message="Fitur notifikasi akan dibuat berikutnya." aria-label="Notifikasi">
                    <span>♧</span>
                </button>
            </div>
        </section>

        <!-- KATEGORI -->
        <section class="section" id="kategori">
            <h2>Kategori</h2>

            <div class="category-grid">
                <button class="category-chip" data-category="Batik">
                    <span>♧</span> Batik
                </button>

                <button class="category-chip" data-category="Formal">
                    <span>♢</span> Formal
                </button>

                <button class="category-chip" data-category="Casual">
                    <span>✂</span> Casual
                </button>

                <button class="category-chip" data-category="Premium">
                    <span>◇</span> Premium
                </button>

                <button class="category-chip" data-category="Accessories">
                    <span>♧</span> Accessories
                </button>
            </div>
        </section>

        <!-- PROMO DAN DISKON -->
        <section class="section" id="promo">
            <div class="section-heading">
                <h2>Promo &amp; Diskon</h2>

                <button
                    class="text-link"
                    type="button"
                    data-message="Semua promo akan tersedia setelah fitur promo dibuat."
                >
                    Lihat semua
                </button>
            </div>

            <div class="promo-grid">
                <button
                    class="promo-card promo-gold"
                    data-message="Promo ALTERA30: diskon 30% untuk pesanan pertama."
                >
                    <strong>30%</strong>
                    <span>Pesanan pertama</span>
                    <small>ALTERA30</small>
                </button>

                <button
                    class="promo-card promo-brown"
                    data-message="Promo BATIK20: diskon 20% untuk koleksi kebaya dan batik."
                >
                    <strong>20%</strong>
                    <span>Kebaya &amp; Batik</span>
                    <small>BATIK20</small>
                </button>

                <button
                    class="promo-card promo-mauve"
                    data-message="Promo JAS15: diskon 15% untuk jas dan setelan pria."
                >
                    <strong>15%</strong>
                    <span>Jas &amp; Setelan Pria</span>
                    <small>JAS15</small>
                </button>
            </div>
        </section>

        <!-- KOLEKSI TERPOPULER -->
        <section class="section popular-products">
            <div class="center-heading">
                <h2>Koleksi Terpopuler</h2>

                <div class="filter-list" aria-label="Filter kategori koleksi">
                    <button class="filter-chip active" data-filter="Semua">Semua</button>
                    <button class="filter-chip" data-filter="Jas">Jas</button>
                    <button class="filter-chip" data-filter="Batik">Batik</button>
                    <button class="filter-chip" data-filter="Kebaya">Kebaya</button>
                    <button class="filter-chip" data-filter="Seragam">Seragam</button>
                </div>
            </div>

            <div class="product-grid" id="productGrid">
                <?php foreach ($produk as $item): ?>
                    <article
                        class="product-card"
                        data-category="<?= htmlspecialchars(
                            $item['label'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        data-name="<?= htmlspecialchars(
                            strtolower(
                                $item['nama'] . ' ' .
                                $item['atelier'] . ' ' .
                                $item['lokasi']
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >
                        <div class="product-image-wrap">
                            <img
                                src="<?= htmlspecialchars(
                                    $item['gambar'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $item['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                loading="lazy"
                            >

                            <button
                                class="heart-button"
                                type="button"
                                aria-label="Simpan favorit"
                                data-message="Fitur favorit akan dibuat pada tahap berikutnya."
                            >♡</button>
                        </div>

                        <div class="product-info">
                            <p class="atelier-name">
                                <?= htmlspecialchars(
                                    $item['atelier'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                                <span>★ 4.9</span>
                            </p>

                            <h3>
                                <?= htmlspecialchars(
                                    $item['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <p class="product-location">
                                ⌖ <?= htmlspecialchars(
                                    $item['lokasi'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                            <p class="old-price">
                                Rp <?= number_format(
                                    (int) str_replace('.', '', $item['harga']) * 1.2,
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </p>

                            <p class="product-price">
                                Rp <?= htmlspecialchars(
                                    $item['harga'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                            <button
                                class="button button-dark order-button"
                                type="button"
                                data-message="Detail dan pemesanan <?= htmlspecialchars(
                                    $item['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?> akan dibuat pada tahap berikutnya."
                            >
                                Pesan Sekarang
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="empty-message" id="emptyProducts" hidden>
                Produk tidak ditemukan.
            </p>
        </section>

        <!-- KOLEKSI POPULER -->
        <section class="section popular-list-section">
            <div class="section-heading">
                <h2>Koleksi Populer</h2>

                <button
                    class="text-link"
                    type="button"
                    data-message="Halaman semua koleksi akan dibuat berikutnya."
                >
                    Lihat semua
                </button>
            </div>

            <div class="popular-list">
                <?php foreach ($koleksi as $item): ?>
                    <article class="popular-row">
                        <img
                            src="<?= htmlspecialchars(
                                $item['gambar'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="<?= htmlspecialchars(
                                $item['nama'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            loading="lazy"
                        >

                        <div class="popular-details">
                            <h3>
                                <?= htmlspecialchars(
                                    $item['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $item['atelier'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                            <div class="popular-meta">
                                <span>
                                    ★ <?= htmlspecialchars(
                                        $item['rating'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $item['terjual'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                    terjual
                                </span>
                            </div>
                        </div>

                        <strong class="popular-price">
                            Rp <?= htmlspecialchars(
                                $item['harga'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- TAILOR TERDEKAT -->
        <section class="section tailor-section">
            <div class="section-heading">
                <h2>Tailor Terdekat</h2>

                <button
                    class="text-link"
                    type="button"
                    data-message="Peta interaktif tailor terdekat akan dibuat pada tahap berikutnya."
                >
                    Buka peta
                </button>
            </div>

            <div class="map-placeholder">
                <div class="map-decoration map-line-one"></div>
                <div class="map-decoration map-line-two"></div>

                <span class="map-pin pin-one">●</span>
                <span class="map-pin pin-two">●</span>
                <span class="map-pin pin-three">●</span>

                <div class="map-caption">
                    <div>
                        <strong>📍 Jakarta Selatan</strong>
                        <span>4 tailor di sekitar kamu</span>
                    </div>

                    <button
                        class="button button-dark"
                        type="button"
                        data-message="Daftar lengkap tailor terdekat akan dibuat berikutnya."
                    >
                        Lihat Semua
                    </button>
                </div>
            </div>

            <div class="tailor-list">
                <article class="tailor-row">
                    <div class="tailor-avatar">AS</div>

                    <div class="tailor-details">
                        <h3>Atelier Sari</h3>
                        <p>Gaun &amp; Kebaya · Jakarta</p>

                        <div>
                            <span class="rating">★ 4.8</span>
                            <span class="status available">● TERSEDIA</span>
                            <span class="distance">0.8 km</span>
                        </div>
                    </div>

                    <strong>Rp 150K <small>/awal</small></strong>

                    <div class="progress">
                        <span style="width: 85%"></span>
                    </div>
                </article>

                <article class="tailor-row">
                    <div class="tailor-avatar avatar-two">RT</div>

                    <div class="tailor-details">
                        <h3>Royal Tailor</h3>
                        <p>Jas &amp; Setelan · Jakarta</p>

                        <div>
                            <span class="rating">★ 4.9</span>
                            <span class="status busy">● HAMPIR PENUH</span>
                            <span class="distance">1.2 km</span>
                        </div>
                    </div>

                    <strong>Rp 350K <small>/awal</small></strong>

                    <div class="progress progress-orange">
                        <span style="width: 70%"></span>
                    </div>
                </article>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-about">
                <h3>Altera</h3>

                <p>
                    Digital Craftsmanship untuk<br>
                    masa busana Indonesia.<br>
                    Menghubungkan tradisi dengan<br>
                    teknologi.
                </p>
            </div>

            <div>
                <h4>Layanan</h4>
                <a href="#kategori">Cari Penjahit</a>
                <a href="#kategori">Kategori Spesialisasi</a>
                <a href="#promo">Layanan Premium</a>
            </div>

            <div>
                <h4>Perusahaan</h4>
                <a href="#tentang">Tentang Kami</a>
                <a href="#karir">Karir</a>
                <a href="#kontak">Kontak</a>
            </div>

            <div>
                <h4>Hubungi Kami</h4>

                <div class="social-links">
                    <a href="#instagram" aria-label="Instagram">◎</a>
                    <a href="#pinterest" aria-label="Pinterest">℗</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>
                © 2024 Altera Atelier. Seluruh Hak Cipta Dilindungi.
            </span>

            <div>
                <a href="#privasi">Kebijakan Privasi</a>
                <a href="#syarat">Syarat &amp; Ketentuan</a>
            </div>
        </div>
    </footer>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
</body>
</html>
