<?php
session_start();
include '../backend-server/connect.php'; // Menggunakan koneksi PDO

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Inisialisasi pesan popup
$popup_message = '';

if (isset($_POST['change_role'])) {
    $user_id = $_POST['user_id']; // Sesuai dengan name="user_id" di form
    $new_role = $_POST['role'];

    // Update role di database menggunakan PDO
    $sql = "UPDATE users SET role = :role WHERE id_user = :id"; // Pastikan id_user adalah nama kolom di database
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':role' => $new_role, ':id' => $user_id])) {
        $popup_message = "Role updated successfully!";
    } else {
        $popup_message = "Error updating role.";
    }
}

// Mendapatkan semua pengguna dari database menggunakan PDO
$sql = "SELECT id_user, email, username, role FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website Personal for My Project">
    <link rel="icon" href="../img/icn.png">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
    /* Main */
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background-color: gray;
    }

    .container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 15px 0px 0px 0px;
    }

    /* table code */
    .container table {
        border: 1px;
        width: 100% auto;

    }

    /* end code table */

    /* Popup styles same as above */
    .popup {
        display: none;
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 2px solid #444;
        z-index: 1000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .popup.active {
        display: block;
    }

    .popup h3 {
        margin: 0 0 10px;
    }

    .popup button {
        margin-top: 10px;
        padding: 5px 10px;
        background-color: #444;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .popup button:hover {
        background-color: #666;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 500;
    }

    .overlay.active {
        display: block;
    }
    </style> -->
</head>

<body>
    <!-- Loading XXX -->
    <!-- <div x-ref="loading"
        class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker">
        Loading.....
    </div> -->
    <!-- Popup Box -->
    <div id="overlay" class="overlay"></div>
    <div id="popup" class="popup">
        <h3 id="popupMessage">Message here</h3>
        <button onclick="closePopup()">OK</button>
    </div>
    <div class="container">
        <table class="table bg-secondary">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>

            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id_user']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <!-- input hidden yang menyimpan id_user -->
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id_user']); ?>">
                            <select name="role">
                                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User
                                </option>
                                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin
                                </option>
                                <option value="master" <?php echo ($user['role'] == 'master') ? 'selected' : ''; ?>>Master
                                </option>
                            </select>
                            <button type="submit" name="change_role">Change Role</button>
                        </form>
                    </td>

                </tr>
            <?php } ?>
        </table><br>
        <a href="admin_dashboard.php"><button>Back</button></a>
    </div>
    <!-- script -->
    <script>
        // Fungsi untuk menampilkan popup dengan pesan
        function showPopup(message) {
            document.getElementById("popupMessage").innerText = message;
            document.getElementById("popup").classList.add("active");
            document.getElementById("overlay").classList.add("active");

            setTimeout(function () {
                closePopup();
            }, 2000); // 3000 ms = 3 detik
        }

        // Fungsi untuk menutup popup
        function closePopup() {
            document.getElementById("popup").classList.remove("active");
            document.getElementById("overlay").classList.remove("active");
        }

        // Tampilkan popup jika ada pesan
        <?php if (!empty($popup_message)) { ?>
            showPopup("<?php echo $popup_message; ?>");
        <?php } ?>
    </script>
</body>

</html>

<?php
$pdo = null; // Menutup koneksi PDO
?>