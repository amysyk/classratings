<head>
</head>

<?php
  // to prevent caching
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include '/home/users/web/b1003/moo.pumpernickeldesignco/www/production/classratings/settings/connect.php';

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
$previousRatingCountStatement = 'SELECT COUNT( * ) previousRatingCount FROM rating WHERE classId=' . $_POST['classId'] . ' AND lastUpdatedBy="'. $clientIP . '"';
$insertStatement  = 'INSERT INTO rating (classId, rating, lastUpdatedBy) VALUES (' . $_POST['classId'] . ',' .  $_POST['rate'] . ',"'. $clientIP . '")';
$updateStatement = 'UPDATE rating SET rating = ' .  $_POST['rate'] . ' WHERE classId=' . $_POST['classId'] . ' AND lastUpdatedBy="'. $clientIP . '"';
$deleteStatement = 'DELETE FROM rating WHERE classId=' . $_POST['classId'] . ' AND lastUpdatedBy="'. $clientIP . '"';

$result = mysql_query($previousRatingCountStatement);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$previousRatingCount = $row['previousRatingCount'];


	if ($previousRatingCount == '0' AND !$_POST['rate'] == '0') {
		$result = mysql_query($insertStatement);
	}
	else
	{
		if ($_POST['rate'] == '0') {
			$result = mysql_query($deleteStatement);
		}
		else
		{
			$result = mysql_query($updateStatement);
		}
	}


mysql_close($conn);
?>                           
            

            


                   
            

           