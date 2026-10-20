
<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";
$nama = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $konfirmasi = $_POST["konfirmasi"] ?? "";

    if ($nama === "" || $email === "" ||
        $password === "" || $konfirmasi === "") {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $cek = $db->prepare(
                "SELECT id FROM users WHERE email = ?"
            );
            $cek->execute([$email]);

            if ($cek->fetch()) {
                $error = "Email sudah terdaftar. Silakan login.";
            } else {
                $hash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $stmt = $db->prepare(
                    "INSERT INTO users (nama, email, password)
                     VALUES (?, ?, ?)"
                );

                $stmt->execute([$nama, $email, $hash]);

                header("Location: login.php?registered=1");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Pendaftaran gagal. Periksa koneksi database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Customer — ALTERA</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <script src="assets/js/auth.js" defer></script>
</head>
<body>
    <main class="auth-page">
        <a href="index.php" class="brand">ALTERA</a>

        <section class="auth-card">
            <p class="eyebrow">WELCOME TO ALTERA</p>
            <h1>Buat Akun</h1>
            <p class="subtitle">
                Bergabung dan temukan penjahit favoritmu.
            </p>

            <?php if ($error !== ""): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <label for="nama">Nama lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?= htmlspecialchars($nama, ENT_QUOTES, "UTF-8") ?>"
                    autocomplete="name"
                    required
                >

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                    autocomplete="email"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <label for="konfirmasi">Konfirmasi password</label>
                <input
                    type="password"
                    id="konfirmasi"
                    name="konfirmasi"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <button class="auth-button" type="submit">
                    Daftar Sekarang
                </button>
            </form>

            <p class="auth-switch">
                Sudah punya akun?
                <a href="login.php">Login</a>
            </p>

            <a href="index.php" class="back-link">
                ← Kembali ke beranda
            </a>
        </section>
    </main>
</body>
</html>
