<?php

session_start();

// recuperation ID_User
if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

require_once('connect_sql2.php');
$sql2 = "SELECT * FROM project
WHERE user_id = {$_SESSION["user_id"]}";;
// on prepare la requette, db est la variable de connexion, la fleche je pense c est comme le pipe
$query2 = $db->prepare($sql2);
//execute la requete
$query2->execute();
//stocke les resultats dans un tableau associatif
$result2 = $query2->fetchAll(PDO::FETCH_ASSOC);  //fetch assoc recupère juste les entetes de colonne

?>
<!DOCTYPE html>
<html>
<head>
 
    <title class>Home</title>   
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet"   href="css/style.css">
</head>
<body >
    <div class="home"><h1 >Home</h1> </div>
    <div class="logout"> <p ><a href="logout.php">Log out</a></p> </div>
  
    
    <?php if (isset($user)): ?>
        
        <p class="container">Hello <?= htmlspecialchars($user["name"]) ?></p>
        <p class="container">Your user ID is number: <?= htmlspecialchars($user["id"]) ?></p>
        

      <main class="container">  
      <div class="row">
      <section class="col-12">
      <h5 class="container">Create your first project</h5>
        <table class="table">
          <thead>
            <th>Add Studies</th>
            <th>Project id</th>
            <th>Project Title</th>
            <th>Comments</th>
            <th>action</th>
          </thead>
          <tbody>
            <?php
            // on boucle sur la variable result
            foreach($result2 as $project){
              ?>
              <tr>
                <td> <a href= "studies/index-study-test.php?idProject=<?=$project['idProject'] ?>"> Add studies </a></td>
                <td><?=$project['idProject'] ?></td>  <!-- le signe = est synonime d'echo -->
                <td><?=$project['projectTitle'] ?></td>
                <td><?=$project['projectComment'] ?></td>
                <td> <a href="project/edit-project.php?idProject=<?= $project['idProject'] ?>">Edit</a>
                  <a style="color: red;" href="project/delete-project.php?idProject=<?= $project['idProject'] ?>">Delete</a></td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <div class="container">
        <a href="project/add-project.php" class="btn btn-primary" style="margin-top: 40px;">Add a project</a>
        </div>
      </section>
      </div>
    </main>
        

    <?php else: ?>
        
        <p><a href="index.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>

    <br>
    <h2 class="container">Check the apps:</h2>
    <h3 class="container">Results from AI model</h3>

    <div class="container">
      <a href="https://2adpb6-federico-sirna.shinyapps.io/forestPlotFromAI/" class="btn btn-success" style="margin-top: 40px; margin-left: 20px;" target="_blank">Forest plot from AI model</a>
      <a href="https://2adpb6-federico-sirna.shinyapps.io/riskFactors/" class=" container btn btn-success" style="margin-top: 40px; margin-left: 20px;" target="_blank">Risk factors from model</a>
      <a href="https://2adpb6-federico-sirna.shinyapps.io/frequency/" class=" container btn btn-success" style="margin-top: 40px; margin-left: 20px;" target="_blank">Prevalence or incidence from model</a>
      <br>
      <br>
      <h3 class="container">Results from your profile</h3>
      <a href="https://2adpb6-federico-sirna.shinyapps.io/forestPlotAppUser/" class=" container btn btn-success" style="margin-top: 40px; margin-left: 20px; background-color: orange;" target="_blank">Your Forest plot</a>
      <a href="https://2adpb6-federico-sirna.shinyapps.io/2_frequencyAppUser/" class=" container btn btn-success" style="margin-top: 40px; margin-left: 20px; background-color: orange;" target="_blank">Your Frequencies</a>
      <br>
      <br>
    </div>
    <!---
    <div class="container-fluid">
      <iframe class="mw-100" height="600" width="100%" frameborder="no" src="https://2adpb6-federico-sirna.shinyapps.io/Sci_results/"> </iframe>
    </div>
    --->
</body>
</html>