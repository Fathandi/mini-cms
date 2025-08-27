<?php
// ==========================
// 1. Generate Artikel dari JSON
// ==========================

// Ambil isi file artikel.json lalu decode ke bentuk array PHP
$artikelData = json_decode(file_get_contents("data/artikel.json"), true);

// Bersihkan semua file lama di folder artikel/
// Tujuannya supaya file lama yang sudah dihapus di artikel.json
// juga ikut hilang dari folder "artikel/"
$files = glob("artikel/*.html"); // ambil semua file .html di folder artikel/
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file); // hapus file satu per satu
    }
}

// Looping semua data artikel yang ada di artikel.json
foreach ($artikelData as $artikel) {
    $slug = $artikel["slug"];      // nama file html nya (contoh: judul-2.html)
    $title = $artikel["title"];    // judul artikel
    $content = $artikel["content"]; // isi artikel

    // Template HTML untuk setiap artikel
    $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>$title</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            max-width: 800px; 
            margin: 40px auto; 
            padding: 0 20px;
        }
        h1 { color: #333; margin-bottom: 20px; }
        p { margin-bottom: 15px; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>$title</h1>
    <p>$content</p>
</body>
</html>";

    // Simpan template HTML ke file baru
    // Contoh: artikel/judul-2.html
    file_put_contents("artikel/$slug.html", $html);
}
?>

<!-- ==========================
2. Tampilan Index (daftar artikel + section card)
========================== -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Index</title>
    <style>
        /* Style global untuk index */
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        h1, h2 { color: #333; }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; }
        a {
            display: inline-block;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #007BFF;
            text-decoration: none;
            transition: 0.2s;
        }
        a:hover {
            background: #007BFF;
            color: white;
        }
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        img {
            height: 80px;
        }
    </style>
</head>
<body>

<h1>Daftar Artikel</h1>
<ul>
<?php 
// Tampilkan semua artikel dari artikel.json
foreach ($artikelData as $artikel): ?>
    <li>
        <!-- Link ke file artikel yang sudah digenerate -->
        <a href="artikel/<?= $artikel['slug'] ?>.html">
            <?= $artikel['title'] ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<h2>Profile Card</h2>
<?php
// Ambil data dari section.json
$sectionData = json_decode(file_get_contents("data/section.json"), true);

// Looping semua section
foreach ($sectionData as $section): ?>
    <div class="card">
        <h3><?= $section['name'] ?></h3>
        <p><?= $section['description'] ?></p>
        <img src="<?= $section['image'] ?>">
    </div>
<?php endforeach; ?>

<script>
// ==========================
// 3. Auto Refresh Data
// ==========================

// Cek apakah di localStorage sudah ada cache artikel.json
if (!localStorage.getItem("artikelData")) {
    fetch("data/artikel.json").then(res => res.text()).then(data => {
        localStorage.setItem("artikelData", data); // simpan ke localStorage
    });
}

// Cek apakah di localStorage sudah ada cache section.json
if (!localStorage.getItem("sectionData")) {
    fetch("data/section.json").then(res => res.text()).then(data => {
        localStorage.setItem("sectionData", data); // simpan ke localStorage
    });
}
</script>

</body>
</html>
