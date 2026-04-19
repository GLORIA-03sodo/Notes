 <?php
 
session_start();
require_once '../Backend/db.php';

$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $prenom = trim($_POST['firstname']);
    $nom = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $specialite = trim($_POST['specialty']);
    $mdp_saisi = $_POST['password'];
    $confirm_mdp = $_POST['confirm_password'];

    // Vérification de la correspondance des mots de passe
    if ($mdp_saisi !== $confirm_mdp) {
        $message_erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // 1. On récupère les infos du prof par son email/nom/prenom
        $sql = "SELECT mdp FROM professeur WHERE nom = ? AND prenom = ? AND email = ? AND specialite = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $specialite]);
        $professeur = $stmt->fetch();

        // 2. On vérifie si le prof existe ET si le mot de passe correspond au hash stocké
        if ($professeur && password_verify($mdp_saisi, $professeur['mdp'])) {
            // Si les informations sont conformes
            header("Location: connexion.php");
            exit();
        } else {
            // Si aucune correspondance n'est trouvée ou mdp faux
            $message_erreur = "identifiants non conformes";
        }
    }
}
?>

<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription-prof</title>
    <link rel="stylesheet" href="Inscription.css">
    <style>
    
    </style>
 </head>
 <body>
    <header>
        <div class="brand">📚EduNotes</div>
        <div class="nav-actions">
            <a href="Interfaceprof.php">Entrer</a>
        </div>
    </header>
     <div class="form-container">
        <h1>Inscription Professeur</h1>

        <!-- Affichage du message d'erreur -->
        <?php if (!empty($message_erreur)): ?>
            <div class="error-msg"><?php echo $message_erreur; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse email </label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone</label>
                <input type="tel" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="specialty">Spécialité</label>
                <input type="text" id="specialty" name="specialty" placeholder="Ex: Mathématiques, Français..." required>
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
        <br>
         <br>
         <footer>
        <p>@ 2026 - Gestion des notes - Consultation des notes</p>
        </footer>
 </body>
 </html>
