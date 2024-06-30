<?php
// contrôle des entêtes headers
// header("Access-Control-Allow-Origin:https://www.google.com")
header("Access-Control-Allow-Origin:*"); //définir les accès à notre api 
header("Content-type:application/json;charset=UTF-8"); //type de données à envoyer ou recevoir
header("Access-Control-Allow-Methods:GET"); //


require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."Database.php");
require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Etudiants.php");

// la méthode d'accès aux données "GET"
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // création d'un objet etudiant 
    $e = new Etudiants($con);
    $r = $e->readAll();
    if ($r ->rowCount() > 0) {
        print json_encode($r->fetchAll(),JSON_PRETTY_PRINT);
    } else {
        print json_encode(["message" => "Liste vide !"],JSON_PRETTY_PRINT);
    } 
} else {
    print json_encode(["message" => "Vous n'avez pas accès aux données !"],JSON_PRETTY_PRINT);
}