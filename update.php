<?php
    //start and keep track of ongoing session
    session_start();

    //Makes sure the $_POST superglobal is properly filled
    if (empty($_POST)) {
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="utf-8">
    <title>Update Orders</title>
    <meta name="description" content="update orders page">
    <meta name="keywords" content="orders,customer,products, update">
    <meta name="author" content="Michael Bamikunle">
    <link rel="stylesheet" href="./styles/styles1.css" type="text/css">
</head>

<body>
    <div class="intro">
        <?php
                //Echos heading of the page
                echo "<h1>Export</h1>";
                echo '<hr />';

                //require the connection php file
                require_once ('connect.php');
                $conn = myconnect();
                $updated = 0;

                try {
                    foreach ($_POST as $orderNo => $quantity) {
                        // Sanitize and validate the input is of required type
                        $quantity = intval($quantity); // Convert to integer
                        if ($quantity == 0) {
                            continue;
                        }
                        // Update the database
                        $sql = "UPDATE orders SET 
                                    quantity = quantity + :quantity 
                                    WHERE OrderNo = :orderNo";
                        $stmt = $conn->prepare($sql);

                        $stmt->execute(array(":quantity" => $quantity, ":orderNo" => $orderNo));
                        $updated++;
                    }
                    // Close the database connection
                    $conn = null;

                } catch (PDOException $e) {
                    echo "PDOException: " . $e->getMessage();
                }
                /* If any updates are made, alert the customer */
                if ($updated > 0) {
                    if ($updated == 1){
                    echo '<p>' . $updated .
                        " order updated for "
                        . $_SESSION['customerName'] . "</p>";
                    }
                    
                    if ($updated > 1) {
                        echo '<p>' . $updated .
                    " orders updated for "
                    . $_SESSION['customerName'] . "</p>";
                    }
                    
                }
            ?>
    </div>
    <input type="button" onclick="location.href='customer.php';" value="Back">
    <input type="button" onclick="location.href='index.php';" value="Exit">
</body>

</html>