<?php
       session_start();
?>
<!DOCTYPE html>

<html>

	<head>

	<title>Welcome</title>

	<link rel="stylesheet" type="text/css" href="style.css">


	</head>

	
	<body>

		<div id="Main">
		<ul id="Menu">

		<li><a href="Home.php">Home</a></li>
		<li><a href="View_Memos.php">View Memos</a></li>
		<li><a href="Fill_Invoices.php">Fill Invoices</a></li>
		<li><a href="Completed_Invoices.php">Completed Invoices</a></li>
		<li><a href="Price_Sheet.php">View Price Sheet</a></li>
		<li><a href="Index.php">Log out</a></li>

		</ul>


		<h1>Welcome,  <?php echo $_SESSION["User"] ; ?></h1>

		<h3>Currently, these are the remaining OPENING MEMOs with Almod:</h3>


		
<table class="Memos">

			<thead>

				<tr>

				<th width="100">Memo#</th>

				<th width="100"># of Jewelry</th>

				<th width="100"># of Stones</th>

				<th width="350">Style Name</th>

				<th width="100">Start Date</th>

				<th width="100">Due Date</th>

				<th width="200">Tracking Number</th>

				<th width="150">Days Remaining</th>



				</tr>

			</thead>


				<?php 
						       $link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
                                                       $openQuery="SELECT memos.Memo, COUNT(Distinct items2.Item) AS N_Jewel, SUM(stones.Qty) AS N_Stones, Style_Name, Start_Date, Due_Date,Tracking_N, DATEDIFF(Due_Date,CURDATE()) AS Days_Remain FROM memos INNER JOIN items2 ON memos.Memo=items2.Memo JOIN stones ON items2.Memo=stones.Memo AND items2.Item=stones.Item WHERE Status='5' AND Vendor_id='". $_SESSION["id"]."' GROUP BY  items2.Memo";
                                                       $openResult=mysqli_query($link,$openQuery);
                                                     while ($row=mysqli_fetch_array($openResult)) {
                                                                 echo "<tr>";
                                                                       echo "<td><a href=View_Memos.php?memo=".$row['Memo'].">".$row['Memo']."</a></td>";
			                                      	       echo "<td>".$row['N_Jewel']."</td>";
                                                                       echo "<td>".$row['N_Stones']."</td>";
                                                                       echo "<td>".$row['Style_Name']."</td>";
                                                                       echo "<td>".$row['Start_Date']."</td>";
                                                                       echo "<td>".$row['Due_Date']."</td>";
                                                                       echo "<td>".$row['Tracking_N']."</td>";
                                                                       if ($row['Days_Remain']<0){
                                                                       echo "<td style='background-color:pink'>".$row['Days_Remain']."</td>";
                                                                       }else{
                                                                       echo "<td>".$row['Days_Remain']."</td>";
                                                                       }
                                                                 echo "</tr>";
                                                        }
                                ?>

				
				</table>



		<hr align="left">



		
	<h3>And these MEMOs has been closed within 30 days:</h3>



			
	
	<table class="Memos">

			<thead>

				<tr>

				<th width="100">Memo#</th>

				<th width="100"># of Jewelry</th>

				<th width="100"># of Stones</th>

				<th width="350">Style Name</th>

				<th width="100">Start Date</th>

				<th width="100">Due Date</th>

				<th width="200">Tracking Number</th>

				<th width="150">Date Finished</th>


				</tr>

			</thead>

					
 				<?php 
						       $link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
                                                       $closedQuery="SELECT memos.Memo, COUNT(Distinct items2.item) AS N_Jewel, SUM( stones.Qty )AS N_Stones, Style_Name, Start_Date, Due_Date, Tracking_N, Updated_Date FROM memos INNER JOIN items2 ON memos.Memo=items2.Memo JOIN stones ON items2.Memo = stones.Memo
AND items2.Item = stones.Item WHERE Status='10' AND Vendor_id='". $_SESSION["id"]."' AND DATEDIFF(CURDATE(),Updated_Date)<30 GROUP BY memos.Memo";
                                                       $closedResult=mysqli_query($link,$closedQuery);
                                                     while ($row=mysqli_fetch_array($closedResult)) {
                                                                 echo "<tr>";
                                                                       echo "<td>".$row['Memo']."</td>";
			                                      	       echo "<td>".$row['N_Jewel']."</td>";
                                                                       echo "<td>".$row['N_Stones']."</td>";
                                                                       echo "<td>".$row['Style_Name']."</td>";
                                                                       echo "<td>".$row['Start_Date']."</td>";
                                                                       echo "<td>".$row['Due_Date']."</td>";
                                                                       echo "<td>".$row['Tracking_N']."</td>";
                                                                       echo "<td>".$row['Updated_Date']."</td>";
                                                                 echo "</tr>";
                                                        }
                                ?>	
		
		</table>
             </div

	</body>

</html>