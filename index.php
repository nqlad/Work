<?php

 try {
     $host = 'localhost';
     $port = 5432;
     $dbname = 'postgres';
     $user = 'postgres';
     $pass = 'yfNL4W';
     $DB = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
     foreach ($DB->query('select * from Notes;') as $row){
         print_r($row);
     }

 } catch(PDOException $e) {
        echo 'Error: '.$e->getMessage();
 }
 echo "test";

 $url = (isset($_GET['q'])) ? $_GET['q']:'';
 $url = rtrim($url,'/');
 $urls = explode('/',$url);
