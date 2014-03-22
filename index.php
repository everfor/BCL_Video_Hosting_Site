<html><body><h1>It works!</h1>
<?php
try {
	$host = "localhost";
	$db = "wordpress";
    $dbh = new PDO("mysql:host={$host};dbname={$db}", "root", "yg19940308");
    foreach($dbh->query('SELECT * from wp_links') as $row) {
        echo $row["link_id"] . " " . $row["link_url"];
        echo "<br/>";
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
</body></html>
