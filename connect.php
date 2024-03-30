<?php
/**
 *This code was modified
 *to connect to the database "mb2202"
 *It helps to abstract away the process 
 *of connecting to the database
 *repeatedly
 *SOURCE: CLASS MATERIAL
 *Author: LB
 */
function myconnect()
{
   $hostname = 'abc';
   $databasename = 'abc';
   $username = 'abc';
   $password = 'abc';

   //Tries to establish a connection
   try {
      $connection = new PDO("mysql:host=$hostname;dbname=$databasename", $username, $password);
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if ($connection) {
         return $connection;
      } else {
         echo 'Connection failed';
      }
   } catch (PDOException $e) {
      echo "PDOException: " . $e->getMessage();
   }
}
?>