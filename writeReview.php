<?php
$classId = $_GET["classId"];
$sortBy = $_GET["sortBy"];
$reviewText = $_POST["reviewText"];
include './settings/connect.php';

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


$clientIP = getRealIpAddr();
$previousRatingCountStatement = 'SELECT COUNT( * ) previousRatingCount FROM rating WHERE classId=' . $classId . ' AND lastUpdatedBy="'. $clientIP . '"';
$insertStatement  = 'INSERT INTO rating (classId, reviewText, lastUpdatedBy) VALUES (' . $classId . ',' .  $reviewText . ',"'. $clientIP . '")';
$updateStatement = 'UPDATE rating SET reviewText = "' .  $reviewText . '" WHERE classId=' . $classId . ' AND lastUpdatedBy="'. $clientIP . '"';

$result = mysql_query($previousRatingCountStatement);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$previousRatingCount = $row['previousRatingCount'];


	if ($previousRatingCount == '0' AND !empty($reviewText)) {
		$result = mysql_query($insertStatement);
	}
	else
	{
		if (!empty($reviewText)) $result = mysql_query($updateStatement);
	}
                       
            
/*
	Set form labels
*/
$ratePrompt = "1) Rate this class:";
$commentPrompt = "2) Add your comment (optional):";
           

$query  = "SELECT class.ClassId, class.ClassNumber, class.ClassName, class.Instructor, ROUND( SUM( rating.rating ) / COUNT( rating.rating ) ) AverageRating, ROUND( SUM( rating.rating ) / COUNT( rating.rating ) , 2 ) AverageTwoDigitRating, COUNT( rating.rating ) ReviewCount
FROM class
LEFT JOIN (
rating
) ON ( class.classId = rating.classId ) 
WHERE class.ClassId =" .$classId. " GROUP BY class.ClassId, class.ClassNumber, class.ClassName, class.Instructor";
$result = mysql_query($query);
$row = mysql_fetch_array($result, MYSQL_ASSOC);

/*
  rightsize the list of ratings for Internet Explorer
*/

function detect_ie() {
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return "95%";
    else
        return "100%";
}

?>

<html>
<head>
 <link rel="stylesheet" type="text/css" href="css/main.css" />
	<!-- demo page js -->
	<script type="text/javascript" src="js/jquery.min.js?v=1.4.2"></script>
	<script type="text/javascript" src="js/jquery-ui.custom.min.js?v=1.8"></script>

	<!-- Star Rating widget stuff here... -->
	<script type="text/javascript" src="js/jquery.ui.stars.js?v=3.0.0b38"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.ui.stars.css?v=3.0.0b38"/>
	
	<!-- Form validation -->
	<script type="text/javascript" src="js/validation.js"></script>

</head>
<body>
<div class="wrapper">
<div class="header">
<a href="index.php" style="color: #ffffff;"><h1>UC Davis GSM Class Rating</h1></a>
<h2>How valuable were your classes?</h2>
</div>

<div class="main">

	<div class="backnav-top"><a href="index.php?sortBy=<?php echo $sortBy;?>" title="Back to all class ratings">&lt;&lt; Back to All Class 

Ratings</a></div>



		<h3>

<?php echo $row['ClassNumber'] . ' ' . $row['ClassName'] . ' with ' . $row['Instructor'];?></h3>		


               

<h4><?php if (!empty($row['AverageTwoDigitRating'])) {
	echo "Average of " . $row['ReviewCount'] . ' ratings: ' . 
	$row['AverageTwoDigitRating'] . " stars";
}?></h4>
<div class="rate">
<div class="rate-left"><h4>
<?php echo $ratePrompt; ?>
</h4></div><div class="rate-right">


