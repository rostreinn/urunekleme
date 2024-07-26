<?php
include("baglanti.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['giris'])) {
    $name = htmlspecialchars($_POST["name"]);
    $password = htmlspecialchars($_POST["parola"]);

    if ($baglanti->connect_error) {
        die('Veritabanı bağlantısı başarısız: ' . $baglanti->connect_error);
    }

    // Kullanıcı bilgilerini al
    $stmt = $baglanti->prepare("SELECT parola, is_admin FROM uyeler WHERE kullaniciadi = ?");
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($baglanti->error));
    }

    $stmt->bind_param("s", $name);
    if (!$stmt->execute()) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $is_admin);
        $stmt->fetch();

        // Parola doğrulamasını kontrol et
        if (password_verify($password, $hashed_password)) {
            if ($is_admin == 1) {
                // Admin girişi başarılı
                echo "Başarılı giriş, admin yönlendiriliyor...";
                header("Location: admin.php");
                exit();
            } else {
                // Admin olmayan kullanıcı
                echo '<div class="alert alert-danger" role="alert">Giriş yetkiniz yok. (Admin değil)</div>';
            }
        } else {
            // Parola yanlış
            echo '<div class="alert alert-danger" role="alert">E-mail veya parola hatalı. (Parola yanlış)</div>';
        }
    } else {
        // Kullanıcı bulunamadı
        echo '<div class="alert alert-danger" role="alert">E-mail veya parola hatalı. (Kullanıcı bulunamadı)</div>';
    }

    $stmt->close();
    $baglanti->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>
<body>
    <form method="POST" action="">
        <div class="form-group p-5">
            <label for="kullaniciadi">Kullanıcı Adı</label>
            <input type="text" name="name" class="form-control" id="kullaniciadi" placeholder="Kullanıcı Adı" required>
        </div>
        <div class="form-group p-5">
            <label for="exampleInputPassword1">Parola</label>
            <input type="password" name="parola" class="form-control" id="exampleInputPassword1" placeholder="Parola" required>
        </div>
        <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
    </form>
</body>
</html>
