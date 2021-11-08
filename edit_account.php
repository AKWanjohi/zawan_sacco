<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare("SELECT * FROM account WHERE account_id=:id");
$statement->bindValue(":id", $_GET['id']);
$statement->execute();
$account = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare(
        "UPDATE account SET account_name=:name, account_desc=:desc WHERE account_id=:id"
    );
    $statement->bindValue(":name", $_POST['account_name']);
    $statement->bindValue(":desc", $_POST['account_desc']);
    $statement->bindValue(":id", $_POST['account_id']);
    $statement->execute();

    header('Location: view_accounts.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Edit Account</h1>
            <a href="view_accounts.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <form method="POST">
            <input type="text" name="account_id" value="<?php echo $_GET['id'] ?>" hidden>
            <div class="form-group">
                <label for="account_name">Account Name</label>
                <input type="text" name="account_name" id="account_name" value="<?php echo $account['account_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="account_desc">Account Description</label>
                <textarea name="account_desc" id="account_desc" required><?php echo $account['account_desc'] ?></textarea>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>