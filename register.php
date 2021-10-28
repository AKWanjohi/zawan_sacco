<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<?php

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_type = $_POST['user_type'];

    if ($user_type == 'admin') {
        $statement = $pdo->prepare(
            "INSERT INTO admin 
            (admin_fname, admin_lname, admin_email, admin_mobile) 
            VALUES (:first_name, :last_name, :email, :mobile)"
        );
    } else if ($user_type == 'clerk') {
        $statement = $pdo->prepare(
            "INSERT INTO clerk 
            (clerk_fname, clerk_lname, clerk_email, clerk_mobile) 
            VALUES (:first_name, :last_name, :email, :mobile)"
        );
    }

    $statement->bindValue(":first_name", $_POST['first_name']);
    $statement->bindValue(":last_name", $_POST['last_name']);
    $statement->bindValue(":email", $_POST['email']);
    $statement->bindValue(":mobile", $_POST['mobile']);
    $statement->execute();

    $user_id = $pdo->lastInsertId();
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($user_type == "admin") {
        $statement = $pdo->prepare(
            "INSERT INTO login
            (login_email, login_password, login_admin_id)
            VALUES (:email, :password, :id)"
        );
    } else if ($user_type == "clerk") {
        $statement = $pdo->prepare(
            "INSERT INTO login
            (login_email, login_password, login_clerk_id)
            VALUES (:email, :password, :id)"
        );
    }
    $statement->bindValue("email", $_POST['email']);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":id", $user_id);
    $statement->execute();

    $_SESSION['user_type'] = $user_type;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_fname'] = $_POST['first_name'];
    $_SESSION['user_lname'] = $_POST['last_name'];

    if ($user_type == 'admin') {
        header('Location: admin.php');
        exit;
    } else if ($user_type == 'clerk') {
        header('Location: clerk.php');
        exit;
    }
}

?>

<main>
    <div class="container">
        <h1>Register</h1>
        <hr>
        <form method="POST">
            <?php

            $statement = $pdo->prepare("SELECT * FROM admin LIMIT 1");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            ?>
            <div class="form-group form-options">
                <p class="form-option">User Type:</p>
                <?php if (!$result) : ?>
                    <input type="radio" name="user_type" id="admin" value="admin" required>
                    <label for="admin">Admin</label>
                <?php endif; ?>
                <input type="radio" name="user_type" id="clerk" value="clerk">
                <label for="clerk">Clerk</label>
            </div>
            <hr>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email address" required>
            </div>
            <div class="form-group">
                <label for="mobile">Phone Number</label>
                <input type="text" name="mobile" id="mobile" maxlength="10" placeholder="Enter your phone number" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter a password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </form>
    </div>
</main>

<script src="script.js"></script>

<?php include 'components/main-bottom.php'; ?>