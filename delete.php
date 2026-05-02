
<?php
include 'dbconnection.php';


$sql = "DELETE FROM users WHERE ID=21";

if ($conn->query($sql) === TRUE)
     {
    echo "One item is deleted successfully";
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

echo "<script>window.open('select.php')</script>"; 
?>