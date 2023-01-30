<?php
include('includes/database.php');
session_start();

$username = $password = '';
$incorrect_input = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $params = array(
    "username" => trim(htmlspecialchars($username)),
    "password" => md5($password),
  );

  try {
    $req = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $req->bindParam(':username', $params["username"]);
    $req->bindParam(':password', $params["password"]);
    $req->execute();
    $user = $req->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $_SESSION['user'] = $user;
      header("Location: home.php");
      exit;
    } else {
      $incorrect_input = true;
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login</title>
</head>

<body class="bg-gray-900 font-['Inter']">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
    <div class="w-full bg-white rounded-lg shadow border sm:max-w-md bg-gray-800 border-gray-700">
      <div class="p-8 space-y-4">
        <h1 class="font-bold text-2xl text-white">
          Log in
        </h1>
        <form class="flex flex-col gap-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div>
            <label for="username" class="block mb-2 text-md font-medium text-white">Username</label>
            <input type="text" name="username" id="username" value="<? echo $username ?>"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              required="">
          </div>
          <div>
            <label for="password" class="block mb-2 text-md font-medium text-white">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" value="<? echo $password ?>"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              required="">
          </div>

          <?
            if ($incorrect_input) {
              echo "<p class='text-sm font-medium text-red-500'>Incorrect username or password.</p>";
            }
          ?>

          <button type="submit"
            class="w-full text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-md px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
            Login
          </button>
          <p class="text-sm font-medium text-gray-400">
            Don't have an account yet?
            <a href="/signup.php" class="font-medium text-blue-500 underline">Sign up</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</body>

</html>