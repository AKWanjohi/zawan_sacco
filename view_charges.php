<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM charge WHERE charge_client_id=:client_id"
);
$statement->bindValue(":client_id", $_SESSION['user_id']);
$statement->execute();
$charges = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>View Charges</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($charges as $i => $charge) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td><?php echo ucfirst($charge['charge_type']) ?></td>
                        <td><?php echo $charge['charge_amount'] ?></td>
                        <td><?php echo date_format(date_create($charge['charge_date']), 'd-m-Y') ?></td>
                        <td>
                            <?php if ($charge['charge_status'] == '1') {
                                echo 'Paid';
                            } else {
                                echo 'Not Paid';
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <?php if ($charge['charge_status'] == '0') : ?>
                                <a href="payment.php?type=charge&id=<?php echo $charge['charge_id'] ?>">Pay</a>
                            <?php else : ?>
                                <a href="payment.php?type=charge&id=<?php echo $charge['charge_id'] ?>" class="disabled">Pay</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>