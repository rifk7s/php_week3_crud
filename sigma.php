<?php
include 'index.php';

$sql = "SELECT * FROM brands";
$result = $conn->query($sql);


$brands = [];
if ($result) {
    $brands = $result->fetch_all(MYSQLI_ASSOC);
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1>Daftar Merek Mobil</h1>
    <?php include 'add_data.php'; ?>
    
    <?php if (count($brands) > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>nama</th>
                        <th>asal</th>
                        <th>harga</th>
                        <th>foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['asal']); ?></td>
                                <td><?php echo htmlspecialchars($row['harga']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($row['foto']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>" width="96"></td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    <?php else: ?>
            <p>No brands found.</p>
    <?php endif; ?>
</body>

</html>