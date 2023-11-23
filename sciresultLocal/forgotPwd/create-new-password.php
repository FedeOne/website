<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset password</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../css/style.css">
</head>
<body>
  <div class="container">
    <section class="section-default">
    
    <!--- Check the tokens --->  
    <?php
    $selector = $_GET["selector"];
    $validator = $_GET["validator"];

    if (empty($selector) || empty($validator)) {
      echo "Could not validate your request!";
    } else {
      if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false ) {
        ?>
        <form action="includes/reset-password.inc.php" method="post">
          <input type="hidden" name="selector" value="<?php echo $selector ?>">
          <input type="hidden" name="validator" value="<?php echo $validator ?>">

          <input type ="password" name="pwd" placeholder="Enter a new password" >
          <input type ="password" name="pwd-repeat" placeholder="repeat new password" >

          <button type="submit" name="reset-password-submit">Reset password</button>

        </form>

        <?php

      }
    }
    ?>

    <!--- form to reset the password --->

    </section>

  </div>

</body>
</html>