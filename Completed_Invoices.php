<?php
     session_start();
?>
<html>
<head>
	<title>Completed Invoice</title>

	<link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
</head>
<body>
	<div id="main" >
	<ul id="Menu">
		<?php 
		if ($_SESSION["Priv"]==1){
			echo "<li><a href='Admin_Home.php'>Home</a></li>";
			echo "<li><a href='Memos.php'>Manage Memos</a></li>";                 
		}else{
			echo "<li><a href='Home.php'>Home</a></li>
				<li><a href='View_Memos.php'>View Memos</a></li>
				<li><a href='Fill_Invoices.php'>Fill Invoices</a></li>";
		}
		?>
		<li><a href="Completed_Invoices.php">Completed Invoices</a></li>
		<li><a href="Price_Sheet.php">View Price Sheet</a></li>

		<li><a href="Index.php">Log out</a></li>

	</ul>


		<h1>Completed Invoices</h1>
		<p>Please select a closed invoice:</p>
		<form method="get">
		<div style="width:100;height:20;position:relative">
			<select name="memo">
				<option></option>
				<?php
				$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
					if ($_SESSION["Priv"]==1){
					$openQuery="SELECT Memo, Style_Name, Start_Date, Due_Date, DATEDIFF(Due_Date,CURDATE()) AS Days_Remain FROM memos WHERE Status='10'";
					} else {
					$openQuery="SELECT Memo, Style_Name, Start_Date, Due_Date, DATEDIFF(Due_Date,CURDATE()) AS Days_Remain FROM memos WHERE Status='10' AND Vendor_id='". $_SESSION["id"]."'";
					}
					$openResult=mysqli_query($link,$openQuery);
					while ($row=mysqli_fetch_array($openResult)){
						echo "<option>".$row[Memo]."</option>";
					}
				?>
			</select>
		</div>
			<input type="submit" value="submit">
		</form>
		<?php
			if (isset($_GET["memo"])){
				$_SESSION["memo"]=$_GET["memo"];
			}else{
				$_SESSION["memo"]='';
			}
		 ?>
		<hr align="left">
		<?php
			if ($_SESSION["Priv"]==1){
				echo  "<h2 align='center'><h2>";
			}
		?>
		<h2 align="center"><font size="72">Invoice# <?php echo $_SESSION["memo"] ?></font><h2>

		<div  ng-app="Invoice_Table"   ng-controller="Table_Ctrl">

		<div class="loader" ng-show="Loading()"></div>
		<form style="width:100%">
						<div class='Title'>
							<table class="Jewelry">
								<tr>
									<th>Ship To:</th>
									<td>{{Memo[0].Ship_To}}</td>
								</tr>
								<tr>
									<th>GroupStyle:</th>
									<td>{{Memo[0].Group_Style}}</td>
								</tr>
								<tr>
									<th>Jewelry Type:</th>
									<td>{{Memo[0].Jewelry_Type}}</td>
								</tr>
								<tr>
									<th>Side Stone Quality:</th>
									<td>{{Memo[0].SS_Quality}}</td>
								</tr>
							</table>

							<table class="Metal">
								<tr>
									<th>Metal Type:</th>
									<td>{{Memo[0].Metal}}</td>
								</tr>
								<tr>
									<th>Metal Rate:</th>
									<td >{{Memo[0].Metal_Rate}}</td>
								</tr>
								
								<tr>
									<th>Metal Date:</th>
									<td>{{Memo[0].Metal_Date}}</td>
								</tr>
								
								<tr>
									<th>Metal Loss:</th>
									<td>{{Memo[0].Metal_Loss}}</td>
								</tr>
							</table>
							
							<div class="Picture">
								<img class="resize" ng-repeat='x in GroupStyle_Labor | filter:{GroupStyle_Name: Memo[0].Group_Style}' ng-src="{{x.Picture}}">
								<div class="comment">
									<textarea disabled ng-model="Memo[0].Comment"></textarea>
								</div>
							</div>
						</div>

							<table style="width:100%" class="fixed">
								<thead>
									<tr>
										<th rowspan="2" width="20"></th>
										<th rowspan="2" width="40">Item#</th>
										<th rowspan="2" width="50">Center Setting</th>
										<th colspan="4">Side Stone Cost</th>
										<th colspan="2">Setting Labor</th>
										<th colspan="2">Metal</th>
										<th rowspan="2">Special Finish Labor</th>
										<th rowspan="2" width="80">Total</th>
									</tr>
									<tr>
										<th style="font-size: 75%">Weight</th>
										<th style="font-size: 75%" width="50">Qty of Stones</th>
										<th style="font-size: 75%">S.S Type</th>
										<th style="font-size: 75%">S.S Cost</th>
										<th style="font-size: 80%" >Setting Type</th>
										<th style="font-size: 75%" width="100">Setting Amount</th>
										<th style="font-size: 75%" width="50">Weight</th>
										<th style="font-size: 75%" width="50">Metal Cost</th>
									</tr>
								</thead>
								
								<tbody ng-repeat="x in item">
									<tr>
										<td rowspan='5'><input type='checkbox' disabled='disabled' ng-checked='x.New_Model'></td>
										<td rowspan='5'>{{x.Index}}</td>
										<td rowspan='5'>${{x.C_S_Setting}}</td>
										<td>{{x.S_S_Weight_1}}</td>
										<td>{{x.S_S_Qty_1}}</td>
										<td>
										{{x.S_S_Quality_1}}
										</td>
										<td>{{x.S_S_Cost_1| number:2}}</td>
										<td>
										{{x.S_S_Setting_Type_1}}
										</td>
										<td>{{x.S_S_Setting_Cost_1|number:1}}</td>
										<td rowspan='4'>{{x.MEW}}</td>
										<td rowspan='4'>${{x.MEC | number :2}}</td>
										<td rowspan='4'>${{x.Total_Labor | number:2}}</td>
										<td rowspan='5'>${{subTotal(x) | number:2}}</td>
									</tr>

									<tr>
										<td>{{x.S_S_Weight_2}}</td>
										<td>{{x.S_S_Qty_2}}</td>
										<td>
										{{x.S_S_Quality_2}}
										</td>
										<td>{{x.S_S_Cost_2 | number:2}}</td>
										<td>
										{{x.S_S_Setting_Type_2}}
										</td>
										<td>{{x.S_S_Setting_Cost_2|number:1}}</td>

									</tr>

									<tr>
										<td>{{x.S_S_Weight_3}}</td>
										<td>{{x.S_S_Qty_3}}</td>
										<td>
										{{x.S_S_Quality_3}}								
										</td>
										<td>{{x.S_S_Cost_3 | number:2}}</td>
										<td>
										{{x.S_S_Setting_Type_3}}
										</td>
										<td>{{x.S_S_Setting_Cost_3|number:1}}</td>

									</tr>

									<tr>
										<td>{{x.S_S_Weight_4}}</td>
										<td>{{x.S_S_Qty_4}}</td>
										<td>
										{{x.S_S_Quality_4}}
										</td>
										<td>{{x.S_S_Cost_4 | number:2}}</td>
										<td>
										{{x.S_S_Setting_Type_4}}
										</td>
										<td>{{x.S_S_Setting_Cost_4|number:1}}</td>
									</tr>
									<tr>
										<td>Comment:</td>
										<td colspan="6">{{x.Comment}}</td>
										<td colspan="1">Adjustment</td>
										<td colspan="1">{{x.Adjustment}}</td>
									</tr>
								</tbody>
								
								<tfoot>
									<tr height="40">
										<th colspan="4">Country of Origin:</th>
										<td colspan="2">{{Memo[0].Country_Origin}}</td>
										<th>Model Charge</th>
										<td>{{Memo[0].Model_Charge}}</td>
										<th colspan="3">Total Invoice Value</th>
										<td colspan="2">${{getTotal() | number:2}}</td>
									</tr>
								</tfoot>
							</table>
							
							<hr>
					</form>
				</div>
			</div>
		<script>
			var app = angular.module('Invoice_Table', []);
			app.controller('Table_Ctrl', function($scope, $http) {

				$http.get("Retrieve/Retrieve_Items2.php")
				.then(function (response) {$scope.item = response.data.records;
				});

				$http.get("Retrieve/Retrieve_Memo.php")
				.then(function (response) {$scope.Memo = response.data.records;
				});

				$http.get("Retrieve/Retrieve_Stones.php")
				.then(function (response) {$scope.Stones = response.data.records;
				});
				$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
				.then(function (response) {$scope.GroupStyle_Labor = response.data.records;
				});

				$scope.Loading=function(){
					var a="<?php echo isset($_GET["memo"]); ?>";
					if (a){
					   if ($scope.item == undefined ||
						   $scope.Memo  == undefined ||
						   $scope.Stones  == undefined) {
						   return true;} else {
						   return false;};
					} else {return false;}
				};


				$scope.Not_Null=function(value){
					if (value==null){
						return 0;} else{
						return value;}
				}

				$scope.subTotal=function(x){
					return $scope.Not_Null(x.C_S_Setting)+$scope.Not_Null(x.S_S_Cost_1)+$scope.Not_Null(x.S_S_Cost_2)+$scope.Not_Null(x.S_S_Cost_3)+$scope.Not_Null(x.S_S_Cost_4)+$scope.Not_Null(x.S_S_Setting_Cost_1)+$scope.Not_Null(x.S_S_Setting_Cost_2)+$scope.Not_Null(x.S_S_Setting_Cost_3)+$scope.Not_Null(x.S_S_Setting_Cost_4)+$scope.Not_Null(x.Total_Labor)+$scope.Not_Null(x.Adjustment)+$scope.Not_Null(x.MEC);
				}

				$scope.getTotal = function(){
					if($scope.item != undefined &&  $scope.Memo != undefined){
						var total = 0;

						for(var j = 0; j < $scope.item.length; j++){
							var x = $scope.item[j];
							total +=$scope.subTotal(x);
						}

						total += $scope.Memo[0].Model_Charge;

						return total;
					}
				};



			});
		</script>
	 
	</body>
</html>