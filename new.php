<?php
    //Starts and maintains session
    session_start();
    if (!isset($_SESSION['customerName'])
        && !isset($_SESSION['customerId'])) {
        //Redirect if session is not properly set
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="utf-8">
    <title>New Orders</title>
    <meta name="description" content="make new orders">
    <meta name="keywords" content="orders,customer,products">
    <meta name="author" content="Michael Bamikunle">
    <link rel="stylesheet" href="./styles/styles1.css" type="text/css">
    <script type="text/javascript">
    /**
     * This script validates the form of the 
     * new.php file. If it is not adequately filled,
     * it cannot be submitted. It will instead give an alert message
     */
    function validateForm() {
        let formValid = false;
        let radios = document.getElementsByName("regionSelected");
        let radiosValid = false;

        //checks if radio button is selected
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                radiosValid = true;
                break;
            }
        }

        //checks if atleast a checkbox is selected
        let checkboxesValid = false;
        let checkBoxes = document.getElementsByClassName('mycheckboxes');

        for (let i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
                checkboxesValid = true;
                break;
            }
        }

        if (radiosValid && checkboxesValid) {
            formValid = true;
        } else {
            formValid = false;
        }

        if (formValid) {
            document.getElementById("form3").submit();
        } else {
            alert("Please select at least one product and at least one region");
        }
    }
    </script>
</head>

<body>
    <?php
            echo "<div class= 'intro'>";
            echo "<h1>Export</h1>";
            echo '<hr />';
            echo "<h2>Hi " . $_SESSION['customerName'] .
                "! Make new order(s) below:</h2>";
            echo "<p>Please make sure to select 
                        at least one product and at least a region.</p>";
            echo '<hr />';

            echo "</div>";
        ?>
    <form id="form3" method="POST" action="add.php">
        <?php
                //Sanitizes potentially malicious user input
                $customerId = htmlspecialchars($_SESSION['customerId']);

                require_once ('connect.php');
                $conn = myconnect();
                try {
                    $sql1 = "SELECT DISTINCT name, PID, price 
                                FROM products;";

                    $handle1 = $conn->prepare($sql1);
                    $handle1->execute();
                    $res1 = $handle1->fetchAll();

                    $sql2 = "SELECT DISTINCT name, RID, tax FROM
                                regions;";

                    $handle2 = $conn->prepare($sql2);
                    $handle2->execute();
                    $res2 = $handle2->fetchAll();

                    echo "<div class='my-new-tables'>
                                <table>
                                <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th></th>";

                    foreach ($res1 as $row) {
                        $productName = $row['name'];
                        $PID = $row['PID'];
                        $productPrice = $row['price'];
                        echo "<tr><td>$productName</td><td>$productPrice</td>
                                <td>
                                <input type='checkbox' class='mycheckboxes' name='$PID'>                           
                                </td></tr>";
                    }

                    echo '</table>';

                    echo "<table>
                                <tr>
                                <th>Region</th>
                                <th>Tax</th>
                                <th></th>";

                    foreach ($res2 as $row) {
                        $regionName = $row['name'];
                        $RID = $row['RID'];
                        $tax = $row['tax'];
                        echo "<tr><td>$regionName</td><td>$tax</td>
                                <td>
                                <input type='radio' name='regionSelected' value='$RID'>                           
                                </td></tr>";
                    }

                    echo '</table>';
                    echo '</div>';

                    $conn = null;
                } catch (PDOException $e) {
                    echo "PDOException: " . $e->getMessage();
                }
            ?>
        <input type="button" onclick="validateForm();" value="Create">
        <input type="button" onclick="location.href='customer.php';" value="Back">
        <input type="button" onclick="location.href='index.php';" value="Exit">
    </form>
</body>

</html>