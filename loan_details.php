<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $pdo->prepare(
        "UPDATE loan SET loan_status=:status WHERE loan_id=:loan_id"
    );
    $statement->bindValue(":status", $_POST['status']);
    $statement->bindValue(":loan_id", $_POST['loan_id']);
    $statement->execute();

    if ($_POST['status'] == 1) {

        $new_balance = $_POST['account_balance'] + $_POST['loan_amount'];

        $statement = $pdo->prepare(
            "UPDATE account SET account_total_balance=:new_balance WHERE account_id=:account_id"
        );
        $statement->bindValue(":new_balance", $new_balance);
        $statement->bindValue(":account_id", $_POST['account_id']);
        $statement->execute();

        $statement = $pdo->prepare(
            "INSERT INTO charge (charge_type, charge_client_id, charge_loan_id, charge_amount) 
            VALUES (:type, :client_id, :loan_id, :amount)"
        );
        $statement->bindValue(":type", 'loan');
        $statement->bindValue(":client_id", $_POST['client_id']);
        $statement->bindValue(":loan_id", $_POST['loan_id']);
        $statement->bindValue(":amount", $_POST['loan_amount'] * 0.12);
        $statement->execute();

        $statement = $pdo->prepare(
            "INSERT INTO loan_charge (loan_charge_charge_id, loan_charge_loan_id) 
            VALUES (:charge_id, :loan_id)"
        );
        $statement->bindValue(":charge_id", $pdo->lastInsertId());
        $statement->bindValue(":loan_id", $_POST['loan_id']);
        $statement->execute();
    }
    header('Location: pending_loans.php');
    exit;
}

$statement = $pdo->prepare(
    "SELECT * FROM loan 
    JOIN client
    ON loan_client_id = client_id
    JOIN account
    ON loan_account_id = account_id
    JOIN loan_type
    ON loan_loan_type_id = loan_type_id
    WHERE loan_id=:id"
);
$statement->bindValue(":id", $_GET['id']);
$statement->execute();
$loan = $statement->fetch(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Loan Details</h1>
            <a href="pending_loans.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <div class="loan-details">
            <p>Requester: <span><?php echo $loan['client_fname'] . " " . $loan['client_lname'] ?></span></p>
            <p>Requester ID No.: <span><?php echo $loan['client_id_no'] ?></span></p>
            <p>Requester Acc. Bal.: <span><?php echo 'Kshs. ' . number_format($loan['account_total_balance']) ?></span></p>
            <p>Loan Type: <span><?php echo $loan['loan_type_name'] ?></span></p>
            <p>Requested Amount: <span><?php echo 'Kshs. ' . number_format($loan['loan_requested_amount']) ?></span></p>
            <p>Requested Date: <span><?php echo date_format(date_create($loan['loan_requested_date']), 'd-m-Y') ?></span></p>
        </div>
        <div class="loan-details-actions">
            <form method="POST">
                <input type="text" name="status" value="1" hidden>
                <input type="text" name="client_id" value="<?php echo $loan['client_id'] ?>" hidden>
                <input type="text" name="loan_id" value="<?php echo $loan['loan_id'] ?>" hidden>
                <input type="text" name="account_id" value="<?php echo $loan['account_id'] ?>" hidden>
                <input type="number" name="loan_amount" value="<?php echo $loan['loan_requested_amount'] ?>" hidden>
                <input type="number" name="account_balance" value="<?php echo $loan['account_total_balance'] ?>" hidden>
                <div class="form-action-group">
                    <button type="submit">Approve</button>
                </div>
            </form>
            <form method="POST">
                <input type="text" name="status" value="0" hidden>
                <input type="text" name="loan_id" value="<?php echo $_GET['id'] ?>" hidden>
                <div class="form-action-group">
                    <button type="submit" class="reject-btn">Reject</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>