<?php
if (!isset($_SESSION['user']['id'])) {
    header("Location: index.php");
    exit();
}
$userId = (int) $_SESSION['user']['id'];
$error  = '';
$edit   = false;
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
        $edit      = true;
        $name      = $team['name'];
        $params    = json_decode($team['params'], true);
        $selGroups = $params['groups'] ?? [];
        $potereVal = $params['potere']['valore']   ?? '';
        $potereAtt = $params['potere']['attacco'] ?? '';
        $potereDif = $params['potere']['difesa']   ?? '';
    }
}

// POST (create o update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['nome']);
    $selGroups = $_POST['gruppo'] ?? [];
    $newGroups = array_filter(array_map('trim', explode(',', $_POST['nuovi_gruppi'] ?? '')));
    $groups    = array_unique(array_merge($selGroups, $newGroups));
    $potereVal = (int) ($_POST['potere_valore']  ?? 0);
    $potereAtt = (int) ($_POST['potere_attacco'] ?? 0);
    $potereDif = (int) ($_POST['potere_difesa']  ?? 0);

    if ($name === '') {
        $error = "Il nome non può essere vuoto.";
    } else {
        $paramsJson = json_encode([
            'groups' => $groups,
            'potere' => [
                'valore'  => $potereVal,
                'attacco' => $potereAtt,
                'difesa'  => $potereDif
            ]
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
                'name'    => $name,
                'params'  => $paramsJson
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
?>

<div class="container py-5">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif ?>

    <div class="card mb-4 shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center">
                <?= $edit ? 'Modifica Squadra' : 'Nuova Squadra' ?>
            </h2>
        </div>
        <div class="card-body">
            <form method="post" action="" class="row g-3">
                <?php if ($edit): ?>
                    <input type="hidden" name="orig_name" value="<?= htmlspecialchars($origName) ?>">
                <?php endif ?>

                <!-- Nome -->
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" name="nome"
                        class="form-control"
                        value="<?= htmlspecialchars($name ?? '') ?>" required>
                </div>

                <!-- Gruppi (select multipla) -->
                <div class="col-md-6">
                    <label for="gruppo" class="form-label">Gruppi (selezione multipla)</label>
                    <select id="gruppo" name="gruppo[]"
                        class="form-select" multiple size="4">
                        <?php foreach ($allGroups as $g): ?>
                            <option value="<?= htmlspecialchars($g) ?>"
                                <?= in_array($g, $selGroups ?? [], true) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <div class="form-text">
                        Tieni premuto Ctrl (Windows) o Cmd (Mac) per selezionare più gruppi.
                    </div>
                </div>

                <!-- Nuovi gruppi -->
                <div class="col-12">
                    <label for="nuovi_gruppi" class="form-label">
                        Aggiungi nuovi gruppi (separati da virgola)
                    </label>
                    <input type="text" id="nuovi_gruppi" name="nuovi_gruppi"
                        class="form-control"
                        placeholder="Es. Serie A, Coppa Italia">
                </div>

                <!-- Potere: Valore, Attacco, Difesa -->
                <div class="col-md-4">
                    <label for="potere_valore" class="form-label">Potere (Valore)</label>
                    <input type="number" id="potere_valore" name="potere_valore"
                        class="form-control"
                        value="<?= htmlspecialchars($potereVal ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="potere_attacco" class="form-label">Potere (Attacco)</label>
                    <input type="number" id="potere_attacco" name="potere_attacco"
                        class="form-control"
                        value="<?= htmlspecialchars($potereAtt ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="potere_difesa" class="form-label">Potere (Difesa)</label>
                    <input type="number" id="potere_difesa" name="potere_difesa"
                        class="form-control"
                        value="<?= htmlspecialchars($potereDif ?? '') ?>">
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <?= $edit ? 'Salva Modifiche' : 'Crea Squadra' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabella Squadre -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Gruppi</th>
                    <th>Potere (V/A/D)</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $t):
                    $p = json_decode($t['params'], true);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td>
                            <?= !empty($p['groups'])
                                ? htmlspecialchars(implode(', ', $p['groups']))
                                : '-' ?>
                        </td>
                        <td>
                            <?= isset($p['potere'])
                                ? sprintf(
                                    "%d / %d / %d",
                                    $p['potere']['valore']  ?? 0,
                                    $p['potere']['attacco'] ?? 0,
                                    $p['potere']['difesa']  ?? 0
                                )
                                : '-' ?>
                        </td>
                        <td>
                            <a href="?page=teams&name=<?= urlencode($t['name']) ?>"
                                class="btn btn-sm btn-warning">Modifica</a>
                            <a href="?page=teams&delete_name=<?= urlencode($t['name']) ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Eliminare questa squadra?');">
                                Elimina
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>