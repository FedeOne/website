<?php
session_start();

try {
    // Connect to the database
    $db = new PDO('mysql:host=localhost; dbname=sciresults;', 'root', '');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    die();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the study ID and color from the POST data
    $idStudy = $_POST['idStudy'];
    $color = $_POST['color'];

    // Update the color in the study table
    $stmt = $db->prepare("UPDATE study SET color = :color WHERE idStudy = :idStudy");
    $stmt->bindParam(':color', $color, PDO::PARAM_STR);
    $stmt->bindParam(':idStudy', $idStudy, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'Color updated successfully.';
    } else {
        echo 'Error updating color.';
    }
} else {
    echo 'Invalid request method.';
}
?>