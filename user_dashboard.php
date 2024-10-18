<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$popup_message = '';

if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['gambar']['tmp_name'])) {
        $datagambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        $propertiesgambar = getimageSize($_FILES['gambar']['tmp_name']);

        try {
            // Insert the image into the database
            $sql = "INSERT INTO tb_images (tipeimage, dataimage) VALUES (:tipeimage, :dataimage)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':tipeimage' => $propertiesgambar['mime'],
                ':dataimage' => $datagambar,
            ]);

            // Get the last inserted ID
            $lastId = $pdo->lastInsertId();
            if ($lastId) {
                $notif = 'Gambar berhasil disimpan, silakan lihat di <a target="_blank" href="view.php?id=' . htmlspecialchars($lastId) . '">sini</a>';
            }
        } catch (PDOException $e) {
            $notif = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Personal Website for My Project">
    <link rel="icon" href="/img/icn.png">
    <title>Fadoirulexiana</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/script/css/star.scss">
    <link rel="stylesheet" href="assets/script/css/index.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid" id="bar">
            <p>Welcome soldier, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../backend-server/logout.php" aria-disabled="true">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Input data -->
    <?php
    if (isset($notif)) {
        echo $notif;
    }
    ?>
    <form name="formupload" enctype="multipart/form-data" action="" method="post">
        <label>Upload Gambar:</label><br />
        <input name="gambar" type="file" />
        <input type="submit" value="Submit" />
    </form>

    <!-- show images when image saved -->


    <!-- Background Star -->
    <div class="star"></div>
    <div class="star"></div>
    <div class="star"></div>
    <div class="star"></div>
    <div class="star"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
