<?php
include('database.php');
session_start();

$incorrect_input = false;
$username = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  try {
    $req = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $req->bindParam(':username', $username);
    $req->bindParam(':password', $password);
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
  <title>Login</title>
</head>

<body>
  <h1>
    Log in
  </h1>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
      <label for="username">Username</label>
      <input type="username" name="username" id="username" value="<? echo $username ?>" required="">
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="••••••••" value="<? echo $password ?>"
        required="">
    </div>

    <?
      if ($incorrect_input) {
        echo "<p>Incorrect username or password.</p>";
      }
    ?>

    <button type="submit">
      Login
    </button>
    <p>
      Don't have an account yet?
      <a href="#">Sign Up</a>
    </p>
  </form>
</body>

</html>