<?php 
// Mulai sesi
session_start();

// Direktori upload
$upload_dir = __DIR__ . '/uploads/';

// Cek apakah user sudah login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Contoh autentikasi sederhana
    if ($username === 'anggitashafira' && $password === '12345678') {
        // Simpan data user di session
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        // Proses upload foto
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $file_name = 'profile_' . uniqid() . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $target_path = $upload_dir . $file_name;

            // Pindahkan file ke folder uploads
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
            }
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                $_SESSION['profile_picture'] = 'uploads/' . $file_name;
            } else {
                $error = "Gagal mengunggah foto profil.";
            }
        }

        // Redirect ke halaman profil
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

// Jika logout diminta
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Jika user sudah login, tampilkan halaman profil
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Profil</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color:rgb(255, 176, 254); }
            .marquee { width: 100%; overflow: hidden; white-space: nowrap; box-sizing: border-box; background-color:rgb(255, 0, 247); color: white; padding: 10px 0; text-align: center; font-size: 18px; font-weight: bold; }
            h1, h2 { text-align: center; color: #333; margin: 20px 0; font-size: 28px; }
            .container { display: flex; flex-direction: column; align-items: center; max-width: 900px; margin: 20px auto; background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; }
            .profile-header { display: flex; flex-direction: column; align-items: center; }
            .profile-header h1 { font-size: 32px; margin: 0; }
            .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-top: 20px; }
            .biodata { text-align: center; margin-top: 20px; font-size: 18px; }
            .biodata p { margin: 5px 0; }
            .about-me { margin-top: 30px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
            .about-me h2 { font-size: 24px; color:rgb(255, 0, 195); text-align: center; margin-bottom: 15px; }
            .about-me p { font-size: 16px; line-height: 1.8; color: #555; }
            .logout-link { display: block; margin-top: 20px; text-align: center; font-size: 16px; text-decoration: none; color: #007bff; }
        </style>
    </head>
    <body>
        <div class="marquee">
            <marquee>Selamat datang di halaman profil!</marquee>
        </div>

        <div class="container">
            <!-- Profile Header with Name and Profile Picture -->
            <div class="profile-header">
                <h1>Msy Anggita Shafira</h1>
                <img src="<?php echo $_SESSION['profile_picture']; ?>" alt="Foto Profil" class="profile-img">
            </div>

            <!-- Biodata Section -->
            <div class="biodata">
                <p><strong>Email:</strong> anggitashfr@gmail.com</p>
                <p><strong>No Telpon:</strong> 085368000380</p>
                <p><strong>Domisili:</strong> Palembang</p>
            </div>

            <!-- About Me Section -->
            <div class="about-me">
                <h2>About Me</h2>
                <p>Seorang mahasiswi aktif di Universitas Sriwijaya jurusan Sistem Informasi, angkatan 2023. Saat ini, saya aktif terlibat di Badan Eksekutif Mahasiswa (BEM) Fakultas Ilmu Komputer sebagai Wakil Kepala Dinas Pengembangan Pemberdayaan Sumber Daya Manusia. Melalui organisasi ini, saya telah mengasah keterampilan kepemimpinan, komunikasi, dan manajemen proyek, sambil berkontribusi dalam menciptakan program-program yang berdampak bagi mahasiswa.</p>
            </div>

            <!-- Logout Link -->
            <a href="?logout=true" class="logout-link">Logout</a>
        </div>
    </body>
    </html>

    <?php
    exit;
}

// Jika user belum login, tampilkan form login
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f4f8; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-container { background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-size: 16px; color: #555; }
        input { width: 100%; padding: 10px; margin-top: 5px; font-size: 14px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color:rgb(0, 255, 106); color: white; font-size: 16px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s ease; }
        button:hover { background-color: #0056b3; }
        .error-message { color: red; font-size: 14px; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Form Login</h2>
        <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="profile_picture">Foto Profil:</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
