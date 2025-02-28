<?php
session_start();

if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['secretNumber'])) {
    $_SESSION['secretNumber'] = rand(1, 20);
    $_SESSION['guessesTaken'] = 0;
    $_SESSION['message'] = "I'm thinking of a number between 1 and 20.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guess'])) {
    $guess = intval($_POST['guess']);
    $_SESSION['guessesTaken']++;

    if ($guess < $_SESSION['secretNumber']) {
        $_SESSION['message'] = "Your guess is too low.";
    } elseif ($guess > $_SESSION['secretNumber']) {
        $_SESSION['message'] = "Your guess is too high.";
    } else {
        $_SESSION['message'] = "Congratulations! You got the number right in " . $_SESSION['guessesTaken'] . " tries!";
        $_SESSION['gameOver'] = true;
    }
    
    if ($_SESSION['guessesTaken'] >= 6 && $guess != $_SESSION['secretNumber']) {
        $_SESSION['message'] = "It didn't happen this time. The number I was thinking of was " . $_SESSION['secretNumber'] . ".";
        $_SESSION['gameOver'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Guessing Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Guessing Game</h1>
    <p class="message"><?php echo $_SESSION['message']; ?></p>
    
    <?php if (!isset($_SESSION['gameOver'])) { ?>
        <form method="post">
            <input type="number" name="guess" min="1" max="20" required>
            <input type="submit" value="Guess">
        </form>
    <?php } else { ?>
        <form method="post">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="Play Again">
        </form>
    <?php } ?>
</div>
</body>
</html>