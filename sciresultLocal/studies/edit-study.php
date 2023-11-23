<?php
// On démarre une session
session_start();

// ce premier bloc sert à recuperer les données mises à jour dans le formulaire plus bas
if($_POST){
    if(isset($_POST['idStudy']) && !empty($_POST['idStudy'])
    && isset($_POST['title']) && !empty($_POST['title'])
      ){
        // On inclut la connexion à la base
        require_once('../connect_sql2.php');

        
        // On nettoie les données envoyées
        $user_id= strip_tags($_SESSION["user_id"]);
        $idStudy = strip_tags($_POST['idStudy']);
        $title = strip_tags($_POST['title']);
        $author = strip_tags($_POST['author']);
        $yearPublication = strip_tags($_POST['yearPublication']);
        $idProject = strip_tags($_POST['idProject']);
        $journal = strip_tags($_POST['journal']);
        $studyPlan = strip_tags($_POST['studyPlan']);
        $centric = strip_tags($_POST['centric']);

        $startYear = strip_tags($_POST['startYear']);
        $endYear = strip_tags($_POST['endYear']);
        $objective = strip_tags($_POST['objective']);
        $discussion = strip_tags($_POST['discussion']);


        $sql = 'UPDATE `study` SET `title`=:title, `author`=:author, `yearPublication`=:yearPublication  ,`journal`=:journal, `studyPlan`=:studyPlan ,
         `centric`=:centric  ,`startYear`=:startYear, `endYear`=:endYear ,`objective`=:objective, `discussion`=:discussion 
        WHERE `idStudy`=:idStudy AND `user_id`=:user_id ;';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':author', $author, PDO::PARAM_STR);
        $query->bindValue(':yearPublication', $yearPublication, PDO::PARAM_INT);
        //$query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':journal',$journal, PDO::PARAM_STR);
        $query->bindValue(':studyPlan', $studyPlan, PDO::PARAM_STR);
        $query->bindValue(':centric', $centric, PDO::PARAM_STR); 

        $query->bindValue(':startYear', $startYear, PDO::PARAM_INT);
        $query->bindValue(':endYear',$endYear, PDO::PARAM_INT);
        $query->bindValue(':objective', $objective, PDO::PARAM_STR);
        $query->bindValue(':discussion', $discussion, PDO::PARAM_STR); 


        $query->execute();

        $_SESSION['message'] = "Study has been modified";
        require_once('close.php');

        header("Location: index-study-test.php?idProject=".$idProject );
    }else{
        $_SESSION['erreur'] = "Must fill a title for the study";
    }
}

// ce deuxieme bloc sert a donner des valeurs pour pre-remplir le formulaire en faisant une query
// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
&& isset($_GET['idProject']) && !empty($_GET['idProject'])){
    require_once('../connect_sql2.php');

    // On nettoie l'id envoyé
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);

    $sql = 'SELECT * FROM `study` WHERE `idStudy` = :idStudy;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $article = $query->fetch();


    // On vérifie si le produit existe
    if(!$article){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header("Location: index-study-test.php?idProject=".$idProject );
    }
}else{
    $_SESSION['erreur'] = "URL invalide";
    header("Location: index-study-test.php?idProject=".$idProject);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update study</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../css/style.css">
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
                <h1>Update study</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="title">Study title</label>
                        <input type="text" id="title" name="title" class="form-control"  value="<?= $article['title']?>">
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control" value="<?= $article['author']?>">

                    </div>
                    <div class="form-group">
                        <label for="yearPublication">yearPublication</label>
                        <input type="number" id="yearPublication" name="yearPublication" class="form-control" value="<?= $article['yearPublication']?>"  max=<?php  echo date("Y");  ?>>
                    </div>

                    <div class="form-group">
                        <label for="journal"><strong>Journal</strong></label>
                        <input type="text" id="journal" name="journal" class="form-control" value="<?= $article['journal']?>">
                    </div>
                    <div class="row">

                    <div class="col">
                            <label for="studyPlan"><strong>Study plan</strong></label>
                            <select name="studyPlan" id="studyPlan" class="form-select">
                                <option value="transversal">Transversal</option>
                                <option value="cohort">Cohort</option>
                                <option value="caseControl">Case control</option>
                                <option value="CT">Clinical trial</option>
                                <option value="RCT">Randomised clinical trial</option>
                                <option value="none">Select study plan</option>
                                <option selected value="<?= $article['studyPlan']?>"><?= $article['studyPlan']?></option>
                            </select>
                    </div>
                    <div class="col">
                        <label for="centric"><strong>Centers</strong></label>
                        <select name="centric" id="centric" class="form-select">
                            <option value="monocentric">Monocentric </option>
                            <option value="multicentric">Multicentric </option>
                            <option value="other">Other </option>
                            <option selected value="<?= $article['centric']?>"><?= $article['centric']?> </option>
                        </select>
                    </div>
                
                    <div class="col">
                        <label for="startYear"><strong>Starting year of follow-up</strong></label>
                        <input type="number" id="startYear" name="startYear" class="form-control"  max=<?php  echo date("Y");  ?>  value="<?= $article['startYear']?>">
                    </div>
                    <div class="col">    
                        <label for="endYear"><strong>End of follow-up</strong></label>
                        <input type="number" id="endYear" name="endYear" class="form-control"  max=<?php  echo date("Y");  ?> value="<?= $article['endYear']?>">
                    </div>
                </div>
                    <div class="form-group">
                        <label for="objective"><strong>Objective</strong></label>
                        <input type="text" id="objective" name="objective" class="form-control form-control-lg" value="<?= $article['objective']?>">
                    </div>

                    <div class="form-group">
                        <label for="discussion"><strong>Discussion</strong></label>
                        <input type="text" id="discussion" name="discussion" class="form-control form-control-lg" value="<?= $article['discussion']?>">
                    </div>


                    <input type="hidden" value="<?= $article['idStudy']?>" name="idStudy">
                    <input type="hidden" value="<?= $article['idProject']?>" name="idProject">
                    <button class="btn btn-primary">Edit</button>
                </form>
            </section>
        </div>
    </main>
   
</body>
</html>
