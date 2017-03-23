<?php
       session_start();
?>
<html>
	<head>
		<title>Price Sheet</title>

		<link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
	</head>

	<body>
		<div id="main">
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
		

			<h1>Price Sheet</h1>
			<p>Please see below as your price sheet.<br>If you have any question, simply give us a call, thanks.</p>
			<hr>
			<h3>Side Stone Cost</h3>
			<table width="100%">
				<thead>
					<tr>
						<th width="165px">Size</th>
						<th width="165px">MM</th>
						<th width="165px">Carat Weight</th>
						<th width="165px">A Quality</th>
						<th width="165px">B Quality</th>
						<th width="165px">Single Cut</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
						$SSQuery="SELECT * FROM side_stone_cost WHERE Sheet_id='".$_SESSION['SSC']."'";
						$SSResult=mysqli_query($link,$SSQuery);
					   
						while ($row=mysqli_fetch_array($SSResult)) {
							echo "<tr>";
								echo "<td>".$row['Size']."</td>";
								echo "<td>".$row['MM']."</td>";
								echo "<td>".number_format($row['Start_Weight'],4)."-".number_format($row['End_Weight'],4)."</td>";
								echo "<td>$".$row['A_Quality']."</td>";
								echo "<td>$".$row['B_Quality']."</td>";
								echo "<td>$".$row['Single_Cut']."</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<hr>
			<h3>Finishing Labor</he>
			<div style="width:300;height:22">
			<input type="text" id="myInput" onkeyup="search()" placeholder="Search for GroupStyle...">
			</div>
			<br>
			<form>
				<div style="width:100%; height:30%; overflow=scroll">
					<table id="LaborList" width="100%">
						<thead>
							<tr>
								<th width='17.5'>Group Style Name</th>
								<th width='7.5%'>Jewelry Type</th>
								<th width='7.5%'>Side Stone Setting</th>
								<th width='7.5%'>CPF</th>
								<th width='7.5%'>Rhodium</th>
								<th width='7.5%'>Assembling</th>
								<th width='7.5%'>Two-Tone Casting</th>
								<th width='7.5%'>Millgrain</th>
								<th width='7.5%'>Micro-Plating</th>
								<th width='7.5%'>Sandblast</th>
								<th width='7.5%'>Black Rhodium</th>
								<th width='7.5%'>Total Finish Labor</th>
							</tr>
						<tbody>
						<?php
							$link = mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
							if ($_SESSION['Priv']==0){
								$Query="SELECT * FROM groupstyle_labor JOIN vendor_groupstyle ON vendor_groupstyle.GroupStyle_id=groupstyle_labor.id WHERE vendor_groupstyle.Vendor_id='" . $_SESSION["id"] . "' ORDER BY Jewelry_Type,GroupStyle_Name";
							}else{
								$Query="SELECT * FROM groupstyle_labor";
							}
							$Result=mysqli_query($link,$Query);
							while($row=mysqli_fetch_array($Result)){
								echo "<tr>";
									echo "<td>".$row['GroupStyle_Name']."</td>";
									echo "<td>".$row['Jewelry_Type']."</td>";
									echo "<td>".$row['SSS_Type']."</td>";
									echo "<td>$".$row['CPF']."</td>";
									echo "<td>$".$row['Rhodium']."</td>";
									echo "<td>$".$row['Assembling']."</td>";
									echo "<td>$".$row['TT_Casting']."</td>";
									echo "<td>$".$row['Millgrain']."</td>";
									echo "<td>$".$row['Micro_Plating']."</td>";
									echo "<td>$".$row['Sandblast']."</td>";
									echo "<td>$".$row['Black_Rhodium']."</td>";
									echo "<td>$".$row['Total_Labor']."</td>";
								echo "</tr>";
							}
						?>
						</tbody>
						</thead>
					</table>
				</div>
			</form>
			
			<?php
				if ($_SESSION['Priv']==1){
					echo "
					<hr>
					<h3>Add GroupStyle Finishing Labor</h3>
					<div  ng-app='Labor_Table'   ng-controller='Table_Ctrl'>
						<form ng-submit='Add_Labor()'>
							<table width=100%>
								<thead>
									<tr>
										<th width='17.5'>Group Style Name</th>
										<th width='7.5%'>Jewelry Type</th>
										<th width='7.5%'>Side Stone Setting</th>
										<th width='7.5%'>CPF</th>
										<th width='7.5%'>Rhodium</th>
										<th width='7.5%'>Assembling</th>
										<th width='7.5%'>Two-Tone Casting</th>
										<th width='7.5%'>Millgrain</th>
										<th width='7.5%'>Micro-Plating</th>
										<th width='7.5%'>Sandblast</th>
										<th width='7.5%'>Black Rhodium</th>
										<th width='7.5%'>Total Finish Labor</th>
									</tr>
								</thead>
						
								<tbody>
									<tr>
										<td><input type='text' ng-model='data[0].GroupStyle' min='0' required></td>
										<td>
											<select ng-model='data[0].J_Type'>";
												$link=mysqli_connect("localhost","web222-memodb","January4","web222-memodb");
												$Query1="SELECT * FROM jewelry_list";
												$Result1=mysqli_query($link,$Query1);
												while ($row=mysqli_fetch_array($Result1)) {
													echo "<option>".$row["Jewelry_Type"]."</option>";
												}
												
										echo "
											</select>
										</td>
										<td>
											<select ng-model='data[0].SSS_Type'>";
												$Query2="SELECT * FROM side_setting_cost";
												$Result2=mysqli_query($link,$Query2);
												while ($row=mysqli_fetch_array($Result2)) {
													echo "<option>".$row["Type"]."</option>";
												}
										echo "
											</select>
										</td>
										<td><input type='number' ng-model='data[0].CPF' min='0' required></td>
										<td><input type='number' ng-model='data[0].Rhodium' min='0' required></td>
										<td><input type='number' ng-model='data[0].Assembling' min='0' required></td>
										<td><input type='number' ng-model='data[0].TT_Casting' min='0' required></td>
										<td><input type='number' ng-model='data[0].Millgrain' min='0' required></td>
										<td><input type='number' ng-model='data[0].Micro_Plating' min='0' required></td>
										<td><input type='number' ng-model='data[0].Sandblast' min='0' required></td>
										<td><input type='number' ng-model='data[0].Black_Rhodium' min='0' required></td>
										<td>{{Total()}}</td>
								   </tr>
								</tbody>
							</table>
							<br>
							<input type='submit' value='Add GroupStyle Labor'>
							{{response}}
						</form>
						<hr>
						<h3>Assign to Vendor</h3>
						<form ng-submit='GroupStyle_Assign()'>
							<div style='width:100%;height:25%'>
								<div style='width:40%;height:80%;float:left'>
									<p align='center'><b>All GroupStyle</b></p>
									<select multiple ng-model='AllGroupStyle'>
										<option ng-repeat='x in GroupStyles' value={{x.id}}>{{x.GroupStyle_Name}}</option>
									</select>
								</div>
								<div style='width:20%;height:80%;left:40%;right:40%;float:left;text-align:center'>
									<br>
									<br>
									<br>
									<br>
									<input type='button' value='>>' ng-click='Assign()'> 
									<br>
									<br>
									<input type='button' value='<<' ng-click='Resign()'>  
								</div>
								<div style='width:40%;height:80%;float:right;display:inline' align='center'>
									<div style='height:16%;width:35%'>
										<select ng-model='Vendor'>
											<option ng-repeat='x in Vendors' value={{x.id}}>{{x.Vendor_Name}}</option>
										</select>
									</div>
										<br>
									<select multiple ng-model='CurrentGroupStyle'>
										<option ng-repeat='y in Assignment | filter:{Vendor_id:Vendor}' value={{y.GroupStyle_id}}>{{y.GroupStyle_Name}}</option>
									</select>
								</div>	
							</div>
							<br>
							<input style='right:0' type='submit' value='Submit Changes'>
						</form>
						<hr>
						{{response}}
					</div>
					";
				}
			?>
		</div>
		
	</body>
<script>
	function search() {
	  // Declare variables 
	  var input, filter, table, tr, td, i;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("LaborList");
	  tr = table.getElementsByTagName("tr");

	  // Loop through all table rows, and hide those who don't match the search query
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[0];
	    if (td) {
	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    } 
	  }
	}
	
	
	var app = angular.module('Labor_Table', []);
	app.controller('Table_Ctrl', function($scope, $http) {
		
		$http.get("Retrieve/Retrieve_Vendor_GroupStyle.php")
		.then(function (response) {$scope.Assignment = response.data.records;
		});
		
		$http.get("Retrieve/Retrieve_GroupStyle_Labor.php")
		.then(function (response) {$scope.GroupStyles = response.data.records;
		});
		
		$http.get("Retrieve/Retrieve_Vendors.php")
		.then(function (response) {$scope.Vendors = response.data.records;
		});
				
		$scope.data=[{GroupStyle:"",J_Type:"",SSS_Type:"",CPF:0,Rhodium:0,Assembling:0,TT_Casting:0,Millgrain:0,Micro_Plating:0,Sandblast:0,Black_Rhodium:0,Total_Finish:0}];
		$scope.check=[];
		
		$scope.Not_Null=function(value){
			if (value==null){
				return 0;} else{
				return value;}
		}
		
		$scope.Total=function(){
			if ($scope.data != undefined){
				$scope.data[0].Total_Finish=$scope.Not_Null($scope.data[0].CPF)+$scope.Not_Null($scope.data[0].Rhodium)+$scope.Not_Null($scope.data[0].Assembling)+$scope.Not_Null($scope.data[0].TT_Casting)+$scope.Not_Null($scope.data[0].Millgrain)+$scope.Not_Null($scope.data[0].Micro_Plating)+$scope.Not_Null($scope.data[0].Sandblast)+$scope.Not_Null($scope.data[0].Black_Rhodium);
				return $scope.data[0].Total_Finish;
			}else {
				return 0;
			}
		}
		
		$scope.FindGroupStyle=function(index){
			for (i=0;i<$scope.GroupStyles.length;i++){
				if ($scope.GroupStyles[i].id==index){
					return $scope.GroupStyles[i].GroupStyle_Name;
				}
			}
		}
		
		$scope.CheckExistence=function(a,b,c){
			for (i=0;i<$scope.Assignment.length;i++){
				if ($scope.Assignment[i].Vendor_id==a && $scope.Assignment[i].GroupStyle_id==b && $scope.Assignment[i].GroupStyle_Name==c){
					return true
				}
			}
			return false
		}
		
		$scope.Assign=function(){
			for (j=0;j<$scope.AllGroupStyle.length;j++){
				Index=$scope.AllGroupStyle[j];
				Name=$scope.FindGroupStyle($scope.AllGroupStyle[j]);
				$scope.check.push({Index})
				if ($scope.CheckExistence($scope.Vendor[0],Index,Name)==false){
					$scope.Assignment.push({Vendor_id:$scope.Vendor[0],GroupStyle_id:Index,GroupStyle_Name:Name});
				}
			}
		}
		
		$scope.Resign=function(){
			for (i=0;i<$scope.CurrentGroupStyle.length;i++){
				Index=$scope.CurrentGroupStyle[i];
				Name=$scope.FindGroupStyle($scope.CurrentGroupStyle[i]);
				for (j=0;j<$scope.Assignment.length;j++){
					if ($scope.Assignment[j].Vendor_id==$scope.Vendor[0] && $scope.Assignment[j].GroupStyle_id==Index && $scope.Assignment[j].GroupStyle_Name==Name){
						$scope.Assignment.splice(j,1);
					}
				}
			}
		}
		
		$scope.Add_Labor=function(){
			if ($scope.data[0].Total_Finish==0){
				alert("Please Input Information First!");
			}else{
				var request =  $http({
					method: "post",
					url: "Create/New_GroupStyle_Labor.php",
					data: $scope.data,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});
				request.success(function(data){
					$scope.response=data;
					alert("Added");
					location.reload(true);
				})
				request.error(function(){alert("An Error Occured:Add");});
			};
		}
		
		$scope.GroupStyle_Assign=function(){
			var request =  $http({
				method: "post",
				url: "Update/Update_Vendor_GroupStyle.php",
				data: $scope.Assignment,
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			});
			request.success(function(data){
				$scope.response=data;
				alert("Updated");
				
			})
			request.error(function(){alert("An Error Occured:Update");});			
		}
	})

</script>
</html>	