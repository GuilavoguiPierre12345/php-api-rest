<?php
header("Access-Control-Allow-Origin:*"); //définir les accès à notre api
header("Content-type:application/json;charset=UTF-8"); //type de données à envoyer ou recevoir
header("Access-Control-Allow-Methods:PUT");

require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."Database.php");
require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Etudiants.php");

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $e = new Etudiants($con);
    // récupération des données envoyer
    $data = json_decode(file_get_contents("php://input"));
    // controle de validation des données envoyés 
    if (!empty($data->id) && !empty($data->nom) && !empty($data->prenom) && !empty($data->age) && !empty($data->niveau_id)) {
        $e->setId(intval(htmlspecialchars($data->id, ENT_QUOTES)));
        $e->setNom(htmlspecialchars($data->nom, ENT_QUOTES));
        $e->setPrenom(htmlspecialchars($data->prenom, ENT_QUOTES));
        $e->setAge(htmlspecialchars($data->age, ENT_QUOTES));
        $e->setNiveau_id(intval(htmlspecialchars($data->niveau_id, ENT_QUOTES)));

        // appel de la méthode de mise à jour
        $u = $e->update();
        if ($u) {
           print json_encode(["message" => "Opération de mise à jour valide"]);
        } else {
            print json_encode(["message" => "Mise à jour non valide"]);
        }
    } else {
        print json_encode(["message" => "Informations imcomplete"]);
    }

} else {
    print json_encode(["message" => "Vous n'avez pas les autorisations de modification d'un etudiant"]);
}