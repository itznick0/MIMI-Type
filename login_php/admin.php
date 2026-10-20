<?php
// admin.php - pannello di amministrazione
session_start();

// se non sono loggato come admin, torno al login
if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] != "admin") {
    header('location: login.php');
    exit();
}

// CREARE UN NUOVO UTENTE
if (isset($_POST['crea'])) {
    $nuovo = $_POST['utente'];
    $pass = $_POST['password'];

    // apro il file in append e scrivo la nuova riga
    $file = fopen("credenziali.txt", "a");
    fwrite($file, $nuovo . "|" . $pass . "|standard\n");
    fclose($file);

    header('location: admin.php'); // ricarico la pagina
    exit();
}

// CANCELLARE UN UTENTE
if (isset($_GET['elimina'])) {
    $da_cancellare = $_GET['elimina'];

    // leggo tutte le righe tranne quella dell'utente da cancellare
    $letto = file("credenziali.txt");
    $file = fopen("credenziali.txt", "w");

    foreach ($letto as $riga) {
        $dati = explode("|", trim($riga));
        if ($dati[0] != $da_cancellare) {
            fwrite($file, $riga); // riscrivo le altre righe
        }
    }
    fclose($file);

    header('location: admin.php');
    exit();
}

// MODIFICARE UN UTENTE
if (isset($_POST['modifica'])) {
    $vecchio = $_POST['vecchio'];
    $nuovo = $_POST['utente'];
    $pass = $_POST['password'];

    $letto = file("credenziali.txt");
    $file = fopen("credenziali.txt", "w");

    foreach ($letto as $riga) {
        $dati = explode("|", trim($riga));
        if ($dati[0] == $vecchio) {
            // sostituisco con i nuovi dati
            fwrite($file, $nuovo . "|" . $pass . "|" . $dati[2] . "\n");
        } else {
            fwrite($file, $riga);
        }
    }
    fclose($file);

    header('location: admin.php');
    exit();
}

// leggo gli utenti per mostrarli nella tabella
$utenti = file("credenziali.txt");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello Admin</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="box">
        <h2>Pannello Amministratore</h2>
        <p>Ciao <?php echo $_SESSION['utente']; ?>!</p>

        <!-- FORM PER CREARE UN NUOVO UTENTE -->
        <h3>Crea nuovo utente</h3>
        <form method="post">
            <label>Utente:</label>
            <input type="text" name="utente" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="crea">Crea</button>
        </form>

        <!-- TABELLA CON GLI UTENTI ESISTENTI -->
        <h3>Utenti esistenti</h3>
        <table border="1" cellpadding="5" style="width:100%; border-collapse: collapse;">
            <tr>
                <th>Utente</th>
                <th>Password</th>
                <th>Ruolo</th>
                <th>Azioni</th>
            </tr>
            <?php foreach ($utenti as $riga):
                $dati = explode("|", trim($riga)); ?>
                <tr>
                    <td><?php echo $dati[0]; ?></td>
                    <td><?php echo $dati[1]; ?></td>
                    <td><?php echo $dati[2]; ?></td>
                    <td>
                        <!-- form per modificare: passo il nome vecchio nascosto -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="vecchio" value="<?php echo $dati[0]; ?>">
                            <input type="text" name="utente" value="<?php echo $dati[0]; ?>" style="width:80px;">
                            <input type="text" name="password" value="<?php echo $dati[1]; ?>" style="width:80px;">
                            <button type="submit" name="modifica">Modifica</button>
                        </form>
                        <a href="admin.php?elimina=<?php echo $dati[0]; ?>">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
