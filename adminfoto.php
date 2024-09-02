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
    <title>Halaman Foto</title>
    <link rel="stylesheet" href="./css/all.min.css">
    <link rel="stylesheet" href="./css/fontawesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans bg-gray-300">
<?php include 'adminnavbar.php'; ?>
    <div class="bg-gray-300 p-4">
        <h1 class="text-3xl text-center text-gray-800">Halaman Foto</h1>
        <p class="text-center mt-2">Selamat datang <b>
                <?= $_SESSION['namalengkap'] ?>
            </b></p>
    </div>

    <div class="container mx-auto mt-8 p-4">
        <button id="btnTambahfoto" class="bg-red-500 text-white px-4 py-2 rounded mb-4">Upload foto</button>
        <form id="formTambahfoto" action="tambah_foto.php" method="post" enctype="multipart/form-data" class="mb-8"
            style="display: none;">
            <div class="max-w-md w-full bg-[#2A3132] rounded-lg shadow-md p-6">
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-white">Judul</td>
                        <td><input type="text" name="judulfoto"
                                class="border p-2 rounded w-full focus:outline-none">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 text-white">Deskripsi</td>
                        <td><input type="text" name="deskripsifoto"
                                class="border p-2 rounded w-full focus:outline-none">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 text-white">Lokasi File</td>
                        <td>
                            <div class="flex items-center justify-center w-full">
                                <label for="lokasifile" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk unggah</span> atau seret dan lepas</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG atau GIF (MAX. 800x400px)</p>
                                    </div>
                                    <input id="lokasifile" name="lokasifile" type="file" class="hidden" />
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 text-white">Album</td>
                        <td>
                            <select name="albumid"
                                class="border p-2 rounded w-full focus:outline-none">
                                <?php
                                include "koneksi.php";
                                $userid = $_SESSION['userid'];
                                $sql = mysqli_query($conn, "select * from album where userid='$userid'");
                                while ($data = mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value="<?= $data['albumid'] ?>">
                                        <?= $data['namaalbum'] ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Tambah"
                                class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600"></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <div class="container mx-auto mt-8 p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
        include "koneksi.php";
        $userid = $_SESSION['userid'];
        $sql = mysqli_query($conn, "SELECT * FROM foto,album WHERE foto.userid='$userid' AND foto.albumid=album.albumid ORDER BY foto.tanggalunggah DESC");
        while ($data = mysqli_fetch_array($sql)) {
            ?>
            <div
                class="bg-[#2A3132] border rounded-md overflow-hidden shadow-md transform transition-transform ease-in-out hover:scale-105 relative">
                <!-- Container untuk gambar -->
                <div class="relative" style="padding-bottom: 56.25%;">
                    <!-- Padding bottom 56.25% untuk membuat rasio 16:9 -->
                    <a href="detail.php?fotoid=<?= $data['fotoid'] ?>">
                        <img src="gambar/<?= $data['lokasifile'] ?>" alt="<?= $data['judulfoto'] ?>"
                            class="absolute inset-0 w-full h-full object-cover rounded-md">
                    </a>
                </div>
                <!-- Container untuk teks -->
                <div class="p-4">
                    <div class="text-lg font-bold mb-2 text-white">
                        <?= $data['judulfoto'] ?>
                    </div>
                    <p class="text-sm text-white mb-2">
                        <?= $data['deskripsifoto'] ?>
                    </p>
                    <p class="text-sm text-red-500 mb-2">
                        <?= $data['tanggalunggah'] ?>
                    </p>
                    <p class="text-sm text-white mb-2">Album:
                        <?= $data['namaalbum'] ?>
                    </p>
                    <p class="text-sm text-white mb-2">Disukai:
                        <?php
                        $fotoid = $data['fotoid'];
                        $sql2 = mysqli_query($conn, "select * from likefoto where fotoid='$fotoid'");
                        echo mysqli_num_rows($sql2);
                        ?>
                    </p>
                    <div class="flex justify-between items-center mt-4">
                        <a href="#" onclick="confirmDelete(<?= $data['fotoid'] ?>)" class="text-red-500 hover:underline"><i
                                class="fa-solid fa-trash"></i></a>
                        <a href="edit_foto.php?fotoid=<?= $data['fotoid'] ?>" class="text-blue-500 hover:underline"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <script>
        document.getElementById("btnTambahfoto").addEventListener("click", function () {
            document.getElementById("formTambahfoto").style.display = "block";
        });

        document.getElementById("formTambahfoto").addEventListener("submit", function (event) {
            // Mengambil nilai input dari form
            var judulFoto = document.getElementsByName("judulfoto")[0].value;
            var deskripsiFoto = document.getElementsByName("deskripsifoto")[0].value;
            var lokasiFile = document.getElementsByName("lokasifile")[0].value;

            // Memeriksa apakah setiap input tidak kosong
            if (judulFoto.trim() === '' || deskripsiFoto.trim() === '' || lokasiFile.trim() === '') {
                // Mencegah formulir untuk dikirim
                event.preventDefault();
                // Menampilkan pesan kesalahan
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tolong Isi Kolom Nya :)",
                });
            }
        });

        function confirmDelete(fotoid) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan foto ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_foto.php?fotoid=' + fotoid;
                }
            })
        }
    </script>
</body>

</html>