<?php
session_start();
							$str_data = file_get_contents("oauth2m/api/json/".$_SESSION['user'].".json");
							$data = json_decode($str_data,true);
							 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
								if($data["application"][$i]["application_name"] == "Twitter"){								
									$user = $data["application"][$i]["application_user"];
									$temp = $i;
									$notif = $data["application"][$i]["notification"];
								}
							}
							
							

							
							$listfollow = "https://api.twitter.com/1/friends/ids.json?id=".$user;
							$response = file_get_contents($listfollow);
							$resp_a = json_decode($response,true);
	
							$t=0;
							foreach($resp_a['ids'] as $p){	
								if($t == 2){
									exit();
									die;
								}else{
										$list[$t] = $resp_a['ids'][$t];
										$tweet = "https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&user_id=".$list[$t]."&count=1";
										$response = file_get_contents($tweet);
										$resp_b = json_decode($response,true);
										foreach($resp_b[] as $txt){	
											$txt['text'][$t];
											echo $txt['text'][$t].'<br />';
										}
										
										$t++;
								}
							}		
								
			

?>