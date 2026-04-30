<?php
	
	/*if(isset($_GET['read_status']) && $_GET['read_status'] == 1) { $additionalQuery = " AND read_status IN({$_GET['read_status']})"; }
	elseif(isset($_GET['read_status']) && $_GET['read_status'] == 0) { $additionalQuery = " AND read_status IN({$_GET['read_status']})"; }
	else { $additionalQuery = ""; }*/

	$post_result = "";


	if(isset($_POST['archivebutton']) && isset($_POST['checkers'])) {
		
		$kAlive = 0;

		$usr_datasets = array("archivedata"=>1);
		$usr_key = "";

		foreach($_POST['checkers'] as $ikey) {
			
			$usr_key = array("id"=>$ikey);
			$result = mysqli_data_update($tbL104,$usr_datasets,$usr_key);

			if(isset($result) && $result == 2) {
				$kAlive += 1;
			}
		}

		if(isset($kAlive) && $kAlive >= 1) {
			$post_result .= '<div class="block-element bottom-push-10" align="center">';
			$post_result .= '<div class="cs-width-300 red-theme white-font pads10 ft-sml-size">';
			$post_result .= 'Selected message(s) archived from inbox successfully';
			$post_result .= '</div>';
			$post_result .= '</div>';
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers'])) {
		
		$data_deleted = 0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach($_POST['checkers'] as $ikey) {
			
			$usr_key = array("id"=>$ikey);
			$del = trash_record($tbL104,$usr_key);

			if(isset($del) && $del == 2) {
				$data_deleted += 1;
			}
		}

		if(isset($data_deleted) && $data_deleted >= 1) {
			$post_result .= '<div class="block-element bottom-push-10" align="center">';
			$post_result .= '<div class="cs-width-300 red-theme white-font pads10 ft-sml-size">';
			$post_result .= 'Selected message removed successfully';
			$post_result .= '</div>';
			$post_result .= '</div>';
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	$additionalQuery = "";
	$inbox_selection_key = array("receiver"=>$userSignedIn,"deletedata"=>0,"archivedata"=>0);
	mysqli_data_check($tbL104,'(*)',$inbox_selection_key);
	$inbox_count = $numOfrows;

	if(isset($inbox_count) && $inbox_count >= 1)
	{
		$additionalQuery = " AND read_status IN(0,1)";
		$inbox_query = array("receiver"=>$userSignedIn,"deletedata"=>0,"archivedata"=>0);
		$get_inbox_all = mysqli_data_fetch($tbL104,'id,subject',$inbox_query,'array');
		$additionalQuery = "";

		if(is_array($get_inbox_all)) {
			foreach($get_inbox_all as $key => $val) {
				$subj = explode('(', $val['subject']);
				$approval_subj = str_replace(')','',$subj[1]);

				$job_query = array("subject"=>$approval_subj,"approval_status"=>"Pending","deletedata"=>0);
				$datasets = "user_one,approval_one,user_two,approval_two,user_three,approval_three,user_four,approval_four,user_five,approval_five,approval_status";
				$get_job = mysqli_data_fetch($tbL151,$datasets,$job_query,'noarray');

				if($get_job[0] == $userSignedIn && $get_job[1] == 0) {
					$read_query = array("id"=>$val['id']);
					$post_data = array("read_status"=>2);
					mysqli_data_update($tbL104,$post_data,$read_query);
				} elseif($get_job[2] == $userSignedIn && $get_job[3] == 0) {
					if($get_job[1] == 1) {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>2);
					} else {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>0);
					}
					mysqli_data_update($tbL104,$post_data,$read_query);
				} elseif($get_job[4] == $userSignedIn && $get_job[5] == 0) {
					if($get_job[3] == 1) {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>2);
					} else {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>0);
					}
					mysqli_data_update($tbL104,$post_data,$read_query);
				} elseif($get_job[6] == $userSignedIn && $get_job[7] == 0) {
					if($get_job[5] == 1) {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>2);
					} else {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>0);
					}
					mysqli_data_update($tbL104,$post_data,$read_query);
				} elseif($get_job[8] == $userSignedIn && $get_job[9] == 0) {
					if($get_job[7] == 1) {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>2);
					} else {
						$read_query = array("id"=>$val['id']);
						$post_data = array("read_status"=>0);
					}
					mysqli_data_update($tbL104,$post_data,$read_query);
				}
			}
		}


		echo $post_result;
		
		?>
			<h3 class="large nobold black-font alignct light-red-font top-pull-5">To read your inbox messages, click on the message subject</h3><br>

			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
				<div class="block-element steel-blue-theme top-pull-7 right-pull-30 bottom-pull-10 left-pull-30">
					<span class="ln-display-box float-left">
						<b class="fa-recycle nobold white-font"></b><input type="submit" name="archivebutton" value="Archive" class="no-back-white anchor right-push-15">
						<b class="fa-trash nobold white-font"></b><input type="submit" name="deletebutton" value="Remove" class="no-back-white anchor right-push-10">
					</span>
					<span class="ln-display-box float-right top-pull-3">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>" class="white-font ft-sml-size"><b class="fa-arrow-left nobold white-font"></b>&nbsp; Back</a>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
				<div class="block-element box-border-thick-bottom top-pull-7 right-pull-30 bottom-pull-10 left-pull-30 ft-xsml-size">
					<span class="ln-display-box float-left">
						<div class="float-right cs-width-400 left-push-30"><b class="float-left fa-search right-push-5"></b><input type="text" id="searchmessage" placeholder="Search for message using keyword e.g PR1010" class="cs-width-350 nopads no-back-black" title="Press enter-key to search" onkeyup="filtermsg(event)"></div>
						<?php echo number_format($inbox_count); ?> Message(s)
					</span>
					<span class="ln-display-box float-right">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>&r=2" class="blue-font right-push-15">All</a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>&r=1" class="blue-font right-push-15">Read</a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>&r=3" class="blue-font">UnRead</a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>&r=5" class="default-text-font-bold royal-blue-font left-push-15">Unsigned</a>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
				<div class="block-element top-pull-7 right-pull-30 bottom-pull-3 left-pull-30 ft-xsml-size dark-blue-font">
					Green - <b class="nobold default-text-font-bold">Read & Signed</b> | Red - <b class="nobold default-text-font-bold">New Message</b> | Black - <b class="nobold default-text-font-bold">Unsigned Message</b>
				</div>
				<div class="block-element pads10 nc-height-85 y-scroll">

					<?php

					if(!isset($_GET['read_msg']))
					{
						
						?>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="30px" align="center" class="black-theme"><input type="checkbox" name="checker" lang="off" onclick="chkrAll(this)" title="Check All"></th>
									<th width="50px" align="center">&nbsp;</th>
									<th width="300px" align="center">Subject</th>
									<th width="200px" align="center">Sent By</th>
									<th width="200px" align="center">Sent On</th>
									<th width="150px" align="center">Priority</th>
									<th width="200px" align="center">Type</th>
								</tr>
								<?php

									//for search keywords
									if(isset($_GET['r']) && $_GET['r'] == 1) {
										$_SESSION['keywords']=" AND read_status IN(1) ORDER BY id DESC";
									} elseif(isset($_GET['r']) && $_GET['r'] == 2) {
										$_SESSION['keywords']=" ORDER BY id DESC";
									} elseif(isset($_GET['r']) && $_GET['r'] == 3) {
										$_SESSION['keywords']=" AND read_status NOT IN(1) ORDER BY id DESC";
									} elseif(isset($_GET['r']) && $_GET['r'] == 4) {
										$_SESSION['keywords']=" AND subject REGEXP '{$_GET['keyword']}' ORDER BY id DESC";
									} elseif(isset($_GET['r']) && $_GET['r'] == 5) {
										$_SESSION['keywords']=" AND read_status IN(2) ORDER BY id DESC";
									} else { 
										if(isset($_SESSION['keywords'])) {
											$_SESSION['keywords'] = $_SESSION['keywords'];
										} else {
											$_SESSION['keywords']=" ORDER BY id DESC";
										}
									}

									//$keywords = " GROUP BY priority ORDER BY priority ASC";

									//pagination controller
									if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
										$curpage = $_GET['pg'];
										$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
										//$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
									} else {
										$curpage = 0;
										$pgstart = 0; $pglimit = 15;
										//$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
									}

									//$inbox_selection_key_ = array("receiver"=>$userSignedIn,"deletedata"=>0,"archivedata"=>0); 
									//$get_inbox_data = mysqli_data_fetch($tbL104,'priority',$inbox_selection_key_,'array');

									//if(is_array($get_inbox_data)) {
										$num=0; $g="";
										//foreach ($get_inbox_data as $inkey => $invalue) {
											
											$keywords_ = $_SESSION['keywords']." LIMIT ".$pgstart.",".$pglimit;
											$additionalQuery = $keywords_;
											$inbox_selection_key_2 = array("receiver"=>$userSignedIn,"deletedata"=>0,"archivedata"=>0);
											$inbox_datasets = "id,subject,sender,receiver,message,priority,msgtype,read_status,datelogged,timelogged";
											$get_inbox_data_ = mysqli_data_fetch($tbL104,$inbox_datasets,$inbox_selection_key_2,'array');

											$subject=""; $sentby=""; $priority=""; $type=""; $msg_color_tag=""; $msg_status="";

											if(is_array($get_inbox_data_)) {
												foreach ($get_inbox_data_ as $in2key => $in2value) {

													//$subject=arrayget_key($notify_title,$in2value['subject']);
													$subject=$in2value['subject'];
													$sentby=idget_data($tbL7,$in2value['sender'],'staffname');
													$priority=arrayget_key($mail_message_priority,$in2value['priority']);
													$type=arrayget_key($notify_arry,$in2value['msgtype']);
													$msg_color_tag=arrayget_key($color_notification,$in2value['read_status']);
													$msg_status=arrayget_key($inb_message_status,$in2value['read_status']);

													$num += 1;
													$g = $num / 2;

													$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

													?>
														
														<tr bgcolor="<?php echo $trcolor; ?>">
															<td width="30px" align="center" class="grey-theme">
																<input type="checkbox" name="checkers[]" value="<?php echo $in2value['id']; ?>" class="item">
															</td>
															<td width="40px" align="center">
																<b class="fa-flag nobold" style="color: <?php echo $msg_color_tag; ?>" title="<?php echo $msg_status; ?>"></b>
															</td>
															<td width="300px" align="left" class="box-border-dark-thick-right">
																<a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=modals&prefix=&param=<?php echo $_GET['param']; ?>&ftoken=<?php echo $_GET['ftoken']; ?>&stoken=<?php echo $_GET['stoken']; ?>&read_msg=<?php echo $in2value['id']; ?>" class="blue-font"><?php echo $subject; ?></a>
															</td>
															<td width="200px" align="center" class="box-border-dark-thick-right">
																<?php echo $sentby; ?>
															</td>
															<td width="200px" align="center" class="box-border-dark-thick-right">
																<?php echo date("d/m/Y",strtotime($in2value['datelogged'])).' '.$in2value['timelogged']; ?>
															</td>
															<td width="150px" align="center" class="box-border-dark-thick-right">
																<b><?php echo $priority; ?></b>
															</td>
															<td width="200px" align="center" class="box-border-dark-thick-right">
																<?php echo $type; ?>
															</td>
														</tr>

													<?php
												}

											} else {
												?>
													<tr><td colspan="8"><h1 class="large nobold dark-grey-font alignct">Inbox is empty at the moment!</h1></td></tr>
												<?php
											}
										/*}
									} else {
										?>
											<tr><td colspan="8"><h1 class="large nobold dark-grey-font alignct">Inbox is empty at the moment!</h1></td></tr>
										<?php
									}*/
								?>
							</table>
							<div class="block-element cs-height-20">
							</div>
						<?php

						$additionalQuery = "";
						mysqli_data_check($tbL104,'(*)',$inbox_selection_key_2);
						$totalcount = $numOfrows;

						$paginate = data_pagenation(15,0,$totalcount);
						if(isset($paginate) && !empty($paginate)) {
							echo $paginate;
						}

						//paginate this page
					}
					else
					{
						$receiver = idget_data($tbL7,$userSignedIn,'staffname');
						$msg = escape_data($_GET['read_msg']);
						
						$additionalQuery = ""; $msg_selection_key = array("id"=>$msg);
						$msg_datasets = "id,subject,sender,receiver,message,priority,msgtype,datelogged,timelogged";
						$get_msg_data = mysqli_data_fetch($tbL104,$msg_datasets,$msg_selection_key,'noarray');

						//update as read
						$msg_update_datasets = array("read_status"=>1);
						mysqli_data_update($tbL104,$msg_update_datasets,$msg_selection_key);

						$subject=arrayget_key($notify_title,$get_msg_data[1]);

						?>
							<div class="block-element box-border-thick-bottom right-pull-10 bottom-pull-50 left-pull-10">
								<h2 class="large"><?php echo $subject; ?> <span class="float-right nobold"><small class="bottom-push-3 dark-grey-font">Delivery Date/Time</small><br><small class="black-font"><?php echo date("d/m/Y",strtotime($get_msg_data[7])).' '.$get_msg_data[8]; ?></small></span></h2>
								<h3 class="large nobold">Hello, <?php echo $receiver; ?></h3>
								<?php echo $get_msg_data[4]; ?>
							</div>
						<?php
					}
					
					?>

				</div>
			</form>
		<?php
	}
	else
	{
		?>
			<br><br><br>
			<h1 class="large nobold dark-grey-font alignct">There are no messages in your inbox at the moment!</h1>
		<?php
	}

	$pageurl = 'inbox.php?inb=1';

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<div id="processbar" class="fx-position-stick fscr zind-1 motion noshow txp2-white" align="center">
	<div class="block-element nc-height-20">&nbsp;</div>
	<div class="cs-width-250 white-theme obj-shadow pads20">Processing data..</div>
