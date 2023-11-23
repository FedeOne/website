<?php
// demarrer une session
session_start();

// est que l'id existe et n'est pas vide dans l'URL pas dans la base
if(isset($_GET['idStudy']) && !empty($_GET['idStudy']) 
&&(isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])) ){
    require_once('../../connect_sql2.php');

    // se proteger de linjection sql avec function strip_tags
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $sql = 'SELECT * FROM populations WHERE `idPopulation` = :idPopulation'; 

    // on prepare la requete
    $query = $db->prepare($sql);
    //on accroche le parametre id
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT); //PDO pour etre sur que idStudy soit de type integer

    // On exécute la requête
    $query->execute();

      // On récupère le produit
    $population = $query->fetch();

      // On vérifie si le produit existe
    if(!$population){
        $_SESSION['erreur'] = "This population doesn't exist";
        header('Location: ../../index-proj.php');
    }
} else{ 
  $_SESSION['erreur'] = 'URL invalide';
  header('Location: ../../index-proj.php');  // si condition pas respectee on renvoi a l'index
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study details</title>
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../../css/style.css">
</head>
<body>
  <main class="container">
        <a href="index-pop.php?idProject=<?= $idProject ?>&idStudy=<?= $idStudy ?>&idPopulation=<?= $idPopulation ?>">
            <button class="btn btt-timeline">Back to populations</button>
        </a>
    <div class ="row">
      <section class = "col-12">
        <h1>Population details </h1>
                <p><strong>ID :</strong> <?= $population['idPopulation'] ?></p>
                <p><strong>Population name :</strong> <?= $population['pNam'] ?></p>
                <p><strong>Population size :</strong>  <?= $population['popSize'] ?></p>
                <p><strong>Population country:</strong> <?= $population['popCountry'] ?></p>
                <p><strong>Population main pathology :</strong> <?= $population['popDisease'] ?></p>
                <p><strong>Population secondary characteristic :</strong> <?= $population['pSecChar'] ?></p>
                <p><strong>Inclusion criteria :</strong> <?= $population['inclCrit'] ?></p>
                <p><strong>Age unit :</strong> <?= $population['ageUni'] ?></p>
                <p><strong>Age mean :</strong> <?= $population['ageMean'] ?></p>
                <p><strong>Age standard deviation :</strong> <?= $population['ageSD'] ?></p>
                <p><strong>% of females:</strong> <?= $population['femaleFreq'] ?></p>
               
      </section>
    </div>
  </main>
</body>
</html>