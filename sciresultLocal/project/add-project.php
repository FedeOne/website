<?php
// On démarre une session
session_start();

$user_id = $_SESSION['user_id'];

if($_POST){
    if(isset($_POST['projectTitle']) && !empty($_POST['projectTitle'])
       ){
        // On inclut la connexion à la base
        require_once('../connect_sql2.php');

        // On nettoie les données envoyées
        $projectTitle = strip_tags($_POST['projectTitle']);
        $projectComment = strip_tags($_POST['projectComment']);
        

        $sql = 'INSERT INTO `project` (`projectTitle`, `projectComment`, `user_id`) VALUES (:projectTitle, :projectComment, :user_id);';

        $query = $db->prepare($sql);

        $query->bindValue(':projectTitle', $projectTitle, PDO::PARAM_STR);
        $query->bindValue(':projectComment', $projectComment, PDO::PARAM_STR);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();

        $_SESSION['message'] = "A project has been added";
        require_once('close.php');

        header('Location: ../index-proj.php');
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
    <title>Add a project</title>

    <link rel="stylesheet"   href="../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../css/style.css">
</head>
<body>
    <div class="container timeline">
        <a href="../index.php">
            <button class="btn btt-timeline">Home</button>
        </a>
    </div>

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
                <h1>Add a project</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="projectTitle">Project title</label>
                        <input type="text" id="projectTitle" name="projectTitle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="projectComment">Comment</label>
                        <input type="text" id="projectComment" name="projectComment" class="form-control">

                    </div>
                    
                    <button class="btn btn-primary">Add</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>