<?php
include 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['nama']) &&
        isset($_POST['asal']) &&
        isset($_POST['harga']) &&
        isset($_FILES['foto']) &&
        $_POST['harga'] !== ''
    ) {
        $nama = $_POST['nama'];
        $asal = $_POST['asal'];
        $harga = $_POST['harga'];
        $foto = $_FILES['foto'];

        $targetDir = 'public/';
        $targetFile = $targetDir . basename($foto['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($foto['tmp_name']);
        if ($check === false) {
            echo "<p>File is not an image.</p>";
            $uploadOk = 0;
        }

        // Check file size (limit 2MB)
        if ($foto['size'] > 2 * 1024 * 1024) {
            echo "<p>Sorry, your file is too large.</p>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
            $uploadOk = 0;
        }

        if ($uploadOk && move_uploaded_file($foto['tmp_name'], $targetFile)) {
            $fotoPath = $targetFile;
            $stmt = $conn->prepare("INSERT INTO brands (nama, asal, harga, foto) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $nama, $asal, $harga, $fotoPath);
            if ($stmt->execute()) {
                // Redirect to avoid resubmission on refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else if ($uploadOk) {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
        $conn->close();
    } else {
        echo '<p style="color:red;">Semua field harus diisi!</p>';
    }
}
?>

<details style="margin-bottom:20px;">
    <summary style="font-size:1.2em;"><b>TAMBAH MEREK MOBIL</b></summary>
    <form method="POST" enctype="multipart/form-data" style="margin-top:10px;">
        Nama: <input type="text" name="nama" required><br><br>
        Asal: <input type="text" name="asal" required><br><br>
        Harga: <input type="number" name="harga" required><br><br>
        Foto: <input type="file" name="foto" accept="image/*" required><br><br>
        <button type="submit" name="submit" value="Tambah Merek">Tambah Merek</button>
    </form>
</details>
