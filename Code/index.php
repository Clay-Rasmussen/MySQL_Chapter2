<?php
#	ini_set('display_errors', 1);
#	ini_set('display_startup_errors', 1);
#	error_reporting(E_ALL);

	require_once '/home/phpdbuser/config.php';
	try {
		$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		error_log($e->getMessage());
		# echo $e . "<br/>";
		die("Database connection failed.");
	}

    $query = 
        "SELECT vendor_name, invoice_number, invoice_total 
         FROM vendors INNER JOIN invoices 
             ON vendors.vendor_id = invoices.vendor_id 
         WHERE invoice_total >= 500 
         ORDER BY vendor_name, invoice_total DESC";            

	
    $statement = $db->prepare($query);
    $statement->execute();
    $rows = $statement->fetchAll();    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Invoice Over 500</title>
    </head>
    <body>
        <h1>Invoices with totals over 500:</h1>

        <?php foreach ($rows as $row) : ?>
        <p>
            Vendor: <?php echo $row['vendor_name']; ?><br/>
            Invoice No: <?php echo $row['invoice_number']; ?><br/>
            Total: $<?php echo number_format($row['invoice_total'], 2); ?>
        </p>
        <?php endforeach; ?>

    </body>
</html>
