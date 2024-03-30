<?php
    /**
     *Clears and ends any current session 
    * so a new user can be tracked
    * https://www.w3docs.com/snippets/php/proper-way-to-logout-from-a-session-in-php.html
    */
    session_start();
    $_SESSION = array();

    /* Unsets the session cookie using the session name call
    */
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 90000, '/');
    }
    session_destroy();
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="utf-8">
    <title>EXPORT HOME</title>
    <meta name="description" content="Exports home page">
    <meta name="keywords" content="orders,customer,products">
    <meta name="author" content="Michael Bamikunle">
    <link rel="stylesheet" href="./styles/styles1.css" type="text/css">
</head>

<body>
    <div class="intro">
        <h1>Export COMP8870</h1>
        <hr />
        <h2>Please insert your name and customer ID to begin</h2>
    </div>
    <hr />
    <div class="form">
        <form name="form1" id="f1" action="customer.php" method="POST">
            <table class="login">
                <tr>
                    <td>
                        <h3>Name: </h3>
                    </td>
                    <td>
                        <input name="customerName" type="text" required value="Omar Zariz">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>Customer ID: </h3>
                    </td>
                    <td>
                        <input name="customerID" id="cid" type="number" value="3" min="1" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>Click enter to continue:</h3>
                    </td>
                    <td>
                        <input type="submit" value="Enter">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>