<?php
  $con=mysqli_connect("localhost","root","","chat");
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit;
} 
	$rid=$_REQUEST['sid'];
	// $qry=mysqli_query($con,"SELECT * FROM `message` WHERE  id='$rid'");
	// echo $_REQUEST['sid'];
	
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
    <script src="js/html5shiv.js"></scripthjkl    <script src="js/respond.min.js"></script>
<![endif]-->
</head>

<body>
<div class="container wrapper">
        <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="dropdown">
				<a onclick="myFunction()" class="dropbtn"><img src="css/dot.png" width="22px"></a>
			  <div id="myDropdown" class="dropdown-content">
          		<a href="logout.php">LOGOUT</a>
			  </div>
			</div>
            <h3><?php echo $_SESSION['user']; ?></h3>
        </div>
        <ul class="list-unstyled components">
            <li class=" list-unstyled" id="active_view"></li>
        </ul>
    </nav>

        <!-- Page Content  -->
<div id="content">
    <div class="">
        <div class="row">
            <form method="POST" class="reset" id="sent">
                <div class="col-md-12">  
                    <div class="panel mypannel">
                        <div class="panel-heading">
                            <?php 
	                           $qry=mysqli_query($con,"SELECT * FROM `login` WHERE  id='$rid'");
	                            if($qry)
	                            	{
	                            		while($row=mysqli_fetch_array($qry))
	                            			{?>
	                                			<p class="chatbox_username"><b><?php echo $row['user']; ?></b></p>
	                                 				<span id="online_offline"></span>

	                                <?php 	}
	                                 }
	                            else{
	                                echo mysqli_error($con);
	                                echo 0; 
	                            	} ?>
                        </div>
	                    <div class="panel-body">
                      	 	<p id="msg"></p> 
                      		<div class="row inbox_chat" id="display_message"></div>
	                    </div>
	                     <div class="panel-footer">
	                        <div class="message_input">
	                            <input type="text" name="message" id="message">
	                            <button name="sent" type="submit" class="hide btn btn-primary">sent</button>
	                        </div>
	                     </div>
	                </div>
                </div>
            </form>
        </div>
    </div>
           
        </div>
    </div>


        
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script>
          $(document).ready(function() {
           $( "#sent" ).submit(function( event ) {
           	 $("#display_message").animate({ scrollTop: 100000 }, "slow");
            	var from_id=" <?php echo $_SESSION['id'];?>";
            	var to_id=" <?php echo $rid;?>";
            	var message=$ ("#message") .val() ;

	            if(message==""){
	                $("#msg").html("please enter 'What you thing?'");
	                return false;
	            }
           
	            $.ajax({
	                type:"POST",
	                url:"ajax.add.php",
	                data:"formName=chat_with_me" + "&from_id=" + from_id +"&to_id=" + to_id +"&message=" + message,
	                success:function(result){
	                if(result=="1"){
	                    refresh();
	                     $('.reset')[0].reset();
	                    // window.location.href = "index.php";
	                }
	                else{
	                    $("#msg").html("insert failed:" + result);
	                }

	            }
	            });
             event.preventDefault();
	            function refresh(){
	                var reqid=" <?php echo $_REQUEST['sid'];?>";
	            	var session_id=" <?php echo $_SESSION['id'];?>";
		            $.ajax({
		                type:"POST",
		                url:"ajax.add.php",
		                 data:"formName=view_message" + "&reqid=" + reqid +"&session_id=" + session_id,
		                success:function(result){
		                    if(result!=0){
		                        $("#display_message").html(result);
		                    }else{
		                        // alert(result);
		                    }
		                }
		            });
		            }
	            });

            // To message view jQuery
		    $("#display_message").animate({ scrollTop: 100000 }, "slow");
		    var interval = setInterval(function(){
	        	var reqid=" <?php echo $_REQUEST['sid'];?>";
	        	var session_id=" <?php echo $_SESSION['id'];?>";
		            $.ajax({
		                type:"POST",
		                url:"ajax.add.php",
		                data:"formName=view_message" + "&reqid=" + reqid +"&session_id=" + session_id,
		                success:function(result){
		                    if(result!=0)
		                    {
		                    	// alert(reqid);
		                        $("#display_message").html(result);
		                    }else
		                    {
		                        // alert(result);
		                    }
		                }
		            });
		    },500);
         
			$("#display_message").animate({ scrollTop: 100000 }, "slow");
		
            var interval = setInterval(function(){
	             var current_id=" <?php echo $_SESSION['id'];?>";
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
            
             var reqid=" <?php echo $_REQUEST['sid'];?>";
            $.ajax({
                type:"POST",
                url:"ajax.add.php",
                data:"formName=online" + "&reqid=" + reqid,
                success:function(result){
                    if(result!=0){
                        $("#online_offline").html(result);
                    }else{
                        // alert(result);
                    }
                }
            });
            },100);
        });
        
      function myFunction()
       {
    		document.getElementById("myDropdown").classList.toggle("show");
		}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(event) {
	  if (!event.target.matches('.dropbtn')) {

    	var dropdowns = document.getElementsByClassName("dropdown-content");
    	var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
    </script>
</body>
</html>