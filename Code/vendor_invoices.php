<?php
#	ini_set('display_errors', 1);
#	ini_set('display_startup_errors', 1);
#	error_reporting(E_ALL);

	include('/home/phpdbuser/config.php');

	echo "<html><body>";
	echo "<h1>Vendor Invoices</h1>";

	echo "<form method='post'>";
    echo "Vendor ID: <input type='text' name='vendorID'/>";
    echo "<input type='submit' name='submit' value='Submit' />";
	echo "</form>";
	echo "Enter ALL to view all vendors<br/>";

	$vendorID = "all";
	
	if(isset($_POST['vendorID'])) {
		
		$vendorID = trim($_POST['vendorID']);
		
		if ($vendorID < 1) $vendorID = "all";
		
	}
	
    $dsn = 'mysql:host=localhost;dbname=phpdbuser_ap';

    try {
        $db = new PDO($dsn, $dbUser, $dbPass);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
    
	$query = "SELECT vendor_name, invoice_number, invoice_total";
	$query .= " FROM vendors INNER JOIN invoices ON vendors.vendor_id = invoices.vendor_id";

	if (strtolower($vendorID) != "all"){
		$query .= " WHERE vendors.vendor_id = " . $vendorID;
		$query .= " ORDER BY invoice_total DESC";  	
	} else {
		$query .= " ORDER BY vendor_name, invoice_total DESC";  	
	}

    $statement = $db->prepare($query);
    $statement->execute();
    $rows = $statement->fetchAll();
	
	if (count($rows) > 0) {

		echo "<table border=1><tr>";
		echo "<th>Vendor Name</th>";
		echo "<th>Invoice No</th>";
		echo "<th>Invoice Total</th>";
		echo "</tr>";

		// output data of each row
		foreach ($rows as $row) : 
			echo "<td>".$row["vendor_name"]."</td>";
			echo "<td>".$row["invoice_number"]."</td>";
			echo "<td>".$row["invoice_total"]."</td>";
			echo "</tr>";
		endforeach;
		
		echo "</table>";
		
	} else {
		echo "No Data Found";
	}
	
	echo "</body></html>";
		
?>