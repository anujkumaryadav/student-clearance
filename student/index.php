<?php
session_start();
error_reporting(0);
include('connect.php');
if(empty($_SESSION['matric_no']))
    {   
    header("Location: login.php"); 
    }
    else{
	}
      

    //get neccesary session details 
    $ID = $_SESSION["ID"];
    $PRN_no = $_SESSION["PRN_no"];
    $dept = $_SESSION['dept'];
    $faculty = $_SESSION['faculty'];
	
   
    $sql = "select SUM(amount) as tot_fee from fee where faculty='$faculty' AND dept='$dept'"; 
    $result = $conn->query($sql);
    $row_fee = mysqli_fetch_array($result);
    $tot_fee=$row_fee['tot_fee'];
    
    //Get outstanding payment etc
    $sql = "select SUM(amount) as tot_pay from payment where studentID='$ID'"; 
    $result = $conn->query($sql);
    $rowpayment = mysqli_fetch_array($result);
    $tot_pay=$rowpayment['tot_pay'];
    
    $outstanding_fee=$tot_fee-$tot_pay;

$sql = "select * from students where matric_no='$matric_no'"; 
$result = $conn->query($sql);
$rowaccess = mysqli_fetch_array($result);

$hostel = $rowaccess["is_hostel_approved"];
$sport = $rowaccess['is_sport_approved'];
$stud_affairs = $rowaccess['is_stud_affairs_approved'];

date_default_timezone_set('Africa/Lagos');
$current_date = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | Online clearance System</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Morris -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <style type="text/css">
    
    </style>
</head>


<body>

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img src=".<?php echo $rowaccess['photo'];  ?>" alt="image" width="142" height="153" class="img-circle" />
                             </span>
  
   
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"><span class="text-muted text-xs block">PRN No: <?php echo $rowaccess['matric_no'];  ?> <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>	
			   <?php
			   include('sidebar.php');
			   
			   ?>
			   
	       </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i> </a>

                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>


                            <span class="m-r-sm text-muted welcome-message">Welcome
                                <?php echo $rowaccess['fullname']; ?></span>
                        </li>
                        <li class="dropdown">

                        <li>
                            <a href="logout.php">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>

                    </ul>

                </nav>
            </div>
            <div class="wrapper wrapper-content">
                <div class="row">
                    <?php 
                 
    $query = "SELECT * FROM students "; 
       $result = mysqli_query($conn, $query); 
      
    if ($result) 
    { 
        // it return number of rows in the table. 
        $row_students = mysqli_num_rows($result); 
          
    }
   
    $sql = "select SUM(amount) as tot_fee from fee where faculty='$faculty' AND dept='$dept'"; 
