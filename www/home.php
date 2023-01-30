<?php
include('database.php');
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
} else {
  $user = $_SESSION['user'];
}

if (isset($_POST['signout'])) {
  session_destroy();
  header("Refresh:0; url=index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Home</title>
</head>

<body class="bg-gray-900 font-['Inter']">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
    <div class="w-full bg-white rounded-lg shadow border sm:max-w-md bg-gray-800 border-gray-700">
      <form method="post">
        <div class="px-12 py-10 space-y-4">
          <p class="font-bold text-2xl text-white">
            Welcome home,
            <? echo "<span class='underline'>" . $user["username"] . "</span>"; ?>!
          </p>
          <button type="submit" name="signout"
            class="w-full mt-2 text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-md px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
            Sign out
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>