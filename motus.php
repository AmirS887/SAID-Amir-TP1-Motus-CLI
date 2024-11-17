<?php

declare(strict_types=1); //supprime le cast automatic de php
include "Dictionnaire.php";
//require_once __DIR__ . "vendor/autoload.php";


$dictionnaire1 = new Dictionnaire();
    
$mot_proposer = $dictionnaire1->generateRandomWord();
$premiere_lettre = $mot_proposer[0];
$saisi = readline(prompt: "veuillez saisir le mot a trouver ! \n");

echo $premiere_lettre;

while ($saisi !== $mot_proposer) {
    $saisi = readline(prompt: "Ce n'est pas le bon mot. Essayez à nouveau : ");
}

echo "Félicitations ! Vous avez trouvé le mot : $mot_proposer\n";

?>