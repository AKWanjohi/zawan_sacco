<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare(
        "INSERT INTO account (account_client_id, account_no, account_name, account_desc)
        VALUES (:user_id, :account_no, :account_name, :account_desc)"
    );
    $statement->bindValue(":user_id", $_SESSION['user_id']);
    $statement->bindValue(":account_no", randomString(12));
    $statement->bindValue(":account_name", $_POST['account_name']);
    $statement->bindValue("account_desc", $_POST['account_desc']);
    $statement->execute();

    header('Location: client.php');
}

function randomString($n)
{
    $characters = "01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Create Account</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <form method="POST">
            <div class="form-group">
                <label for="account_name">Account Name</label>
                <input type="text" name="account_name" id="account_name" placeholder="Enter an account name" required>
            </div>
            <div class="form-group">
                <label for="account_desc">Account Description</label>
                <textarea name="account_desc" id="account_desc" placeholder="Enter an account description" required></textarea>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>