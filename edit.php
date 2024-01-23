<?php

// This page allows user to edit records.

include "connect.php";
include "functions.php";

$errors = [];

$company = null;
$weight = null;
$payment = null;

$id = sanitize_input($_GET['id']);

if (isset($_POST['edit'])) {
    $company = sanitize_input($_POST['company']);
    $weight = sanitize_input($_POST['weight']);
    $payment = sanitize_input($_POST['payment']);

    // Validations

    if (empty($company)) {
        $errors['company'] = "Please enter a company name.";
    }

    if (empty($weight)) {
        $errors['weight'] = "Please enter a gross weight.";
    }

    if (empty($payment)) {
        $errors['payment'] = "Please select a mode of payment.";
    }

    if ($weight <= 0) {
        $errors['weight'] = "Gross weight cannot be negative or zero.";
    }

    if (($payment != "CASH") && ($payment != "INSTALLMENT")) {
        $errors['payment'] = "Please select a valid mode of payment.";
    }

    // Query

    if (count($errors) == 0) {
        $query = "UPDATE storage SET company = '$company', weight = '$weight', payment = '$payment' WHERE id = '$id'";

        if ($conn->query($query)) {
            $conn->close();
            header("Location: index.php");
        } else {
            echo "Cannot edit record. Error: " . $conn->error;
        }
    }


} else {
    $query = "SELECT * FROM storage where id = '$id'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $company = $row['company'];
        $weight = $row['weight'];
        $payment = $row['payment'];
    } else {
        die("Storage ID not found.");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT RECORD</title>
</head>
<body>
    <a href="index.php">Go Back to Index</a>
    <hr>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

        <div>
            <label for="company">Company Name</label>
            <input value="<?= $company ?>" type="text" name="company" id="company">
            <?php
            if (isset($errors['company'])) {
                ?>
                <p class="error"><?= $errors['company'] ?></p>
                <?php
            }
            ?>
        </div>
        <div>
            <label for="weight">Gross Weight</label>
            <input value="<?= $weight ?>" type="number" name="weight" id="weight">
            <?php
            if (isset($errors['weight'])) {
                ?>
                <p class="error"><?= $errors['weight'] ?></p>
                <?php
            }
            ?>
        </div>
        <div>
            <label for="payment">Mode of Payment</label>
            <select name="payment" id="payment">
                <option value="" disabled selected>Select mode of payment</option>
                <option value="CASH" <?= $payment == "CASH" ? 'selected' : '' ?>>Cash</option>
                <option value="INSTALLMENT" <?= $payment == "INSTALLMENT" ? 'selected' : '' ?>>Installment</option>
            </select>
            <?php
            if (isset($errors['payment'])) {
                ?>
                <p class="error"><?= $errors['payment'] ?></p>
                <?php
            }
            ?>
        </div>
        <div>
            <button name="edit" type="submit">Save</button>
        </div>
    </form>
</body>
</html>