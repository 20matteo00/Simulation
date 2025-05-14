<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $groups = $_POST['groups'];

        // Validate name
        if (empty($name || empty($groups))) {
            $error = $langfile['empty_fields'];
        } elseif ($db->getOne("teams", "name = '$name' AND id != '" . $_SESSION['user']['id'] . "'")) {
            $error = $langfile['name_exists'];
        } else {
            $insert = $db->insert("teams", [
                'user_id' => $_SESSION['user']['id'],
                'name' => $name
            ]);
            if (!$insert) {
                $error = $langfile['insert_failure'];
            } else {
                // Ora inseriamo i gruppi associati al team
                foreach ($groups as $group) {
                    $db->insert("groupteam", [
                        'user_id' => $_SESSION['user']['id'],
                        'team_name' => $name,
                        'group_name' => $group
                    ]);
                }
                header("Location: index.php?page=team");
                exit();
            }
        }
    }
    if (isset($_POST['delete'])) {
        $name = $_POST['name'];

        // Validate name
        if (empty($name)) {
            $error = $langfile['empty_fields'];
        } else {
            $delete = $db->delete("teams", "name = '$name' AND user_id = '" . $_SESSION['user']['id'] . "'");

            if (!$delete) {
                $error = $langfile['delete_failure'];
            } else {
                header("Location: index.php?page=team");
                exit();
            }
        }
    }
}
$groups = $db->getAll("groups", "user_id = '" . $_SESSION['user']['id'] . "'");
$teams = $db->getAll("teams", "user_id = '" . $_SESSION['user']['id'] . "'");

?>
<div class="container">
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <div class="card bg-white text-dark shadow rounded-4">
        <div class="card-header">
            <h2 class="text-center"><?= $langfile['teams'] ?></h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="name" class="form-label"><?= $langfile['name'] ?></label>
                    <input type="text" name="name" id="name" class="form-control" value="" required>
                </div>
                <div class="mb-3">
                    <label for="groups" class="form-label"><?= $langfile['groups'] ?></label>
                    <select name="groups[]" id="groups" class="form-control" required multiple>
                        <option value="" disabled selected><?= $langfile['select_group'] ?></option>
                        <?php foreach ($groups as $group) : ?>
                            <option value="<?= $group['name'] ?>"><?= htmlspecialchars($group['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="add"><?= $langfile['add'] ?></button>
            </form>
        </div>
        <div class="card-footer">
        </div>
    </div>


    <div class="table-responsive mt-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= $langfile['name'] ?></th>
                    <th><?= $langfile['groups'] ?></th>
                    <th><?= $langfile['actions'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team) : ?>
                    <tr>
                        <td><?= htmlspecialchars($team['name']) ?></td>
                        <td>
                            <?php
                            $g = $db->getAll("groupteam", "team_name = '" . $team['name'] . "' AND user_id = '" . $_SESSION['user']['id'] . "'");
                            $grou = [];
                            if (!empty($g)) {
                                foreach ($g as $gg) {
                                    echo htmlspecialchars($gg['group_name']) . "<br>";
                                }
                            }
                            ?>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="name" value="<?= $team['name'] ?>">
                                <?php foreach ($grou as $group_member): ?>
                                    <input type="hidden" name="groups[]" value="<?= htmlspecialchars($group_member) ?>">
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