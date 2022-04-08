# sl.regeneratedidentities.org
## How to run?
In the "db_files" folder - create the following db_config.php file.(following the structure below)


**Note: These files should not be pushed to the repository - include them in your .gitignore**

##db_config.php
```
<?php
$server = '';
$username = '';
$password = '';
$database = '';
try {
    $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
```
