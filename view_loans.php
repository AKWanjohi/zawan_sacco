<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM loan 
    JOIN account
    ON loan_account_id = account_id 
    WHERE loan_client_id=:id"
);
$statement->bindValue(":id", $_SESSION['user_id']);
$statement->execute();
$loans = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>View Loans</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account</th>
                    <th>Type</th>
                    <th>Amount Requested</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th>Amount Repaid</th>
                    <th>Premium Amount</th>
                    <th>Date Actualized</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $i => $loan) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td><?php echo $loan['account_name'] ?></td>
                        <td>
                            <?php
                            $statement = $pdo->prepare(
                                "SELECT loan_type_name FROM loan_type WHERE loan_type_id=:id"
                            );
                            $statement->bindValue(":id", $loan['loan_loan_type_id']);
                            $statement->execute();
                            $loan_type_name = $statement->fetch(PDO::FETCH_COLUMN);
                            echo $loan_type_name;
                            ?>
                        </td>
                        <td>Kshs. <?php echo number_format($loan['loan_requested_amount']) ?></td>
                        <td><?php echo date_format(date_create($loan['loan_requested_date']), 'd-m-Y') ?></td>
                        <td>
                            <?php
                            if ($loan['loan_status'] == '1') {
                                echo "Approved";
                            } elseif ($loan['loan_status'] == '0') {
                                echo "Rejected";
                            } else {
                                echo "Not Approved";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($loan['loan_status'] == '1') {
                                echo 'Kshs. ' . number_format($loan['loan_repayment_amount']);
                            } else {
                                echo "";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($loan['loan_status'] == '1') {
                                echo 'Kshs. ' . number_format($loan['loan_requested_premium_amount']);
                            } else {
                                echo "";
                            }

                            ?>
                        </td>
                        <td>
                            <?php if ($loan['loan_actualized_date']) {
                                echo date_format(date_create($loan['loan_actualized_date']), 'd-m-Y');
                            } else {
                                echo "";
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <?php if (($loan['loan_status'] == '1') && ($loan['loan_repayment_amount'] < $loan['loan_requested_amount'])) : ?>
                                <a href="payment.php?type=loan&id=<?php echo $loan['loan_id'] ?>">Pay</a>
                            <?php else : ?>
                                <a href="payment.php?type=loan&id=<?php echo $loan['loan_id'] ?>" class="disabled">Pay</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>