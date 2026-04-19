 <!DOCTYPE html>
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
</html>