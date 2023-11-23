<?php
// demarrer une session
session_start();

// est que l'id existe et n'est pas vide dans l'URL pas dans la base
if(isset($_GET['idStudy']) && !empty($_GET['idStudy']) ){
    require_once('../connect_sql2.php');

    // se proteger de linjection sql avec function strip_tags
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $sql = 'SELECT * FROM study WHERE `idStudy` = :idStudy'; 

    // on prepare la requete
    $query = $db->prepare($sql);
    //on accroche le parametre id
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT); //PDO pour etre sur que idStudy soit de type integer

    // On exécute la requête
    $query->execute();

      // On récupère le produit
    $article = $query->fetch();

      // On vérifie si le produit existe
    if(!$article){
        $_SESSION['erreur'] = "This study doesn't exist";
        header('Location: ../index-proj.php');
    }
} else{ 
  $_SESSION['erreur'] = 'URL invalide';
  header('Location: ../index-proj.php');  // si condition pas respectee on renvoi a l'index
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study details</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../css/style.css">
</head>
<body>
  <main class="container">
        <a href="index-study-test.php?idProject=<?= $idProject ?>">
            <button class="btn btt-timeline">Back to studies</button>
        </a>
    <div class ="row">
      <section class = "col-12">
        <h1>Study details </h1>
                <p><strong>ID study :</strong> <?= $article['idStudy'] ?></p>
                <p><strong>Title :</strong> <?= $article['title'] ?></p>
                <p><strong>Author :</strong>  <?= $article['author'] ?></p>
                <p><strong>Year of publication :</strong> <?= $article['yearPublication'] ?></p>
                <p><strong>Journal :</strong> <?= $article['journal'] ?></p>
                <p><strong>Study plan :</strong> <?= $article['studyPlan'] ?></p>
                <p><strong>Monocentric / multicentric :</strong> <?= $article['centric'] ?></p>
                <p><strong>Starting year of folloz up :</strong> <?= $article['startYear'] ?></p>
                <p><strong>End of follow up :</strong> <?= $article['endYear'] ?></p>
                <p><strong>Objectives :</strong> <?= $article['objective'] ?></p>
                <p><strong>Discussion :</strong> <?= $article['discussion'] ?></p>
                <p><strong>Abstract :</strong> <?= $article['ABSTRACT'] ?></p>
               
      </section>
    </div>
  </main>
</body>
</html>