<?php
// On démarre une session
session_start();

// ce premier bloc sert à recuperer les données mises à jour dans le formulaire plus bas
if($_POST){
  if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  && isset($_GET['idProject']) && !empty($_GET['idProject'])
  && isset($_GET['idFreq']) && !empty($_GET['idFreq'])){
        // On inclut la connexion à la base
        require_once('../../../connect_sql2.php');

        // On nettoie les données envoyées
        $user_id= strip_tags($_SESSION["user_id"]);
        $idStudy = strip_tags($_POST['idStudy']);
        $idProject = strip_tags($_POST['idProject']);
        $idPopulation = strip_tags($_POST['idPopulation']);
        $idFreq = strip_tags($_POST['idFreq']);
        
        $expFrequencyName = strip_tags($_POST['expFrequencyName']);
        $freqType = strip_tags($_POST['freqType']);
        $freqMeasure = strip_tags($_POST['freqMeasure']);
        $freqUnit = strip_tags($_POST['freqUnit']);

        $subpopSize = strip_tags($_POST['subpopSize']);
        $subset1 = strip_tags($_POST['subset1']);
        $subset2 = strip_tags($_POST['subset2']);
        $subset3 = strip_tags($_POST['subset3']);

      
        $sql = 'UPDATE `frequencies` SET `subpopSize`= :subpopSize, `subset1`=:subset1, `subset2`=:subset2, `subset3`=:subset3,
        `expFrequencyName`=:expFrequencyName, `freqType`=:freqType, `freqMeasure`=:freqMeasure, `freqUnit`=:freqUnit WHERE 
        `idStudy`=:idStudy AND `idPopulation`=:idPopulation AND `idFreq`=:idFreq AND `user_id`=:user_id ;';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
        //$query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':idFreq', $idFreq, PDO::PARAM_INT);

        $query->bindValue(':expFrequencyName', $expFrequencyName, PDO::PARAM_STR);
        $query->bindValue(':freqType', $freqType, PDO::PARAM_STR);
        $query->bindValue(':freqMeasure', $freqMeasure, PDO::PARAM_STR);
        $query->bindValue(':freqUnit', $freqUnit, PDO::PARAM_STR);

        $query->bindValue(':subpopSize', $subpopSize, PDO::PARAM_STR);
        $query->bindValue(':subset1', $subset1, PDO::PARAM_STR);
        $query->bindValue(':subset2', $subset2, PDO::PARAM_STR);
        $query->bindValue(':subset3', $subset3, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Frequency details have been modified";
        require_once('close.php');

        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          'idFreq' => $idFreq,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-freq.php?$quer");
    }else{
        $_SESSION['erreur'] = "Form is incomplete";
    }
}

// ce deuxieme bloc sert a donner des valeurs pour pre-remplir le formulaire en faisant une query
// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  // && isset($_GET['idProject']) && !empty($_GET['idProject'])
  && isset($_GET['idFreq']) && !empty($_GET['idFreq'])){
    require_once('../../../connect_sql2.php');

    // On nettoie l'id envoyé
    $idStudy = strip_tags($_GET['idStudy']);
   // $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idFreq = strip_tags($_GET['idFreq']);

    $sql = 'SELECT * FROM `frequencies` WHERE `idFreq` = :idFreq;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idFreq', $idFreq, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

   
    // On récupère le produit
    $freqs = $query->fetch();

  }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update frequencies</title>

    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../../css/style.css">
    <!--- <script type = "text/javascript" src="jsRes/functionRes.js"> </script> --->
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
                <h1>Update frequency</h1>
                <form method="post">
                
                
                    
                <div class="form-group row">
                        <div class="form-group col">
                            <label for="expFrequencyName">Exposure name</label>
                            <input type="text" id="expFrequencyName" name="expFrequencyName" class="form-control" placeholder="ex: BMI > 25 kg/m2" value="<?= $freqs['expFrequencyName']?>">
                        </div>
                    <div class="form-group col">
                        <label for="freqType">Frequency type</label>
                        <select id="freqType" name ="freqType" class="form-select" >
                            <option value="prevalence"> prevalence</option>
                            <option value="incidence"> incidence</option>
                            <option value="<?= $freqs['freqType']?>" > <?= $freqs['freqMeasure']?></option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="freqMeasure">Frequency measure</label>
                        <input type="number" step =0.01 id="freqMeasure" name="freqMeasure" class="form-control" min =0 value="<?= $freqs['freqMeasure']?>">
                    </div>
                    <div class="form-group col">
                        <label for="frequnit">Unit</label>
                        <input type="text" id="freqUnit" name="freqUnit" class="form-control" placeholder="ex: %" value="<?= $freqs['freqUnit']?>">
                    </div>
                    <input type="hidden" value="<?= $freqs['idStudy']?>" name="idStudy">
                    <input type="hidden" value="<?= $freqs['idProject']?>" name="idProject">
                    <input type="hidden" value="<?= $freqs['idPopulation']?>" name="idPopulation">
                    <input type="hidden" value="<?= $freqs['idFreq']?>" name="idFreq">
                </div>
                
                    <br>
                    <br>
                
                    
                <div class="form-group row">

                    <!-- <div class="form-group col">
                        <label for="subpopSize">Population size</label>
                        <input type="number" min = 0 id="subpopSize" name="subpopSize" class="form-control"  value="<?= $freqs['subpopSize']?>">
                    </div> -->
                    <div class="form-group col">
                        <label for="subset1">Subset 1</label>
                        <input type="text" id="subset1" name="subset1" class="form-control" value="<?= $freqs['subset1']?>">
                    </div>
                    <div class="form-group col">
                        <label for="subset2">Subset 2</label>
                        <input type="text" id="subset2" name="subset2" class="form-control" value="<?= $freqs['subset2']?>">
                    </div>
                    <div class="form-group col">
                        <label for="subset3">Subset 3</label>
                        <input type="text" id="subset3" name="subset3" class="form-control" value="<?= $freqs['subset3']?>">
                    </div>
                </div>
                  
                    <button class="btn btn-primary" style="margin-top: 20px;">Update</button>
                </form>
            </section>
        
    </main>
   
</body>
</html> 

