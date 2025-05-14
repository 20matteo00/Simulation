<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gestione dell'azione 'add'
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        $teams = $_POST['teams'];
        var_dump($name);
        // Validazione dei campi
        if (empty($name)) {
            $error = $langfile['empty_fields'];
        } elseif ($db->getOne("groups", "name = '$name' AND id != '" . $_SESSION['user']['id'] . "'")) {
            $error = $langfile['name_exists'];
        } else {
            // Inserimento del gruppo
            $insert = $db->insert("groups", [
                'user_id' => $_SESSION['user']['id'],
                'name' => $name
            ]);

            if (!$insert) {
                $error = $langfile['insert_failure'];
            } else {
                // Inserimento dei team associati al gruppo
                foreach ($teams as $team) {
                    $db->insert("groupteam", [
                        'user_id' => $_SESSION['user']['id'],
                        'group_name' => $name,
                        'team_name' => $team
                    ]);
                }
                header("Location: index.php?page=group");
                exit();
            }
        }
    }

    // Gestione dell'azione 'delete'
    if (isset($_POST['delete'])) {
        $name = $_POST['name'];

        if (empty($name)) {
            $error = $langfile['empty_fields'];
        } else {
            $delete = $db->delete("groups", "name = '$name' AND user_id = '" . $_SESSION['user']['id'] . "'");

            if (!$delete) {
                $error = $langfile['delete_failure'];
            } else {
                header("Location: index.php?page=group");
                exit();
            }
        }
    }
}

// Recupero dei gruppi e dei team dell'utente
$groups = $db->getAll("groups", "user_id = '" . $_SESSION['user']['id'] . "'");
$teams = $db->getAll("teams", "user_id = '" . $_SESSION['user']['id'] . "'");
?>

<div class="container">
    <!-- Gestione degli errori -->
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="card bg-white text-dark shadow rounded-4">
        <div class="card-header">
            <h2 class="text-center"><?= $langfile['groups'] ?></h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="name" class="form-label"><?= $langfile['name'] ?></label>
                    <input type="text" name="name" id="name" class="form-control" value="" required>
                </div>
                <div class="mb-3">
                    <label for="teams" class="form-label"><?= $langfile['teams'] ?></label>
                    <select name="teams[]" id="teams" class="form-control" required multiple>
                        <option value="" disabled selected><?= $langfile['select_team'] ?></option>
                        <?php foreach ($teams as $team) : ?>
                            <option value="<?= htmlspecialchars($team['name']) ?>"><?= htmlspecialchars($team['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="add"><?= $langfile['add'] ?></button>
            </form>
        </div>
    </div>

    <!-- Tabella dei gruppi -->
    <div class="table-responsive mt-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= $langfile['name'] ?></th>
                    <th><?= $langfile['teams'] ?></th>
                    <th><?= $langfile['actions'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group) : ?>
                    <tr>
                        <td><?= htmlspecialchars($group['name']) ?></td>
                        <td>
                            <?php
                            $groupTeams = $db->getAll("groupteam", "group_name = '" . $group['name'] . "' AND user_id = '" . $_SESSION['user']['id'] . "'");
                            if (!empty($groupTeams)) {
                                foreach ($groupTeams as $team) {
                                    echo htmlspecialchars($team['team_name']) . "<br>";
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($group['name']) ?>">
                                <?php foreach ($groupTeams as $team): ?>
                                    <input type="hidden" name="teams[]" value="<?= htmlspecialchars($team['team_name']) ?>">
                                <?php endforeach; ?>
                                <button type="submit" class="btn btn-danger" name="delete"><?= $langfile['delete'] ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>