</div>

<script>

	function pager() {
		var cpager = setInterval(() => {
			if(sessionStorage.getItem('pager') !== null && sessionStorage.getItem('pager') !== undefined) {
				if(document.getElementById('pagenumbr').value != sessionStorage.getItem('pager')) {
					objDisplay('processbar');
				}
			}
		},1000);
	}

	window.onload = function() {
		document.getElementById('searchmessage').focus();
		pager();

		if(document.getElementById('pagenumbr')) {
			sessionStorage.setItem('pager',document.getElementById('pagenumbr').value);
		}
	}


	function filtermsg(e) {
		
		var keyword = document.getElementById('searchmessage').value;
		
		if(e instanceof KeyboardEvent) {
			if(e.keyCode == 13 || e.which == 13 || e.code == 13 || e.key == 'Enter' || e.enterKey) {
				if((window.location.href).indexOf('?r=') > -1) {
					var uri = (window.location.href).split('?r=');
					window.location.href = uri[0]+'?r=4&keyword='+keyword;
				} else {
					window.location.href = window.location.href+'?r=4&keyword='+keyword;
				}
			}
		}
	}


	function chkrAll(obj) {

		var items = document.getElementsByClassName('item');

		if(obj.lang == 'off') {
			obj.lang = 'on';
			for(var i=0; i<items.length; i++) {
				items[i].setAttribute('checked','checked');
			}
		} else if(obj.lang == 'on') {
			obj.lang = 'off';
			for(var i=0; i<items.length; i++) {
				items[i].removeAttribute('checked');
			}
		}
	}

</script>