<script type="text/javascript">
		$(function(){
			$("#CurrentRating").children().not(":radio").hide();
			$("#CurrentRating").stars({
				callback: function(ui, type, value)
				{
					// Display message to the user at the begining of request
					$("#messagesCurrentRating").text("Saving...").fadeIn(100);
					
					$.post("updateRating.php", {rate: value, classId: "<?php echo $classId;?>"}, function(data)
					{
						$("#ajax_response").html(data);
					});
					// Fade out message
					setTimeout(function(){
							$("#messagesCurrentRating").fadeOut(500)
						}, 250);
				}
			});
			$('<div id="messagesCurrentRating"/>').appendTo("#CurrentRating");
		});
	</script>
	<form id="CurrentRating" action="demo1.php" method="post">
			<input type="radio" name="rate" value="1" title="Poor" id="rate1" /> <label for="rate1_1">Poor</label><br />
			<input type="radio" name="rate" value="2" title="Fair" id="rate2"/> <label for="rate1_2">Fair</label><br />
			<input type="radio" name="rate" value="3" title="Average" id="rate3"/> <label for="rate1_3">Average</label><br />
			<input type="radio" name="rate" value="4" title="Good" id="rate4"/> <label for="rate1_4">Good</label><br />
			<input type="radio" name="rate" value="5" title="Excellent" id="rate5"/> <label for="rate1_5">Excellent</label><br />
			<input type="submit" value="Rate it!" />
	</form></div></div><br>
<div><h4>
<?php echo $commentPrompt; ?>
</h4></div>
<table style= "width: 600px;">
<form action="writeReview.php?classId=<?php echo $classId;?>&sortBy=<?php echo $sortBy;?>" method="POST" id="theform" name="theform">
	 <textarea id="reviewText" style="width: 600px; height:150px;" name="reviewText"></textarea>
</td></tr>
<tr><td>
	 <div align= "right"> <input type="submit" value="Submit"></div>
</td></tr>
</form>
</table>

<p id="error">1,500 characters or less, please</p>
<p id="error2">please enter a rating above</p>

<table style= "width: 600px;">
<?php
$query = "SELECT reviewText, DATE_FORMAT( lastUpdatedOn,  '%M %e, %Y' ) FormattedReviewDate, rating FROM rating WHERE ClassId = "  .$classId.  " AND reviewText IS NOT NULL ORDER BY lastUpdatedOn DESC";
$result = mysql_query($query);
$reviewCounter = 1;
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
				{
				echo "<tr><td> &nbsp </td></tr>";
				echo "<tr><td>" . $row["FormattedReviewDate"];
?>
<script type="text/javascript">
$(function(){
	$("#stars-wrapper<?php echo $reviewCounter;?>").children().not("select").hide();
	$("#stars-wrapper<?php echo $reviewCounter;?>").stars({
		inputType: "select",
		disabled: true
	});
});
</script>
		<div  id="stars-wrapper<?php echo $reviewCounter;?>">
         <select name="rating">
             <option value="1" <?php if ($row['rating'] == '1' ) {echo 'selected="selected"';}?>>Poor</option>
             <option value="2" <?php if ($row['rating'] == '2' ) {echo 'selected="selected"';}?>>Fair</option>
             <option value="3" <?php if ($row['rating'] == '3' ) {echo 'selected="selected"';}?>>Average</option>
             <option value="4" <?php if ($row['rating'] == '4' ) {echo 'selected="selected"';}?>>Good</option>
             <option value="5" <?php if ($row['rating'] == '5' ) {echo 'selected="selected"';}?>>Excellent</option>
         </select>
        </div>

<?php
				echo "</td></tr><tr><td>";
				echo "<div style= 'color:#666;'>";
				echo nl2br($row["reviewText"]);
				echo "</div></td></tr>";
				echo "<tr><td>";
				echo  "<a class='report-abuse' href='mailto:contact@pumpernickeldesign.com?subject=abuse report for class ". $classId . "'>report abuse</a>";
				echo "</td></tr>";
				 $reviewCounter += 1;
				}
?>


			<?php
mysql_close($conn);
?>
</table>


<div class="backnav-bottom"><a href="index.php?sortBy=<?php echo $sortBy;?>" title="Back to all class ratings">&lt;&lt; Back to All Class 

Ratings</a></div>
</div>
<?php include 'footer.html'; ?>
</div>
</body></html>