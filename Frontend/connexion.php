 <?php
session_start();
require_once '../Backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //  utilisation de name="username" pour l'email
    $email = htmlspecialchars($_POST['username']); 
    $password = $_POST['password'];

    //  Vérification PROFESSEUR
    $stmt = $pdo->prepare("SELECT * FROM professeur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && ($password== $user['mdp'])) {
        $_SESSION['user_id'] = $user['id_professeur'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = 'professeur'; 
        header("Location: ../Frontend/Interfaceprof.php");
        exit();
    } elseif ($user) { // Email trouvé mais mauvais mot de passe
        header("Location: Inscriptionprof.php");
        exit();
    }

    // Vérification ÉTUDIANT (si non trouvé chez les profs)
    $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mdp'])) {
        $_SESSION['user_id'] = $user['id_etud'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = 'etudiant';
        header("Location: ../Frontend/InterfaceEtudiant.php");
        exit();
    } elseif ($user) { // Email trouvé mais mauvais mot de passe
        header("Location: InterfaceEtudiant.php");
        exit();
    }

    //  Vérification ADMINISTRATEUR
    $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mdp'])) {
        $_SESSION['user_id'] = $user['id_admin'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = 'administrateur';
        header("Location: ../Frontend/InterfaceSecretaire.php");
        exit();
    } else {
        // Si rien ne correspond du tout
        header("Location: Inscriptionsecre.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNotes</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>
     <!-- Header -->
    <header>
        <h1>EduNotes</h1>
        <button class="btn-connexion" onclick="window.location.href='Accueil.php'">Retour</button>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="login-form">
            <h2>Connexion</h2>
            <form action=" " method="post">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur ou Email</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
            <div class="forgot-password">
         <p>Mot de passe oublié? veuillez vous inscrire </p>
            
                
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer>
        <p>@ 2026 - Gestion des notes - Consultation des notes</p>
        
    </footer> 
</body>
</html>
