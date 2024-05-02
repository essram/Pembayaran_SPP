<?php 

if($_POST){
    //data
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];

    include 'connection.php';

    //check register data
    $sqlCheck =  "select * from siswa where nis = '".$nis."' or email = '".$email."'";
    // echo $sqlCheck;
    $resultCheck = mysqli_query($connect, $sqlCheck);
    if(mysqli_num_rows($resultCheck) > 0){
        echo"<script>alert('user telah terdaftar'); location.href = 'login.php'</script>";
    } else {
        $sqlInsert = "insert into siswa (nis,nama, email, password) values ('".$nis."','".$nama."','".$email."','".md5($_POST['pwd'])."')";
        echo $sqlInsert;

        $resultInsert = mysqli_query($connect, $sqlInsert);

        if($resultInsert){
            echo"<script>alert('register berhasil, silahkan login'); location.href = 'login.php'</script>";
        } else {
            echo "Error: ".$sql ."<br>".$connect->error;
        }
    }
}

?>









<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-200 font-sans h-screen flex justify-center items-center">
    <div class="w-full max-w-lg mt-2 mx-4 mb-4">
      <h1 class="text-center text-4xl font-bold text-gray-900 mb-6">
        Register
      </h1>
      <form
        action=""
        method="post"
        class="bg-white shadow-md rounded-xl px-8 pt-6 pb-10 mb-4"
      >
        <div class="mb-4">
          <label for="nis" class="block text-gray-700 text-sm font-bold mb-2"
            >NIS</label
          >
          <input
            type="text"
            name="nis"
            id="nis"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-4">
          <label for="nama" class="block text-gray-700 text-sm font-bold mb-2"
            >Nama</label
          >
          <input
            type="text"
            name="nama"
            id="nama"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2"
            >Email</label
          >
          <input
            type="email"
            name="email"
            id="email"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-6">
          <label
            for="pwd"
            class="block text-gray-700 text-sm font-bold mb-2"
            >Password</label
          >
          <input
            type="password"
            name="pwd"
            id="pwd"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="flex items-center justify-between">
          <button
            type="submit"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
          >
            Register
          </button>
        </div>
      </form>
    </div>
  </body>
</html>
