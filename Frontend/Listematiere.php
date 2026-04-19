 <?php
require_once '../Backend/db.php';

// ---  SUPPRESSION ---
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM matiere WHERE id_matiere = ?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: Listematiere.php");
    exit();
}

// ---  AJOUT OU MODIFICATION ---
if (isset($_POST['enregistrer'])) {
    $id_mat = $_POST['id_matiere'];
    $nom_mat = $_POST['noml_matiere'];
    $code_fi = $_POST['Code_fi'];
    $code_niv = $_POST['Code_niv'];
    $id_prof = $_POST['id_professeur'];

    if (isset($_POST['mode_edition']) && $_POST['mode_edition'] == '1') {
        // UPDATE
        $sql = "UPDATE matiere SET noml_matiere=?, Code_fi=?, Code_niv=?, id_professeur=? WHERE id_matiere=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom_mat, $code_fi, $code_niv, $id_prof, $id_mat]);
    } else {
        // INSERT
        $sql = "INSERT INTO matiere (id_matiere, noml_matiere, Code_fi, Code_niv, id_professeur) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_mat, $nom_mat, $code_fi, $code_niv, $id_prof]);
    }
    header("Location: Listematiere.php");
    exit();
}

// --- PRÉPARATION MODIFICATION ---
$matiere_a_modifier = null;
if (isset($_GET['Modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM matiere WHERE id_matiere = ?");
    $stmt->execute([$_GET['Modifier']]);
    $matiere_a_modifier = $stmt->fetch();
}

// --- CHARGEMENT DES DONNÉES ---
$matieres = $pdo->query("
    SELECT m.*, p.nom as nom_prof 
    FROM matiere m 
    LEFT JOIN professeur p ON m.id_professeur = p.id_professeur
")->fetchAll();

// Pour remplir la liste déroulante des profs
$profs_liste = $pdo->query("SELECT id_professeur, nom FROM professeur")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNotes</title>
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
       
    </div>    
    <main class="container-principal">
    <div class="left-container">
         <ul class="nav-menu">
            <li><a href="Interfacesecretaire.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="Listeetudiant.php" class="nav-link"><i class="fas fa-users"></i> Étudiants</a></li>
            <li><a href="Listematiere.php" class="nav-link"><i class="fas fa-book"></i> Matière</a></li>
            <li><a href="listefi.php" class="nav-link"><i class="fas fa-layer-group"></i> Filière</a></li>
            <li><a href="Listeprof.php" class="nav-link"><i class="fas fa-chalkboard-user"></i> Professeurs</a></li>
            <li><a href="Index.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="right-container">
        <h1><strong>Gestion des Matières</strong></h1>

        <!-- Formulaire Dynamique -->
        <form method="POST" class="form-gestion">
            <input type="hidden" name="mode_edition" value="<?= $matiere_a_modifier ? '1' : '0' ?>">
            
            <input type="text" name="id_matiere" placeholder="Code Matière (ex: MAT01)" value="<?= $matiere_a_modifier['id_matiere'] ?? '' ?>" <?= $matiere_a_modifier ? 'readonly' : 'required' ?>>
            <input type="text" name="noml_matiere" placeholder="Nom de la matière" value="<?= $matiere_a_modifier['noml_matiere'] ?? '' ?>" required>
            <input type="text" name="Code_fi" placeholder="Code Filière" value="<?= $matiere_a_modifier['Code_fi'] ?? '' ?>" required>
            <input type="text" name="Code_niv" placeholder="Code Niveau" value="<?= $matiere_a_modifier['Code_niv'] ?? '' ?>" required>
            
            <select name="id_professeur" required>
                <option value="">-- Choisir un professeur --</option>
                <?php foreach($profs_liste as $pr): ?>
                    <option value="<?= $pr['id_professeur'] ?>" <?= (isset($matiere_a_modifier) && $matiere_a_modifier['id_professeur'] == $pr['id_professeur']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pr['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <br>

            <button type="submit" name="enregistrer">
                
                <?= $matiere_a_modifier ? 'Enregistrer les modifications' : 'Ajouter la matière' ?>
            </button>
            <br>
                <br>
            <?php if($matiere_a_modifier): ?> <a href="Listematiere.php">Annuler</a> <?php endif; ?>
                <br>
        </form>

        <table border="1">
            <tr>
                <th>Code</th>
                <th>Matière</th>
                <th>Filière</th>
                <th>Niveau</th>
                <th>Professeur en charge</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($matieres as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['id_matiere']) ?></td>
                <td><?= htmlspecialchars($m['noml_matiere']) ?></td>
                <td><?= htmlspecialchars($m['Code_fi']) ?></td>
                <td><?= htmlspecialchars($m['Code_niv']) ?></td>
                <td><?= htmlspecialchars($m['nom_prof'] ?? 'Non assigné') ?></td>
                <td>
                    <a href="?Modifier=<?= $m['id_matiere'] ?>">Modifier</a> | 
                    <a href="?supprimer=<?= $m['id_matiere'] ?>" onclick="return confirm('Supprimer ?')" style="color:red;">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>

</body>
</html>

