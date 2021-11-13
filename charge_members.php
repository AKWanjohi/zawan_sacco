<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $pdo->prepare("SELECT client_id FROM client");
    $statement->execute();
    $clients = $statement->fetchAll(PDO::FETCH_COLUMN);

    foreach ($clients as $client) {
        $statement = $pdo->prepare(
            "INSERT INTO charge (charge_type, charge_client_id, charge_amount) 
            VALUES (:type, :client, :amount)"
        );
        $statement->bindValue(":type", "membership");
        $statement->bindValue(":client", $client);
        $statement->bindValue(":amount", $_POST['amount']);
        $statement->execute();
    }
    header('Locations: admin.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Charge Members</h1>
            <a href="admin.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <form method="POST">
            <div class="form-group">
                <label for="charge_type">Charge Type</label>
                <select name="charge_type" id="charge_type" required>
                    <option>Select charge type...</option>
                    <option value="membership">Monthly membership</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" min="0" step="100" placeholder="Enter amount" required>
            </div>
            <div class="form-action-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>


<?php include 'components/main-bottom.php'; ?>