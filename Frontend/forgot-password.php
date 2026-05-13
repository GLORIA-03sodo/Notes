<?php
require_once '../Backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $role = null;

    // Vérifier dans les 3 tables
    $tables = ['professeur', 'etudiant', 'administrateur'];
    foreach ($tables as $t) {
        $stmt = $pdo->prepare("SELECT email FROM $t WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $role = $t;
            break;
        }
    }

    if ($role) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // On stocke l'email ET le rôle pour le fichier reset-password
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        $insert = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $insert->execute([$email, $token, $expires]);

        // En local, affiche le lien directement pour tester
        $link = "http://localhost/NOTES/Frontend/reset-password.php?token=$token&role=$role";
        echo "Lien de test : <a href='$link'>Réinitialiser mon mot de passe</a>";
    } else {
        echo "Email non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Mot de passe oublié</title></head>
<body>
    <h2>Récupération de mot de passe</h2>
    <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Votre email" required>
        <button type="submit">Envoyer le lien</button>
    </form>
</body>
</html>