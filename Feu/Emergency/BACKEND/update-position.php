<?php

//FICHIER CONTIENT LE TRAITEMENT DE LA DEMANDE

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    // On inclut les fichiers de configuration et d'accès aux données
    include_once './database.php';
    include_once './feu.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    $content = file_get_contents('php://input');
   
    $file = fopen('text.txt', "w");
    fwrite($file, $content);

    if (is_array($array = json_decode($content, true))) {

        foreach($array as $elem) {
            $camion = new Camion($db);
            $camion->id = $elem['id'];
            $camion->statut = $elem['statut'];
            $camion->lon = $elem['lon'];
            $camion->lat = $elem['lat'];
            $camion->position();
        }

        // On envoie le code réponse 200 OK
        http_response_code(200);
    }
} else {
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

