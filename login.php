
<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Email dan password wajib diisi.";
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $stmt = $db->prepare(
                "SELECT id, nama, email, password, role
                 FROM users
                 WHERE email = ?
                 LIMIT 1"
            );

            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify(
                $password,
                $user["password"]
            )) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["nama"] = $user["nama"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                header("Location: index.php");
                exit;
            }

            $error = "Email atau password salah.";
        } catch (PDOException $e) {
            $error = "Login gagal. Periksa koneksi database.";
        }
    }
}

$registered = isset($_GET["registered"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer — ALTERA</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <script src="assets/js/auth.js" defer></script>
</head>
<body>
    <main class="auth-page">
        <a href="index.php" class="brand">ALTERA</a>

        <section class="auth-card">
            <p class="eyebrow">WELCOME BACK</p>
            <h1>Login</h1>
            <p class="subtitle">
                Masuk untuk melanjutkan perjalanan fashion-mu.
            </p>

            <?php if ($registered): ?>
                <div class="alert success">
                    Pendaftaran berhasil! Silakan login.
                </div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
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
                    autocomplete="current-password"
                    required
                >

                <button class="auth-button" type="submit">
                    Masuk
                </button>
            </form>

            <p class="auth-switch">
                Belum punya akun?
                <a href="register.php">Daftar</a>
            </p>

            <a href="index.php" class="back-link">
                ← Kembali ke beranda
            </a>
        </section>
    </main>
</body>
</html>
