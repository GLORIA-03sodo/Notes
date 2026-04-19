 <?php
require_once '../Backend/db.php';

// ---  STATISTIQUES PAR FILIERE ---
$stats = $pdo->query("
    SELECT f.libelle_fi, 
    (SELECT COUNT(*) FROM etudiant WHERE Code_fi = f.Code_fi) as nb_etud,
    (SELECT COUNT(*) FROM matiere WHERE Code_fi = f.Code_fi) as nb_mat,
    (SELECT COUNT(*) FROM professeur) as nb_prof 
    FROM filieres f
")->fetchAll();

// ---  LOGIQUE DE SUPPRESSION ---
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM etudiant WHERE id_etud = ?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: Listeetudiant.php");
    exit();
}

// ---  LOGIQUE D'AJOUT OU MODIFICATION ---
if (isset($_POST['enregistrer'])) {
    $id = $_POST['id_etud'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $ddn = $_POST['Date_de_naissance'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $code_fi = $_POST['Code_fi'];
    $code_niv = $_POST['Code_niv'];
    $sexe = $_POST['Sexe'];

    if (isset($_POST['mode_edition']) && $_POST['mode_edition'] == '1') {
        $sql = "UPDATE etudiant SET nom=?, prenom=?, Date_de_naissance=?, email=?, mdp=?, Code_fi=?, Code_niv=?, Sexe=? WHERE id_etud=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $ddn, $email, $mdp, $code_fi, $code_niv, $sexe, $id]);
    } else {
        $sql = "INSERT INTO etudiant (id_etud, nom, prenom, Date_de_naissance, email, mdp, Code_fi, Code_niv, Sexe) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $nom, $prenom, $ddn, $email, $mdp, $code_fi, $code_niv, $sexe]);
    }
    header("Location: Listeetudiant.php");
    exit();
}

// ---  RÉCUPÉRATION POUR MODIFICATION ---
$etud_a_modifier = null;
if (isset($_GET['Modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_etud = ?");
    $stmt->execute([$_GET['Modifier']]);
    $etud_a_modifier = $stmt->fetch();
}

$etudiants = $pdo->query("SELECT * FROM etudiant")->fetchAll();
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
        
        <!-- Affichage des Statistiques par Filière -->
        <h2>Statistiques par Filière</h2>
        <div class="dashboard-grid" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($stats as $s): ?>
                <div class="card" style="border: 1px solid #ccc; padding: 10px;">
                    <strong><?= $s['libelle_fi'] ?></strong><br>
                    Étudiants: <?= $s['nb_etud'] ?> | Matières: <?= $s['nb_mat'] ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h1><strong>Gestion des Étudiants</strong></h1>

        <form method="POST" class="form-gestion">
            <input type="hidden" name="mode_edition" value="<?= $etud_a_modifier ? '1' : '0' ?>">
            
            <input type="text" name="id_etud" placeholder="ID Étudiant" value="<?= $etud_a_modifier['id_etud'] ?? '' ?>" <?= $etud_a_modifier ? 'readonly' : 'required' ?>>
            <input type="text" name="nom" placeholder="Nom" value="<?= $etud_a_modifier['nom'] ?? '' ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $etud_a_modifier['prenom'] ?? '' ?>" required>
            <input type="date" name="Date_de_naissance" value="<?= $etud_a_modifier['Date_de_naissance'] ?? '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $etud_a_modifier['email'] ?? '' ?>" required>
            <input type="password" name="mdp" placeholder="Mot de passe" value="<?= $etud_a_modifier['mdp'] ?? '' ?>" required>
            <input type="text" name="Code_fi" placeholder="Code Filière" value="<?= $etud_a_modifier['Code_fi'] ?? '' ?>" required>
            <input type="text" name="Code_niv" placeholder="Code Niveau" value="<?= $etud_a_modifier['Code_niv'] ?? '' ?>" required>
            <select name="Sexe">
                <option value="M" <?= (isset($etud_a_modifier) && $etud_a_modifier['Sexe'] == 'M') ? 'selected' : '' ?>>Masculin</option>
                <option value="F" <?= (isset($etud_a_modifier) && $etud_a_modifier['Sexe'] == 'F') ? 'selected' : '' ?>>Féminin</option>
            </select>
            <button type="submit" name="enregistrer"><?= $etud_a_modifier ? 'Modifier' : 'Ajouter' ?></button>
        </form>

        <table border="1" style="width:100%; margin-top:20px;">
            <tr>
                <th>ID</th><th>Nom</th><th>Prénom</th><th>Filière</th><th>Sexe</th><th>Actions</th>
            </tr>
            <?php foreach ($etudiants as $e): ?>
            <tr>
                <td><?= $e['id_etud'] ?></td>
                <td><?= $e['nom'] ?></td>
                <td><?= $e['prenom'] ?></td>
                <td><?= $e['Code_fi'] ?></td>
                <td><?= $e['Sexe'] ?></td>
                <td>
                    <a href="?Modifier=<?= $e['id_etud'] ?>">Modifier</a> | 
                    <a href="?supprimer=<?= $e['id_etud'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>

</body>
</html>

