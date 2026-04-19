<?php
require_once '../Backend/db.php';

// --- LOGIQUE DE SUPPRESSION ---
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM professeur WHERE id_professeur = ?");
    $stmt->execute([$id]);
    header("Location: listeProf.php"); // Rediriger pour nettoyer l'URL
    exit();
}

// --- LOGIQUE D'AJOUT OU MODIFICATION ---
if (isset($_POST['enregistrer'])) {
    $id = $_POST['id_professeur'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $specialite = $_POST['specialite'];
    $mdp = $_POST['mdp']; 

    if (isset($_POST['mode_edition']) && $_POST['mode_edition'] == '1') {
        // UPDATE
        $sql = "UPDATE professeur SET nom=?, prenom=?, email=?, mdp=?, specialite=? WHERE id_professeur=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $mdp, $specialite, $id]);
    } else {
        // INSERT
        $sql = "INSERT INTO professeur (id_professeur, nom, prenom, email, mdp, specialite) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $nom, $prenom, $email, $mdp, $specialite]);
    }
    header("Location: listeProf.php");
    exit();
}

// --- RÉCUPÉRATION DES DONNÉES POUR MODIFIER ---
$prof_a_modifier = null;
if (isset($_GET['Modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM professeur WHERE id_professeur = ?");
    $stmt->execute([$_GET['Modifier']]);
    $prof_a_modifier = $stmt->fetch();
}

// --- LISTE DES PROFESSEURS ---
$professeurs = $pdo->query("SELECT * FROM professeur")->fetchAll();
?>

<link rel="stylesheet" href="secretaire.css">
<header>
    <nav>
        <h1>📚 EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </nav>
</header>

<main class="container-principal">
    <div class="left-container">
        <ul class="nav-menu">
            <li><a href="Interfacesecretaire.php" class="nav-link">Accueil</a></li>
            <li><a href="Listeetudiant.php" class="nav-link">Étudiants</a></li>
            <li><a href="Listematiere.php" class="nav-link">Matière</a></li>
            <li><a href="listefi.php" class="nav-link">Filière</a></li>
            <li><a href="Listeprof.php" class="nav-link active">Professeurs</a></li>
            <li><a href="Index.php" class="nav-link">Déconnexion</a></li>
        </ul>
    </div>

    <div class="right-container">
        <h1><strong>Gestion des Professeurs</strong></h1>

        <!-- Formulaire Complet -->
        <form method="POST" class="form-gestion">
            <input type="hidden" name="mode_edition" value="<?= $prof_a_modifier ? '1' : '0' ?>">
            
            <input type="text" name="id_professeur" placeholder="ID Prof" value="<?= $prof_a_modifier['id_professeur'] ?? '' ?>" <?= $prof_a_modifier ? 'readonly' : 'required' ?>>
            <input type="text" name="nom" placeholder="Nom" value="<?= $prof_a_modifier['nom'] ?? '' ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $prof_a_modifier['prenom'] ?? '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $prof_a_modifier['email'] ?? '' ?>" required>
            <input type="password" name="mdp" placeholder="Mot de passe" value="<?= $prof_a_modifier['mdp'] ?? '' ?>" required>
            <input type="text" name="specialite" placeholder="Spécialité" value="<?= $prof_a_modifier['specialite'] ?? '' ?>" required>
              <br>
              <br>
            <button type="submit" name="enregistrer"> 
        
                <?= $prof_a_modifier ? 'Mettre à jour' : 'Ajouter le professeur' ?>
            </button>
            <?php if($prof_a_modifier): ?> <a href="listeProf.php">Annuler</a> <?php endif; ?>
        </form>

        <table border="1">
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Spécialité</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($professeurs as $p): ?>
            <tr >
                <td class="dedans"><?= htmlspecialchars($p['id_professeur']) ?></td>
                <td class="dedans"><?= htmlspecialchars($p['nom']) ?></td>
                <td class="dedans"><?= htmlspecialchars($p['prenom']) ?></td>
                <td class="dedans"><?= htmlspecialchars($p['email']) ?></td>
                <td class="dedans"><?= htmlspecialchars($p['specialite']) ?></td>
                <td>
                    <a href="?Modifier=<?= $p['id_professeur'] ?>">Modifier</a> | 
                    <a href="?supprimer=<?= $p['id_professeur'] ?>" onclick="return confirm('Supprimer ce professeur ?')" style="color:white;">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
