<?php 
session_start();

// on prend userID de la session en theorie pas d'inquietude
$user_id = $_SESSION['user_id'];
$idProject = strip_tags($_GET['idProject']);
$idStudy = strip_tags($_GET['idStudy']);

/*
// on prends idProject de l'URL et on nettoie avec strip tags
if(isset($_GET['idProject']) && !empty($_GET['idProject'])){
  require_once('../../connect_sql2.php');

  // On nettoie l'id envoyé
  $idProject = strip_tags($_GET['idProject']);
}*/
?>

<?php
if($_POST){
    
        // On inclut la connexion à la base
        require_once('../../connect_sql2.php');

        // On nettoie les données envoyées par le formulaire de ce script
        $pNam = strip_tags($_POST['pNam']);
        $popSize = strip_tags($_POST['popSize']);
        $popCountry = strip_tags($_POST['popCountry']);
        $popMainChar = strip_tags($_POST['popMainChar']);
        $pSecChar = strip_tags($_POST['pSecChar']);
        $inclCrit = strip_tags($_POST['inclCrit']);
        $ageUni = strip_tags($_POST['ageUni']);
        $ageMean = strip_tags($_POST['ageMean']);
        $ageSD = strip_tags($_POST['ageSD']);
        $femaleFreq = strip_tags($_POST['femaleFreq']);

        /*$sql = 'INSERT INTO `populations` (`user_id`, `idProject`,`idStudy`,`popName`,`popNumber`,`popCountry`, `popMainChar`, `popSecChar`,`ageUnit`,`ageSD`,`femaleFreq`) VALUES (:user_id,
          :idProject, :idStudy, :popName, :popNumber, :popCountry, :popMainChar, :popSecChar, :ageUnit, :ageSD, :femaleFreq );'; */

         $sql = 'INSERT INTO `populations` (`user_id`, `idProject`,`idStudy`,`pNam`,`popSize`,`popCountry`,`popMainChar`,`pSecChar`,`inclCrit`,`ageUni`,`ageMean`,`ageSD`,`femaleFreq`) VALUES 
         (:user_id,:idProject,:idStudy,:pNam,:popSize,:popCountry,:popMainChar,:pSecChar,:inclCrit,:ageUni,:ageMean,:ageSD,:femaleFreq);';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':pNam', $pNam, PDO::PARAM_STR);
        $query->bindValue(':popSize', $popSize, PDO::PARAM_INT);
        $query->bindValue(':popCountry', $popCountry, PDO::PARAM_STR);
        $query->bindValue(':popMainChar', $popMainChar, PDO::PARAM_STR);
        $query->bindValue(':pSecChar', $pSecChar, PDO::PARAM_STR);
        $query->bindValue(':inclCrit', $inclCrit, PDO::PARAM_STR);
        $query->bindValue(':ageUni', $ageUni, PDO::PARAM_STR);
        $query->bindValue(':ageMean', $ageMean, PDO::PARAM_INT);
        $query->bindValue(':ageSD', $ageSD, PDO::PARAM_INT);
        $query->bindValue(':femaleFreq', $femaleFreq, PDO::PARAM_INT); 
        $query->execute();

        $_SESSION['message'] = "A population has been added";
        require_once('close.php');

        // meilleur methode pour passer arguments par l URL
        $quer = array(
          'idStudy' => $idStudy, 
          'idProject' => $idProject,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-pop.php?$quer");
       // var_dump($_POST);
       // var_dump($query);
       // var_dump($popName);
        //header("Location: index-pop.php?idStudy=".$idStudy);
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add population details</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../css/style.css">
</head>
<body>
    <p>This is study number: <?= htmlspecialchars($idStudy) ?></p>
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
                <h1>Add population details</h1>
                <form method="post">
                    
                    <div class="row">
                        <div class="col">
                            <label for="pNam">Population name</label>
                            <input type="text" id="pNam" name="pNam" class="form-control" placeholder="ex: Cohort name">

                        </div>
                
                        <div class="col">
                            <label for="popSize">Population size</label>
                            <input type="number" id="popSize" name="popSize" class="form-control">
                        </div>
                        
                        <!--- need to add dependencies --- to check further
                        <div class="form-group">
                            <label for="popCountry">Population country</label>
                            <select name="popCountry" id=popCountry class="selectpicker countrypicker" multiple></select>
                        </div> --->
                        
                        <div class="col">
                            <label for="popCountry">Population country</label>
                            <input type="text" id="popCountry" name="popCountry" class="form-control">

                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="popMainChar">Population main characteristic</label>
                        <input type="text" id="popMainChar" name="popMainChar" class="form-control" placeholder="A characteristic common to the whole population">
                    </div>
                    <div class="form-group">
                        <label for="pSecChar">Population Secondary characteristic</label>
                        <input type="text" id="pSecChar" name="pSecChar" class="form-control" placeholder="A characteristic common to a large % of the population">
                    </div>
                    <div class="form-group">
                        <label for="inclCrit">Inclusion criteria</label>
                        <input type="text" id="inclCrit" name="inclCrit" class="form-control" placeholder="as mentioned in the study">
                    </div>

                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="ageMean">Age mean</label>
                            <input type="number" id="ageMean" name="ageMean" class="form-control" step = 0.1>
                        </div>
                        
                        <div class="form-group col">
                            <label for="ageSD">Age standard deviation</label>
                            <input type="number" id="ageSD" name="ageSD" class="form-control" step = 0.1>
                        </div>
    
                        
                            <div class="form-group col">
                                <label for="ageUni">Age unit</label>
                                <select id="ageUni" name="ageUni"  class="form-select">
                                    <option value="years" selected>years</option>
                                    <option value="months">months</option>
                                    <option value="weeks">weeks</option>
                                    <option value="days">days</option>    
                                    <option value="other">other</option> 
                                </select>
                        </div>

                        <div class="form-group col">
                            <label for="femaleFreq">Female sex frequency (%)</label>
                            <input type="number" id="femaleFreq" name="femaleFreq" class="form-control" step = 0.1>
                        </div>
                    </div>

                    <button class="btn btn-primary">Send</button>
                </form>
            </section>
        </div>
    </main>
</body>


</html>