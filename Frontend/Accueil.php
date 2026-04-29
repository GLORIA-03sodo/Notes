<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNotes - Portail</title>
    <link rel="stylesheet" href="Accueil.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">EduNotes</div>
        <button class="btn-retour" onclick="window.location.href='index.php'">
            Retour
        </button>
    </header>
    
    <main class="hero-container">
        <section class="intro">
            <h1>Bienvenue sur la plateforme Notes</h1>
            <p>Sélectionnez votre profil pour accéder à votre espace de travail sécurisé.</p>
        </section>
        
        <div class="sections-grid">
            <div class="section-card">
                <div class="card-content">
                    <div class="icon-wrapper">👨‍🎓</div>
                    <h3>Espace Étudiant</h3>
                    <p>Consultez vos notes et suivez votre progression académique en temps réel.</p>
                </div>
                <a href="connexion.php" class="action-link">
                    <button class="section-btn" onclick="rediriger('etudiant')">Accéder à l'espace</button>
                </a>
            </div>
            
            <div class="section-card">
                <div class="card-content">
                    <div class="icon-wrapper">👨‍🏫</div>
                    <h3>Espace Professeur</h3>
                    <p>Gérez les évaluations, saisissez les notes et communiquez avec vos classes.</p>
                </div>
                <a href="connexion.php" class="action-link">
                    <button class="section-btn" onclick="rediriger('professeur')">Accéder à l'espace</button>
                </a>
            </div>
            
            <div class="section-card">
                <div class="card-content">
                    <div class="icon-wrapper">👔</div>
                    <h3>Administration</h3>
                    <p>Contrôle global, gestion des utilisateurs et configuration du système.</p>
                </div>
                <a href="connexion.php" class="action-link">
                    <button class="section-btn" onclick="rediriger('administration')">Accéder à l'espace</button>
                </a>
            </div>
        </div>
    </main>
    
    <footer class="main-footer">
        <p>&copy; 2026 EduNotes — Système de gestion académique</p>
    </footer>
</body>
</html>