<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
  // connection
    $mysqli = require __DIR__ . "/database.php";
  // on check si l'email existe dans le db et on fait real_escape pour eviter les injections sql  

    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    // si l'utilisateur existe on check le password
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index-proj.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    
    <h1>Welcome to SciResults</h1>
    <h2>Organize, analyze and display your research</h2>
    <h3>Login</h3>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <button>Log in</button>
    </form>
<p>Don't have an account yet? </p>
<br/>
<a href="signup.html"> Sign up</a>

<br>
<br>

<a href="forgotPwd/reset-password.php"> Forgot your password?</a>
<?php 
if (isset($_GET["newpwd"])) {
    if ($_GET["newpwd"] == "passwordupdated"){
        echo '<p class="signupsuccess>Your password has been reset!</p>';
    }
}

?> 

</body>
</html>
