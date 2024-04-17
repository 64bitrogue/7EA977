<?php

// This file allows user to delete records.

include "connect.php";
include "functions.php";

$id = sanitize_input($_POST['id']);

$query = "DELETE FROM storage WHERE id = '$id'";

if ($conn->query($query)) {
    $conn->close();
    header("Location: index.php");
} else {
    die("Cannot delete record. Error: " . $conn->error);
}