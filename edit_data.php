<?php
include 'index.php';

// Get brand data for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM brands WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $brand = $result->fetch_assoc();
    $stmt->close();
} else {
    die('ID not specified.');
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $asal = $_POST['asal'];
    $harga = $_POST['harga'];
    $fotoPath = $brand['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = 'public/';
        $targetFile = $targetDir . basename($_FILES['foto']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['foto']['tmp_name']);
        if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['foto']['size'] <= 2 * 1024 * 1024) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile);
            $fotoPath = $targetFile;
        }
    }
    $stmt = $conn->prepare("UPDATE brands SET nama=?, asal=?, harga=?, foto=? WHERE id=?");
    $stmt->bind_param("ssisi", $nama, $asal, $harga, $fotoPath, $id);
    if ($stmt->execute()) {
        header("Location: sigma.php");
        exit();
    } else {
        echo '<p style="color:red;">Update failed: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Brand</title>
</head>
<body>
    <h2>Edit Merek Mobil</h2>
    <form method="POST" enctype="multipart/form-data">
        Nama: <input type="text" name="nama" value="<?php echo htmlspecialchars($brand['nama']); ?>" required><br><br>
        Asal: <input type="text" name="asal" value="<?php echo htmlspecialchars($brand['asal']); ?>" required><br><br>
        Harga: <input type="number" name="harga" value="<?php echo htmlspecialchars($brand['harga']); ?>" required><br><br>
        Foto: <input type="file" name="foto" accept="image/*"><br>
        <img src="<?php echo htmlspecialchars($brand['foto']); ?>" alt="<?php echo htmlspecialchars($brand['nama']); ?>" width="96"><br><br>
        <button type="submit">Update</button>
    </form>
    <p><a href="sigma.php">Kembali ke Daftar</a></p>
</body>
</html>
