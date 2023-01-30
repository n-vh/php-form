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

?>