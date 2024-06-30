<?php
header("Access-Control-Allow-Origin:*"); //définir les accès à notre api
header("Content-type:application/json;charset=UTF-8"); //type de données à envoyer ou recevoir
header("Access-Control-Allow-Methods:DELETE");

require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."Database.php");
require_once (dirname(__DIR__).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Etudiants.php");

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $e = new Etudiants($con);
    // récupération des données envoyer
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $e->setId(intval(htmlspecialchars($data->id, ENT_QUOTES)));
        $d = $e->delete();
        if ($d) {
           print json_encode(["message" => "Suppression valide"]);
        } else {
            print json_encode(["message" => "Invalide opération"]);
        }
    } else {
        print json_encode(["message" => "Informations imcomplete"]);
    }

} else {
    print json_encode(["message" => "Vous n'avez pas les autorisations de suppression d'un etudiant"]);
}