<?php
include('includes/database.php');
include('includes/form_validation.php');
session_start();

$email = $username = $password = $confirm_password = '';
$errors = array();
$successful = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  $errors["email"] = check_email($email);
  $errors["username"] = check_username($username);
  $errors["password"] = check_password($password);
  $errors["confirm_password"] = check_confirm_password($password, $confirm_password);

  foreach ($errors as $key => $value) {
    if ($value == null) {
      unset($errors[$key]);
    }
  }

  if (empty($errors)) {
    $params = array(
      "email" => filter_var($email, FILTER_SANITIZE_EMAIL),
      "username" => htmlspecialchars($username),
      "password" => md5($password),
    );

    try {
      $req = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
      $req->bindParam(':username', $params["username"]);
      $req->bindParam(':email', $params["email"]);
      $req->execute();
      $user = $req->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        $errors["email"] = verify_email_equality($params["email"], $user["email"]);
        $errors["username"] = verify_username_equality($params["username"], $user["username"]);
      } else {
        $q = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $q->bindParam(':username', $params["username"]);
        $q->bindParam(':email', $params["email"]);
        $q->bindParam(':password', $params["password"]);

        $q->execute();

        $successful = true;
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
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
  <title>Sign Up</title>
</head>

<body class="bg-gray-900 font-['Inter']">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
    <div class="w-full bg-white rounded-lg shadow border sm:max-w-md bg-gray-800 border-gray-700">
      <div class="p-8 space-y-4">
        <h1 class="font-bold text-2xl text-white">
          Register an account
        </h1>
        <form class="flex flex-col gap-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div>
            <label for="email" class="block mb-2 text-md font-medium text-white">Email</label>
            <input type="text" name="email" id="email" value="<? echo $email ?>" maxlength="255"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="example@becode.be">
            <?php
            if (isset($errors['email'])) {
              echo "<p class='mt-2 text-sm text-red-500'>" . $errors['email'] . "</p>";
            }
            ?>
          </div>
          <div>
            <label for="username" class="block mb-2 text-md font-medium text-white">Username</label>
            <input type="text" name="username" id="username" value="<? echo $username ?>" maxlength="255"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="swartz7">
            <?php
            if (isset($errors['username'])) {
              echo "<p class='mt-2 text-sm text-red-500'>" . $errors['username'] . "</p>";
            }
            ?>
          </div>
          <div>
            <label for="password" class="block mb-2 text-md font-medium text-white">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" value="<? echo $password ?>"
              maxlength="255"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              required="">
            <?php
            if (isset($errors['password'])) {
              echo "<p class='mt-2 text-sm text-red-500'>" . $errors['password'] . "</p>";
            }
            ?>
          </div>
          <div>
            <label for="confirm_password" class="block mb-2 text-md font-medium text-white">Confirm password</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••"
              value="<? echo $confirm_password ?>" maxlength="255"
              class="border rounded-lg block w-full px-4 py-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
              required="">
            <?php
            if (isset($errors['confirm_password'])) {
              echo "<p class='mt-2 text-sm text-red-500'>" . $errors['confirm_password'] . "</p>";
            }
            ?>
          </div>
          <button type="submit"
            class="w-full mt-2 text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-md px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
            Sign up
          </button>

          <?php if ($successful) { ?>
            <p class="text-md font-medium text-green-500">
              Successfully registered!
              <a href="/index.php" class="text-blue-500 underline">Login</a>
            </p>
          <?php } else { ?>
            <p class="text-sm font-medium text-gray-400">
              Do you already have an account?
              <a href="/index.php" class="font-medium text-blue-500 underline">Login</a>
            </p>
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
</body>

</html>