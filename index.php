<?php
include("baglanti.php");

$urunler = [];
if ($baglanti->connect_error) {
    die('Veritabanı bağlantısı başarısız: ' . $baglanti->connect_error);
}

$sql = "SELECT urun_adi, urun_fiyati, urun_aciklama FROM urunler";
$result = $baglanti->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $urunler[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Example</h1>
        <nav>
            <ul>
                <li><a href="#home">Ana Sayfa</a></li>
                <li><a href="#products">Ürünler</a></li>
                <li><a href="#contact">İletişim</a></li>
                <li><a href="adminlogin.php">Yönetici</a></li>
                <li><a href="kayit.php">Kayıt Ol</a></li>
            </ul>
        </nav>
    </header>

    <section id="home">
        <h2>Hoş Geldiniz!</h2>
        <p>Av malzemeleri ve daha fazlası için en doğru adres.</p>
    </section>

    <section id="products">
        <h2>Ürünlerimiz</h2>
        <?php foreach ($urunler as $urun): ?>
            <div class="product">

                <h3><?php echo htmlspecialchars($urun['urun_adi']); ?></h3>
                <p>Fiyat: <?php echo htmlspecialchars($urun['urun_fiyati']); ?> TL</p>
                <p><?php echo htmlspecialchars($urun['urun_aciklama']); ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <section id="contact">
        <h2>İletişim</h2>
        <p>Bize ulaşmak için <a href="mailto:example@example.com">example@example.com</a> adresine mail atabilirsiniz.</p>
    </section>

    <footer>
        <p>&copy; Example. Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
