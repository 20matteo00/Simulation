<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate username, email, and password
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = $langfile['empty_fields'];
        } elseif ($password !== $confirm_password) {
            $error = $langfile['password_mismatch'];
        } elseif ($db->getOne("users", "email = '$email'")) {
            $error = $langfile['mail_exists'];
        } else {
            $insert = $db->insert("users", [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'params' => json_encode([
                    'lang' => $lang,
                    'level' => 3
                ])
            ]);
            if (!$insert) {
                $error = $langfile['registration_failed'];
            } else {
                $_SESSION['user'] = [
                    'username' => $username,
                    'email' => $email,
                    'level' => 3,
                    'lang' => $lang
                ];
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
            <h2 class="text-center"><?= $langfile['register'] ?></h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="regUsername" class="form-label"><?= $langfile['username'] ?></label>
                    <input type="text" class="form-control" id="regUsername" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="regEmail" class="form-label"><?= $langfile['email'] ?></label>
                    <input type="email" class="form-control" id="regEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="regPassword" class="form-label"><?= $langfile['password'] ?></label>
                    <input type="password" class="form-control" id="regPassword" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="regPassword2" class="form-label"><?= $langfile['confirm_password'] ?></label>
                    <input type="password" class="form-control" id="regPassword2" name="confirm_password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success" name="login"><?= $langfile['register'] ?></button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <p class="text-center mt-3"><?= $langfile['already_have_account'] ?> <a href="?page=login" class="text-decoration-none"><?= $langfile['login'] ?></a></p>
        </div>
    </div>
</div>