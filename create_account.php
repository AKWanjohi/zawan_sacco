<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare("SELECT client_id FROM client WHERE client_email=:email");
    $statement->bindValue(":email", $_POST['client_email']);
    $statement->execute();
    $client_id = $statement->fetch(PDO::FETCH_COLUMN);

    $statement = $pdo->prepare(
        "INSERT INTO account (account_client_id, account_no, account_name, account_desc)
        VALUES (:user_id, :account_no, :account_name, :account_desc)"
    );
    $statement->bindValue(":user_id", $client_id);
    $statement->bindValue(":account_no", randomString(12));
    $statement->bindValue(":account_name", $_POST['account_name']);
    $statement->bindValue("account_desc", $_POST['account_desc']);
    $statement->execute();

    header('Location: admin.php');
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
                <label for="client_email">Client Email</label>
                <input type="text" name="client_email" id="client_email" list='client-list' required>
            </div>
            <datalist id='client-list'>
                <?php 
                
                $statement = $pdo->prepare("SELECT * FROM client");
                $statement->execute();
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <?php foreach($clients as $client) : ?>
                    <option><?php echo $client['client_email'] ?></option>
                <?php endforeach; ?>
            </datalist>
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