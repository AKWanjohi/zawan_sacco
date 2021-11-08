<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$user_type = $_GET['user'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_type = $_POST['user_type'];

    if ($user_type == 'admin') {
        $statement = $pdo->prepare(
            "INSERT INTO admin 
            (admin_fname, admin_lname, admin_email, admin_mobile) 
            VALUES (:first_name, :last_name, :email, :mobile)"
        );
    } elseif ($user_type == 'clerk') {
        $statement = $pdo->prepare(
            "INSERT INTO clerk 
            (clerk_fname, clerk_lname, clerk_email, clerk_mobile) 
            VALUES (:first_name, :last_name, :email, :mobile)"
        );
    } elseif ($user_type == 'client') {
        $statement = $pdo->prepare(
            "INSERT INTO client 
            (client_fname, client_lname, client_email, client_mobile, client_id_no) 
            VALUES (:first_name, :last_name, :email, :mobile, :id_no)"
        );
        $statement->bindValue(":id_no", $_POST['id_no']);
    }
    $statement->bindValue(":first_name", $_POST['first_name']);
    $statement->bindValue(":last_name", $_POST['last_name']);
    $statement->bindValue(":email", $_POST['email']);
    $statement->bindValue(":mobile", $_POST['mobile']);
    $statement->execute();

    $user_id = $pdo->lastInsertId();
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($user_type == 'admin') {
        $statement = $pdo->prepare(
            "INSERT INTO login
            (login_email, login_password, login_rank, login_admin_id)
            VALUES (:email, :password, :rank, :id)"
        );
    } elseif ($user_type == 'clerk') {
        $statement = $pdo->prepare(
            "INSERT INTO login
            (login_email, login_password, login_rank, login_clerk_id)
            VALUES (:email, :password, :rank, :id)"
        );
    } elseif ($user_type == 'client') {
        $statement = $pdo->prepare(
            "INSERT INTO login
            (login_email, login_password, login_rank, login_client_id)
            VALUES (:email, :password, :rank, :id)"
        );
    }
    $statement->bindValue("email", $_POST['email']);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":rank", $user_type);
    $statement->bindValue(":id", $user_id);
    $statement->execute();

    if ($user_type == 'admin') {
        header('Location: view_users.php?user=admins');
    } elseif ($user_type == 'clerk') {
        header('Location: view_users.php?user=clerks');
    } elseif ($user_type == 'client') {
        header('Location: view_users.php?user=clients');
    }
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <?php if ($user_type == 'admin') : ?>
                <h1>Create Admin</h1>
                <a href="view_users.php?user=admins" class="btn btn-cancel">Back</a>
            <?php elseif ($user_type == 'clerk') : ?>
                <h1>Create Clerk</h1>
                <a href="view_users.php?user=clerks" class="btn btn-cancel">Back</a>
            <?php elseif ($user_type == 'client') : ?>
                <h1>Create Client</h1>
                <a href="view_users.php?user=clients" class="btn btn-cancel">Back</a>
            <?php endif; ?>
        </div>
        <hr>
        <form method="POST">
            <input type="text" name="user_type" value="<?php echo $user_type ?>" hidden>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" placeholder="Enter first name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" placeholder="Enter last name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter email address" required>
            </div>
            <div class="form-group">
                <label for="mobile">Phone Number</label>
                <input type="text" name="mobile" id="mobile" maxlength="10" placeholder="Enter phone number" required>
            </div>
            <?php if ($user_type == 'client') : ?>
                <div class="form-group">
                    <label for="id_no">National ID No.</label>
                    <input type="text" name="id_no" id="id_no" maxlength="8" placeholder="Enter your ID number" required>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Create</button>
            </div>
        </form>
    </div>
</main>

<script src="script.js"></script>

<?php include 'components/main-bottom.php'; ?>