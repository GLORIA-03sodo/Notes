 <?php
require_once '../Backend/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['lastname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "<p style='color:red;'>Les mots de passe ne correspondent pas.</p>";
    } elseif (strlen($password) < 8) {
        $message = "<p style='color:red;'>Le mot de passe doit faire au moins 8 caractères.</p>";
    } else {
        try {
            // 1. Vérifier si l'email est déjà utilisé
            $checkEmail = $pdo->prepare("SELECT id_admin FROM administrateur WHERE email = ?");
            $checkEmail->execute([$email]);

            if ($checkEmail->rowCount() > 0) {
                $message = "<p style='color:red;'>Cet email est déjà utilisé par un autre administrateur.</p>";
            } else {
                // 2. Vérifier si le mot de passe est déjà utilisé par quelqu'un d'autre
                // On récupère tous les admins pour comparer les hashs
                $stmtPasswords = $pdo->query("SELECT mdp FROM administrateur");
                $all_passwords = $stmtPasswords->fetchAll();
                $password_exists = false;

                foreach ($all_passwords as $row) {
                    if (password_verify($password, $row['mdp'])) {
                        $password_exists = true;
                        break;
                    }
                }

                if ($password_exists) {
                    $message = "<p style='color:red;'>Ce mot de passe est déjà utilisé. Veuillez en choisir un autre pour plus de sécurité.</p>";
                } else {
                    // 3. Tout est OK : Hachage et Insertion
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO administrateur (nom, mdp, email) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nom, $hashed_password, $email]);

                    header("Location: connexion.php?success=admin_created");
                    exit();
                }
            }
        } catch (PDOException $e) {
            $message = "<p style='color:red;'>Erreur BDD : " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateur</title>
    <link rel="stylesheet" href="Inscriptionsecre.css">
</head>
<body>
    <header>
        <h1>📚 EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </header>

    <div class="form-container">
        <h1>Inscription Administration</h1>
        
        <?= $message ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="lastname">Nom complet</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required>
            </div>
           
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Au moins 8 caractères" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit">S'inscrire</button>

            <div class="form-footer">
                <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            </div>
        </form>
    </div>
    <footer>
        <p>@ 2026 - Gestion des notes - Consultation des notes</p>
        
    </footer>
</body>
</html>
