<?php
if (isset($_SESSION['user'])) {
    header("Location: index.php?lang=" . $lang);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email and password
        if (empty($email) || empty($password)) {
            $error = $langfile['empty_fields'];
        } else {
            $user = $db->getOne("users", "email = '$email'");
            if ($user && password_verify($password, $user['password']) && $email === $user['email']) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'level' => json_decode($user['params'], true)['level'],
                    'lang' => json_decode($user['params'], true)['lang']
                ];
                header("Location: index.php?lang=" . $lang);
                exit();
            } else {
                $error = $langfile['invalid_credentials'];
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
            <h2 class="text-center"><?= $langfile['login'] ?></h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label"><?= $langfile['email'] ?></label>
                    <input type="email" class="form-control" id="loginEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label"><?= $langfile['password'] ?></label>
                    <input type="password" class="form-control" id="loginPassword" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="login"><?= $langfile['login'] ?></button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <p class="text-center mt-3"><?= $langfile['no_account'] ?> <a href="?page=register&lang=<?= $lang ?>" class="text-decoration-none"><?= $langfile['register'] ?></a></p>
        </div>
    </div>
</div>