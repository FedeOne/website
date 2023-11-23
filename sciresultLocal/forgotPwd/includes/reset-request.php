<?php

if (isset($_POST["reset-request-submit"])){
  
  // Token creation , we'll have 2, 1 to authenticate that it is the correct user, the second to find the user in the database
  
  $selector = bin2hex(random_bytes(8));  // on crée un token en format byte et on le converti en hexadecimal pour le joindre dans le link envoye par e-mail
  $token = random_bytes(32);

  $url = "https://www.sciresult.com/forgotPwd/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    
  // create expiry date for token

  $expires = date("U") + 1800; //today date in seconds + 1800 seconds 

  require '../../database.php';

  $userEmail = $_POST["email"];

  // need to be sure there isnt already a token in the database for this user

  $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?";
  $stmt = mysqli_stmt_init($mysqli);
  if(!mysqli_stmt_prepare($stmt, $sql)) {
    echo "error with db connection in delete data from pwdreset";
    exit();
  } else {
    mysqli_stmt_bind_param($stmt,"s", $userEmail);
    mysqli_stmt_execute($stmt);
  }

  $sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetselector, pwdResetToken, pwdResetExpires ) VALUES (?,?,?,?); ";
  $stmt = mysqli_stmt_init($mysqli);
  if(!mysqli_stmt_prepare($stmt, $sql)) {
    echo "error with db connection in inserting data into pwdreset";
    exit();
  } else {
    // il faut hasher tous les nouveaux parametres
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt,"ssss", $userEmail, $selector, $hashedToken, $expires);
    mysqli_stmt_execute($stmt);
  }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqli); // ici le mec ne mets pas de parametre mais ça me donne erreur

    // on envois le mail

    $to = $userEmail;
    $subject = 'Reset your password for Sciresult';

    $message = 'We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email. ';
    $message .= 'Here is your password reset link: <a href="' . $url . '">' . $url . '</a>';

    $message .= "<a href=" . $url . "> . $url . </a>";


    $headers = "From: sciresult <fede@sciresult.com>\r\n";
    $headers .= "Reply-To: sciresult <fede@sciresult.com>\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n"; // Specify the character set
    
    
    // send email to the user

    mail($to, $subject, $message, $headers);
    header("Location: ../reset-password.php?reset=success");

  

  mysqli_stmt_close($stmt);


} else{
  header("Location: ../../index.php"); // a modifier
}