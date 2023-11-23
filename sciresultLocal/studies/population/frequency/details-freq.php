<?php
// demarrer une session
session_start();

// est que l'id existe et n'est pas vide dans l'URL pas dans la base
if(isset($_GET['idStudy']) && !empty($_GET['idStudy']) 
&&(isset($_GET['idPopulation']) && !empty($_GET['idPopulation']) )
&&(isset($_GET['idProject']) && !empty($_GET['idProject']) )
&&(isset($_GET['idFreq']) && !empty($_GET['idFreq']) )
) {
    require_once('../../../connect_sql2.php');

    // se proteger de linjection sql avec function strip_tags
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idFreq = strip_tags($_GET['idFreq']);
    $sql = 'SELECT * FROM frequencies WHERE `idFreq` = :idFreq'; 

    // on prepare la requete
    $query = $db->prepare($sql);
    //on accroche le parametre id
    $query->bindValue(':idFreq', $idFreq, PDO::PARAM_INT); //PDO pour etre sur que idStudy soit de type integer

    // On exécute la requête
    $query->execute();

      // On récupère le produit
    $result = $query->fetch();

      // On vérifie si le produit existe
    if(!$result){
        $_SESSION['erreur'] = "This result doesn't exist";
        header('Location: ../../../index.php');
    }
} else{ 
  $_SESSION['erreur'] = 'URL invalide';
  header('Location: ../../../index.php');  // si condition pas respectee on renvoi a l'index
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study details</title>
  <link rel="stylesheet" href="../../../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../../../css/style.css">
</head>
<body>
  <main class="container">
        <a href="index-freq.php?idProject=<?= $idProject ?>&idStudy=<?= $idStudy ?>&idPopulation=<?= $idPopulation ?>">
            <button class="btn btt-timeline">Back to frequencies</button>
        </a>
    <div class ="row">
      <section class = "col-12">
        <h1>Frequency details </h1>
                <p><strong>ID :</strong> <?= $result['idFreq'] ?></p>

                <p><strong>Population size:</strong> <?= $result['subpopSize'] ?></p>
                <p><strong>Characterisitc 1:</strong>  <?= $result['subset1'] ?></p>
                <p><strong>Characteristic 2:</strong> <?= $result['subset2'] ?></p>
                <p><strong>Characteristic 3:</strong> <?= $result['subset3'] ?></p>

                <p><strong>Exposure :</strong> <?= $result['expFrequencyName'] ?></p>
                <p><strong>Frequency type:</strong>  <?= $result['freqType'] ?></p>
                <p><strong>Frequency measure:</strong> <?= $result['freqMeasure'] ?></p>
                <p><strong>unit :</strong> <?= $result['freqUnit'] ?></p>
                       
      </section>
    </div>
  </main>
</body>
</html>