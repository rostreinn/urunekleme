<?php
include("baglanti.php");

// Hata ayıklama için tüm hataları göster
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["giris"])) {
    $name = htmlspecialchars($_POST["kullaniciadi"]);
    $password = htmlspecialchars($_POST["parola"]);

    // Veritabanı bağlantısını kontrol et
    if ($baglanti->connect_error) {
        die('Veritabanı bağlantısı başarısız: ' . $baglanti->connect_error);
    }

    // Kullanıcı bilgilerini al
    $stmt = $baglanti->prepare("SELECT parola  FROM uyeler WHERE kullaniciadi = ?");
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($baglanti->error));
    }

    $stmt->bind_param("s", $name);
    if (!$stmt->execute()) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Parola doğrulamasını kontrol et
        if (password_verify($password, $hashed_password)) {
            // Başarılı giriş
            echo "Başarılı giriş, yönlendiriliyor...";
            header("Location: index.php");

            exit();
        } else {
            // Parola yanlış
            echo '<div class="alert alert-danger" role="alert">E-mail veya parola hatalı. (Parola yanlış)</div>';
        }
    } else {
        // Kullanıcı bulunamadı
        echo '<div class="alert alert-danger" role="alert">Kullanocı Adıveya parola hatalı. (Kullanıcı bulunamadı)</div>';
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
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<form method="POST" action="">
  <div class="form-group p-5">
    <label for="kullanici">Kullanıcı Adı</label>
    <input type="text" name="kullaniciadi" class="form-control" id="kullaniciadi" placeholder="Kullanıcı Adı" required>
  </div>
  <div class="form-group p-5">
    <label for="exampleInputPassword1">Parola</label>
    <input type="password" name="parola" class="form-control" id="exampleInputPassword1" placeholder="Parola" required>
  </div>
  <div class="form-group form-check p-5">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Beni hatırla</label>
  </div>
  <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
</form>
</body>
</html>
