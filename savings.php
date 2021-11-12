<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT account_id, account_name FROM account WHERE account_client_id=:client_id"
);
$statement->bindValue(":client_id", $_SESSION['user_id']);
$statement->execute();
$accounts = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $pdo->prepare(
        "INSERT INTO saving (saving_client_id, saving_account_id, saving_mode, saving_amount)
        VALUES (:client_id, :account_id, :mode, :amount)"
    );
    $statement->bindValue(":client_id", $_SESSION['user_id']);
    $statement->bindValue(":account_id", $_POST['account_id']);
    $statement->bindValue(":mode", $_POST['mode']);
    $statement->bindValue(":amount", $_POST['amount']);
    $statement->execute();

    $statement = $pdo->prepare(
        "SELECT account_total_balance FROM account WHERE account_id=:account_id"
    );
    $statement->bindValue(":account_id", $_POST['account_id']);
    $statement->execute();
    $account_balance = $statement->fetch(PDO::FETCH_COLUMN);

    $account_balance += $_POST['amount'];
    $statement = $pdo->prepare(
        "UPDATE account SET account_total_balance=:account_balance WHERE account_id"
    );
    $statement->bindValue(":account_balance", $account_balance);
    $statement->execute();

    header('Location: view_accounts.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Save Money</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <form method="POST">
            <div class="form-group">
                <label for="account_id">Account</label>
                <select name="account_id" id="account_id" required>
                    <option>Select account...</option>
                    <?php foreach ($accounts as $account) : ?>
                        <option value="<?php echo $account['account_id'] ?>"><?php echo $account['account_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="mode">Mode</label>
                <select name="mode" id="mode" required>
                    <option>Select mode...</option>
                    <option value="cash">Cash</option>
                    <option value="m-pesa">M-PESA</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" value="0" min="0" step="100" required>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>