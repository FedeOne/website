<?php 
session_start();

// on prend userID de la session en theorie pas d'inquietude
$user_id = $_SESSION['user_id'];
$idPopulation = strip_tags($_GET['idPopulation']);
$idStudy = strip_tags($_GET['idStudy']);
$idProject = strip_tags($_GET['idProject']);


if($_POST){
    if(isset($_POST['expFrequencyName']) && !empty($_POST['expFrequencyName'])
    ){
        // On inclut la connexion à la base
        require_once('../../../connect_sql2.php');

        // On nettoie les données envoyées par le formulaire de ce script
        $subpopSize = strip_tags($_POST['subpopSize']);
        $freqType = strip_tags($_POST['freqType']);
        $freqMeasure = strip_tags($_POST['freqMeasure']);
        $freqUnit = strip_tags($_POST['freqUnit']);
        
        $expFrequencyName = strip_tags($_POST['expFrequencyName']);
        $subset1 = strip_tags($_POST['subset1']);
        $subset2 = strip_tags($_POST['subset2']);
        $subset3 = strip_tags($_POST['subset3']);
      

             $sql = 'INSERT INTO `frequencies` (`idPopulation`, `user_id`, `idProject`,`idStudy`,`subpopSize`,`subset1`, `subset2`, `subset3`,
             `expFrequencyName`,`freqType` ,`freqMeasure`,`freqUnit`) VALUES 
         (:idPopulation, :user_id, :idProject, :idStudy, :subpopSize, :subset1, :subset2, :subset3,
         :expFrequencyName, :freqType, :freqMeasure,  :freqUnit);';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);

        $query->bindValue(':expFrequencyName', $expFrequencyName, PDO::PARAM_STR);
        $query->bindValue(':freqType', $freqType, PDO::PARAM_STR);
        $query->bindValue(':freqMeasure', $freqMeasure, PDO::PARAM_STR);
        $query->bindValue(':freqUnit', $freqUnit, PDO::PARAM_STR);

        $query->bindValue(':subpopSize', $subpopSize, PDO::PARAM_STR);
        $query->bindValue(':subset1', $subset1, PDO::PARAM_STR);
        $query->bindValue(':subset2', $subset2, PDO::PARAM_STR);
        $query->bindValue(':subset3', $subset3, PDO::PARAM_STR);
        
        $query->execute();
    
        $_SESSION['message'] = "A result has been added";
        require_once('close.php');

        // array to explain results

        
        // meilleur methode pour passer arguments par l URL
        $quer = array(
          'idStudy' => $idStudy, 
          'idProject' => $idProject,
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
       header("Location: index-freq.php?$quer");
    }else{
        $_SESSION['erreur'] = "Fill at least Exposure";
    }
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add result</title>

    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../../css/style.css">
 
    <script type = "text/javascript" src="jsRes/functionRes.js"> </script>
        
</head>
<body>
    <p>This is population number: <?= htmlspecialchars($idPopulation) ?></p>
    
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
                <h1>Add frequency measures for this population</h1>
                <h2>or for a subset of this population</h2>
                <form method="post" >
                
                <div class="form-group row">

                        <div class="form-group col">
                            <label for="subpopSize">Population size</label>
                            <input type="number" min = 0 id="subpopSize" name="subpopSize" class="form-control" >
                        </div>
                        <div class="form-group col">
                            <label for="subset1">Subset characteristic 1</label>
                            <input type="text" id="subset1" name="subset1" class="form-control" placeholder="ex: French">
                        </div>
                        <div class="form-group col">
                            <label for="subset2">Subset characteristic 2</label>
                            <input type="text" id="subset2" name="subset2" class="form-control" placeholder="ex: Anemic">
                        </div>
                        <div class="form-group col">
                            <label for="subset3">Subset characteristic 3</label>
                            <input type="text" id="subset3" name="subset3" class="form-control" placeholder="ex: Chronic kidney disease">
                        </div>
                </div>
                <br>
                <br>
                <div class="form-group row">
                        <div class="form-group col">
                            <label for="expFrequencyName">Exposure name</label>
                            <input type="text" id="expFrequencyName" name="expFrequencyName" class="form-control" placeholder="ex: BMI > 25 kg/m2">
                        </div>
                    <div class="form-group col">
                        <label for="freqType">Frequency type</label>
                        <select id="freqType" name ="freqType" class="form-select" >
                            <option value="prevalence" selected> prevalence</option>
                            <option value="incidence"> incidence</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="freqMeasure">Frequency measure</label>
                        <input type="number" step =0.01 id="freqMeasure" name="freqMeasure" class="form-control" min =0>
                    </div>
                    <div class="form-group col">
                        <label for="frequnit">Unit</label>
                        <input type="text" id="freqUnit" name="freqUnit" class="form-control" placeholder="ex: %">
                    </div>
                </div>
                
                   <br>
                    <button class="btn btn-primary">Add</button>
                </form>
            </section>
        </div>

    </main>
</body>


</html>