<?php 
	$con=mysqli_connect("localhost","root","","chat");

	if($_POST['formName']=="login"){
		$user=$_POST['user'];
		$password=$_POST['password'];
		$qry=mysqli_query($con,"SELECT * FROM `login` WHERE  user='$user' && password='$password'");
		$n=mysqli_num_rows($qry);
		$row=mysqli_fetch_array($qry);
		if($n==1){
			session_start();
			$_SESSION['id']=$row['id'];
			$_SESSION['user']=$row['user'];
			$_SESSION['password']=$row['password'];
			$_SESSION['active']=$row['active'];
			echo 1;
		}
		else{
			echo mysqli_error($con);
			echo 0;
		}
	}

	if($_POST['formName']=="signup"){
		$user=$_POST['user'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$qry=mysqli_query($con,"INSERT INTO `login`(user,password,email) values('$user','$password','$email')");
		if($qry){
			echo 1;
			
		}
		else{
			echo mysqli_error($con);
			echo 0;
		}
	}

	if($_POST['formName']=="chat_with_me"){
		$from_id=$_POST['from_id'];
		$to_id=$_POST['to_id'];
		$message=$_POST['message'];
		// $create_date=$_POST['create_date'];
		$qry=mysqli_query($con,"INSERT INTO `message`(from_id,to_id,message) values('$from_id','$to_id','$message')");
		if($qry){
			echo 1;
			
		}
		else{
			echo mysqli_error($con);
			echo 0;
		}
	}


	

	if($_POST['formName']=="view_message"){
		$currentid=$_POST['reqid'];
		$user_id=$_POST['session_id'];
		// $active=$_POST['active'];
		$date = date('m/d/Y h:i:s a');
		
		$qry=mysqli_query($con,"SELECT * FROM `message` WHERE  (from_id='$user_id' AND to_id='$currentid') OR (from_id='$currentid' AND to_id='$user_id')  order by id ");

		// $row=mysqli_fetch_array($qry);
			// echo $reqid;
		if($qry){ $i=1; while($row=mysqli_fetch_array($qry)) { 
		if ($currentid == $row['from_id']) {
			$origDate = $row['create_date'];;
 			
			$newDate = date("h:i:A", strtotime($origDate));
			
			?>
			<div class="incoming_msg">
              <!-- <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div> -->
              <div class="received_msg">
                <div class="received_withd_msg">
                 	<p><?php echo $row['message']; ?></p>
                  	<span class="time_date"><?php echo $newDate; ?></span>
                </div>
              </div>
            </div>
			<?php
		}
		else{
			$origDate = $row['create_date'];;
 			
			$newDate = date("h:i:A", strtotime($origDate));
			?>

			<div class="outgoing_msg">
              <div class="sent_msg">
                <p><?php echo $row['message']; ?></p>
                <span class="time_date"><?php echo $newDate; ?></span> </div>
            </div>

			
			<?php
		}
		

			?>
	<?php	 } }
		else{
			echo mysqli_error($con);
			echo 0;
		}
		// SEEN TIME
		$seen_msg=mysqli_query($con,"UPDATE `message` SET seen_time= CURRENT_TIMESTAMP WHERE to_id='$user_id' AND from_id='$currentid' AND seen_time='0000-00-00 00:00:00'");
			if($seen_msg){
				// echo "update sucess";
			}
			else{
				echo mysqli_error($con);
				echo "active user failed";
			}
	}

	if($_POST['formName']=="activeview"){
		$currentid=$_POST['current_id'];
	    $current_datetime = date('m/d/Y h:i:s a');
            $qry=mysqli_query($con,"SELECT * FROM `login` WHERE id != ".$currentid);
            if($qry)
            {
             $i=1;
              while($row=mysqli_fetch_array($qry))
               {
                $pass=$row['id'];
                $date = date('m/d/Y h:i:s a');
                $activedate = $row['active'];

                $current_date = new DateTime($date);
                $active_date = new DateTime($activedate);
                $interval = $current_date->diff($active_date);
                $hours   = $interval->format('%h'); 
                $minutes = $interval->format('%i');
                // echo 'Diff. in minutes is: '.($hours * 60 + $minutes); 
                //date wise validation if start
                if ($date == $activedate > $hours * 60 + $minutes)
                {
                ?>
               <div class="user_bg ">
               		<a href="conversation.php?sid=<?php echo $pass; ?>" class="userlist "><?php echo $row['user']; ?> <span class="active badge">&nbsp;</span> </a>
               	<?php
               		// Notification cound
					$notify_msg=mysqli_query($con,"SELECT COUNT(id) as unread_count FROM `message` WHERE to_id='$currentid'  && from_id='$pass' && seen_time ='0000-00-00 00:00:00'");
					while($row=mysqli_fetch_array($notify_msg)){ ?>
               			<p class="text-right" style="display: block;"><?php echo $row['unread_count']; ?></p> 
               		<!-- Start last message view -->
               	<?php
               		}
               		$lastmsg=mysqli_query($con,"SELECT * FROM `message` WHERE  from_id='$pass' OR to_id='$pass'  ORDER BY id desc LIMIT 1");
               		if($lastmsg)
               		{
		             $i=1;
		              while($row=mysqli_fetch_array($lastmsg)){
		                ?>
		               	<p class="lastmsg"><?php echo substr($row['message'],0,40); ?></p> 
		               	<?php
		               }
		           }
				else{
	                echo mysqli_error($con);
	                echo 0;
            		}
		            ?>
		            <!-- end if last message view -->
		        </div>
            <?php
               	?>
                <?php
                 } //date wise validation if close
            else{
                ?>
                <div class="user_bg ">
                	<a href="conversation.php?sid=<?php echo $pass; ?>" class="userlist "> <?php echo $row['user']; ?></a>
                <?php
                	 // Notification cound
					$notify_msg=mysqli_query($con,"SELECT COUNT(id) as unread_count FROM `message` WHERE to_id='$currentid'  && from_id='$pass' && seen_time ='0000-00-00 00:00:00'");
							while($row=mysqli_fetch_array($notify_msg)){
                	?>
                	<p class="text-right"><?php echo $row['unread_count']; ?></p> 
                	<!-- Start last message else view -->
                 	<?php
                 		}
               			$lastmsg=mysqli_query($con,"SELECT * FROM `message` WHERE from_id='$pass' OR to_id='$pass' ORDER BY id desc LIMIT 1");
               			if($lastmsg)
               			{
			             $i=1;
			              while($row=mysqli_fetch_array($lastmsg)){
			               ?>
			               	<p class="lastmsg"><?php echo substr($row['message'],0,20); ?></p>
			               <?php
			               }
			          	}
			            else{
			                echo mysqli_error($con);
			                echo 0;
			            	}
		            		?>
			           	<!-- end else last message view -->
		        </div>
		            		<?php
		               	
		         } //date wise validation else end

		          $i=$i+1; 
		         } //view message loop close
		    }  //view message if close

            else{
                echo mysqli_error($con);
                echo 0;
            	}//view message else close

            	// ACTIVE TIME
			$active=mysqli_query($con,"UPDATE `login` SET active='$current_datetime' WHERE id='$currentid'");
				// echo $active;
				if($active){
					// echo "update sucess";
				}
				else{
					echo mysqli_error($con);
					echo "active user failed";
				}

			 // DELIVER TIME
			$deliver=mysqli_query($con,"UPDATE `message` SET deliver_time= CURRENT_TIMESTAMP WHERE to_id='$currentid' AND deliver_time='0000-00-00 00:00:00'");
				if($deliver){
					// echo "update sucess";
				}
				else{
					echo mysqli_error($con);
					echo "active user failed";
				}
	}

	if($_POST['formName']=="online"){
		$reqid=$_POST['reqid'];

		$qry=mysqli_query($con,"SELECT * FROM `login` WHERE  id='$reqid'");
		$row=mysqli_fetch_array($qry);
		
	 		$date = date('m/d/Y h:i:s a');
            $activedate = $row['active'];

            $current_date = new DateTime($date);
            $active_date = new DateTime($activedate);
            $interval = $current_date->diff($active_date);
            $hours   = $interval->format('%h'); 
            $minutes = $interval->format('%i');
		if($date == $activedate > $hours * 60 + $minutes)
		{
			?><p><?php echo "online"; ?></p> <?php
		}
		else{
			?><p><?php  echo $row['active']; ?> Lastseen</p><?php
		}
	}





 ?>