<?php
// On démarre une session
session_start();

require_once('../../connect_sql2.php');
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    
    $sql = 'SELECT * FROM `results` WHERE  `idPopulation` = :idPopulation;';
    $query = $db->prepare($sql);
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
    // $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetch();

    if(isset($results['idResult']) ){
        echo "You have results associated with this population, first delete them";
        //header('Location: ../index.php');
        die();
    } else

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
&& isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
&& isset($_GET['idProject']) && !empty($_GET['idProject'])){
    require_once('../../connect_sql2.php');

    // On nettoie l'id envoyé
    $user_id= strip_tags($_SESSION["user_id"]);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idStudy = strip_tags($_GET['idStudy']);


    
    $sql = 'SELECT * FROM `populations` WHERE `idStudy` = :idStudy AND `idPopulation` = :idPopulation ;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $population = $query->fetch();

    // On vérifie si le produit existe
    if(!$population){
        $_SESSION['erreur'] = "Cette population n'existe pas";

        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-pop.php?$quer");
        
        die();
    }

    $sql = 'DELETE FROM `populations` WHERE `idStudy` = :idStudy AND `idPopulation` = :idPopulation AND `user_id`=:user_id ;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);    

    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Population deleted";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-pop.php?$quer");


}else{
    $_SESSION['erreur'] = "Invalid URL";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-pop.php?$quer");
}

