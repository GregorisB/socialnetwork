<?php
if(!defined('social'))
	die("Access denied");

loadHeader($title)?>

	<div class="profile-container">	
		
		<div class="user-container user" data-id="<?php echo $row->id ?>">
			<div class="user">
				<div class="img">
					<?php echo $profileAvatar; ?>
				</div>
				<div class="name">
					<h3><?php echo $row->fullname ?> <small><?php echo "@".$row->username ?></small></h3>
				</div>
				<div class="info">
					<p><span id="followers"><?php echo $row->followers ?></span> followers</p>
					<p><span id="following"><?php echo $row->following ?></span> following</p>
				</div>
			</div>
			<p id="btn-profile">
				<?php 
					// get follow unfollow btn
					$rowFollow = '';

					$sql = "SELECT id FROM `users` WHERE `username` = ?";
					$params = array($_SESSION["user"]);
					$user = $db->row($sql, $params);
					print_r($userCheck[0]);
					$sql = "SELECT id FROM `users` WHERE`username` = ?";
					$params = array($userCheck[0]);
					// $params = array($userCheck);
					$userCheck = $db->row($sql, $params);

					if($user->id != $userCheck->id){
						$sql = "SELECT * FROM `follow` WHERE `user_id` = ? AND `follow_user_id` = ?";
						$params = array($user->id, $userCheck->id);
						$rowFollow = $db->row($sql, $params);
						
					}
					if($rowFollow){
						$follow = "<button class='follow-btn'>unfollow</button>";
					}else{
						$follow = "<button class='follow-btn'>follow</button>";
					}
					
					echo $user == $userCheck 
						? "<button class='edit'><a href='".$appURL.$_SESSION['user']."/edit'>edit profile</a></button>" 
						: $follow."<button class='msg'>Message</button>"?>
			</p>
			<p class="bio"><?php echo $row->bio ?></p>
			
		</div> <!-- end user-container -->

		<div class="actions-container">
			<div class="action-type">
				<div class="action post-action"> 
					<button>Posts</button>
				</div>
				<div class="action image-action">
					<button>Images</button>
				</div>
				<div class="action followers-action">
					<button>Followers</button>
				</div>
				<div class="action following-action">
					<button>Following</button>
				</div>
			</div>
		</div> <!-- end actions-container -->

		<div class="status-container">
					
			<!-- user's posts -->
			<div class='get-posts'>
				<div class="users-posts"></div> <!-- load user's posts with ajax -->
				<div id="loading-container">
					<button class="load-posts">more...</button>
					<?php require_once "../files/assets/img/svg/loading.svg"?>
					<p class="no-more-posts"></p>
				</div>
			</div>

			<!-- user's images -->
			<div class='get-images show-action'>
				<div class="users-images"></div> <!-- load user's images with ajax -->
				<div id="loading-container">
					<button class="load-posts">more...</button>
					<?php require_once "../files/assets/img/svg/loading.svg"?>
					<p class="no-more-posts"></p>
				</div>
			</div>

			<!-- user's following -->
			<div class='get-following show-action'>
				<div class="users-following"></div> <!-- load user's following with ajax -->
				<div id="loading-container">
					<button class="load-posts">more...</button>
					<?php require_once "../files/assets/img/svg/loading.svg"?>
					<p class="no-more-posts"></p>
				</div>
			</div>

			<!-- user's followers -->
			<div class='get-followers show-action'>
				<div class="users-followers"></div> <!-- load user's followers with ajax -->
				<div id="loading-container">
					<button class="load-posts">more...</button>
					<?php require_once "../files/assets/img/svg/loading.svg"?>
					<p class="no-more-posts"></p>
				</div>
			</div>
			
		</div> <!-- end status-container -->	
		
	</div> <!-- end profile-container -->

<?php endBody()?>