<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM payment WHERE payment_client_id=:client_id"
);
$statement->bindValue(":client_id", $_SESSION['user_id']);
$statement->execute();
$payments = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Payment History</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Ref No.</th>
                    <th>Mode</th>
                    <th>Amount</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $i => $payment) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td>
                            <?php
                            if ($payment['payment_loan_id']) {
                                echo "Loan";
                            } else {
                                echo "Charge";
                            }
                            ?>
                        </td>
                        <td><?php echo $payment['payment_ref_no'] ?></td>
                        <td><?php echo ucfirst($payment['payment_mode']) ?></td>
                        <td><?php echo 'Kshs. ' . number_format($payment['payment_amount']) ?></td>
                        <td><?php echo 'Kshs. ' . number_format($payment['payment_balance_amount']) ?></td>
                        <td><?php echo date_format(date_create($payment['payment_date']), 'd-m-Y') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>