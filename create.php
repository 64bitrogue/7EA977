<?php

// This page allows user to create records.

include "connect.php";
include "functions.php";

$errors = [];

$company = null;
$weight = null;
$payment = null;

if (isset($_POST['add'])) {
    // Sanitize user input

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
        $query = "INSERT INTO storage (company, weight, payment) VALUES ('$company', '$weight', '$payment')";

        // If query is successful, close connection. Otherwise, print error.
        if ($conn->query($query)) {
            $conn->close();
            header("Location: index.php");
        } else {
            echo "Cannot add record. Error: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD STORAGE</title>
</head>
<body>
    <a href="index.php">Go Back to Index</a>
    <hr>
    <form action="create.php" method="post">
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
            <button name="add" type="submit">Add</button>
        </div>
    </form>
</body>
</html>