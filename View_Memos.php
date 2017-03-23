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
						 <li><a href='View_Memos.php'>View Memos</a></li>
					<li><a href='Fill_Invoices.php'>Fill Invoices</a></li>";
				}
				?>
				<li><a href="Completed_Invoices.php">Completed Invoices</a></li>
				<li><a href="Price_Sheet.php">View Price Sheet</a></li>

				<li><a href="Index.php">Log out</a></li>

			</ul>
		
		
			<h1>View Memo</h1>
			<hr>
			<form method="get">
				<div style="width:100;height:20;position:relative">
				<select name='memo'>
					<option></option>
					<?php
						$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
						$Query="SELECT * FROM memos WHERE Status=5";
						$Result=mysqli_query($link,$Query);
						while ($row=mysqli_fetch_array($Result)) {
							echo "<option>".$row["Memo"]."</option>";
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
			<form  ng-app="Create_Memo" ng-submit="New_Memo()"  ng-controller="Table_Ctrl">
			<h2 align="center"><font size="72">Memo # {{MemoInfo[0].Memo}}
			</font></h2>
			<div class='Title'>
				<table class="Memo">
					<tr>
						<th >Group Style Name</h>
						<td>
						{{MemoInfo[0].Group_Style}}
						</td>
					</tr>
					<tr>
						<th>Jewelry Type</th>
						<td>
						{{MemoInfo[0].Jewelry_Type}}
						</td>
					</tr> 
					<tr>
						<th>Metal</th>
						<td>{{MemoInfo[0].Metal}}</td>
					</tr>
					<tr>
						<th>Side Stone Quality</th>
						<td>
						{{MemoInfo[0].SS_Quality}}
						</td>
					</tr> 
									<tr>
						<th>Side Setting Type</th>
						<td>
						{{MemoInfo[0].SSS_Type}}
						</td>
					</tr> 
				 
				</table>
				<table class="Vendor">
					<tr>
						<th>Vendor</th>
						<td>
							<select ng-model="MemoInfo[0].Vendor_id" required disabled>
								<?php
									$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
									$Query="SELECT * FROM vendor_list WHERE Priv=0";
									$Result=mysqli_query($link,$Query);
									while ($row=mysqli_fetch_array($Result)) {
										echo "<option value='".$row["id"]."'>".$row["Vendor_Name"]."</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
							<th rowspan="2">Picture</th>
							<td>
								<input ng-model="photo" style="width:175px" onchange="angular.element(this).scope().file_changed(this)" type="file" accept="image/*" / disabled='ture'>
							</td>
					</tr>
					<tr>
						<td>
							<input type="button" style="width:175px; background-color:#d9d9d9" value="upload"  disabled='ture'>
						</td>
					</tr>
					<tr>
						<th>Start Date</th>
						<td><input type="date" ng-model="MemoInfo[0].sDate" required disabled='true'></td>
					</tr>
					<tr>
						<th>Days</th>
						<td><input type="number" min="0" value="42" ng-model="MemoInfo[0].Days"  required disabled='true'></td>
					</tr>
				</table>
				<div class="Picture">
					<img class="resize" ng-repeat='x in GroupStyle_Labor | filter:{GroupStyle_Name: MemoInfo[0].Group_Style}' ng-src="{{x.Picture}}">
					<div class="comment">
						<textarea ng-model="MemoInfo[0].Comment" disabled='true'></textarea>
					</div>
				</div>
			</div>
			<table width=100%>
				<thead>
					<tr>
						<th width="150px">Item #</th>
						<th width="150px">Sku</th>
						<th width="150px">Cut</th>
						<th width="150px">Color</th>
						<th width="150px">Clarity</th>
						<th width="150px">Quantity</th>
						<th width="150px">Carat Weight</th>
						<th width="150px">Cost</th>
					</tr>
				</thead>
				
				<tbody>
					<tr ng-repeat="x in range">
						<td>{{x.Item}}</td>
						<td>{{x.Sku}}</td>
						<td>{{x.Cut}}</td>
						<td>{{x.Color}}</td>
						<td>{{x.Clarity}}</td>
						<td>{{x.Qty}}</td>
						<td>{{x.Weight}}</td>
						<td>{{x.Cost}}</td>
				   </tr>
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="4"></td>
						<th colspan=>Total</th>
						<td>{{Total_Quantity()}}</td>
						<td>{{Total_Weight()|number:2}}</td>
						<td>${{Total_Cost()|number:2}}</td>
					</tr>
				</tfoot>
			</table>
			<hr>
			{{response}}
			</form>
		</div>
	</body>

	<script>
		angular.module('Create_Memo', [])
		.controller('Table_Ctrl', function($scope,$window, $http,$timeout) {
			$scope.range=[];
			$http.get("Retrieve/Retrieve_Vendor_GroupStyle.php")
				.then(function (response) {$scope.Assignment = response.data.records;
			});
			$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
				.then(function (response) {$scope.GroupStyle_Labor = response.data.records;
			});
			$http.get("Retrieve/Retrieve_Stones.php")
			.then(function (response) {$scope.range=response.data.records;
			});
			
			$http.get("Retrieve/Retrieve_Memo.php")
			.then(function (response) {$scope.MemoInfo=response.data.records;
										$scope.MemoInfo[0].sDate=new Date($scope.MemoInfo[0].Start_Date);
										$timeout($scope.Select_GroupStyle,2000);
			});
			

			$scope.Total_Quantity=function(){
				var t_Q=0;
				for (i=0;i<$scope.range.length;i++){
					t_Q  +=$scope.range[i].Qty;
				};
				return t_Q;
			};
			
			$scope.Total_Weight=function(){
				var t_W=0;
				for (i=0;i<$scope.range.length;i++){
					t_W  +=$scope.range[i].Weight;
				};
				return t_W;
			};
			
			$scope.Total_Cost=function(){
				var t_C=0;
				for (i=0;i<$scope.range.length;i++){
					t_C  +=$scope.range[i].Cost;
				};
				return t_C;
			};

			
			$scope.Select_GroupStyle=function(){
				if($scope.GroupStyle_Labor != undefined){
					for (i=0;i<$scope.GroupStyle_Labor.length;i++){
						if ($scope.MemoInfo[0].Group_Style==$scope.GroupStyle_Labor[i].GroupStyle_Name){
							$scope.MemoInfo[0].Jewelry_Type=$scope.GroupStyle_Labor[i].Jewelry_Type;
							$scope.MemoInfo[0].SSS_Type=$scope.GroupStyle_Labor[i].SSS_Type;
							break;
						}
					}
				}
					
				
			};

		});
	</script>
</html>
