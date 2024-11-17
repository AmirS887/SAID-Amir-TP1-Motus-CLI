<?php

class Dictionnaire
{
    private array $mots;

    //Le constructeur de la class Dictionnaire qui va charger par defaut
    public function __construct($fichier = 'dictinnaire.txt')
    {
        if (file_exists($fichier)) {
            $this->mots = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        } else {
            echo("Le Fichier dictionnaire pour alimeter le programme n'existe pas");
        }

    }

    //La fonction pour generer un mot aleatoirement
    public function generateRandomWord()
    {
        if (empty($this->mots)) {
            return "votre dictionnaire est vide ";
        }

        $randomWord = $this->mots[rand(0, count($this->mots) - 1)];

        return $randomWord;
    }
}

?>