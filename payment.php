<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$payment_type = $_GET['type'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($payment_type == 'loan') {
        $amount_paid = (int)$_POST['amount_paid'] + $_POST['amount'];
        $balance = $_POST['loan_amount'] - $amount_paid;

        $statement = $pdo->prepare(
            "INSERT INTO payment (payment_client_id, payment_loan_id, payment_ref_no, payment_mode, payment_amount, payment_balance_amount) 
            VALUES (:client_id, :loan_id, :ref_no, :mode, :amount, :balance)"
        );
        $statement->bindValue(":client_id", $_SESSION['user_id']);
        $statement->bindValue(":loan_id", $_POST['loan_id']);
        $statement->bindValue(":ref_no", randomString(6));
        $statement->bindValue(":mode", $_POST['mode']);
        $statement->bindValue(":amount", $_POST['amount']);
        $statement->bindValue(":balance", $balance);
        $statement->execute();

        if ($balance <= 0) {
            $statement = $pdo->prepare(
                "UPDATE loan 
                SET loan_repayment_amount=:amount_paid, loan_requested_premium_amount=:balance, loan_actualized_date=:date 
                WHERE loan_id=:id"
            );
            $statement->bindValue(":date", date('Y-m-d'));
        } else {
            $statement = $pdo->prepare(
                "UPDATE loan SET loan_repayment_amount=:amount_paid, loan_requested_premium_amount=:balance WHERE loan_id=:id"
            );
        }
        $statement->bindValue(":amount_paid", $amount_paid);
        $statement->bindValue(":balance", $balance);
        $statement->bindValue(":id", $_POST['loan_id']);
        $statement->execute();

        header('Location: view_loans.php');
    } elseif ($payment_type == 'charge') {
        $statement = $pdo->prepare(
            "INSERT INTO payment (payment_client_id, payment_charge_id, payment_ref_no, payment_mode, payment_amount, payment_balance_amount) 
            VALUES (:client_id, :charge_id, :ref_no, :mode, :amount, :balance)"
        );
        $statement->bindValue(":client_id", $_SESSION['user_id']);
        $statement->bindValue(":charge_id", $_POST['charge_id']);
        $statement->bindValue(":ref_no", randomString(6));
        $statement->bindValue(":mode", $_POST['mode']);
        $statement->bindValue(":amount", $_POST['amount']);
        $statement->bindValue(":balance", 0);
        $statement->execute();

        $statement = $pdo->prepare(
            "UPDATE charge SET charge_status=:status WHERE charge_id=:charge_id"
        );
        $statement->bindValue(":status", 1);
        $statement->bindValue(":charge_id", $_POST['charge_id']);
        $statement->execute();

        header('Location: view_charges.php');
    }
}

if ($payment_type == 'loan') {
    $statement = $pdo->prepare("SELECT * FROM loan WHERE loan_id=:id");
    $statement->bindValue(":id", $_GET['id']);
    $statement->execute();
    $loan = $statement->fetch(PDO::FETCH_ASSOC);
} elseif ($payment_type == 'charge') {
    $statement = $pdo->prepare("SELECT * FROM charge WHERE charge_id=:id");
    $statement->bindValue(":id", $_GET['id']);
    $statement->execute();
    $charge = $statement->fetch(PDO::FETCH_ASSOC);
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
            <h1>Make Payment</h1>
            <?php if ($payment_type == 'loan') : ?>
                <a href="view_loans.php" class="btn btn-cancel">Back</a>
            <?php elseif ($payment_type == 'charge') : ?>
                <a href="view_charges.php" class="btn btn-cancel">Back</a>
            <?php endif; ?>
        </div>
        <hr>
        <?php if ($payment_type == 'loan') : ?>
            <div class="loan-details">
                <p>Amount Requested: <span><?php echo 'Kshs. ' . number_format($loan['loan_requested_amount']) ?></span></p>
                <p>Amount Repaid: <span><?php echo 'Kshs. ' . number_format($loan['loan_repayment_amount']) ?></span></p>
                <p>Premium Amount: <span><?php echo 'Kshs. ' . number_format($loan['loan_requested_premium_amount']) ?></span></p>
            </div>
            <form method="POST">
                <input type="text" name="loan_id" value="<?php echo $loan['loan_id'] ?>" hidden>
                <input type="number" name="loan_amount" value="<?php echo $loan['loan_requested_amount'] ?>" hidden>
                <input type="number" name="amount_paid" value="<?php echo $loan['loan_repayment_amount'] ?>" hidden>
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
        <?php elseif ($payment_type == 'charge') : ?>

            <form method="POST">
                <input type="text" name="charge_id" value="<?php echo $charge['charge_id'] ?>" hidden>
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
                    <input type="number" name="amount" id="amount" value="<?php echo $charge['charge_amount'] ?>" readonly required>
                </div>
                <div class="form-action-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>