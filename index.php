<?php
include './settings/connect.php';
$sortBy = "ClassNumber";
$sortOrder = "ASC";
if (!empty($_GET["sortBy"])) $sortBy= $_GET["sortBy"];
if ($sortBy == "AverageRating" OR $sortBy == "ReviewCount") $sortOrder ="DESC";
$query  = "SELECT class.ClassId, class.ClassNumber, class.ClassName, class.Instructor, ROUND(SUM( rating.rating ) / COUNT( rating.rating )) AverageRating, COUNT( rating.reviewText ) ReviewCount
FROM class
LEFT JOIN (
rating
) ON ( class.classId = rating.classId ) 
GROUP BY class.ClassId, class.ClassNumber, class.ClassName, class.Instructor
ORDER BY " . $sortBy . " " . $sortOrder;
$result = mysql_query($query);

/*
  rightsize the list of ratings for Internet Explorer
*/

function detect_ie() {
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return "95%";
    else
        return "100%";
}?>
<html>
<head>
 <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<div class="wrapper">
<div class="header">
<h1>UC Davis GSM Class Rating</h1>
<h2>How valuable were your classes?</h2>
</div>

<div class="main">
<div class="main-page">
		<table>
			<tr>
				<th><a title="Sort by class number" href="index.php?sortBy=ClassNumber">Class</a></th>
               <th ><a title="Sort by instructor" href="index.php?sortBy=Instructor">Instructor</a></th> <th ><a title="Sort by average rating" href="index.php?sortBy=AverageRating">Average Rating</a></th>  <th><a title="Sort by number of comments" href="index.php?sortBy=ReviewCount">Comments</a></th>  </tr>
			


               

                
<?php
				while($row = mysql_fetch_array($result, MYSQL_ASSOC))
				{
					$ratingToDisplay = "0";
					if (!empty($row['AverageRating'])) $ratingToDisplay = $row['AverageRating'];
			?>

	
	
	
<tr >
                    <td>
                        <a title = "Click to view individual comments and add your own" href="writeReview.php?classId=<?php echo $row['ClassId'];?>&sortBy=<?php echo $sortBy;?>"><?php echo $row['ClassNumber'] . ' ' . $row['ClassName'];?></a>
                    </td>

<td><a title = "Click to view individual comments and add your own" href="writeReview.php?classId=<?php echo $row['ClassId'];?>&sortBy=<?php echo $sortBy;?>"><?php echo $row['Instructor'];?></a></td>
                    <td >
			
			<a title = "Click to view individual reviews and add your own" href="writeReview.php?classId=<?php echo $row['ClassId'];?>&sortBy=<?php echo $sortBy;?>">
				<img src="images/stars<?php echo $ratingToDisplay;?>.gif" alt="Click to view individual comments and add your own" width ="80" height="16" border="0"/>
			</a>

	</td><td><a title = "Click to view individual comments and add your own" href="writeReview.php?classId=<?php echo $row['ClassId'];?>&sortBy=<?php echo $sortBy;?>"><?php echo $row['ReviewCount']; echo ' ';?> comments</a></td>

                   </tr>  
                   
	
                
            

            


                
  
 	<?php
				}
			?>
			
			<?php
mysql_close($conn);
?>                           
            

            


                   
            

           


		</table>
</div>
</div>
<?php include 'footer.html'; ?>
</body></html>
