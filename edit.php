<?php
session_start();
require_once 'Database.php';
$db = Database::getInstance();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

// Ambil data lama
$stmt = $db->prepare("SELECT * FROM produk WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) { header("Location: index.php"); exit; }

// Fetch Dropdown
$kategori = $db->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
$supplier = $db->query("SELECT * FROM supplier")->fetchAll(PDO::FETCH_ASSOC);

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_produk'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $kategori_id = $_POST['kategori_id'];
    $supplier_id = $_POST['supplier_id'];

    $updateStmt = $db->prepare("UPDATE produk SET nama_produk=:nama, stok=:stok, harga=:harga, kategori_id=:kat, supplier_id=:sup WHERE id=:id");
    $updateStmt->bindParam(':nama', $nama);
    $updateStmt->bindParam(':stok', $stok);
    $updateStmt->bindParam(':harga', $harga);
    $updateStmt->bindParam(':kat', $kategori_id);
    $updateStmt->bindParam(':sup', $supplier_id);
    $updateStmt->bindParam(':id', $id);

    if ($updateStmt->execute()) {
        $_SESSION['flash'] = ['type' => 'info', 'message' => 'Data produk berhasil diperbarui!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data.'];
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Produk</h2>
    <div class="card mt-3">
        <div class="card-body">
            <!-- Form mengirim (POST) ke file ini sendiri -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $k['id'] == $produk['kategori_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <?php foreach ($supplier as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $produk['supplier_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nama_supplier']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($produk['stok']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($produk['harga']) ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Data</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>