<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

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
        "SELECT vendors.vendor_id, vendor_name, invoice_number, invoice_total, invoice_date 
         FROM vendors INNER JOIN invoices 
             ON vendors.vendor_id = invoices.vendor_id 
         WHERE invoice_total >= 1000 
         ORDER BY vendor_name, invoice_total DESC";            

	
    $statement = $db->prepare($query);
    $statement->execute();
    $rows = $statement->fetchAll();    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Invoice Over 1000</title>
    </head>
    <body>
        <h1>Invoices with totals over 1000:</h1>

        <?php foreach ($rows as $row) : ?>
		<hr/>
        <p>
			<b>Vendor ID:</b> <?php echo $row['vendor_id']; ?></br>
            <b>Vendor:</b> <?php echo $row['vendor_name']; ?><br/>
            <b>Invoice No:</b> <?php echo $row['invoice_number']; ?><br/>
			<b>Invoice Date:</b> <?php echo $row['invoice_date']; ?></br>
		    <b>Total:</b> $<?php echo number_format($row['invoice_total'], 2); ?>
        </p>
        <?php endforeach; ?>

    </body>
</html>
