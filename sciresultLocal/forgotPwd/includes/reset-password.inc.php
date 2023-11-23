<?php

if (isset($_POST["reset-password-submit"])) {  // si existe le bouton clique reset password

  $selector = $_POST["selector"];
  $validator = $_POST["validator"];
  $password = $_POST["pwd"];
  $passwordRepeat = $_POST["pwd-repeat"];

  if (empty($password) || empty($passwordRepeat)) {  // si ces conditions ne sont pas respectees on sort
      header("Location: ../create-new-password.php?newpwd=empty");
      exit();
  } else if ($password != $passwordRepeat) {
    header("Location: ../create-new-password.php?newpwd=pwdnotsame");
    exit();
  }

  $currentDate = date("U"); // date d'ajd

  require '../../database.php';

  $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector =? AND pwdResetExpires >= ?";   // on cherche dans la table pour retrouver les inputs des nouveaux mdp
  $stmt = mysqli_stmt_init($mysqli);  // connexion
  if(!mysqli_stmt_prepare($stmt, $sql)) {  // si ca ne marche pas
    echo "error with db in getting tokens from pwdreset in reset-password.inc.php ";
    exit();
  } else {
    mysqli_stmt_bind_param($stmt,"ss", $selector,$currentDate );
    mysqli_stmt_execute($stmt);  // sinon on fait la requete

    $result = mysqli_stmt_get_result($stmt);  // et on met le resultat dans result
    if (!$row = mysqli_fetch_assoc($result)){
      echo "Can't get any data from your request, you need to re-submit the form";
      exit();
    } else {
      $tokenBin = hex2bin($validator);  // need to see if the token in the DB is same as the one sent from the form, mais il faut le transformer en binaire car dans le form etait en hex
      $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);  // ici on checke et ca donne true or false, $validator (token form) $row etc: token database

      if ($tokenCheck == false){ // on check le true false de plus haut
        echo "token check not working, You need to resubmit your request";
        exit();
      } else if ($tokenCheck == true){
        
        $tokenEmail = $row['pwdResetEmail'];  // on prend l'adresse mail de la personne en question'

        $sql = "SELECT * FROM user WHERE email=?";  // on recherche cette personne dans la table user
        $stmt = mysqli_stmt_init($mysqli);
          if(!mysqli_stmt_prepare($stmt, $sql)) {
            echo "error with db in getting your email from user table ";
            exit();
          } else {
              mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);  // et on met le resultat dans result
              if (!$row = mysqli_fetch_assoc($result)){
                echo "Cant find the sent e-mail!";
                exit();
              } else {
                
                $sql = "UPDATE user set password_hash = ? WHERE email =?";
                $stmt = mysqli_stmt_init($mysqli);
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                  echo "error with updating your pwd ";
                  exit();
                } else {
                    $newPwdHash = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                    //$newPwdHash = password_hash($password, PASSWORD_DEFAULT); // on rajoute le mot de passe hashed
                    // $newPwdHash = $password; // on rajoute le mot de passe hashed
                    mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                    mysqli_stmt_execute($stmt);

                    // delete the token

                    $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?";
                    $stmt = mysqli_stmt_init($mysqli);
                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                      echo "error with db connection in delete token from pwdreset";
                      exit();
                    } else {
                      mysqli_stmt_bind_param($stmt,"s", $tokenEmail);
                      mysqli_stmt_execute($stmt);
                      header("Location: ../../index.php?newpwd=passwordupdated"); // header a changer avec index.php
                    }


                }

              }

          } // check this parentesis
      }
    }
  }

} else{
  header("Location: ../../login.php"); // a modifier
}