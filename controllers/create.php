<?php
header('Accept: application/json');
header("Access-Control-Allow-Origin:*"); //définir les accès à notre api
header("Content-type:application/json;charset=UTF-8"); //type de données à envoyer ou recevoir
header("Access-Control-Allow-Methods:POST"); //

require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."Database.php");
require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Etudiants.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $e = new Etudiants($con);

    // récupération des données envoyer
    $data = json_decode(file_get_contents("php://input"));
    
    // controle de validation des données envoyés 
    if (!empty($data->nom) && !empty($data->prenom) && !empty($data->age) && !empty($data->niveau_id)) {
        $e->setNom(htmlspecialchars($data->nom, ENT_QUOTES));
        $e->setPrenom(htmlspecialchars($data->prenom, ENT_QUOTES));
        $e->setAge(htmlspecialchars($data->age, ENT_QUOTES));
        $e->setNiveau_id(htmlspecialchars($data->niveau_id, ENT_QUOTES));

        $a = $e->create();
        if ($a) {
           print json_encode(["message" => "ajout effectuer avec succes"]);
        } else {
            print json_encode(["message" => "erreur d'ajout de l'etudiant"]);
        }
    } else {
        print json_encode(["message" => "erreur données imcomplète !"]);
    }
} else {
    print json_encode(["message" => "Vous n'avez pas les autorisations d'ajout d'un etudiant"]);
}