<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);

    $valid = true;
    if (!preg_match("/^[a-zA-Z-' ]*$/", $nom)) {
        $valid = false;
        $nomErr = "Veuillez utiliser que des lettres et des espaces";
    }
    if (!preg_match("/^[a-zA-Z-' ]*$/", $prenom)) {
        $valid = false;
        $prenomErr = "Veuillez utiliser que des lettres et des espaces";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = false;
        $emailErr = "Format d'email invalide";
    }
    if (!preg_match("/^0[1-9](?:[\s-]?[0-9]{2}){4}$/", $telephone)) {
        $valid = false;
        $telErr = "Format de numéro de téléphone invalide";
    }

    if ($valid) {
        try {
            $sql = "INSERT INTO clients (nom, prenom, email, telephone) VALUES (:nom, :prenom, :email, :telephone)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone
            ]);
            header("Location: affichage.php");
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>" required>
            <?php if (isset($nomErr)) echo "<span class='error'>$nomErr</span>"; ?>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?= isset($prenom) ? htmlspecialchars($prenom) : '' ?>" required>
            <?php if (isset($prenomErr)) echo "<span class='error'>$prenomErr</span>"; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            <?php if (isset($emailErr)) echo "<span class='error'>$emailErr</span>"; ?>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone:</label>
            <input type="tel" id="telephone" name="telephone" value="<?= isset($telephone) ? htmlspecialchars($telephone) : '' ?>" required>
            <?php if (isset($telErr)) echo "<span class='error'>$telErr</span>"; ?>
        </div>

        <a href="affichage.php" > <button type="submit">S'inscrire</button> </a>
    </form>
</body>
</html>