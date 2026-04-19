 <?php
session_start();
require_once '../Backend/db.php';

// Sécurité : Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit();
}

try {
    // 1. Récupération des totaux 
    $totalEtudiants = $pdo->query("SELECT COUNT(*) FROM etudiant")->fetchColumn();
    $totalProfesseurs = $pdo->query("SELECT COUNT(*) FROM professeur")->fetchColumn();
    $totalMatieres = $pdo->query("SELECT COUNT(*) FROM matiere")->fetchColumn();
    $totalFilieres = $pdo->query("SELECT COUNT(*) FROM filieres")->fetchColumn();

    // 2. Récupération des 5 dernières activités 
    $activites = [];
    $checkTable = $pdo->query("SHOW TABLES LIKE 'activites'");
    if ($checkTable->rowCount() > 0) {
        $queryActivites = $pdo->query("SELECT description, date_action FROM activites ORDER BY date_action DESC LIMIT 5");
        $activites = $queryActivites->fetchAll();
    }

} catch (PDOException $e) {
    $error = "Erreur de base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Universitaire - Secrétariat</title>
    <link rel="stylesheet" href="secretaire.css">
</head>
<body>
<header>
    <nav>
        <h1>📚 EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </nav>
</header>

<main class="container-principal">
    <div class="left-container">
        <div class="profile-etudiant">
            <p style="padding: 20px; color: white;">
                <i class="fas fa-user-shield"></i> Admin : <br>
                <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
            </p>
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

    <div class="right-container">
        <h2>Tableau de Bord</h2>
        
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-title">Total Étudiants</div>
                <div class="card-value"><?= $totalEtudiants ?></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-book"></i></div>
                <div class="card-title">Total Matières</div>
                <div class="card-value"><?= $totalMatieres ?></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-chalkboard-user"></i></div>
                <div class="card-title">Total Professeurs</div>
                <div class="card-value"><?= $totalProfesseurs ?></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="card-title">Total Filières</div>
                <div class="card-value"><?= $totalFilieres ?></div>
            </div>
        </div>

        <h2 style="margin-top: 30px;">Dernières Activités</h2>
        <div class="activity-container" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <?php if (empty($activites)): ?>
                <p>Aucune activité enregistrée pour le moment.</p>
            <?php else: ?>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($activites as $act): ?>
                        <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                            <small style="color: #666;"><?= date('d/m/Y H:i', strtotime($act['date_action'])) ?></small> - 
                            <strong><?= htmlspecialchars($act['description']) ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>