$result = $conn->query($sql);
$row_fee = mysqli_fetch_array($result);
$tot_fee=$row_fee['tot_fee'];   
    ?>


                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><span class="label label-primary pull-right">Total Fee </span>
                                </h5>
                            </div>

                            <div class="ibox-content">
                                <h3 class="no-margins">NGN<?php echo number_format((float) $tot_fee ,2); ?> </h3>
                                <small> </small>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><span class="label label-secondary pull-right">Amount Paid</span>
                                </h5>
                            </div>

                            <div class="ibox-content">
                                <h3 class="no-margins">NGN<?php echo number_format((float) $tot_pay ,2); ?></h3>
                                <small> </small>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><span class="label label-info pull-right">Status</span>
                                </h5>
                            </div>
                        
                            <div class="ibox-content">
                                <h3 class="no-margins">

                                    <?php if (($outstanding_fee)=="0" && ($sport)=="1" &&($hostel)=="1" && ($stud_affairs)=="1")  { ?>
                                    <div align="center"><i class="fa fa-check-circle"
                                            style="font-size:28px;color:green"></i>
                                        <?php echo "Cleared"; ?></div>
                                    <?php } else {?>
                                    <div align="left"><i class="fa fa-times-circle"
                                            style="font-size:28px;color:orange"></i>
                                        <?php echo "Pending"; ?></div>
                                    <?php } ?>
                                </h3>
                                <p class="no-margins">&nbsp;</p>
                                <?php if (($outstanding_fee)=="0" && ($sport)=="1" &&($hostel)=="1" && ($stud_affairs)=="1")  { ?>
                                <span class="style2"><small> <a href="letter.php" target="_blank">Print Clearance
                                            Letter</a></small></span>


                                <?php } ?>
                            </div>
                        
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <p>&nbsp;</p>
                            <p>

                            </p>
                            <p> </p>
                            <table width="1201" height="341" align="center">
                                <tr>
                                    <td width="1237">
                                        <table border="0" align="center" class="table table-hover no-margins">
                                            <thead>
                                                <tr>
                                                    <th style="color:blue;text-align:center;" width="30%">
                                                        <div align="center"><span class="style1">Class Incharge / HOD
                                                            </span></div>
                                                    </th>
                                                    <th style="color:blue;text-align:center;" width="30%">
                                                        <div align="center"><span class="style1">Library</span></div>
                                                    </th>
                                                    <th style="color:blue;text-align:center;" width="63%">
                                                        <div align="center" class="style1">Workshop</div>
                                                    </th>
                                                    <th style="color:blue;text-align:center;" width="63%">
                                                        <div align="center" class="style1"> Sports </div>
                                                    </th>
                                                    <th style="color:blue;text-align:center;" width="63%">
                                                        <div align="center" class="style1"> Scholarship Department
                                                        </div>
                                                    </th>
                                                    <th style="color:blue;text-align:center;" width="63%">
                                                        <div align="center" class="style1"> Account Department </div>
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>


                                                    <td>
                                                        <?php if (($rowaccess['is_workshop_approved'])==(("0")))  { ?>
                                                        <div align="center"><span
                                                                class="label label-warning">Pending</span> </div>
                                                        <?php } else {?>
                                                        <div align="center"><span
                                                                class="label label-primary">Cleared</span> </div>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <?php if (($rowaccess['is_sport_approved'])==(("0")))  { ?>
                                                        <div align="center"><span
                                                                class="label label-warning">Pending</span> </div>
                                                        <?php } else {?>
                                                        <div align="center"><span
                                                                class="label label-primary">Cleared</span> </div>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <?php if (($rowaccess['is_library_approved'])==(("0")))  { ?>
                                                        <div align="center"><span
                                                                class="label label-warning">Pending</span> </div>
                                                        <?php } else {?>
                                                        <div align="center"><span
                                                                class="label label-primary">Cleared</span> </div>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <?php if (($rowaccess['is_scholarship_approved'])==(("0")))  { ?>
                                                        <div align="center"><span
                                                                class="label label-warning">Pending</span> </div>
                                                        <?php } else {?>
                                                        <div align="center"><span
                                                                class="label label-primary">Cleared</span> </div>
                                                        <?php } ?>
                                                    </td>


                                                    <td>
                                                        <?php if (($rowaccess['is_class_incharge_approved'])==(("0")))  { ?>
                                                        <div align="center"><span
                                                                class="label label-warning">Pending</span> </div>
                                                        <?php } else {?>
                                                        <div align="center"><span
                                                                class="label label-primary">Cleared</span> </div>
                                                        <?php } ?>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p align="center"></p>
                            <div class="row">&nbsp; </p>
                            </div>
                        </div>


                    </div>
                    <div class="footer">

                        <div>
                            <?php include('footer.php');  ?> </div>
                    </div>
                </div>
            </div>

            <!-- Mainly scripts -->
            <script src="js/jquery-2.1.1.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
            <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

            <!-- Flot -->
            <script src="js/plugins/flot/jquery.flot.js"></script>
            <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
            <script src="js/plugins/flot/jquery.flot.spline.js"></script>
            <script src="js/plugins/flot/jquery.flot.resize.js"></script>
            <script src="js/plugins/flot/jquery.flot.pie.js"></script>
            <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
            <script src="js/plugins/flot/jquery.flot.time.js"></script>

            <!-- Peity -->
            <script src="js/plugins/peity/jquery.peity.min.js"></script>
            <script src="js/demo/peity-demo.js"></script>

            <!-- Custom and plugin javascript -->
            <script src="js/inspinia.js"></script>
            <script src="js/plugins/pace/pace.min.js"></script>

            <!-- jQuery UI -->
            <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

            <!-- Jvectormap -->
            <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
            <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

            <!-- EayPIE -->
            <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script>

            <!-- Sparkline -->
            <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

            <!-- Sparkline demo data  -->
            <script src="js/demo/sparkline-demo.js"></script>

   
</body>

</html>