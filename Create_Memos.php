<?php
session_start();
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Create Memo</title>
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
		
		
			<h1>Make New Memo</h1>
			<hr>
			<h2 align="center"><font size="72">Memo #<?php
					$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
					$openQuery="SELECT (MAX(Memo)+1) AS New FROM memos";
					$openResult=mysqli_query($link,$openQuery);
					while ($row=mysqli_fetch_array($openResult)) {
						echo $row["New"];
					}
				?>
			</font></h2>
			<form  ng-app="Create_Memo" ng-submit="New_Memo()"  ng-controller="Table_Ctrl">
			<div class='Title'>
				<table class="Memo">
					<tr>
						<th >Group Style Name</h>
						<td>
						<select ng-model="MemoInfo[0].Group_Style" ng-change='Select_GroupStyle()' required>
							<option ng-repeat='y in Assignment | filter:{Vendor_id:MemoInfo[0].Vendo_idr}'>{{y.GroupStyle_Name}}</option>
						</select>
						</td>
					</tr>
					<tr>
						<th>Jewelry Type</th>
						<td>
						<select ng-model="MemoInfo[0].Jewelry_Type" required>
							<?php
								$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
								$Query="SELECT * FROM jewelry_list";
								$Result=mysqli_query($link,$Query);
								while ($row=mysqli_fetch_array($Result)) {
									echo "<option>".$row["Jewelry_Type"]."</option>";
								}
							?>
						</td>
					</tr> 
					<tr>
						<th>Metal</th>
						<td><input type="text" ng-model="MemoInfo[0].Metal" required></td>
					</tr>
					<tr>
						<th>Side Stone Quality</th>
						<td>
							<select ng-model="MemoInfo[0].SS_Quality" required>
								<?php
									$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
									$Query="SELECT * FROM side_stone_list";
									$Result=mysqli_query($link,$Query);
									while ($row=mysqli_fetch_array($Result)) {
										echo "<option>".$row["Quality"]."</option>";
									}
								?>
							</select>
						</td>
					</tr> 
									<tr>
						<th>Side Setting Type</th>
						<td>
							<select ng-model="MemoInfo[0].SSS_Type" required>
								<?php
									$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
									$Query="SELECT * FROM side_setting_cost";
									$Result=mysqli_query($link,$Query);
									while ($row=mysqli_fetch_array($Result)) {
										echo "<option>".$row["Type"]."</option>";
									}
								?>
							</select>
						</td>
					</tr> 
				 
				</table>
				<table class="Vendor">
					<tr>
						<th>Vendor</th>
						<td>
							<select ng-model="MemoInfo[0].Vendor_id" required>
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
								<input ng-model="photo" style="width:175px" onchange="angular.element(this).scope().file_changed(this)" type="file" accept="image/*" />
							</td>
					</tr>
					<tr>
						<td>
							<input type="button" style="width:175px; background-color:#d9d9d9" value="upload" ng-click="upload_picture()">
						</td>
					</tr>
					<tr>
						<th>Start Date</th>
						<td><input type="date" ng-model="MemoInfo[0].sDate" required></td>
					</tr>
					<tr>
						<th>Days</th>
						<td><input type="number" min="0" value="42" ng-model="MemoInfo[0].Days"  required></td>
					</tr>
				</table>
				<div class="Picture">
					<img class="resize" ng-repeat='x in GroupStyle_Labor | filter:{GroupStyle_Name: MemoInfo[0].Group_Style}' ng-src="{{x.Picture}}">
					<div class="comment">
						<textarea ng-model="MemoInfo[0].Comment"></textarea>
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
						<td><input type="number" ng-model="x.Item" min="0" required></td>
						<td><input type="text" ng-model="x.Sku" required></td>
						<td><input type="text" ng-model="x.Cut" required></td>
						<td><input type="text" ng-model="x.Color" required></td>
						<td><input type="text" ng-model="x.Clarity" required></td>
						<td><input type="number" ng-model="x.Qty" min="0" value="1" required></td>
						<td><input type="number" ng-model="x.Weight" step="0.01" min="0" required></td>
						<td><input type="number" ng-model="x.Cost" step="0.01" min="0" required></td>
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
			<input type="submit" value="Create" style="float:right">
			<div style="wdith:100px;float:left">
				<div style=" width:100px;" ><input type="number" min="0" ng-model="Lines"></div>Rows <input type="button" ng-click=AddRows() value="Add">
				<br>
				<input type="button" ng-click=Import() value="Import from Clipborad">
			</div>
			{{response}}
			</form>
		</div>
	</body>

	<script>
		angular.module('Create_Memo', [])
		.controller('Table_Ctrl', function($scope,$window, $http) {
			$http.get("Retrieve/Retrieve_Vendor_GroupStyle.php")
				.then(function (response) {$scope.Assignment = response.data.records;
			});
			$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
				.then(function (response) {$scope.GroupStyle_Labor = response.data.records;
			});
			$scope.MemoInfo=[
			   {Memo:
					<?php
						$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
						$openQuery="SELECT (MAX(Memo)+1) AS New FROM memos";
						$openResult=mysqli_query($link,$openQuery);
						while ($row=mysqli_fetch_array($openResult)) {
							echo $row["New"];
						}
					?>
					, Vendor:null,Picture:null,sDate:null,Days:42,Group_Style:"",Metal:"",SS_Quality:"",SSS_Type:"",Jewelry_Type:"",Comment:""
			   }
			];
			$scope.PictureFile=[
			{Name:"",Picture:""}
			];
			$scope.Lines=1;
			$scope.range=[];
			$scope.AddRows=function(){ 
				for(var i=0;i<$scope.Lines;i++) {
					if ($scope.range.length<150){
							$scope.range.push({Memo:$scope.MemoInfo[0].Memo,SS_Quality:$scope.MemoInfo[0].SS_Quality,Item:$scope.range.length+1,SSS_Type:"",Sku:"",Cut:"",Color:"",Clarity:"",Qty:1,Weight:0,Cost:0});
					}else{
						alert('Eddie said: Maxium 150 lines');
					}
				}
				$scope.Lines=1;
			};

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

			$scope.Import=function(){
				var clipText = $window.clipboardData.getData('Text');

				// split into rows

				clipRows = clipText.split(String.fromCharCode(13));

				// split rows into columns

				for (i=0; i<clipRows.length; i++) {
					clipRows[i] = clipRows[i].split(String.fromCharCode(9));
				}


				// write out in a table

				for (i=0; i<clipRows.length - 1; i++) {
					   $scope.range.push({Memo:$scope.MemoInfo[0].Memo,SSQ:$scope.MemoInfo[0].SSQ,SSS_Type:$scope.MemoInfo[0].SSS_Type,Item:$scope.range.length+1,Sku:clipRows[i][0],Cut:clipRows[i][1],Color:clipRows[i][2],Clarity:clipRows[i][3],Quantity:parseInt(clipRows[i][4]),Weight:parseFloat(clipRows[i][5]),Cost:parseFloat(clipRows[i][6])})

				}
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

			$scope.file_changed = function(element) {

				 $scope.$apply(function(scope) {
					var photofile = element.files[0];
					var reader = new FileReader();

					reader.readAsDataURL(photofile);
					reader.onload= function(e) {
						$scope.PictureFile[0].Name=$scope.MemoInfo[0].Group_Style;
						$scope.PictureFile[0].Picture=event.target.result;
					};
				 });
			};
						
			$scope.upload_picture=function(){
				if ($scope.PictureFile[0].Picture==""){
					alert("Select Picture First");
				}else if($scope.MemoInfo[0].GroupStyle==""){
						alert("Choose GroupStyle First");
				}else{
					var con=confirm("Updating Picture?");
					if (con==true){
						var request =  $http({
						method: "post",
						url: "Update/Update_Picture.php",
						data: $scope.PictureFile,
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						});
						request.success(function(data){
							alert("Updated");
                            $scope.response=data;
							$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
								.then(function (response) {$scope.GroupStyle_Labor = response.data.records;
							});
						});
						request.error(function(){alert("An Error Occured: Picture");});
					};
				};	
			};
			
			$scope.New_Memo=function(){
				if ($scope.range.length==0){
					alert("Please Input Information First!");
				}else{
					var con=confirm("You are creating a New Memo.\nAre you sure EVERYTHING IS CORRECT?!");
					if (con==true){
						var request =  $http({
						method: "post",
						url: "Create/New_Stone_Item.php",
						data: $scope.range,
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						});
						request.success(function(data){
							alert("Stones Logged In");
                                                        $scope.response=data;
							var request2 =  $http({
								method: "post",
								url: "Create/New_Memo.php",
								data: $scope.MemoInfo,
								headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
							
							request2.success(function(data){
								alert("Memo Created");
								$scope.response+=data;
								location.reload(true);
							});
							request2.error(function(){alert("An Error Occured: Memo");});
						});
						request.error(function(){alert("An Error Occured: Stone");});
					};
				};
			};
		});
	</script>
</html>
