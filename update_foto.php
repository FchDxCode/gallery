<?php
include "koneksi.php";
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}

$userid = $_SESSION["userid"];
$fotoid = $_POST["fotoid"];
$judulfoto = $_POST['judulfoto'];
$deskripsifoto = $_POST['deskripsifoto'];
$albumid = $_POST['albumid'];

// Memeriksa apakah pengguna memiliki role admin
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $redirect_page = "adminfoto.php"; // Jika role admin, arahkan ke adminfoto.php
} else {
    $redirect_page = "foto.php"; // Jika bukan admin, arahkan ke foto.php
}

// Jika ada file foto yang diupload
if (!empty($_FILES['lokasifile']['name'])) {
    $target_dir = "gambar/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES["lokasifile"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;

    // Cek ukuran file (batas maksimum 5MB)
    if ($_FILES["lokasifile"]["size"] > 5 * 1024 * 1024) {
        echo "File terlalu besar. Maksimum 5MB.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["lokasifile"]["tmp_name"], $target_file)) {
            // Store only the filename, not the full path
            $update_query = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', lokasifile='$file_name', albumid='$albumid' WHERE fotoid='$fotoid' AND userid='$userid'";
        } else {
            echo "Gagal mengunggah file.";
            exit();
        }
    } else {
        echo "File tidak diupload karena error.";
        exit();
    }
} else {
    // Jika tidak ada file baru yang diupload, update tanpa mengubah lokasifile
    $update_query = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', albumid='$albumid' WHERE fotoid='$fotoid' AND userid='$userid'";
}

if (mysqli_query($conn, $update_query)) {
    header("location:" . $redirect_page);
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Edit Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans bg-gray-300">
    <div class="bg-gray-300 p-4">
    <ul class="flex items-center justify-center mt-4">
            <?php
            if ($_SESSION['role'] === 'admin') {
                include 'adminnavbar.php';
            } else {
                include 'navbar.php';
            }
            ?>
        </ul>
        <h1 class="text-3xl text-center text-gray-800">Halaman Edit Foto</h1>
        <p class="text-center mt-2">Selamat datang <b><?=$_SESSION['namalengkap']?></b></p>
    </div>

    <?php
    if (isset($error)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '$error',
            });
        </script>";
    }
    if (isset($success)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '$success',
            });
        </script>";
    }
    ?>

    <form action="update_foto.php" method="post" enctype="multipart/form-data" class="container mx-auto mt-8 p-4 flex justify-center">
    <div class="max-w-md w-full bg-[#2A3132] rounded-lg shadow-md p-6">
        <?php
            if ($fotoid) {
                $sql = mysqli_query($conn, "SELECT * FROM foto WHERE fotoid='$fotoid'");
                if ($data = mysqli_fetch_array($sql)) {
        ?>
        <input type="hidden" name="fotoid" value="<?=$data['fotoid']?>">
        <div class="mb-4">
            <label for="judul" class="block text-white text-sm font-bold mb-2">Judul</label>
            <input type="text" name="judulfoto" id="judul" value="<?=$data['judulfoto']?>" class="border p-2 rounded w-full outline-none">
        </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-white text-sm font-bold mb-2">Deskripsi</label>
            <input type="text" name="deskripsifoto" id="deskripsi" value="<?=$data['deskripsifoto']?>" class="border p-2 rounded w-full outline-none">
        </div>
        <div class="mb-4">
            <label for="lokasi" class="block text-white text-sm font-bold mb-2">Lokasi File</label>
            <input type="file" name="lokasifile" id="lokasi" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-2">
        </div>
        <div class="mb-4">
            <label for="album" class="block text-white text-sm font-bold mb-2">Album</label>
            <select name="albumid" id="album" class="border p-2 rounded w-full outline-none">
                <?php
                    $userid = $_SESSION['userid'];
                    $sql2 = mysqli_query($conn, "SELECT * FROM album WHERE userid='$userid'");
                    while ($data2 = mysqli_fetch_array($sql2)) {
                ?>
                    <option value="<?=$data2['albumid']?>" <?php if($data2['albumid'] == $data['albumid']) { echo 'selected'; } ?>><?=$data2['namaalbum']?></option>
                <?php
                    }
                ?>
            </select>
        </div>
        <div class="flex justify-end">
            <input type="submit" value="Ubah" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
        </div>
        <?php
                } else {
                    echo "<p>Data tidak ditemukan.</p>";
                }
            } else {
                echo "<p>ID foto tidak ditemukan.</p>";
            }
        ?>
    </div>
</form>

</body>
</html>