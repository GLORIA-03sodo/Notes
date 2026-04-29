<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes - Accueil</title>
    <link rel="stylesheet" href="Accueil.css">
</head>
<body>
    <!-- Header -->
    <header>
        <h1>EduNotes</h1>
        <button class="btn-retour" onclick="window.location.href='index.php'">Retour</button>
    </header>
    
    <!-- Main Content -->
    <main>
        <h1>Bienvenue sur la plateforme Notes</h1>
        <br>
        <strong><p>Sélectionnez votre profil pour accéder à votre espace</p></strong>
        
        <!-- 3 Sections -->
        <div class="sections-container">
            <!-- Section Étudiant -->
            <div class="section-card">
                <div class="section-icon">👨‍🎓</div>
                <h3>Espace Étudiant</h3>
                <p>Consultez vos notes et suivez votre progression académique</p>
                  <a href="connexion.php"><button class="section-btn" onclick="rediriger('etudiant')">Accéder</button></a>
            </div>
            
            <!-- Section Professeur -->
            <div class="section-card">
                <div class="section-icon">👨‍🏫</div>
                <h3>Espace Professeur</h3>
                <p>Gérez les notes de vos étudiants facilement</p>
                  <a href="connexion.php"><button class="section-btn" onclick="rediriger('professeur')">Accéder</button></a>
            </div>
            
            <!-- Section Administration -->
            <div class="section-card">
                <div class="section-icon">👔</div>
                <h3>Espace Administration</h3>
                <p>Administrez la plateforme et les utilisateurs</p>
                  <a href="connexion.php"><button class="section-btn" onclick="rediriger('administration')">Accéder</button></a>
            </div>
        </div>
    </main>
    
  
    <!-- Footer -->
    <footer>
        <p>@ 2026 - Gestion des notes - Consultation des notes</p>
    
    </footer>
    
</body>
</html>
