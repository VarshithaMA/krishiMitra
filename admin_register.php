<?php
@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $cpass = md5($_POST['cpass']);

   // Check if email already exists
   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'Admin already exists!';
   } else {
      if($pass != $cpass){
         $message[] = 'Passwords do not match!';
      } else {
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, user_type) VALUES(?, ?, ?, 'admin')");
         $insert->execute([$name, $email, $pass]);
         $message[] = 'New admin registered successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Register</title>
   <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">
   <form action="" method="POST">
      <h3>Register New Admin</h3>
      <input type="text" name="name" class="box" placeholder="Enter name" required>
      <input type="email" name="email" class="box" placeholder="Enter email" required>
      <input type="password" name="pass" class="box" placeholder="Enter password" required>
      <input type="password" name="cpass" class="box" placeholder="Confirm password" required>
      <input type="submit" value="Register Admin" class="btn" name="submit">
      <p><a href="login.php">Back to Login</a></p>
   </form>
</section>

</body>
</html>
