<?php
// login.php - pagina di login
session_start();

$errore = ""; // messaggio di errore se il login fallisce

if (isset($_POST['invia'])) {
    // l'utente ha premuto il bottone "Accedi"

    $utente = $_POST['utente'];
    $password = $_POST['password'];

    // leggo il file delle credenziali
    $file = fopen("credenziali.txt", "r");
    $trovato = false;

    while ($riga = fgets($file)) {
        // ogni riga e': utente|password|ruolo
        $dati = explode("|", trim($riga));

        if ($dati[0] == $utente && $dati[1] == $password) {
            $trovato = true;
            $_SESSION['utente'] = $utente;
            $_SESSION['ruolo'] = $dati[2];

            if ($dati[2] == "admin") {
                header('location: admin.php'); // vado al pannello admin
            } else {
                header('location: benvenuto.php'); // utente standard
            }
            exit();
        }
    }
    fclose($file);

    if (!$trovato) {
        $errore = "Utente o password non validi!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="box">
        <h2>Area Login</h2>
        <p class="error"><?php echo $errore; ?></p>
        <form method="post">
            <label>Utente:</label>
            <input type="text" name="utente" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="invia">Accedi</button>
        </form>
    </div>
</body>
</html>
