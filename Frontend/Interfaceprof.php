 <?php
session_start();
require_once '../Backend/db.php';

// 1. SÉCURITÉ : Vérifier si c'est bien un professeur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'professeur') {
    header("Location: connexion.php");
    exit();
}

$id_prof = $_SESSION['user_id'];
$message = "";
$etudiants = [];
$filiere_selectionnee = $_POST['filiere'] ?? '';
$matiere_selectionnee = $_POST['matiere'] ?? '';

try {
    // A. Charger les filières du professeur
    $queryFil = $pdo->prepare("SELECT DISTINCT f.Code_fi, f.libelle_fi 
                               FROM filieres f 
                               JOIN matiere m ON f.Code_fi = m.Code_fi 
                               WHERE m.id_professeur = ?");
    $queryFil->execute([$id_prof]);
    $filieres = $queryFil->fetchAll();

    // B. Charger les matières selon la filière
    $matieres = [];
    if ($filiere_selectionnee) {
        $queryMat = $pdo->prepare("SELECT id_matiere, noml_matiere FROM matiere 
                                   WHERE Code_fi = ? AND id_professeur = ?");
        $queryMat->execute([$filiere_selectionnee, $id_prof]);
        $matieres = $queryMat->fetchAll();
    }

    // C. Charger les étudiants et leurs notes
    if (isset($_POST['charger']) && $filiere_selectionnee && $matiere_selectionnee) {
        $sql = "SELECT e.id_etud, e.nom, e.prenom, e.email, n.note 
                FROM etudiant e 
                LEFT JOIN note n ON e.id_etud = n.id_etud AND n.id_matiere = ?
                WHERE e.Code_fi = ?";
        $queryEtud = $pdo->prepare($sql);
        $queryEtud->execute([$matiere_selectionnee, $filiere_selectionnee]);
        $etudiants = $queryEtud->fetchAll();
    }

    // D. TRAITEMENT : AJOUT / MODIFICATION / SUPPRESSION
    if (isset($_POST['enregistrer'])) {
        $id_mat = $_POST['matiere_hidden'];
        $notes_saisies = $_POST['notes']; 

        $pdo->beginTransaction();
        
        // Préparation des requêtes
        $stmtSave = $pdo->prepare("INSERT INTO note (id_etud, id_matiere, note) VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE note = VALUES(note)");
        $stmtDelete = $pdo->prepare("DELETE FROM note WHERE id_etud = ? AND id_matiere = ?");
        
        foreach ($notes_saisies as $id_etud => $valeur_note) {
            if ($valeur_note !== "" && $valeur_note !== null) {
                // Si une valeur est saisie -> On ajoute ou on modifie
                $stmtSave->execute([$id_etud, $id_mat, $valeur_note]);
            } else {
                // suppression de la note si le champs est vide
                $stmtDelete->execute([$id_etud, $id_mat]);
            }
        }
        
        $pdo->commit();
        $message = "<div class='success-message'> Mise à jour effectuée (Notes enregistrées et/ou supprimées).</div>";
        
        // Recharger la liste après enregistrement pour voir les changements
        $sql = "SELECT e.id_etud, e.nom, e.prenom, e.email, n.note 
                FROM etudiant e 
                LEFT JOIN note n ON e.id_etud = n.id_etud AND n.id_matiere = ?
                WHERE e.Code_fi = ?";
        $queryEtud = $pdo->prepare($sql);
        $queryEtud->execute([$id_mat, $_POST['filiere_hidden']]);
        $etudiants = $queryEtud->fetchAll();
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $message = "<div class='error-message'>Erreur : " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Professeur - Gestion des Notes</title>
        <link rel="stylesheet" href="Interfaceprof.css">
    <style>
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Quitter</a>
        
        <div class="header">
            <h1>📊 Gestion des Notes</h1>
            <p>Professeur : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong></p>
        </div>

        <?= $message ?>

        <form method="POST" id="filterForm">
            <div class="filters">
                <div class="filter-group">
                    <label>Filière :</label>
                    <select name="filiere" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Sélectionner une filière --</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?= $f['Code_fi'] ?>" <?= ($filiere_selectionnee == $f['Code_fi']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f['libelle_fi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Matière :</label>
                    <select name="matiere">
                        <option value="">-- Choisir la matière --</option>
                        <?php foreach ($matieres as $m): ?>
                            <option value="<?= $m['id_matiere'] ?>" <?= ($matiere_selectionnee == $m['id_matiere']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['noml_matiere']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="charger" class="btn" style="background: #3498db;">Charger la liste</button>
        </form>

        <form method="POST" style="margin-top:30px;">
            <input type="hidden" name="matiere_hidden" value="<?= $matiere_selectionnee ?>">
            <input type="hidden" name="filiere_hidden" value="<?= $filiere_selectionnee ?>">
            
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Email</th>
                        <th>Note (/20)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($etudiants)): ?>
                        <tr><td colspan="4" style="text-align:center; padding:30px; color:#999;">Sélectionnez une filière et une matière pour commencer.</td></tr>
                    <?php else: ?>
                        <?php foreach ($etudiants as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['nom'] . " " . $e['prenom']) ?></td>
                                <td><?= htmlspecialchars($e['email']) ?></td>
                                <td>
                                    <input type="number" name="notes[<?= $e['id_etud'] ?>]" 
                                           min="0" max="20" step="0.25" 
                                           value="<?= $e['note'] ?>" 
                                           placeholder="Vide pour supprimer">
                                </td>
                                <td style="font-size: 12px; color: #7f8c8d;">
                                    <?= ($e['note'] !== null) ? "Enregistrée" : "Non saisie" ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($etudiants)): ?>
            <div style="margin-top:20px; text-align:right;">
                <button type="submit" name="enregistrer" class="btn btn-save">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
