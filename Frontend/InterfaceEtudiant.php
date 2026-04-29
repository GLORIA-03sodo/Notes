 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Étudiant | Premium</title>
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-body: #ffffff;
            --bg-card: #f8fafc;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            padding: 40px 20px;
            line-height: 1.5;
        }
        
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
        }
        
        header h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        .user-info {
            text-align: right;
        }

        .user-info p:first-child {
            font-weight: 600;
            font-size: 14px;
        }

        .user-info p:last-child {
            color: var(--text-muted);
            font-size: 12px;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .card {
            background: var(--bg-card);
            padding: 24px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        
        .card:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        
        .card-icon {
            font-size: 20px;
            margin-bottom: 16px;
            display: inline-block;
            background: white;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .card h2 {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .card-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }
        
        .card p {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 4px;
        }

        /* Couleurs spécifiques pour les valeurs */
        .val-moyenne { color: var(--primary); }
        .val-valide { color: var(--success); }
        .val-total { color: var(--text-main); }
        .val-echec { color: var(--danger); }
        
        .table-section {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header h2 {
            font-size: 18px;
            font-weight: 700;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            padding: 16px 24px;
            text-align: left;
            background: #fcfcfd;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        
        table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        table tr:last-child td {
            border-bottom: none;
        }
        
        table tbody tr:hover {
            background: #f8fafc;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success { background: #ecfdf5; color: #065f46; }
        .badge-danger { background: #fef2f2; color: #991b1b; }

        @media (max-width: 900px) {
            .dashboard { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>Tableau de bord</h1>
            </div>
            <div class="user-info">
                <p>Bienvenue, Étudiant</p>
                <p>ID: E001234</p>
            </div>
        </header>
        
        <div class="dashboard">
            <div class="card">
                <div class="card-icon">📈</div>
                <h2>Moyenne</h2>
                <div class="card-value val-moyenne">14.5<small style="font-size: 14px;">/20</small></div>
                <p>Semestre actuel</p>
            </div>
            
            <div class="card">
                <div class="card-icon">🛡️</div>
                <h2>Validés</h2>
                <div class="card-value val-valide">08</div>
                <p>Sur 12 unités</p>
            </div>
            
            <div class="card">
                <div class="card-icon">📚</div>
                <h2>Inscrits</h2>
                <div class="card-value val-total">12</div>
                <p>Cours totaux</p>
            </div>
            
            <div class="card">
                <div class="card-icon">⚠️</div>
                <h2>Restants</h2>
                <div class="card-value val-echec">04</div>
                <p>À valider</p>
            </div>
        </div>
        
        <div class="table-section">
            <div class="table-header">
                <span>📖</span>
                <h2>Mes Cours et Notes</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Professeur</th>
                        <th>Note</th>
                        <th>Moyenne Classe</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Algorithmique Avancée</strong></td>
                        <td>Dr. Martin</td>
                        <td>16.0</td>
                        <td>12.5</td>
                        <td><span class="badge badge-success">Validé</span></td>
                    </tr>
                    <tr>
                        <td><strong>Base de données NoSQL</strong></td>
                        <td>Mme. Leroy</td>
                        <td>09.5</td>
                        <td>11.0</td>
                        <td><span class="badge badge-danger">Non validé</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


<!-- ANCIEN CODE  -->


<!-- <!DOCTYPE html>
 <html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Étudiant</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .card h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .card-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .card p {
            color: #666;
            font-size: 14px;
        }
        
        .table-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table thead {
            background: #f8f9fa;
        }
        
        table th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>📚 Tableau de Bord Étudiant</h1>
            </div>
            <div class="user-info">
                <p><strong>Bienvenue</strong></p>
                <p>Étudiant ID: E001234</p>
            </div>
        </header>
        
        <div class="dashboard">
            <div class="card">
                <div class="card-icon">📊</div>
                <h2>Moyenne Générale</h2>
                <div class="card-value">14.5/20</div>
                <p>Semestre actuel</p>
            </div>
            
            <div class="card">
                <div class="card-icon">✅</div>
                <h2>Cours Validés</h2>
                <div class="card-value">8</div>
                <p>Parmi 12 cours</p>
            </div>
            
            <div class="card">
                <div class="card-icon">📝</div>
                <h2>Total des Cours</h2>
                <div class="card-value">12</div>
                <p>Semestre actuel</p>
            </div>
            
            <div class="card">
                <div class="card-icon">❌</div>
                <h2>Cours non Validés</h2>
                <div class="card-value">4</div>
                <p>Parmi 12 cours</p>
            </div>
        </div>
        
        <div class="table-section">
            <h2>📖 Mes Cours et Notes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Professeur</th>
                        <th>Note</th>
                        <th>Moyenne Classe</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                  
            </table>
        </div>
    </div>
</body>
</html> -->