<?php
include 'index.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: sigma.php");
        exit();
    } else {
        echo '<p style="color:red;">Delete failed: ' . $stmt->error . '</p>';
    }
    $stmt->close();
} else {
    echo '<p style="color:red;">ID not specified.</p>';
}
$conn->close();
?>