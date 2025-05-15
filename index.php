<?php
/* Classi */
include_once 'Class/Database.php';
include_once 'Class/Helper.php';

/* DB */
$db = new Database();
$db->setIgnoreErrors(true);
$db->connect();
$db->createAllTable();

/* Helper */
$help = new Helper();

/* Session */
session_start();
if (!isset($_SESSION['user'])) {
    $access = $help->getAccess(false);
} else {
    $access = $help->getAccess(true, $_SESSION['user']['level']);
}

/* Lingua */
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'it';
$langfile = $help->loadLanguage($lang);

/* Page */
$page = isset($_GET['page']) ? $_GET['page'] : 'modality';

/* var_dump($_SESSION); */

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <?php include_once 'Block/head.php'; ?>
</head>

<body>
    <?php ob_start(); ?>
    <div class="header">
        <?php include_once 'Block/navbar.php'; ?>
    </div>
    <main class="my-5">
        <?php include_once 'Menu/' . $page . '.php'; ?>
    </main>
    <div class="footer">
        <?php include_once 'Block/footer.php'; ?>
    </div>

</body>

</html>