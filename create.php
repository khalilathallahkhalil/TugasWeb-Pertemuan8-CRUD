<?php
session_start();
require_once 'Database.php';
$db = Database::getInstance();

// Fetch data untuk Dropdown
$kategori = $db->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
$supplier = $db->query("SELECT * FROM supplier")->fetchAll(PDO::FETCH_ASSOC);

// Proses Insert Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_produk'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $kategori_id = $_POST['kategori_id'];
    $supplier_id = $_POST['supplier_id'];

    $stmt = $db->prepare("INSERT INTO produk (nama_produk, stok, harga, kategori_id, supplier_id) VALUES (:nama, :stok, :harga, :kat, :sup)");
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':stok', $stok);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':kat', $kategori_id);
    $stmt->bindParam(':sup', $supplier_id);

    if ($stmt->execute()) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil ditambahkan!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menambah produk.'];
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Tambah Produk Baru</h2>
    <div class="card mt-3">
        <div class="card-body">
            <!-- Form mengirim (POST) ke file ini sendiri -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        <?php foreach ($supplier as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_supplier']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>