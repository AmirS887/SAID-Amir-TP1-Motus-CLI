<?php

declare(strict_types=1);
include "Dictionnaire.php";

session_start();

$dictionnaire1 = new Dictionnaire();

// Réinitialiser la session si l'utilisateur clique sur "Rejouer"
if (isset($_POST['rejouer'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Initialiser le mot à deviner
$mot_proposer = $_SESSION['mot_proposer'] ?? $dictionnaire1->generateRandomWord();
$_SESSION['mot_proposer'] = $mot_proposer;

// Initialiser les tentatives
$_SESSION['tentatives'] = $_SESSION['tentatives'] ?? 0;
$_SESSION['historique'] = $_SESSION['historique'] ?? [];

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mot'])) {
    $saisi = strtoupper(trim($_POST['mot']));
    $_SESSION['tentatives']++;

    if ($saisi === strtoupper($mot_proposer)) {
        $success = true;
        $message = "Félicitations ! Vous avez trouvé le mot : <strong>$mot_proposer</strong>";
    } else {
        // Vérifier les lettres
        $feedback = "";
        for ($i = 0; $i < strlen($mot_proposer); $i++) {
            if (isset($saisi[$i])) {
                if ($saisi[$i] === strtoupper($mot_proposer[$i])) {
                    $feedback .= "<span style='color: green; font-weight: bold;'>{$saisi[$i]}</span>";
                } elseif (strpos(strtoupper($mot_proposer), $saisi[$i]) !== false) {
                    $feedback .= "<span style='color: orange;'>{$saisi[$i]}</span>";
                } else {
                    $feedback .= "<span style='color: red;'>{$saisi[$i]}</span>";
                }
            } else {
                $feedback .= "_";
            }
        }
        $message = "Ce n'est pas le bon mot : $feedback";
        $_SESSION['historique'][] = $feedback;
    }

    // Révéler le mot si les 4 tentatives sont épuisées
    if ($_SESSION['tentatives'] >= 4 && !$success) {
        $message = "Désolé, vous avez épuisé vos tentatives. Le mot était : <strong>$mot_proposer</strong>";
        $success = true; // Fin du jeu
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .feedback {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .failure {
            color: red;
            font-weight: bold;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 1em;
        }

        .legend {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .legend span {
            display: inline-block;
            margin-right: 10px;
        }

        .legend .green {
            color: green;
            font-weight: bold;
        }

        .legend .orange {
            color: orange;
            font-weight: bold;
        }

        .legend .red {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
<h1>Motus</h1>

<div class="legend">
    <h3>Légende :</h3>
    <span><span class="green">V</span> : Lettre bien placée</span>
    <span><span class="orange">O</span> : Lettre présente mais mal placée</span>
    <span><span class="red">R</span> : Lettre absente</span>
    <span>_ : Lettre manquante</span>
</div>

<?php if ($success): ?>
    <p class="success"><?php echo $message; ?></p>
    <form method="post">
        <button type="submit" name="rejouer">Rejouer</button>
    </form>
<?php else: ?>
    <p>La première lettre du mot est : <strong><?php echo strtoupper($mot_proposer[0]); ?></strong></p>
    <p>Le mot à deviner contient <strong><?php echo strlen($mot_proposer); ?></strong> lettres.</p>
    <p>Nombre de tentatives restantes : <strong><?php echo 4 - $_SESSION['tentatives']; ?></strong></p>
    <?php if ($message): ?>
        <p class="feedback"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="mot">Saisissez votre mot :</label>
        <input type="text" id="mot" name="mot" maxlength="<?php echo strlen($mot_proposer); ?>" required>
        <button type="submit">Valider</button>
    </form>

    <?php if (!empty($_SESSION['historique'])): ?>
        <h3>Historique des tentatives :</h3>
        <ul>
            <?php foreach ($_SESSION['historique'] as $tentative): ?>
                <li><?php echo $tentative; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
</body>

</html>
