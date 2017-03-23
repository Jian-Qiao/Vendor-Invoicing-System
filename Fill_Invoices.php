<?php
     session_start();
?>

<html>
	<head>
		<title>Fill Invoice</title>

		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
	</head>
	<body>
		<div id="main" >
			<ul id="Menu">
				<?php 
				if ($_SESSION["Priv"]==1){
					echo "<li><a href='Admin_Home.php'>Home</a></li>";
					echo "<li><a href='Create_Memos.php'>Create Memo</a></li>";                 
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


			<h1>Fill Invoices</h1>
			<p>Please select an open invoice:</p>
			<form method="get">
			<div style="width:100;height:20;position:relative">
					<select name="memo">
						<option></option>
						<?php
						$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
							$openQuery="SELECT Memo, Style_Name, Start_Date, Due_Date, DATEDIFF(Due_Date,CURDATE()) AS Days_Remain FROM memos WHERE Status='5' AND Vendor_id='". $_SESSION["id"]."'";
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
			<h2 align="center"><font size="72">Invoice# <?php echo $_SESSION["memo"] ?></font><h2>

			<div  ng-app="Invoice_Table"   ng-controller="Table_Ctrl">
				<div class="loader" ng-show="Loading()"></div>
					<form style="width:100%" ng-submit="SubmitInvoice()">
						<div class='Title'>
							<table class="Jewelry">
								<tr>
									<th>Ship To:</th>
									<td>
										<select ng-model="Memo[0].Ship_To" required>
											<option></option>
											<option>Miami</option>
											<option>New York</option>
											<option>Drop off</option>
										</select>
									</td>
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
									<td ><input type='number' ng-model="Memo[0].Metal_Rate" step="0.01" required></td>
								</tr>
								
								<tr>
									<th>Metal Date:</th>
									<td><input type='date' ng-model="Memo[0].Metal_Date" required></td>
								</tr>
								
								<tr>
									<th>Metal Loss:</th>
									<td><input type='number' ng-model="Memo[0].Metal_Loss" step="0.01"></td>
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
									<td rowspan='5'><input type='checkbox' ng-checked='x.New_Model'></td>
									<td rowspan='5'>{{x.Index}}</td>
									<td rowspan='5'>${{CSS(x)}}</td>
									<td><input type='number' size='1' ng-model="x.S_S_Weight_1" step='0.01' min='0'></td>
									<td><input type='number' size='1' ng-model="x.S_S_Qty_1" min='0'></td>
									<td>
										<select ng-model="x.S_S_Quality_1">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$Query="SELECT  * FROM side_stone_list";
												$Result=mysqli_query($link,$Query);
												while ($row=mysqli_fetch_array($Result)){
													echo "<option>".$row[Quality]."</option>";
												} 
											?>
										</select>
									</td>
									<td>{{Price_Range(x.S_S_Weight_1,x.S_S_Qty_1,x.S_S_Quality_1) | number:2}}</td>
									<td>
										<select ng-model="x.S_S_Setting_Type_1">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$openQuery="SELECT * FROM side_setting_cost WHERE Sheet_id='". $_SESSION["SSS"]."'";
												$openResult=mysqli_query($link,$openQuery);
												while ($row=mysqli_fetch_array($openResult)){
													echo "<option>".$row[Type]."</option>";
												} 
											?>
										</select>
									</td>
									<td>{{SSS(x.S_S_Qty_1,x.S_S_Setting_Type_1)|number:1}}</td>
									<td rowspan='4'><input type='number' size='1' ng-model="x.MEW" step='0.1' min='0'></td>
									<td ng-model="x.MEC" rowspan='4'>${{Metal_Cost(x.MEW) | number :2}}</td>
									<td rowspan='4'>${{ SpecialLabor(x) | number:2}}</td>
									<td rowspan='5'>${{subTotal(x) | number:2}}</td>
								</tr>

								<tr>
									<td><input type='number' size='1' ng-model="x.S_S_Weight_2" step='0.001' min='0'></td>
									<td><input type='number' size='1' ng-model="x.S_S_Qty_2" min='0'></td>
									<td>
										<select ng-model="x.S_S_Quality_2">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$Query="SELECT  * FROM side_stone_list";
												$Result=mysqli_query($link,$Query);
												while ($row=mysqli_fetch_array($Result)){
													echo "<option>".$row[Quality]."</option>";
												}
											?>
										</select>
									</td>
									<td>{{Price_Range(x.S_S_Weight_2,x.S_S_Qty_2,x.S_S_Quality_2) | number:2}}</td>
									<td>
										<select ng-model="x.S_S_Setting_Type_2">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$openQuery="SELECT * FROM side_setting_cost WHERE Sheet_id='". $_SESSION["SSS"]."'";
												$openResult=mysqli_query($link,$openQuery);
												while ($row=mysqli_fetch_array($openResult)){
													echo "<option>".$row[Type]."</option>";
														 } 
											?>
										</select>
									</td>
									<td>{{SSS(x.S_S_Qty_2,x.S_S_Setting_Type_2)|number:1}}</td>

								</tr>

								<tr>
									<td><input type='number' size='1' ng-model="x.S_S_Weight_3" step='0.01' min='0'></td>
									<td><input type='number' size='1' ng-model="x.S_S_Qty_3" min='0'></td>
									<td>
										<select ng-model="x.S_S_Quality_3">
										<?php
										$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
											$Query="SELECT  * FROM side_stone_list";
											$Result=mysqli_query($link,$Query);
											while ($row=mysqli_fetch_array($Result)){
												echo "<option>".$row[Quality]."</option>";
											} 
										?>
										</select>
									</td>
									<td>{{Price_Range(x.S_S_Weight_3,x.S_S_Qty_3,x.S_S_Quality_3) | number:2}}</td>
									<td>
										<select ng-model="x.S_S_Setting_Type_3">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$openQuery="SELECT * FROM side_setting_cost WHERE Sheet_id='". $_SESSION["SSS"]."'";
												$openResult=mysqli_query($link,$openQuery);
												while ($row=mysqli_fetch_array($openResult)){
													echo "<option>".$row[Type]."</option>";
												} 
											?>
										</select>
									</td>
									<td>{{SSS(x.S_S_Qty_3,x.S_S_Setting_Type_3)|number:1}}</td>

								</tr>

								<tr>
									<td><input type='number' size='1' ng-model="x.S_S_Weight_4" step='0.01' min='0'></td>
									<td><input type='number' size='1' ng-model="x.S_S_Qty_4" min='0'></td>
									<td>
										<select ng-model="x.S_S_Quality_4">
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$Query="SELECT  * FROM side_stone_list";
												$Result=mysqli_query($link,$Query);
												while ($row=mysqli_fetch_array($Result)){
													echo "<option>".$row[Quality]."</option>";
												} 
												?>
										</select>
									</td>
									<td>{{Price_Range(x.S_S_Weight_4,x.S_S_Qty_4,x.S_S_Quality_4) | number:2}}</td>
									<td>
										<select ng-model="x.S_S_Setting_Type_4">
											<option></option>
											<?php
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$openQuery="SELECT * FROM side_setting_cost WHERE Sheet_id='". $_SESSION["SSS"]."'";
												$openResult=mysqli_query($link,$openQuery);
												while ($row=mysqli_fetch_array($openResult)){
													echo "<option>".$row[Type]."</option>";
												} 
											?>
										</select>
									</td>
									<td>{{SSS(x.S_S_Qty_4,x.S_S_Setting_Type_4)|number:1}}</td>
								</tr>
								<tr>
									<td>Comment:</td>
									<td colspan="6"><input type="text" align="left" ng-model="x.Comment"></td>
									<td colspan="1">Adjustment</td>
									<td colspan="1"><input type="number" ng-model="x.Adjustment" step="0.01"></td>
								</tr>
							</tbody>
							
							<tfoot>
								<tr height="40">
									<th colspan="4">Country of Origin:</th>
									<td colspan="2"><input type="text" ng-model="Memo[0].Country_Origin"></td>
									<th>Model Charge</th>
									<td><input type="number" ng-model="Memo[0].Model_Charge" min="0"></td>
									<th colspan="3">Total Invoice Value</th>
									<td colspan="2">${{getTotal() | number:2}}</td>
								</tr>
							</tfoot>
						</table>
						
						<hr align="left">
						<input type="submit" value="Submit Invoice">
						<input type="button" value="Save Changes" ng-click="SaveInvoice()">
					{{response}}
				</form>
			</div>
		</div>
	</body>
	
	<script>
	var app = angular.module('Invoice_Table', []);
	app.controller('Table_Ctrl', function($scope, $http) {

		$http.get("Retrieve/Retrieve_Items2.php")
		.then(function (response) {$scope.item = response.data.records;
		});

		$http.get("Retrieve/Retrieve_SS_Cost.php")
		.then(function (response) {$scope.SS_Cost = response.data.records;
		});

		$http.get("Retrieve/Retrieve_Memo.php")
		.then(function (response) {$scope.Memo = response.data.records;
		});

		$http.get("Retrieve/Retrieve_SS_Setting.php")
		.then(function (response) {$scope.SS_Setting = response.data.records;
		});

		$http.get("Retrieve/Retrieve_CS_Setting.php")
		.then(function (response) {$scope.CS_Setting = response.data.records;
		});
		$http.get("Retrieve/Retrieve_Stones.php")
		.then(function (response) {$scope.Stones = response.data.records;
		});
		$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
		.then(function (response) {$scope.GroupStyle_Labor = response.data.records;
		});
		$scope.CSS=function(x){
			if($scope.CS_Setting != undefined & $scope.Stones != undefined){
			var C_Setting=0;
				if (x.MEW!=0 && x.MEW!=undefined){
					for(var j = 0; j < $scope.Stones.length; j++) {
						if(x.Index==$scope.Stones[j].Item){
							for(var i = 0; i < $scope.CS_Setting.length; i++) {
								if(($scope.Stones[j].Weight/$scope.Stones[j].Qty) < $scope.CS_Setting[i].Weight){
									if ($scope.Stones[j].Cut =="COL"){
										C_Setting += $scope.Stones[j].Qty*$scope.CS_Setting[i].COL; break;
									} else  {C_Setting +=  $scope.Stones[j].Qty*$scope.CS_Setting[i].Fancy; break;
								}
							}
						}
					}
				}
				return C_Setting;
				}else {return 0;}
			}
		};

		$scope.Loading=function(){
			var a="<?php echo isset($_GET["memo"]); ?>";
			if (a){
			   if ($scope.item == undefined ||
				   $scope.SS_Cost == undefined ||
				   $scope.Memo  == undefined ||
				   $scope.SS_Setting  == undefined ||
				   $scope.CS_Setting  == undefined ||
				   $scope.Stones  == undefined) {
				   return true;} else {
				   return false;};
			} else {return false;}
		};

		$scope.Price_Range=function(tweight,tqty,ttype) {
			if($scope.SS_Cost != undefined && $scope.Memo!= undefined){
				if(ttype=="A_Quality"){
					for(var i=0;i<$scope.SS_Cost.length;i++){
						if ((tweight/tqty) < $scope.SS_Cost[i].End_Weight ){return tweight*$scope.SS_Cost[i].A_Quality;break;};
					}
				}
				else if (ttype=="B_Quality"){
					for(var i=0;i<$scope.SS_Cost.length;i++){
						if ((tweight/tqty) < $scope.SS_Cost[i].End_Weight ){return tweight*$scope.SS_Cost[i].B_Quality;break;};
						}
					}
				else if (ttype=="Single_Cut"){
					for(var i=0;i<$scope.SS_Cost.length;i++){
						if ((tweight/tqty) < $scope.SS_Cost[i].End_Weight ){return tweight*$scope.SS_Cost[i].Single_Cut;break;};
					}
				}
			}
		};

		$scope.Not_Null=function(value){
			if (value==null){
				return 0;} else{
				return value;}
		}

		$scope.SSS=function(qty,type){
			if ($scope.SS_Setting != undefined){
				for(var i = 0; i < $scope.SS_Setting.length; i++) {
					if($scope.SS_Setting[i].Type ==type) {
						return  qty*$scope.SS_Setting[i].Cost;
					}
				}
			}
		};
		
		$scope.SpecialLabor=function(x){
			if($scope.Memo != undefined){
				if (x.MEW!=0 && x.MEW!=undefined){
					x.Total_Labor=$scope.GroupStyle_Labor[0].Total_Labor;
					return x.Total_Labor;
				} else {
					x.Total_Labor=0;
					return x.Total_Labor;
				}
			}
		}
			

		$scope.Metal_Cost=function(weight){
			if($scope.Memo != undefined){
				if($scope.Memo[0].Metal.indexOf("18K")>=0){
					return  weight*($scope.Memo[0].Metal_Rate/31.1035*0.585*(1+$scope.Memo[0].Metal_Loss)+0.3);
				}else if ($scope.Memo[0].Metal.indexOf("14K")>=0){
					return  weight*($scope.Memo[0].Metal_Rate/31.1035*0.750*(1+$scope.Memo[0].Metal_Loss)+0.3);
				}
			}
		};

		$scope.subTotal=function(x){
			return $scope.CSS(x)+$scope.Not_Null($scope.Price_Range(x.S_S_Weight_1,x.S_S_Qty_1,x.S_S_Quality_1))+$scope.Not_Null($scope.Price_Range(x.S_S_Weight_2,x.S_S_Qty_2,x.S_S_Quality_2))+$scope.Not_Null($scope.Price_Range(x.S_S_Weight_3,x.S_S_Qty_3,x.S_S_Quality_3))+$scope.Not_Null($scope.Price_Range(x.S_S_Weight_4,x.S_S_Qty_4,x.S_S_Quality_4))+$scope.Not_Null($scope.SSS(x.S_S_Qty_1,x.S_S_Setting_Type_1))+$scope.Not_Null($scope.SSS(x.S_S_Qty_2,x.S_S_Setting_Type_2))+$scope.Not_Null($scope.SSS(x.S_S_Qty_3,x.S_S_Setting_Type_3))+$scope.Not_Null($scope.SSS(x.S_S_Qty_4,x.S_S_Setting_Type_4))+$scope.Not_Null(x.Total_Labor)+$scope.Not_Null(x.Adjustment)+$scope.Not_Null($scope.Metal_Cost($scope.Not_Null(x.MEW)));
		}

		$scope.getTotal = function(){
			if($scope.item != undefined &&  $scope.Memo != undefined){
				var total = 0;

				for(var j = 0; j < $scope.item.length; j++){
					var x = $scope.item[j];
					total +=$scope.subTotal(x);
					x.S_S_Cost_1=$scope.Price_Range(x.S_S_Weight_1,x.S_S_Qty_1,x.S_S_Quality_1);
					x.S_S_Cost_2=$scope.Price_Range(x.S_S_Weight_2,x.S_S_Qty_2,x.S_S_Quality_2);
					x.S_S_Cost_3=$scope.Price_Range(x.S_S_Weight_3,x.S_S_Qty_3,x.S_S_Quality_3);
					x.S_S_Cost_4=$scope.Price_Range(x.S_S_Weight_4,x.S_S_Qty_4,x.S_S_Quality_4);
					x.S_S_Setting_Cost_1=$scope.SSS(x.S_S_Qty_1,x.S_S_Setting_Type_1);
					x.S_S_Setting_Cost_2=$scope.SSS(x.S_S_Qty_2,x.S_S_Setting_Type_2);
					x.S_S_Setting_Cost_3=$scope.SSS(x.S_S_Qty_3,x.S_S_Setting_Type_3);
					x.S_S_Setting_Cost_4=$scope.SSS(x.S_S_Qty_4,x.S_S_Setting_Type_4);
					x.C_S_Setting=$scope.CSS(x);
					x.MEC=$scope.Metal_Cost(x.MeW);
				}

				total += $scope.Memo[0].Model_Charge;

				return total;
			}
		};

		$scope.SubmitInvoice=function(){
			if ($scope.Memo.length==0){
				alert("Please Input Information First!");
			}else{
				var con=confirm("You are submitting an Invoice.\nAre you sure EVERYTHING IS CORRECT?!");
				if (con==true){

					var request =  $http({
						method: "post",
						url: "Update/Update_Invoice.php",
						data: $scope.item,
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					});
					request.success(function(data){
						$scope.response=data;
						alert("Invoice Submitted!");

						var request2 =  $http({
							method: "post",
							url: "Update/Update_Memo.php",
							data: $scope.Memo,
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						});
						request2.success(function(data){
							alert("Memo Updated!");
							$scope.response+=data;
							window.location.href ="Fill_Invoices.php";
						});
						request2.error(function(){alert("An Error Occured");});
					});
					request.error(function(){alert("An Error Occured");});
				};
			};
		};

		$scope.SaveInvoice=function(){
			if ($scope.Memo.length==0){
				  alert("Please Input Information First!");
			}else{
				var con=confirm("You want to saving this Invoice?");
				if (con==true){

					var request =  $http({
						method: "post",
						url: "Update/Update_Invoice.php",
						data: $scope.item,
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					});
					request.success(function(data){
						$scope.response=data;
						alert("Invoice Submitted!");

						var request2 =  $http({
							method: "post",
							url: "Update/Save_Memo.php",
							data: $scope.Memo,
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						});
						request2.success(function(data){
							alert("Memo Updated!");
							$scope.response+=data;
							window.location.href ="Fill_Invoices.php";
						});
						request2.error(function(){alert("An Error Occured");});
					});
					request.error(function(){alert("An Error Occured");});
				};
			};
		};
	});
</script>
</html>