<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Utilisation de htmlspecialchars au lieu de FILTER_SANITIZE_STRING
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $prenom = htmlspecialchars($_POST['prenom'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars($_POST['telephone'] ?? '');

    $valid = true;
    if (!preg_match("/^[a-zA-ZÀ-ÿ\-' ]*$/u", $nom)) {
        $valid = false;
        $nomErr = "Seuls les lettres, les accents, les tirets et les espaces sont autorisés";
    }
    
    if (!preg_match("/^[a-zA-ZÀ-ÿ\-' ]*$/u", $prenom)) {
        $valid = false;
        $prenomErr = "Seuls les lettres, les accents, les tirets et les espaces sont autorisés";
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
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Formulaire</h1>
    <form method="POST">
        <div class="form-group">
            <!-- <label for="nom">Nom</label><br> -->
            <input type="text" id="nom" name="nom" placeholder="nom" value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>" required>
            <?php if (isset($nomErr)) echo "<br>" . "<span class='error'>$nomErr</span>"; ?>
        </div>

        <div class="form-group">
            <!-- <label for="prenom">Prénom</label><br> -->
            <input type="text" id="prenom" name="prenom" placeholder="prénom" value="<?= isset($prenom) ? htmlspecialchars($prenom) : '' ?>" required>
            <?php if (isset($prenomErr)) echo "<br>" . "<span class='error'>$prenomErr</span>"; ?>
        </div>

        <div class="form-group">
            <!-- <label for="email">Email</label><br> -->
            <input type="email" id="email" name="email" placeholder="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            <?php if (isset($emailErr)) echo "<br>" . "<span class='error'>$emailErr</span>"; ?>
        </div>

        <div class="form-group">
            <!-- <label for="telephone">Téléphone</label><br> -->
            <input type="tel" id="telephone" name="telephone" placeholder="téléphone" value="<?= isset($telephone) ? htmlspecialchars($telephone) : '' ?>" required>
            <?php if (isset($telErr)) echo "<br>" . "<span class='error'>$telErr</span>"; ?>
        </div>

        <a href="affichage.php"> <button type="submit">S'inscrire</button> </a>
    </form>
</body>

</html>