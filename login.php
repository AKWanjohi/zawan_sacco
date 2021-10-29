<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<?php

require 'components/database.php';

$message = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare(
        "SELECT * FROM login WHERE login_email=:email"
    );
    $statement->bindValue(":email", $_POST['email']);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($_POST['password'], $result['login_password'])) {
            if ($result['login_admin_id']) {
                $user_type = 'admin';
                $statement = $pdo->prepare(
                    "SELECT * FROM admin WHERE admin_id=:id"
                );
                $statement->bindValue(":id", $result['login_admin_id']);
            } else if ($result['login_clerk_id']) {
                $user_type = 'clerk';
                $statement = $pdo->prepare(
                    "SELECT * FROM clerk WHERE clerk_id=:id"
                );
                $statement->bindValue(":id", $result['login_clerk_id']);
            } else if ($result['login_client_id']) {
                $user_type = 'client';
                $statement = $pdo->prepare(
                    "SELECT * FROM client WHERE client_id=:id"
                );
                $statement->bindValue(":id", $result['login_client_id']);
            }
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user_type'] = $user_type;
            $_SESSION['user_id'] = $user['admin_id'] ?? $user['clerk_id'] ?? $user['client_id'];
            $_SESSION['user_fname'] = $user['admin_fname'] ?? $user['clerk_fname'] ?? $user['client_fname'];
            $_SESSION['user_lname'] = $user['admin_lname'] ?? $user['clerk_lname'] ?? $user['client_lname'];

            if ($user_type == 'admin') {
                header('Location: admin.php');
                exit;
            } else if ($user_type == 'clerk') {
                header('Location: clerk.php');
                exit;
            } else if ($user_type == 'client') {
                header('Location: client.php');
                exit;
            }
        } else {
            $email = $_POST['email'];
            $message = "Incorrect password!";
        }
    } else {
        $message = "User does not exist!";
    }
}

?>

<main>
    <div class="container">
        <h1>Login</h1>
        <hr>
        <form method="POST">
            <?php if ($message) : ?>
                <div class="message">
                    <p><?php echo $message ?></p>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" <?php if ($email) echo "value='$email'" ?> placeholder="Enter your email address" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter a password" required>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>