<?php
    //starts a new session to track the user
    session_start();
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="utf-8">
    <title>Your Export Orders</title>
    <meta name="description" content="customer account page">
    <meta name="keywords" content="orders,customer,products">
    <meta name="author" content="Michael Bamikunle">
    <link rel="stylesheet" href="./styles/styles1.css" type="text/css">
    <script type="text/javascript">
    /**
     * Client-side validation
     * Makes sure form is properly filled
     * before it can be submitted
     */
    function validateForm() {
        let numbersValid = false;

        // Get all number input
        let numbers = document.getElementsByClassName('mynums');

        //Checks to see if the form has any input to update with
        for (let i = 0; i < numbers.length; i++) {
            if (parseInt(numbers[i].value) != 0) {
                numbersValid = true;
                break;
            }
        }

        /* Alerts user to make necessary changes
        Halts form submission */
        if (!numbersValid) {
            alert("Please input a reasonable number to update\n" +
                "Note: Cannot update until atleast one order update field is changed from zero (0)\n" +
                "(Max: 10+, min: will reduce quantity to min of 1)");
            return false;
        }

        //Allow form to be submitted
        if (numbersValid) {
            return true;
        }
    }

    /**
     * This function is used to alter the background colour
     * of similar classed products once clicked
     */
    function colourYellow(row) {
        //get class name
        let clNm = row.className;

        //get current background colour of document
        let docBac = document.body.style.backgroundColor;

        //get all table rows
        let tableRows = document.getElementsByTagName('tr');

        //reset all table row background colour
        for (let i = 0; i < tableRows.length; i++) {
            tableRows[i].style.backgroundColor = docBac;
        }

        //gets all elements with same class name
        let family = document.getElementsByClassName(clNm);
        //change background colour of all elements
        for (let i = 0; i < family.length; i++) {
            family[i].style.backgroundColor = 'yellow';
        }
    }
    </script>
</head>

<body>
    <div class="intro">
        <?php
                /* captures if there is new post data or not */
                $newCustSet = isset($_POST['customerName']);
                $newCustIdSet = isset($_POST['customerID']);

                /* if session keys are not set, but there is new post 
                set the session keys */
                if (!isset($_SESSION['customerId'])
                    && !isset($_SESSION['customerName'])
                    && $newCustIdSet
                    && $newCustIdSet) { 
                    //cleans up potentially malicious user input
                    $_SESSION['customerName'] = htmlspecialchars($_POST['customerName']);
                    $_SESSION['customerId'] = htmlspecialchars($_POST['customerID']);
                }

                /* If session not set and no new post data, 
                redirect user to index.php */
                if (!isset($_SESSION['customerName'])
                    && !isset($_SESSION['customerId'])) {
                    if (!$newCustIdSet && !$newCustSet) {
                        header("Location:index.php");
                    }
                }
                //Sets the title and welcomes the customer
                echo "<h1>Export COMP8870</h1>";
                echo '<hr />';
                echo "<h2>Hi " . $_SESSION['customerName'] . "! Here are your orders:</h2>";
                echo "<p>You can update your order 
                            by increasing or decreasing the update number for any order 
                            and clicking the update button (Note: you can update
                            multiple orders at a time)</p>";
                echo '<hr />';
            ?>
    </div>
    <div class="form">
        <form id="form2" method="POST" action="update.php" onsubmit="return validateForm();">
            <?php
                    $customerName = $_SESSION['customerName'];
                    $customerId = $_SESSION['customerId'];

                    //Includes the connection php file
                    require_once ('connect.php');
                    $conn = myconnect();
                    try {
                        $sql = "SELECT OrderNo, products.name as pname, price, 
                                    regions.name as rname, tax, quantity FROM orders 
                                    JOIN products ON orders.PID = products.PID 
                                    JOIN regions ON orders.RID = regions.RID 
                                    WHERE orders.CID = :n;";

                        $handle = $conn->prepare($sql);

                        $handle->execute(array(":n" => $customerId));
                        $conn = null;
                        $res = $handle->fetchAll();
                    
                        //Echos the table out
                        echo "<table id='maintable'>";
                        echo '<tr>';
                        echo '<th>Order No</th>';
                        echo '<th>Product</th>';
                        echo '<th>Price</th>';
                        echo '<th>Region</th>';
                        echo '<th>Tax</th>';
                        echo '<th>Quantity</th>';
                        echo '<th> Update(+/-)</th>';
                        echo '</tr>';

                        //fills up the table with required data and input fields
                        foreach ($res as $row) {
                            $pname = $row['pname'];
                            echo "<tr class='$pname' onclick='colourYellow(this);'>";
                            echo "<td>" . $row['OrderNo'] . "</td>";
                            echo "<td>" . $row['pname'] . "</td>";
                            echo "<td>" . $row['price'] . "</td>";
                            echo "<td>" . $row['rname'] . "</td>";
                            echo "<td>" . $row['tax'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";

                            $minimum = 0;
                            if ($row['quantity'] > 0) {
                                $minimum = -($row['quantity'] - 1);
                            }

                            $rowName = $row['OrderNo'];
                            echo "<td> <input name='$rowName' type='number' class='mynums' value='0' max='10' min='$minimum' ></td>";
                            echo '</tr>';
                        }
                        echo '</table>';
                    } catch (PDOException $e) {
                        echo "PDOException: " . $e->getMessage();
                    }
                ?>
            <input type="submit" value="Update">
            <input type="button" onclick="location.href='index.php';" value="Exit">
        </form>
    </div>
    <div id="newButton">
        <form id="form3" method="POST" action="new.php">
            <input type="submit" value="New">
        </form>
    </div>
</body>

</html>