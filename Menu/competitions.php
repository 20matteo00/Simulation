<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?lang=" . $lang);
    exit();
}
if (isset($_GET['action']) && isset($_GET['mod'])) {
    $mod = $_GET['mod'];
    $action = $_GET['action'];
    $allTeams = $db->getAll("teams");
    $allGroups = $help->getAllGroups($allTeams);
    $campi = $help->getModalityParams($mod);
    if ($action === 'create') {
?>
        <div class="container">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <div class="card bg-white text-dark shadow rounded-4">
                <div class="card-header">
                    <h2 class="text-center"><?= $langfile['create_competition'] . " - " . $langfile[$mod] ?></h2>
                </div>
                <div class="card-body">
                    <form class="p-4 rounded" method="post" action="">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserisci nome">
                            </div>

                            <div class="col-md-4">
                                <label for="group" class="form-label"><?= $langfile['groups'] ?></label>
                                <select id="group" name="group[]" class="form-select" multiple size="4">
                                    <?php foreach ($allGroups as $g): ?>
                                        <option value="<?= htmlspecialchars($g) ?>">
                                            <?= htmlspecialchars($g) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <?= $langfile['select_multiple'] ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="team" class="form-label"><?= $langfile['teams'] ?></label>
                                <select id="team" name="team[]" class="form-select" multiple size="4">
                                    <?php foreach ($allTeams as $t): ?>
                                        <?php $params = json_decode($t['params'], true); ?>
                                        <option value="<?= htmlspecialchars($t['name']) ?>" data-group="<?= htmlspecialchars(implode(', ', $params['groups'])) ?>">
                                            <?= htmlspecialchars($t['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <?= $langfile['select_multiple'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($campi as $name => $config): ?>
                                <div class="col-md-2 d-flex flex-column mt-auto">
                                    <label for="<?= $name ?>" class="form-label">
                                        <?= $langfile[$name] ?>
                                    </label>

                                    <?php if ($config['type'] === 'select' && isset($config['options'])): ?>
                                        <select id="<?= $name ?>" name="<?= $name ?>" class="form-select">
                                            <?php foreach ($config['options'] as $value => $label): ?>
                                                <option value="<?= $value ?>" <?= ($value == $config['default']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($label) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input
                                            type="<?= htmlspecialchars($config['type']) ?>"
                                            id="<?= $name ?>"
                                            name="<?= $name ?>"
                                            class="form-control"
                                            value="<?= htmlspecialchars($config['default']) ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Invia</button>
                    </form>
                </div>
            </div>
        </div>
<?php
    }
} else {
}
?>