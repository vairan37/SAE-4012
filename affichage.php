<?php
require_once 'connection.php';

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = htmlspecialchars($_POST['searchTerm'] ?? '');

    try {
        $sql = "SELECT * FROM clients WHERE nom LIKE :search OR prenom LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':search' => $searchTerm . '%'
        ]);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->query("SELECT * FROM clients");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $clients = [];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des clients</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <a id="retour" href="index.php">Retour au formulaire</a>

        <form id="recherche" method="POST">
            <section>
                <label for="searchTerm" hidden>Rechercher par nom ou prénom</label>
                <input type="text" id="searchTerm" placeholder="Rechercher par nom ou prénom" name="searchTerm" required>
            </section>
            <button type="submit">Rechercher</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_POST['searchTerm']) && !empty($searchResults)): ?>
                    <?php foreach ($searchResults as $client): ?>
                        <tr>
                            <td><?= htmlspecialchars($client['nom']) ?></td>
                            <td><?= htmlspecialchars($client['prenom']) ?></td>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['telephone']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif (!isset($_POST['searchTerm'])): ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= htmlspecialchars($client['nom']) ?></td>
                            <td><?= htmlspecialchars($client['prenom']) ?></td>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['telephone']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>