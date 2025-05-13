<?php
/* Classi */
include_once 'Class/Database.php';
include_once 'Class/Helper.php';

/* DB */
$db = new Database();
$db->setIgnoreErrors(true);
$db->connect();
$db->createAllTable();

/* Lingua */
if (isset($_GET['lang']) || isset($_SESSION['lang'])) {
    $lang = isset($_GET['lang']) ? $_GET['lang'] : $_SESSION['lang'];
} else {
    $lang = 'it';
}

/* Helper */
$lg = new Helper(); 
$langfile = $lg->loadLanguage($lang);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once 'Block/head.php'; ?>
</head>
<body>
    <?php include_once 'Block/navbar.php'; ?>

</body>

</html>