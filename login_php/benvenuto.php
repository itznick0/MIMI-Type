<?php
// benvenuto.php - pagina per gli utenti standard
session_start();

// se non sono loggato, torno al login
if (!isset($_SESSION['utente'])) {
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Benvenuto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="box">
        <h2>Benvenuto!</h2>
        <p>Ciao <?php echo $_SESSION['utente']; ?>, sei loggato con successo.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
