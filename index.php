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
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'it';

/* Page */
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

/* Helper */
$help = new Helper();
$langfile = $help->loadLanguage($lang);

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <?php include_once 'Block/head.php'; ?>
</head>

<body>
    <div class="header">
        <?php include_once 'Block/navbar.php'; ?>
    </div>
    <main>
        <?php include_once 'Menu/' . $page . '.php'; ?>
    </main>
    <div class="footer">
        <?php include_once 'Block/footer.php'; ?>
    </div>

</body>

</html>