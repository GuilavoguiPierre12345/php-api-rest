<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $filename = $_FILES['file']['tmp_name'];
        if (move_uploaded_file($filename,$_FILES['file']['name'])) {
            print json_encode(["message" => "Bingo le fichier : ".$_FILES['file']['name'] ." a été uploadé avec succès"]);
        }
    }
}
?>
