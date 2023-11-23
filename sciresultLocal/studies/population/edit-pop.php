<?php
// On démarre une session
session_start();

// ce premier bloc sert à recuperer les données mises à jour dans le formulaire plus bas
if($_POST){
  if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  && isset($_GET['idProject']) && !empty($_GET['idProject'])){
        // On inclut la connexion à la base
        require_once('../../connect_sql2.php');

        // On nettoie les données envoyées
        $user_id= strip_tags($_SESSION["user_id"]);
        $idStudy = strip_tags($_POST['idStudy']);
        $idProject = strip_tags($_POST['idProject']);
        $idPopulation = strip_tags($_POST['idPopulation']);
        $pNam = strip_tags($_POST['pNam']);
        $popSize = strip_tags($_POST['popSize']);
        $popCountry = strip_tags($_POST['popCountry']);
        $popDisease = strip_tags($_POST['popDisease']);
        $pSecChar = strip_tags($_POST['pSecChar']);
        $inclCrit = strip_tags($_POST['inclCrit']);
        $ageUni = strip_tags($_POST['ageUni']);
        $ageMean = strip_tags($_POST['ageMean']);
        $ageSD = strip_tags($_POST['ageSD']);
        $ageLow = strip_tags($_POST['ageLow']);
        $ageUp = strip_tags($_POST['ageUp']);
        $femaleFreq = strip_tags($_POST['femaleFreq']);
       
        $sql = 'UPDATE `populations` SET `pNam`=:pNam, `popSize`=:popSize, `popCountry`=:popCountry, `popDisease`=:popDisease, `pSecChar`=:pSecChar, `inclCrit`=:inclCrit, 
        `ageUni`=:ageUni, `ageMean`=:ageMean, `ageSD`=:ageSD, `ageLow`=:ageLow, `ageUp`=:ageUp,`femaleFreq`=:femaleFreq WHERE `idStudy`=:idStudy AND `idPopulation`=:idPopulation AND `user_id`=:user_id ;';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
        //$query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':pNam', $pNam, PDO::PARAM_STR);
        $query->bindValue(':popSize', $popSize, PDO::PARAM_INT);
        $query->bindValue(':popCountry', $popCountry, PDO::PARAM_STR);
        $query->bindValue(':popDisease', $popDisease, PDO::PARAM_STR);
        $query->bindValue(':pSecChar', $pSecChar, PDO::PARAM_STR);
        $query->bindValue(':inclCrit', $inclCrit, PDO::PARAM_STR);
        $query->bindValue(':ageUni', $ageUni, PDO::PARAM_STR);
        $query->bindValue(':ageMean', $ageMean, PDO::PARAM_INT);
        $query->bindValue(':ageSD', $ageSD, PDO::PARAM_INT);
        $query->bindValue(':ageLow', $ageLow, PDO::PARAM_INT);
        $query->bindValue(':ageUp', $ageUp, PDO::PARAM_INT);
        $query->bindValue(':femaleFreq', $femaleFreq, PDO::PARAM_INT); 
        $query->execute();

        $_SESSION['message'] = "Population details have been modified";
        require_once('close.php');

        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-pop.php?$quer");
    }else{
        $_SESSION['erreur'] = "Form is incomplete";
    }
}

// ce deuxieme bloc sert a donner des valeurs pour pre-remplir le formulaire en faisant une query
// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  && isset($_GET['idProject']) && !empty($_GET['idProject'])){
    require_once('../../connect_sql2.php');

    // On nettoie l'id envoyé
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);

    $sql = 'SELECT * FROM `populations` WHERE `idPopulation` = :idPopulation;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $population = $query->fetch();

  }
/*
    // On vérifie si le produit existe
    if(!$population){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-pop.php?$quer");
    
}else{
    $_SESSION['erreur'] = "URL invalide";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-pop.php?$quer");
}
*/
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update study</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../css/style.css">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                                '. $_SESSION['erreur'].'
                            </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <h1>Edit population details</h1>
                <form method="post">

                    <div class="row">
                        <div class="form-group col">
                            <label for="pNam">Population name</label>
                            <input type="text" id="pNam" name="pNam" class="form-control"  value="<?= $population['pNam']?>">
                        </div>
                        <div class="form-group col">
                            <label for="popSize">Population size</label>
                            <input type="number" id="popSize" name="popSize" class="form-control" value="<?= $population['popSize']?>">
                        </div>
                        <div class="form-group col">
                            <label for="popCountry">Population country</label>
                            <input type="text" id="popCountry" name="popCountry" class="form-control" value="<?= $population['popCountry']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="popMainChar">Population main pathology</label>
                        <input type="text" id="popDisease" name="popDisease" class="form-control" value="<?= $population['popDisease']?>">
                    </div>
                    <div class="form-group">
                        <label for="pSecChar">Population Secondary characteristic</label>
                        <input type="text" id="pSecChar" name="pSecChar" class="form-control" value="<?= $population['pSecChar']?>">
                    </div>
                    <div class="form-group">
                        <label for="inclCrit">Inclusion criteria</label>
                        <input type="text" id="inclCrit" name="inclCrit" class="form-control" value="<?= $population['inclCrit']?>">
                    </div>

                    <div class="form-group row">
                        
                        <div class="form-group col">
                            <label for="ageMean">Age mean</label>
                            <input type="number" id="ageMean" name="ageMean" step =0.1 class="form-control" value="<?= $population['ageMean']?>">
                        </div>
                        <div class="form-group col">
                            <label for="ageSD">Age standard deviation</label>
                            <input type="number" id="ageSD" name="ageSD" step =0.1  class="form-control" value="<?= $population['ageSD']?>">
                        </div>
                        <div class="form-group col">
                            <label for="ageUni">Age unit</label>
                            <select id="ageUni" name="ageUni"  class="form-select">
                                    <option value="years" selected>years</option>
                                    <option value="months">months</option>
                                    <option value="weeks">weeks</option>
                                    <option value="days">days</option>    
                                    <option value="other">other</option> 
                                    <option selected value="<?= $population['ageUni']?>"><?= $population['ageUni']?></option> 
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="ageLow">Age min.</label>
                            <input type="number" id="ageLow" name="ageLow" step =0.1  class="form-control" value="<?= $population['ageLow']?>">
                        </div>
                        <div class="form-group col">
                            <label for="ageUp">Age max.</label>
                            <input type="number" id="ageUp" name="ageUp" step =0.1  class="form-control" value="<?= $population['ageUp']?>">
                        </div>

                        <div class="form-group col">
                            <label for="femaleFreq">Female sex frequency (%)</label>
                            <input type="number" id="femaleFreq" name="femaleFreq" step =0.1  class="form-control" value="<?= $population['femaleFreq']?>">
                        </div>
                    </div>
                    <input type="hidden" value="<?= $population['idStudy']?>" name="idStudy">
                    <input type="hidden" value="<?= $population['idProject']?>" name="idProject">
                    <input type="hidden" value="<?= $population['idPopulation']?>" name="idPopulation">
                    <button class="btn btn-primary">Send</button>
                </form>
            </section>
        </div>
    </main>
   
</body>
</html> 

