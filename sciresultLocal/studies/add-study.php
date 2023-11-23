<?php 
session_start();

// on prend userID de la session en theorie pas d'inquietude
$user_id = $_SESSION['user_id'];
$idProject = strip_tags($_GET['idProject']);

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
    if(isset($_POST['title']) && !empty($_POST['title'])
    ){
        // On inclut la connexion à la base
        require_once('../connect_sql2.php');

        // On nettoie les données envoyées
        $title = strip_tags($_POST['title']);
        $author = strip_tags($_POST['author']);
        $yearPublication = strip_tags($_POST['yearPublication']);
        $journal = strip_tags($_POST['journal']);
        $studyPlan = strip_tags($_POST['studyPlan']);
        $centric = strip_tags($_POST['centric']);

        $startYear = strip_tags($_POST['startYear']);
        $endYear = strip_tags($_POST['endYear']);
        $objective = strip_tags($_POST['objective']);
        $discussion = strip_tags($_POST['discussion']);

        $sql = 'INSERT INTO `study` (`title`, `author`, `yearPublication`,`user_id`,`idProject`, `journal`,`studyPlan`,`centric`, `startYear`, `endYear`,`objective`,`discussion`) 
        VALUES (:title, :author, :yearPublication, :user_id, :idProject, :journal, :studyPlan, :centric, :startYear, :endYear, :objective, :discussion );';

        $query = $db->prepare($sql);

        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':author', $author, PDO::PARAM_STR);
        $query->bindValue(':yearPublication', $yearPublication, PDO::PARAM_INT);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':journal',$journal, PDO::PARAM_STR);
        $query->bindValue(':studyPlan', $studyPlan, PDO::PARAM_STR);
        $query->bindValue(':centric', $centric, PDO::PARAM_STR); 

        $query->bindValue(':startYear', $startYear, PDO::PARAM_INT);
        $query->bindValue(':endYear',$endYear, PDO::PARAM_INT);
        $query->bindValue(':objective', $objective, PDO::PARAM_STR);
        $query->bindValue(':discussion', $discussion, PDO::PARAM_STR); 
        $query->execute();

        $_SESSION['message'] = "A study has been added";
        require_once('close.php');

        header("Location: index-study-test.php?idProject=".$idProject );
    }else{
        $_SESSION['erreur'] = "Form is incomplete";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a study</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../css/style.css">
</head>
<body>
    <p>This is project number: <?= htmlspecialchars($idProject) ?></p>
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
                <h1>Add a study</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="title"><strong>Study title</strong></label>
                        <input required type="text" id="title" name="title" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="author"><strong>Author</strong></label>
                        <input type="text" id="author" name="author" class="form-control">

                    </div>
                    <div class="form-group">
                        <label for="yearPublication"><strong>Year of Publication</strong></label>
                        <input type="number" id="yearPublication" name="yearPublication" class="form-control" max=<?php  echo date("Y");  ?>>
                    </div>
                    <div class="form-group">
                        <label for="journal"><strong>Journal</strong></label>
                        <input type="text" id="journal" name="journal" class="form-control">
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
                                <option selected value="none">Select study plan</option>
                            </select>
                    </div>
                    <div class="col">
                        <label for="centric"><strong>Centers</strong></label>
                        <select name="centric" id="centric" class="form-select">
                            <option value="monocentric">Monocentric </option>
                            <option value="multicentric">Multicentric </option>
                            <option selected value="other">Other </option>
                        </select>
                    </div>
                
                    <div class="col">
                        <label for="startYear"><strong>Starting year of follow-up</strong></label>
                        <input type="number" id="startYear" name="startYear" class="form-control"  max=<?php  echo date("Y");  ?>>
                    </div>
                    <div class="col">    
                        <label for="endYear"><strong>End of follow-up</strong></label>
                        <input type="number" id="endYear" name="endYear" class="form-control"  max=<?php  echo date("Y");  ?>>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="objective"><strong>Objective</strong></label>
                        <input type="text" id="objective" name="objective" class="form-control form-control-lg">
                    </div>

                    <div class="form-group">
                        <label for="discussion"><strong>Discussion</strong></label>
                        <input type="text" id="discussion" name="discussion" class="form-control form-control-lg">
                    </div>
                    <button class="btn btn-primary">Add study</button>
                </form>
            </section>
        </div>
    </main>
</body>

</html>