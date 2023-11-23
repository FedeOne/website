<?php
// demarrer une session
session_start();

// est que l'id existe et n'est pas vide dans l'URL pas dans la base
if(isset($_GET['idStudy']) && !empty($_GET['idStudy']) 
&&(isset($_GET['idPopulation']) && !empty($_GET['idPopulation']) )
&&(isset($_GET['idProject']) && !empty($_GET['idProject']) )
&&(isset($_GET['idResult']) && !empty($_GET['idResult']) )
) {
    require_once('../../../connect_sql2.php');

    // se proteger de linjection sql avec function strip_tags
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idResult = strip_tags($_GET['idResult']);
    $sql = 'SELECT * FROM results WHERE `idResult` = :idResult'; 

    // on prepare la requete
    $query = $db->prepare($sql);
    //on accroche le parametre id
    $query->bindValue(':idResult', $idResult, PDO::PARAM_INT); //PDO pour etre sur que idStudy soit de type integer

    // On exécute la requête
    $query->execute();

      // On récupère le produit
    $result = $query->fetch();

      // On vérifie si le produit existe
    if(!$result){
        $_SESSION['erreur'] = "This result doesn't exist";
        header('Location: ../../../index-proj.php');
    }
} else{ 
  $_SESSION['erreur'] = 'URL invalide';
  header('Location: ../../../index-proj.php');  // si condition pas respectee on renvoi a l'index
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
        <a href="index-results.php?idProject=<?= $idProject ?>&idStudy=<?= $idStudy ?>&idPopulation=<?= $idPopulation ?>">
            <button class="btn btt-timeline">Back to results</button>
        </a>
    <div class ="row">
      <section class = "col-12">
        <h1>Result details </h1>
                <p><strong>ID :</strong> <?= $result['idResult'] ?></p>
                <p><strong>Exposure :</strong> <?= $result['exp1'] ?></p>
                <p><strong>Exposure type :</strong> <?= $result['expType'] ?></p>
                <p><strong>Lower treshold :</strong>  <?= $result['expLow1'] ?></p>
                <p><strong>Upper treshold:</strong> <?= $result['expHigh1'] ?></p>
                <p><strong>Exposure unit :</strong> <?= $result['expUnit1'] ?></p>
                <p><strong>Reference :</strong> <?= $result['refExp1'] ?></p>
                <p><strong>Outcome :</strong> <?= $result['outcome'] ?></p>
                <p><strong>Result :</strong> <?= $result['result'] ?></p>
                <p><strong>Lower confint :</strong> <?= $result['icLow'] ?></p>
                <p><strong>Upper confint :</strong> <?= $result['icUpper'] ?></p>
                <p><strong>Measure type:</strong> <?= $result['measureType'] ?></p>
                <p><strong>Subgroup :</strong> <?= $result['exp2'] ?></p>
                <p><strong>Adjustment variables:</strong> <?= $result['adjustment'] ?></p>
               
      </section>
    </div>
  </main>
</body>
</html>