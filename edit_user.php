<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$user_type = $_GET['user'];
$user_id = $_GET['id'];

if ($user_type == 'admin') {
    $statement = $pdo->prepare("SELECT * FROM admin WHERE admin_id=:id");
    $statement->bindValue(":id", $user_id);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
} elseif ($user_type == 'clerk') {
    $statement = $pdo->prepare("SELECT * FROM clerk WHERE clerk_id=:id");
    $statement->bindValue(":id", $user_id);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
} elseif ($user_type == 'client') {
    $statement = $pdo->prepare("SELECT * FROM client WHERE client_id=:id");
    $statement->bindValue(":id", $user_id);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
}

$first_name = $user['admin_fname'] ?? $user['clerk_fname'] ?? $user['client_fname'];
$last_name = $user['admin_lname'] ?? $user['clerk_lname'] ?? $user['client_lname'];
$email = $user['admin_email'] ?? $user['clerk_email'] ?? $user['client_email'];
$mobile = $user['admin_mobile'] ?? $user['clerk_mobile'] ?? $user['client_mobile'];
$id_no = $user['client_id_no'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_type = $_POST['user_type'];
    $user_id = $_POST['user_id'];

    if ($user_type == 'admin') {
        $statement = $pdo->prepare(
            "UPDATE admin SET admin_fname=:first_name, admin_lname=:last_name, admin_email=:email, admin_mobile=:mobile
            WHERE admin_id=:id"
        );
    } elseif ($user_type == 'clerk') {
        $statement = $pdo->prepare(
            "UPDATE clerk SET clerk_fname=:first_name, clerk_lname=:last_name, clerk_email=:email, clerk_mobile=:mobile
            WHERE clerk_id=:id"
        );
    } elseif ($user_type == 'client') {
        $statement = $pdo->prepare(
            "UPDATE client SET client_fname=:first_name, client_lname=:last_name, client_email=:email, client_mobile=:mobile, client_id_no=:id_no
            WHERE client_id=:id"
        );
        $statement->bindValue(":id_no", $_POST['id_no']);
    }
    $statement->bindValue(":first_name", $_POST['first_name']);
    $statement->bindValue(":last_name", $_POST['last_name']);
    $statement->bindValue(":email", $_POST['email']);
    $statement->bindValue(":mobile", $_POST['mobile']);
    $statement->bindValue(":id", $user_id);
    $statement->execute();

    if ($user_type == 'admin') {
        $statement = $pdo->prepare(
            "UPDATE login SET login_email=:email WHERE login_admin_id=:id"
        );
    } elseif ($user_type == 'clerk') {
        $statement = $pdo->prepare(
            "UPDATE login SET login_email=:email WHERE login_clerk_id=:id"
        );
    } elseif ($user_type == 'client') {
        $statement = $pdo->prepare(
            "UPDATE login SET login_email=:email WHERE login_client_id=:id"
        );
    }
    $statement->bindValue("email", $_POST['email']);
    $statement->bindValue(":id", $user_id);
    $statement->execute();

    if ($_SESSION['user_type'] == 'admin') {
        if ($user_type == 'admin') {
            header('Location: view_users.php?user=admins');
        } elseif ($user_type == 'clerk') {
            header('Location: view_users.php?user=clerks');
        } elseif ($user_type == 'client') {
            header('Location: view_users.php?user=clients');
        }
    } elseif ($_SESSION['user_type'] == 'client') {
        header('Location: client.php');
    } elseif ($_SESSION['user_type'] == 'clerk') {
        header('Location: clerk.php');
    }
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <?php if ($user_type == 'admin') : ?>
                <h1>Edit Admin</h1>
                <a href="view_users.php?user=admins" class="btn btn-cancel">Back</a>
            <?php elseif ($user_type == 'clerk') : ?>
                <h1>Edit Clerk</h1>
                <?php if ($_SESSION['user_type'] == 'clerk') : ?>
                    <a href="clerk.php" class="btn btn-cancel">Back</a>
                <?php elseif ($_SESSION['user_type'] == "admin") : ?>
                    <a href="view_users.php?user=clerks" class="btn btn-cancel">Back</a>
                <?php endif; ?>
            <?php elseif ($user_type == 'client') : ?>
                <h1>Edit Client</h1>
                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                    <a href="view_users.php?user=clients" class="btn btn-cancel">Back</a>
                <?php elseif ($_SESSION['user_type'] == 'client') : ?>
                    <a href="client.php" class="btn btn-cancel">Back</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <hr>
        <form method="POST">
            <input type="text" name="user_type" value="<?php echo $user_type ?>" hidden>
            <input type="text" name="user_id" value="<?php echo $user_id ?>" hidden>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $email ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile">Phone Number</label>
                <input type="text" name="mobile" id="mobile" maxlength="10" value="<?php echo $mobile ?>" required>
            </div>
            <?php if ($user_type == 'client') : ?>
                <div class="form-group">
                    <label for="id_no">National ID No.</label>
                    <input type="text" name="id_no" id="id_no" maxlength="8" value="<?php echo $id_no ?>" readonly required>
                </div>
            <?php endif; ?>
            <div class="form-action-group">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>