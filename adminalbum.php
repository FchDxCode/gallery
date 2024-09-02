<?php
include "./koneksi.php";
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
}
function getUserProfile($conn, $userid)
{
    $query = mysqli_query($conn, "SELECT * FROM user WHERE userid = $userid");
    return mysqli_fetch_assoc($query);
}

$user = isset($_SESSION['userid']) ? getUserProfile($conn, $_SESSION['userid']) : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Album</title>
    <link rel="stylesheet" href="./css/all.min.css">
    <link rel="stylesheet" href="./css/fontawesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans bg-gray-300">
<?php include 'adminnavbar.php'; ?>
    <div class="bg-gray-300 p-4">
        <h1 class="text-3xl text-center text-gray-800">Halaman Album</h1>
        <p class="text-center mt-2">Selamat datang <b>
                <?= $_SESSION['namalengkap'] ?>
            </b></p>
    </div>

    <div class="container mx-auto mt-8 p-4">
        <button id="btnTambahAlbum" class="bg-red-500 text-white px-4 py-2 rounded mb-4">Tambah Album</button>
        <form id="formTambahAlbum" action="tambah_album.php" method="post" class="mb-8" style="display: none;">
            <div class="max-w-md bg-[#2A3132] rounded-lg shadow-md p-6 ml-0">
                <div class="mb-4">
                    <label for="namaalbum" class="block text-white text-sm font-bold mb-2">Nama Album</label>
                    <input type="text" name="namaalbum" id="namaalbum" class="border border-gray-300 p-2 rounded w-full focus:outline-none">
                </div>
                <div class="mb-4">
                    <label for="deskripsi" class="block text-white text-sm font-bold mb-2">Deskripsi</label>
                    <input type="text" name="deskripsi" id="deskripsi" class="border border-gray-300 p-2 rounded w-full focus:outline-none ">
                </div>
                <div class="flex justify-end">
                    <input type="submit" value="Tambah" class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-red-600">
                </div>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            include "koneksi.php";
            $userid = $_SESSION['userid'];
            $sql = mysqli_query($conn, "select * from album where userid='$userid'");
            while ($data = mysqli_fetch_array($sql)) {
                ?>
                <div
                    class="bg-[#2A3132] border rounded-md overflow-hidden shadow-md transform transition-transform ease-in-out hover:scale-105">
                    <div class="p-4">
                        <a href="album_detail.php?albumid=<?= $data['albumid'] ?>" class="block">
                            <h2 class="text-lg text-white font-semibold mb-2">
                                <?= $data['namaalbum'] ?>
                            </h2>
                            <p class="text-sm text-white mb-2">
                                <?= $data['deskripsi'] ?>
                            </p>
                            <p class="text-xs text-red-500">Tanggal dibuat:
                                <?= $data['tanggaldibuat'] ?>
                            </p>
                        </a>
                        <div class="mt-4 flex justify-between">
                            <a href="#" onclick="confirmDelete(<?= $data['albumid'] ?>)" class="text-red-500 hover:underline"><i class="fa-solid fa-trash"></i></a>
                            <a href="edit_album.php?albumid=<?= $data['albumid'] ?>"
                                class="text-green-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <script>
            document.getElementById("btnTambahAlbum").addEventListener("click", function () {
                document.getElementById("formTambahAlbum").style.display = "block";
            });

            document.getElementById("formTambahAlbum").addEventListener("submit", function (event) {
                var namaalbum = document.getElementById("namaalbum").value.trim();
                var deskripsi = document.getElementById("deskripsi").value.trim();

                if (namaalbum === '' || deskripsi === '') {
                    event.preventDefault();
                    Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Tolong Isi Kolom Nya :)",
                    });
                }
            });

            function confirmDelete(albumid) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan album ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'hapus_album.php?albumid=' + albumid;
                    }
                })
            }
        </script>
    </div>
    </div>
</body>

</html>