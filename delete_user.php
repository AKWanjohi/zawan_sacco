<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$user_type = $_GET['user'];
$user_id = $_GET['id'];

if ($user_type == 'admin') {
    $statement = $pdo->prepare("SELECT * FROM admin WHERE admin_id=:id");
} elseif ($user_type == 'clerk') {
    $statement = $pdo->prepare("SELECT * FROM clerk WHERE clerk_id=:id");
} elseif ($user_type == 'client') {
    $statement = $pdo->prepare("SELECT * FROM client WHERE client_id=:id");
}

$statement->bindValue(":id", $_GET['id']);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

$first_name = $user['admin_fname'] ?? $user['clerk_fname'] ?? $user['client_fname'];
$last_name = $user['admin_lname'] ?? $user['clerk_lname'] ?? $user['client_lname'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_type = $_POST['user_type'];

    if ($user_type == 'admin') {
        $statement = $pdo->prepare("DELETE FROM admin WHERE admin_id=:id");
    } elseif ($user_type == 'clerk') {
        $statement = $pdo->prepare("DELETE FROM clerk WHERE clerk_id=:id");
    } elseif ($user_type == 'client') {
        $statement = $pdo->prepare("DELETE FROM client WHERE client_id=:id");
    }
    $statement->bindValue(":id", $_POST['user_id']);
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
                <h1>Delete Admin</h1>
                <a href="view_users.php?user=admins" class="btn btn-cancel">Back</a>
            <?php elseif ($user_type == 'clerk') : ?>
                <h1>Delete Clerk</h1>
                <a href="view_users.php?user=clerks" class="btn btn-cancel">Back</a>
            <?php elseif ($user_type == 'client') : ?>
                <h1>Delete Client</h1>
                <a href="view_users.php?user=clients" class="btn btn-cancel">Back</a>
            <?php endif; ?>
        </div>
        <hr>
        <div>
            <form method="POST">
                <input type="text" name="user_type" value="<?php echo $user_type ?>" hidden>
                <input type="text" name="user_id" value="<?php echo $user_id ?>" hidden>
                <p class="confirm-delete">
                    Are you sure you want to delete <?php echo $first_name . " " . $last_name ?>?
                </p>
                <button type="submit">Confirm</button>
            </form>
        </div>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>