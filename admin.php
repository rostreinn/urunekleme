<?php
include("baglanti.php");

session_start();


if(isset($_POST['urun_ekle'])) {
    $urun_adi = htmlspecialchars($_POST["urun_adi"]);
    $urun_fiyati = htmlspecialchars($_POST["urun_fiyati"]);
    $urun_aciklama = htmlspecialchars($_POST["urun_aciklama"]);

    if ($baglanti->connect_error) {
        die('Veritabanı bağlantısı başarısız: ' . $baglanti->connect_error);
    }

    $stmt = $baglanti->prepare("INSERT INTO urunler (urun_adi, urun_fiyati, urun_aciklama) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($baglanti->error));
    }

    $stmt->bind_param("sss", $urun_adi, $urun_fiyati, $urun_aciklama);
    if (!$stmt->execute()) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    } else {
        echo '<div class="alert alert-success" role="alert">Ürün başarıyla eklendi.</div>';
        header("Location: index.php");
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
    <title>Ürün Ekle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ürün Ekle</h1>
    <form method="POST" action="">
        <div class="form-group p-5">
            <label for="urun_adi">Ürün Adı</label>
            <input type="text" name="urun_adi" class="form-control" id="urun_adi" placeholder="Ürün Adı" required>
        </div>
        <div class="form-group p-5">
            <label for="urun_fiyati">Ürün Fiyatı</label>
            <input type="text" name="urun_fiyati" class="form-control" id="urun_fiyati" placeholder="Ürün Fiyatı" required>
        </div>
        <div class="form-group p-5">
            <label for="urun_aciklama">Ürün Açıklaması</label>
            <textarea name="urun_aciklama" class="form-control" id="urun_aciklama" placeholder="Ürün Açıklaması" required></textarea>
        </div>
        <button type="submit" name="urun_ekle" class="btn btn-primary">Ürün Ekle</button>
    </form>
    
</body>
</html>
