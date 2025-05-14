<?php
if (!isset($_SESSION['user']['id'])) {
    header("Location: index.php");
    exit();
}
$userId = (int) $_SESSION['user']['id'];
$error = '';
$edit = false;
$origName = null;

// DELETE
if (isset($_GET['delete_name'])) {
    $deleteName = $_GET['delete_name'];
    $db->delete(
        "teams",
        "user_id = ? AND name = ?",
        [$userId, $deleteName]
    );
    header("Location: index.php?page=teams");
    exit;
}

// EDIT MODE: se arrivo con ?name=…
if (isset($_GET['name'])) {
    $origName = $_GET['name'];
    $team = $db->getOne(
        "teams",
        "user_id = ? AND name = ?",
        [$userId, $origName]
    );
    if ($team) {
        $edit = true;
        $name = $team['name'];
        $params = json_decode($team['params'], true);
        $selGroups = $params['groups'] ?? [];
        $potereVal = $params['potere']['valore'] ?? '';
        $potereAtt = $params['potere']['attacco'] ?? '';
        $potereDif = $params['potere']['difesa'] ?? '';
        $colorBack = $params['color']['background'] ?? '#000000';
        $colorText = $params['color']['text'] ?? '#FFFFFF';
        $colorBorder = $params['color']['border'] ?? '#000000';
    }
}

// POST (create o update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nome']);
    $selGroups = $_POST['gruppo'] ?? [];
    $newGroups = array_filter(array_map('trim', explode(',', $_POST['nuovi_gruppi'] ?? '')));
    $groups = array_unique(array_merge($selGroups, $newGroups));
    $potereVal = (int) ($_POST['potere_valore'] ?? 0);
    $potereAtt = (int) ($_POST['potere_attacco'] ?? 0);
    $potereDif = (int) ($_POST['potere_difesa'] ?? 0);
    $colorBack = $_POST['color_background'] ?? '#000000';
    $colorText = $_POST['color_text'] ?? '#FFFFFF';
    $colorBorder = $_POST['color_border'] ?? '#000000';

    if ($name === '') {
        $error = $langfile['empty_name'];
    } else {
        $paramsJson = json_encode([
            'groups' => $groups,
            'potere' => [
                'valore' => $potereVal,
                'attacco' => $potereAtt,
                'difesa' => $potereDif
            ],
            'color' => [
                'background' => $colorBack,
                'text' => $colorText,
                'border' => $colorBorder
            ],
        ]);

        if (!empty($_POST['orig_name'])) {
            // UPDATE
            $db->update(
                "teams",
                ['name' => $name, 'params' => $paramsJson],
                "user_id = ? AND name = ?",
                [$userId, $_POST['orig_name']]
            );
        } else {
            // INSERT (includo user_id)
            $db->insert('teams', [
                'user_id' => $userId,
                'name' => $name,
                'params' => $paramsJson
            ]);
        }

        header("Location: index.php?page=teams");
        exit;
    }
}

// Carico solo le squadre dell’utente
$teams = $db->getAll(
    "teams",
    "user_id = ?",
    [$userId]
);

// Costruisco l’elenco globale dei gruppi
$allGroups = [];
foreach ($teams as $t) {
    $p = json_decode($t['params'], true);
    if (!empty($p['groups'])) {
        $allGroups = array_merge($allGroups, $p['groups']);
    }
}
$allGroups = array_unique($allGroups, SORT_STRING);
sort($allGroups, SORT_STRING);
?>

