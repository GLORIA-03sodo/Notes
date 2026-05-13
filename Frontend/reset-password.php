<?php
require_once '../Backend/db.php';

$token = $_GET['token'] ?? '';
$role = $_GET['role'] ?? ''; // On récupère le rôle via l'URL

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$request = $stmt->fetch();

if (!$request || !in_array($role, ['professeur', 'etudiant', 'administrateur'])) {
    die("Lien invalide ou expiré.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_mdp = $_POST['password']; 
    // ATTENTION : Pour professeur, tu n'utilises pas password_hash dans ton code login.
    // Pour étudiant et admin, tu l'utilises. On s'adapte :
    
    if ($role === 'professeur') {
        $final_password = $new_mdp; // Simple texte comme dans ton login actuel
    } else {
        $final_password = password_hash($new_mdp, PASSWORD_DEFAULT);
    }

    // Définir la colonne ID selon la table
    $id_column = ($role === 'professeur') ? 'id_professeur' : (($role === 'etudiant') ? 'id_etud' : 'id_admin');

    $update = $pdo->prepare("UPDATE $role SET mdp = ? WHERE email = ?");
    $update->execute([$final_password, $request['email']]);

    $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$request['email']]);

    echo "Succès ! <a href='Connexion.php'>Se connecter</a>";
}
?>

<!DOCTYPE html>
<html>
<head><title>Nouveau mot de passe</title></head>
<body>
    <h2>Saisissez votre nouveau mot de passe</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Changer le mot de passe</button>
    </form>
</body>
</html>