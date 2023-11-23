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
      <h1>Reset your password</h1>
      <p>An e-mail will be sent to you with instruction on how to reset your password</p>
      <form action="includes/reset-request.php" method="post">
        <input type="text" name="email" placeholder="Enter your e-mail address...">
        <button type="submit" name="reset-request-submit">send</button>

      </form>
      <?php
      if (isset($_GET["reset"])) {
        if ($_GET["reset"] == "success") {
          echo '<p class ="signupsuccess">Check your e-mail! </p>';
        }
      }
      ?>
    </section>

  </div>

</body>
</html>