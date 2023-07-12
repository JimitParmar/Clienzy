 <div class="sidebar-menu">
    <header class="logo">
        
    <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="dashboard.php"> <span id="logo"> <h1 stlye="font-weight: 600;">CLIEZY</h1></span> 
            
        <!--<img id="logo" src="" alt="Logo"/>--> 
        </a> 
    </header>
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    
    
    <!--/down-->
    <div class="down">
    <?php
$aid=$_SESSION['clientmsuid'];
$sql="SELECT EmployeeName from  tblemployee where EmployeeID=:aid";
$query = $dbh -> prepare($sql);
$query->bindParam(':aid',$aid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $client)
{               ?>
        <a href="dashboard.php"><span class=" name-caret"><?php  echo $client->EmployeeName;?></span></a><br>
        
        <?php $cnt=$cnt+1;}} ?>
        <ul>
            <li><a class="tooltips" href="employee-profile.php"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
            <li><a class="tooltips" href="change-password.php" style="width:100px;"><span>Settings</span><i class="lnr lnr-cog"></i></a></li>
            <li><a class="tooltips" href="logout.php"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
        </ul>
    </div>
    <!--//down-->
    <div class="menu">
        <ul id="menu" >
            <li><a href="dashboard.php"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
            <li><a href="add-client.php"><i class="fa fa-user"></i> <span>Add Client</span></a></li>
            <li><a href="manage-clients.php"><i class="fa fa-user"></i> <span>Manage Clients</span></a></li>
            <li><a href="manage-dsc.php"><i class="fa fa-tachometer"></i> <span>Manage DSC</span></a></li>
         
        </ul>
    </div>
</div>