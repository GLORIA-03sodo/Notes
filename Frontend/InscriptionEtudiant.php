 <?php
require_once '../Backend/db.php';

$message = "";

// --- 1. RÉCUPÉRATION DES FILIÈRES ET NIVEAUX POUR LE FORMULAIRE ---
try {
    $queryFil = $pdo->query("SELECT Code_fi, libelle_fi FROM filieres");
    $filieres = $queryFil->fetchAll();

    $queryNiv = $pdo->query("SELECT Code_niv, libelle_niv FROM niveau");
    $niveaux = $queryNiv->fetchAll();
} catch (PDOException $e) {
    $message = "<p style='color:red;'>Erreur BDD : " . $e->getMessage() . "</p>";
}

// --- 2. TRAITEMENT DU FORMULAIRE ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $date_naissance = $_POST['dateNaissance'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['motDePasse'];
    $filiere = $_POST['filiere']; 
    $niveau = $_POST['niveau'];   
    $sexe = $_POST['sexe']; 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // ÉTAPE A : Vérifier si l'email est déjà pris par quelqu'un d'autre
        $checkEmail = $pdo->prepare("SELECT id_etud FROM etudiant WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $message = "<p style='color:red;'>Cet email est déjà associé à un compte.</p>";
        } else {
            // ÉTAPE B : Vérifier si l'étudiant a été pré-enregistré par l'admin
            $checkPreReg = $pdo->prepare("SELECT id_etud FROM etudiant WHERE nom = ? AND prenom = ? AND Code_fi = ? AND Code_niv = ?");
            $checkPreReg->execute([$nom, $prenom, $filiere, $niveau]);
            $existingUser = $checkPreReg->fetch();

            if ($existingUser) {
                // ÉTAPE C : Mise à jour (Activation) du compte
                $updateSql = "UPDATE etudiant SET email = ?, mdp = ?, Date_de_naissance = ?, Sexe = ? WHERE id_etud = ?";
                $stmtUpdate = $pdo->prepare($updateSql);
                $stmtUpdate->execute([$email, $hashed_password, $date_naissance, $sexe, $existingUser['id_etud']]);
                
                // Redirection vers la connexion
                header("Location: connexion.php?success=account_activated");
                exit();
            } else {
                // L'étudiant n'est pas dans la liste de l'admin
                $message = "<p style='color:red;'>Erreur : Vos informations (Nom, Prénom, Filière, Niveau) ne correspondent pas à la liste de l'administration.</p>";
            }
        }
    } catch (PDOException $e) {
        $message = "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation Compte Étudiant</title>
    <link rel="stylesheet" href="Inscriptionetudiant.css">
</head> 
<body>
    <header>
        <h1>📚 EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </header>
    <main>
        <div class="container">
            <h1>Inscription Étudiant</h1>
            <?= $message ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="dateNaissance">Date de Naissance:</label>
                    <input type="date" id="dateNaissance" name="dateNaissance" required>
                </div>
                <div class="form-group">
                    <label for="sexe">Sexe:</label>
                    <select id="sexe" name="sexe" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email (pour vos futures connexions):</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="motDePasse">Mot de Passe:</label>
                    <input type="password" id="motDePasse" name="motDePasse" placeholder="8 caractères minimum" required>
                </div>

                <div class="form-group">
                    <label for="filiere">Filière:</label>
                    <select id="filiere" name="filiere" required>
                        <option value="">-- Votre filière --</option>
                        <?php foreach ($filieres as $f) : ?>
                            <option value="<?= htmlspecialchars($f['Code_fi']) ?>">
                                <?= htmlspecialchars($f['libelle_fi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="niveau">Niveau:</label>
                    <select id="niveau" name="niveau" required>
                        <option value="">-- Votre niveau --</option>
                        <?php foreach ($niveaux as $n) : ?>
                            <option value="<?= htmlspecialchars($n['Code_niv']) ?>">
                                <?= htmlspecialchars($n['libelle_niv']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Activer mon compte</button>

                <div class="form-footer">
                    <p>Déjà activé ? <a href="connexion.php">Se connecter</a></p>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>@ 2026 - Gestion des notes - Consultation des notes</p>
        
    </footer>
</body>
</html>
