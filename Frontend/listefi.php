 <?php
require_once '../Backend/db.php';

// --- AJOUTER ---
if (isset($_POST['ajouter'])) {
    $Code_fi = $_POST['Code_fi'];
    $libelle = $_POST['libelle_fi'];
    $stmt = $pdo->prepare("INSERT INTO filieres (Code_fi, libelle_fi) VALUES (?,?)");
    $stmt->execute([$Code_fi, $libelle]);
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit();
}

// --- SUPPRIMER ---
if (isset($_GET['supprimer'])) {
    $Code_fi = $_GET['supprimer'];
    // Utilisation d'une requête préparée avec des guillemets implicites
    $stmt = $pdo->prepare("DELETE FROM filieres WHERE Code_fi = ?");
    $stmt->execute([$Code_fi]);
    header("Location: " . $_SERVER['PHP_SELF']); // Rafraîchir la page proprement
    exit();
}

// --- MODIFIER  ---
$filiere_a_modifier = null;
if (isset($_GET['Modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM filieres WHERE Code_fi = ?");
    $stmt->execute([$_GET['Modifier']]);
    $filiere_a_modifier = $stmt->fetch();
}

// --- LISTE ---
$filieres = $pdo->query("SELECT * FROM filieres");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunotes</title>
    <link rel="stylesheet" href="secretaire.css">
</head>
<body>
     <header>
    <nav>
           <h1>📚 EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </nav>
</header>
 <div class="left-container">
        <div class="profile-etudiant">

        </div>
        <ul class="nav-menu">
            <li><a href="Interfacesecretaire.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="Listeetudiant.php" class="nav-link"><i class="fas fa-users"></i> Étudiants</a></li>
            <li><a href="Listematiere.php" class="nav-link"><i class="fas fa-book"></i> Matière</a></li>
            <li><a href="listefi.php" class="nav-link"><i class="fas fa-layer-group"></i> Filière</a></li>
            <li><a href="Listeprof.php" class="nav-link"><i class="fas fa-chalkboard-user"></i> Professeurs</a></li>
            <li><a href="Index.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>    
<main class="container-principal">
   
    <div class="right-container">
 <h1><strong>Gestion des filières</strong></h1>
    <!-- Formulaire dynamique : change selon si on modifie ou ajoute -->
    <form method="POST">
        <input type="text" name="Code_fi" placeholder="Code" value="<?= $filiere_a_modifier['Code_fi'] ?? '' ?>" <?= isset($filiere_a_modifier) ? 'readonly' : '' ?>>
        <input type="text" name="libelle_fi" placeholder="Désignation" value="<?= $filiere_a_modifier['libelle_fi'] ?? '' ?>">
        
        <?php if ($filiere_a_modifier): ?>
            <button name="valider_modification">Enregistrer les modifs</button>
            <button name="annuler"><a href="?">Annuler</a></button>
        <?php else: ?>
            <button name="ajouter">Ajouter</button>
        <?php endif; ?>
    </form>

    <table border="1">
        <tr>
            <th>Code</th>
            <th>Designation</th>
            <th>Action</th>
        </tr>
        <?php foreach ($filieres as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['Code_fi']) ?></td>
            <td><?= htmlspecialchars($f['libelle_fi']) ?></td>
            <td>
                <!-- Ajout d'une confirmation JS pour la suppression -->
                <a class="sup" href="?supprimer=<?= urlencode($f['Code_fi']) ?>" onclick="return confirm('Supprimer cette filière ?')">Supprimer</a>
                <a href="?Modifier=<?= urlencode($f['Code_fi']) ?>">Modifier</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

