<?php
session_start();
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Edit Memo</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="Main">
			<ul id="Menu">
				<?php 
				if ($_SESSION["Priv"]==1){
					echo "<li><a href='Admin_Home.php'>Home</a></li>";
					echo "<li><a href='Memos.php'>Manage Memos</a></li>";                 
				}else{
					echo "<li><a href='Home.php'>Home</a></li>
					<li><a href='Fill_Invoices.php'>Fill Invoices</a></li>";
				}
				?>
				<li><a href="Completed_Invoices.php">Completed Invoices</a></li>
				<li><a href="Price_Sheet.php">View Price Sheet</a></li>

				<li><a href="Index.php">Log out</a></li>

			</ul>
		
			<form  ng-app="Ship_Memo" ng-submit="Ship_Memo()"  ng-controller="Table_Ctrl">
				<h1>Ship Memos</h1>
				<hr>
				<h3>Memos waiting to be shipped:</h3>
				
				<table class='Memos'>
					<thead>
						<tr>
							<th width='100'>Vendor</th>
							<th width='100'>Memo#</th>
							<th width='125'># of Jewelry</th>
							<th width='125'># of Stones</th>
							<th width='250'>Style Name</th>
							<th width='100'>Start Date</th>
							<th width='100'>Due Date</th>
							<th width='150'>Tracking Number</th>
							<th width='150'>Ship?</th>

						</tr>
					</thead>
					<tbody>
						<tr ng-repeat='x in Created'>
							<td>{{x.Vendor_Name}}</td>
							<td>{{x.Memo}}</td>
							<td>{{x.N_Jewel}}</td>
							<td>{{x.N_Stones}}</td>
							<td>{{x.Style_Name}}</td>
							<td>{{x.Start_Date}}</td>
							<td>{{x.Due_Date}}</td>
							<td>{{x.Tracking_N}}</td>
							<td><input type='checkbox' ng-model='x.Selected'></td>
						</tr>
					</tbody>
				</table>
				<input type='submit' value='Ship' align:'right'>
				<h3>And these MEMOs has been shipped to Vendors within 30 days:</h3>
				<table class='Memos'>
					<thead>
						<tr>
							<th width='100'>Vendor</th>
							<th width='100'>Memo#</th>
							<th width='125'># of Jewelry</th>
							<th width='125'># of Stones</th>
							<th width='250'>Style Name</th>
							<th width='100'>Start Date</th>
							<th width='100'>Due Date</th>
							<th width='150'>Tracking Number</th>
							<th width='150'>Date Shipped</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat='x in Opened'>
							<td>{{x.Vendor_Name}}</td>
							<td>{{x.Memo}}</td>
							<td>{{x.N_Jewel}}</td>
							<td>{{x.N_Stones}}</td>
							<td>{{x.Style_Name}}</td>
							<td>{{x.Start_Date}}</td>
							<td>{{x.Due_Date}}</td>
							<td>{{x.Tracking_N}}</td>
							<td>{{x.Updated_Date}}</td>
						</tr>
					</tbody>
				</table>
				{{response}}
			</form>
			<hr>
		</div>
	</body>
	<script>
		angular.module('Ship_Memo', [])
		.controller('Table_Ctrl', function($scope,$window, $http) {
			$http.get("Retrieve/Retrieve_Created_Memos.php")
				.then(function (response) {$scope.Created = response.data.records;
			});
			$http.get("Retrieve/Retrieve_Opened_Memos.php")
				.then(function (response) {$scope.Opened = response.data.records;
			});
			$scope.Ship_Memo=function(){
				$scope.Shipmemt=$scope.Created.filter(function(t){
					return t.Selected==1;
				}
				);
				if ($scope.Shipmemt.length==0){
					alert("Please Select Memos Need to Be Shipped First!");
				}else{
					var con=confirm("You Are Shipping These Memos.\nAre you sure EVERYTHING IS CORRECT?!");
					if (con==true){
						var request =  $http({
						method: "post",
						url: "Update/Ship_Memo.php",
						data: $scope.Shipmemt,
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						});
						request.success(function(data){
							alert("Shipped!");
							//location.reload(true);
							$scope.response=data;
						});
						request.error(function(){alert("An Error Occured");});
					};
				};
			}
		});
	</script>