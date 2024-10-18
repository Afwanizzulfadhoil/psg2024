<?php
session_start();
include("../backend-server/connect.php"); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../daashboard/login.php");
    exit();
}

// Initialize message variable
$popup_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $user_id = $_SESSION['user_id']; // Ensure user_id is stored in session
    $username = htmlspecialchars(trim($_POST['username']));
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));
    $pendidikan_akhir = htmlspecialchars(trim($_POST['pendidikan_akhir']));
    $pekerjaan = htmlspecialchars(trim($_POST['pekerjaan']));

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO `db_data` (`id_user`, `username`, `jabatan`, `pendidikan_akhir`, `pekerjaan`) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        $_SESSION['message'] = "SQL Prepare failed: " . htmlspecialchars($conn->error);
        header("Location: input_form.php");
        exit();
    }

    $stmt->bind_param("sssss", $user_id, $username, $jabatan, $pendidikan_akhir, $pekerjaan);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil ditambahkan.";
    } else {
        $_SESSION['message'] = "Gagal menambahkan data: " . htmlspecialchars($stmt->error);
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Redirect back to the input form
header("Location: input_form.php"); // Redirect to your form page
exit();
?>
