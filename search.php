<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="get" action="">
    <div class="form-group row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="form-group row"> <!-- 2nd row -->
        <label for="keywords" 
               class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit">Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>

<?php
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
	$keyword=$_GET["keywords"]; // Original Keyword without % signs wrapped around the variable
    $keyword2="%".$keyword."%"; // To allow for successful binding of parameter and execution of query

    // Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php"); 
    $qry = "SELECT * from product WHERE ProductTitle LIKE ? OR ProductDesc LIKE ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ss", $keyword2, $keyword2); 	// "s" - 1 param

    if($stmt->execute()){
        $result = $stmt->get_result();
        $stmt->close();
        echo "<p style='font-size:18px; font-weight:bold;'>Search results for ".$keyword.":</p>";
        if ($result->num_rows == 0){
            echo "<div class='row'>";
            echo "<div class='col-sm-12'>";
            echo "<h4 style='color:red'> No record found! Please try another keyword. </h4>";
            echo "</div>";
            echo "</div>";
        }
        else{
            while ($row = $result->fetch_array()){
                $product = "productDetails.php?pid=$row[ProductID]";
                echo "<div class='row'>";
                echo "<div class='col-sm-12'>";
                echo "<p><a href=$product>$row[ProductTitle]</a></p>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
    // To Do (DIY): End of Code
    $conn->close(); // Close database connnection
}

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>