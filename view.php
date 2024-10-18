<?php
session_start();
include 'connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_images WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            header("Content-type: " . $row["tipeimage"]);
            echo $row["dataimage"];
        } else {
            echo "Image not found.";
        }
    } catch (PDOException $e) {
        die("<b>Error:</b> Ada kesalahan<br/>" . $e->getMessage());
    }
} else {
    echo "Invalid ID.";
}

// No need to close the connection explicitly with PDO; it closes automatically when the script ends.
?>
