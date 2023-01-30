<?php

function check_email($email)
{
  $email = trim($email);

  if (empty($email)) {
    return "Email was left blank.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Email is not valid.";
  }

  return null;
}

function check_username($username)
{
  $username = trim($username);

  if (empty($username)) {
    return "Username was left blank.";
  } elseif (strlen($username) < 4) {
    return "Username is too short.";
  } elseif (strlen($username) > 26) {
    return "Username is too long.";
  } elseif (!preg_match('/^[a-zA-Z0-9]*$/', $username)) {
    return "Username must only contain letters and numbers.";
  }

  return null;
}

function check_password($password)
{
  if (empty($password)) {
    return "Password was left blank.";
  } elseif (strlen($password) < 8) {
    return "Password must be 8 characters or longer.";
  }

  return null;
}

function check_confirm_password($password, $confirm_password)
{
  if ($password !== $confirm_password) {
    return "Passwords do not match.";
  }

  return null;
}

function verify_email_equality($email, $server_email)
{
  if ($email == $server_email) {
    return "This email is already used.";
  }
}

function verify_username_equality($username, $server_username)
{
  if ($username == $server_username) {
    return "This username is unavailable.";
  }
}

?>