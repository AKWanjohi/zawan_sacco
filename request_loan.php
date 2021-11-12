<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT account_id, account_name FROM account WHERE account_client_id=:id"
);
$statement->bindValue(":id", $_SESSION['user_id']);
$statement->execute();
$accounts = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT loan_type_id, loan_type_name FROM loan_type");
$statement->execute();
$loan_types = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $pdo->prepare(
        "INSERT INTO loan (loan_client_id, loan_account_id, loan_loan_type_id, loan_requested_amount)
        VALUES (:client_id, :account_id, :loan_type_id, :amount)"
    );
    $statement->bindValue(":client_id", $_SESSION['user_id']);
    $statement->bindValue(":account_id", $_POST['account_id']);
    $statement->bindValue(":loan_type_id", $_POST['loan_type']);
    $statement->bindValue(":amount", $_POST['amount']);
    $statement->execute();

    header('Location: view_loans.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Request Loan</h1>
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
                <label for="loan_type">Type</label>
                <select name="loan_type" id="loan_type" required>
                    <option>Select loan type...</option>
                    <?php foreach ($loan_types as $loan_type) : ?>
                        <option value="<?php echo $loan_type['loan_type_id'] ?>"><?php echo $loan_type['loan_type_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" value="0" min="0" step="100" required>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Request</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>