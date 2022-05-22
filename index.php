<?php
require 'config/db.php';

$msg = "";
$firstName = "";
$lastName = "";
$email = "";
$password = "";

# SIGNUP
if (isset($_POST['signup'])) {
  // Get last state
  $firstName = isset($_POST['firstName']) ? mysqli_real_escape_string($conn, htmlentities($_POST['firstName'])) : "";
  $lastName = isset($_POST['lastName']) ? mysqli_real_escape_string($conn, htmlentities($_POST['lastName'])) : "";
  $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, htmlentities($_POST['email'])) : "";
  $gender = $_POST['gender'] !== "Null" ? mysqli_real_escape_string($conn, htmlentities($_POST['gender'])) : "";
  $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, htmlentities($_POST['password'])) : "";

  # VALIDATION
  if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password) && !empty($gender)) {
    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName) || !preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
      $msg = "Please enter a valid name.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg = "Please enter a valid email.";
    }

    // Check for agreement
    else if (!isset($_POST['agree'])) {
      $msg = "You have to agree to our terms.";
    }

    // Everything is valid
    else {
      $token = hash("sha512", $email . ',' . $password);
      $query = "INSERT INTO users(first_name, last_name, email, token, gender) VALUES('$firstName', '$lastName', '$email', '$token', '$gender')";

      if (mysqli_query($conn, $query)) {
        $msg = 'Signed up successfully!';
        $firstName = "";
        $lastName = "";
        $email = "";
        $password = "";
      } else {
        $msg = 'ERROR: ' . mysqli_error($conn);
      }
    }
  } else {
    $msg = "Please fill all the fields.";
  }
}

# LOGIN
else if (isset($_POST['login'])) {
  $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, htmlentities($_POST['email'])) : "";
  $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, htmlentities($_POST['password'])) : "";

  if (!empty($email) && !empty($password)) {
    $token = hash("sha512", $email . ',' . $password);
    $query = "SELECT * FROM users WHERE token='$token'";

    $result = mysqli_query($conn, $query);

    if(isset($result)){
      $user = mysqli_fetch_assoc($result);
      print_r($user);
    } else {
      $msg = "Invalid credintials";
    }
    mysqli_free_result($result);
    mysqli_close($conn);
  } else {
    $msg = 'Please fill all the fields.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <header>
    <h1 id="header">Register</h1>
  </header>
  <div class="box">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <div id="error">
        <h3><?php echo $msg ?></h3>
      </div>
      <div id="name_div">
        <div class="name_field">
          <label>First Name</label>
          <input type="text" name="firstName" class="name_input" value="<?php echo $firstName ?>" />
        </div>
        <div class="name_field" id="last_name">
          <label>Last Name</label>
          <input type="text" name="lastName" class="name_input" value="<?php echo $lastName ?>" />
        </div>
      </div>
      <br />
      <div class="field">
        <label>Email</label>
        <input type="text" name="email" class="text_input" value="<?php echo $email ?>" />
      </div>
      <br />
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" class="text_input" value="<?php echo $password ?>" />
      </div>
      <br />
      <div id="gender-div" class="field">
        <label>Gender</label>
        <select id="gender" name="gender">
          <option value="Null">Please Select</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>
      <br />
      <div id="agree-div" class="check_box">
        <input type="checkbox" id="agree" name="agree" />
        <label>I agree to the terms and conditions</label>
      </div>
      <br />
      <input type="submit" name="signup" value="Sign up" id="submit" />
    </form>
    <br />
    <br />
    <p id="switch" style="cursor: context-menu">
      Already have an account?<br />
      <span id="login_instead" style="
            color: rgb(72, 72, 206);
            text-decoration: underline;
            cursor: pointer;
          ">Login instead.</span>
    </p>
  </div>
  <script src="main.js"></script>
</body>

</html>