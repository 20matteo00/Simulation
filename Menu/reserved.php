<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] !== 1) {
    header("Location: index.php?lang=" . $lang);
    exit();
}

?>