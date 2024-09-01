<?php
require_once '../config.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM books WHERE id=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "ERROR: Could not execute query. " . mysqli_error($link);
}
?>