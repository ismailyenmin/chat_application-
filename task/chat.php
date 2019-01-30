<?php 
$con=mysqli_connect("localhost","root","","chat");
session_start();

if(!isset($_SESSION['user'])) {
header("Location: index.html");
}
$current_datetime = date('m/d/Y h:i:s a');

?>
<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/style.css" rel="stylesheet">


<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
<![endif]-->
</head>

<body>
<div class="container wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><?php echo $_SESSION['user']; ?></h3>
            </div>

            <ul class="list-unstyled components">
                <li class=" list-unstyled" id="active_view"></li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div class="" id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
            <!-- <h1>Welcome <?php echo $_SESSION['user']; ?></h1> -->
                    
                </div>
            </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    
                </div>
            </div>
        </div>
           
        </div>
</div>


     
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<script>
        $(document).ready(function(){
             var current_id=" <?php echo $_SESSION['id'];?>";
            var interval = setInterval(function(){
            $.ajax({
                type:"POST",
                url:"ajax.add.php",
                data:"formName=activeview" + "&current_id=" + current_id,
                success:function(result){
                    if(result!=0){
                        $("#active_view").html(result);
                    }else{
                        // alert(result);
                    }
                }
            });
            },1000);
        });
        
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

           
        });
    </script>

</body>
</html>