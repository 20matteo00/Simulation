<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $username = $_POST['username'];

        // Validate username
        if (empty($username)) {
            $error = $langfile['empty_fields'];
        } else {
            // Update user information in the database
            $update = $db->update("users", [
                'username' => $username,
            ], "id = " . $_SESSION['user']['id']);
            if (!$update) {
                $error = $langfile['update_failed'];
            } else {
                $_SESSION['user']['username'] = $username;
                header("Location: index.php");
                exit();
            }
        }
    }
}
?>
<div class="container">
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <div class="card bg-white text-dark shadow rounded-4">
        <div class="card-header">
            <h2 class="text-center"><?= $langfile['profile'] ?></h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label"><?= $langfile['username'] ?></label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="update"><?= $langfile['update'] ?></button>
            </form>
        </div>
        <div class="card-footer">
            <p class="text-center mt-3"><?= $langfile['welcome'] ?> <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
        </div>
    </div>
</div>