<div class="container py-5">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif ?>

    <div class="card mb-4 shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center">
                <?= $edit ? $langfile['edit'] . " " . $langfile['team'] : $langfile['new'] . " " . $langfile['team'] ?>
            </h2>
        </div>
        <div class="card-body">
            <form method="post" action="" class="row g-3">
                <?php if ($edit): ?>
                    <input type="hidden" name="orig_name" value="<?= htmlspecialchars($origName) ?>">
                <?php endif ?>

                <!-- Nome -->
                <div class="col-md-4">
                    <label for="nome" class="form-label"><?= $langfile['name'] ?></label>
                    <input type="text" id="nome" name="nome" class="form-control"
                        value="<?= htmlspecialchars($name ?? '') ?>" required>
                </div>

                <!-- Gruppi (select multipla) -->
                <div class="col-md-4">
                    <label for="gruppo" class="form-label"><?= $langfile['groups'] ?></label>
                    <select id="gruppo" name="gruppo[]" class="form-select" multiple size="4">
                        <?php foreach ($allGroups as $g): ?>
                            <option value="<?= htmlspecialchars($g) ?>" <?= in_array($g, $selGroups ?? [], true) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <div class="form-text">
                        <?= $langfile['select_multiple'] ?>
                    </div>
                </div>

                <!-- Nuovi gruppi -->
                <div class="col-md-4">
                    <label for="nuovi_gruppi" class="form-label">
                        <?= $langfile['add'] . " " . $langfile['new'] . " " . $langfile['groups'] ?>
                    </label>
                    <input type="text" id="nuovi_gruppi" name="nuovi_gruppi" class="form-control"
                        placeholder="Es. Serie A, Coppa Italia">
                </div>

                <!-- Potere: Valore, Attacco, Difesa -->
                <div class="col-md-6">
                    <p class="text-center"><?= $langfile['power'] ?></p>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="potere_valore" class="form-label"><?= $langfile['value'] ?></label>
                            <input type="number" id="potere_valore" name="potere_valore" class="form-control"
                                value="<?= htmlspecialchars($potereVal ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="potere_attacco" class="form-label"><?= $langfile['attack'] ?></label>
                            <input type="number" id="potere_attacco" name="potere_attacco" class="form-control"
                                value="<?= htmlspecialchars($potereAtt ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="potere_difesa" class="form-label"><?= $langfile['defense'] ?></label>
                            <input type="number" id="potere_difesa" name="potere_difesa" class="form-control"
                                value="<?= htmlspecialchars($potereDif ?? '') ?>">
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <p class="text-center"><?= $langfile['colors'] ?></p>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="color_background"
                                class="form-label"><?= $langfile['color_background'] ?></label>
                            <input type="color" id="color_background" name="color_background" class="form-control"
                                value="<?= htmlspecialchars($colorBack ?? '#000000') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="color_text" class="form-label"><?= $langfile['color_text'] ?></label>
                            <input type="color" id="color_text" name="color_text" class="form-control"
                                value="<?= htmlspecialchars($colorText ?? '#FFFFFF') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="color_border" class="form-label"><?= $langfile['color_border'] ?></label>
                            <input type="color" id="color_border" name="color_border" class="form-control"
                                value="<?= htmlspecialchars($colorBorder ?? '#000000') ?>">
                        </div>
                    </div>

                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <?= $edit ? $langfile['update'] : $langfile['new'] . " " . $langfile['team'] ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabella Squadre -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle" id="myTable">
            <thead class="table-light">
                <tr>
                    <th><?= $langfile['name'] ?></th>
                    <th><?= $langfile['groups'] ?></th>
                    <th><?= $langfile['value'] ?></th>
                    <th><?= $langfile['attack'] ?></th>
                    <th><?= $langfile['defense'] ?></th>
                    <th><?= $langfile['actions'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ordinare le squadre in base al valore di potere['valore']
                usort($teams, function ($a, $b) {
                    $pA = json_decode($a['params'], true);
                    $pB = json_decode($b['params'], true);

                    // Se il valore di 'potere' è presente, usa quello per ordinare
                    $valoreA = $pA['potere']['valore'] ?? 0; // Se non esiste, usa 0
                    $valoreB = $pB['potere']['valore'] ?? 0; // Se non esiste, usa 0
                
                    // Ordina in ordine crescente (puoi modificare per ordina decrescente se necessario)
                    return $valoreB <=> $valoreA;
                });
                ?>
                <?php foreach ($teams as $t):
                    $p = json_decode($t['params'], true);
                    ?>
                    <tr>
                        <td>
                            <div class="rounded-pill text-center" style="
                            background-color: <?= htmlspecialchars($p['color']['background'] ?? '#000000') ?>; 
                            border: 1px solid <?= htmlspecialchars($p['color']['border'] ?? '#000000') ?>; 
                            color: <?= htmlspecialchars($p['color']['text'] ?? '#FFFFFF') ?>;
                            padding: 5px;">
                                <strong><?= htmlspecialchars($t['name']) ?></strong>
                            </div>
                        </td>
                        <td>
                            <?= !empty($p['groups'])
                                ? htmlspecialchars(implode(', ', sort($p['groups']) ? $p['groups'] : []))
                                : '-' ?>
                        </td>
                        <td>
                            <?= isset($p['potere'])
                                ? htmlspecialchars($p['potere']['valore'] ?? 0)
                                : '-' ?>
                        </td>
                        <td>
                            <?= isset($p['potere'])
                                ? htmlspecialchars($p['potere']['attacco'] ?? 0)
                                : '-' ?>
                        </td>
                        <td>
                            <?= isset($p['potere'])
                                ? htmlspecialchars($p['potere']['difesa'] ?? 0)
                                : '-' ?>
                        </td>
                        <td>
                            <a href="?page=teams&name=<?= urlencode($t['name']) ?>"
                                class="btn btn-sm btn-warning"><?= $langfile['edit'] ?></a>
                            <a href="?page=teams&delete_name=<?= urlencode($t['name']) ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Eliminare questa squadra?');">
                                <?= $langfile['delete'] ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>