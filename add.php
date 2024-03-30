<?php
    //Starts and keeps track of session
    session_start();
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="utf-8">
    <title>Add Orders</title>
    <meta name="description" content="Add orders">
    <meta name="keywords" content="orders,customer,products">
    <meta name="author" content="Michael Bamikunle">
    <link rel="stylesheet" href="./styles/styles1.css" type="text/css">
</head>

<body>
    <div class="intro">
        <h1>Export</h1>
        <hr />

        <?php
                //captures and validates the customer id of the current session
                $CID;

                if (isset($_SESSION['customerId'])) {
                    $CID = $_SESSION['customerId'];
                } else {
                    header("Location:index.php");
                }

                //captures and validates the region ID selected on the new page
                $RID;
                if (isset($_POST['regionSelected'])) {
                    $RID = $_POST['regionSelected'];
                } else {
                    header("Location:index.php");
                }

                require_once ('connect.php');
                $conn = myconnect();
                $number_inserted = 0;

                try {
                    
                    foreach ($_POST as $PID => $PIDon) {
                        /* 
                        Checks if same product with same region already exists for this customer
                        */
                        if (is_int($PID)) {
                            $check = "SELECT * FROM orders WHERE PID = :pid 
                                                AND RID = :rid 
                                                AND CID = :cid";
                            $checkHandle = $conn->prepare($check);
                            $checkHandle->execute(array(":pid" => $PID, ":rid" => $RID, ":cid" => $CID));

                            if ($checkHandle->rowCount() > 0) {
                                echo "<p class='alert'>
                                                    Please note: You already have an order for 
                                                    product ID $PID and 
                                                    region ID $RID</p>";
                                continue;
                            }

                            /*If this order (region and product) does not exist, insert into orders
                            Default insert quantity is set to 10
                            this can be updated from the update table*/
                            $sql = "INSERT INTO orders (CID, PID, RID, quantity) VALUES
                                            (:cid, :pid, :rid, 10);";

                            $handle = $conn->prepare($sql);
                            $handle->execute(array(":cid" => $CID, ":pid" => $PID, ":rid" => $RID));
                            $number_inserted++;
                        }
                    }
                    $conn = null;
                    
                } catch (PDOException $e) {
                    echo "PDOException: " . $e->getMessage();
                }

                if ($number_inserted > 0) {
                    if ($number_inserted == 1) {
                    echo '<p>' . $number_inserted .
                        " order added for "
                        . $_SESSION['customerName']
                        . "</p>";
                    }

                    if ($number_inserted > 1) {
                        echo '<p>' . $number_inserted .
                            " orders added for "
                            . $_SESSION['customerName']
                            . "</p>";
                        }
                }
            ?>
    </div>
    <input type="button" onclick="location.href='customer.php';" value="Back">
    <input type="button" onclick="location.href='index.php';" value="Exit">
</body>

</html>