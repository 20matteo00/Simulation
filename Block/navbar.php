<?php
$menu = [
    'group',
    'team',
    'modality',
];
$l = [
    'it',
    'en',
];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="index.php"><?= $langfile['site_name'] ?></a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($menu as $m): ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= "Menu/$m.php" ?>"><?= $langfile[$m] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="ms-auto me-3 mb-3 mb-lg-0">
                <!-- Language selector -->
                <form method="get" action="">
                    <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($l as $langOption): ?>
                            <option value="<?= $langOption ?>" <?= $lang === $langOption ? 'selected' : '' ?>>
                                <?= strtoupper($langOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div>
                <!-- Search form -->
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="<?= $langfile['search'] ?>…"
                        aria-label="<?= $langfile['search'] ?>">
                    <button class="btn btn-outline-primary" type="submit"><?= $langfile['search'] ?></button>
                </form>
            </div>
        </div>
    </div>
</nav>