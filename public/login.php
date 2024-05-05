<?php
// Inisialisasi session
session_start();

// Cek apakah pengguna sudah login, jika sudah, redirect ke halaman utama
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include file koneksi database
require_once "connection.php";

// Inisialisasi variabel
$email = $password = "";
$email_err = $password_err = "";

// Memproses data yang dikirim dari form saat submit
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validasi email
    if(empty(trim($_POST["email"]))){
        $email_err = "Masukkan email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validasi password
    if(empty(trim($_POST["password"]))){
        $password_err = "Masukkan password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Cek apakah email dan password valid
    if(empty($email_err) && empty($password_err)){
        // Persiapkan query
        $sql = "SELECT id, email, password FROM siswa WHERE email = ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){
            // Bind variabel parameter ke pernyataan persiapan sebagai parameter
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameter
            $param_email = $email;
            
            // Mencoba mengeksekusi pernyataan yang telah disiapkan
            if(mysqli_stmt_execute($stmt)){
                // Menyimpan hasil
                mysqli_stmt_store_result($stmt);
                
                // Memeriksa apakah email ada, jika ya, verifikasi password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind hasil dari kolom password
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password cocok, mulai sesi baru
                            session_start();
                            
                            // Simpan data dalam variabel sesi
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirect ke halaman welcome
                            header("location: welcome.php");
                        } else{
                            // Menampilkan pesan error jika password tidak valid
                            $password_err = "Password yang Anda masukkan tidak valid.";
                        }
                    }
                } else{
                    // Menampilkan pesan error jika email tidak valid
                    $email_err = "Tidak ada akun yang terdaftar dengan email tersebut.";
                }
            } else{
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
        }
        
        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    }
    
    // Tutup koneksi
    mysqli_close($connect);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error { color: red; }
    </style>
</head>
<body class="bg-gray-200 font-sans h-screen flex justify-center items-center">
    <div class="w-full max-w-lg mt-2 mx-4 mb-4">
        <h1 class="text-center text-4xl font-bold text-gray-900 mb-6">
            Login
        </h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded-xl px-8 pt-6 pb-10 mb-4">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
