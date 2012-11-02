<?php
 if(isset($_GET['user']) && isset($_GET['app'])){
			
				if($_GET['app'] == 'Facebook'){
				
							//Recuperation des données utilisateurs notifcenter
							$str_data = file_get_contents("json/".$_GET['user'].".json");
							$data = json_decode($str_data,true);
							 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
								if($data["application"][$i]["application_name"] == "Facebook"){								
									$token = $data["application"][$i]["application_token"];
									$id = $data["application"][$i]["application_id"];
									$temp = $i;
									$notif = $data["application"][$i]["notification"];
								}
							}
							
							//Récuperation de la langue de l'utilisateur facebook
							$user = "https://graph.facebook.com/".$id."?" . $token;
							$response2 = file_get_contents($user);
							$resp_2 = json_decode($response2,true);
							$language = $resp_2['locale'];
							
							//récupération notification utilisateur facebook
							$notifications = "https://graph.facebook.com/".$id."/notifications?" . $token."&locale=".$language;
							$response = file_get_contents($notifications);
							$resp_a = json_decode($response,true);

							$fbnotif = "";
							foreach($resp_a['data'] as $p){	
								$fbnotif =	$fbnotif."<tr><td onmouseover='javascript:this.style.background=\"#aaa\"' onmouseout='javascript:this.style.background=\"\" ' ><a href=\"".$p['link']."\" target='_blank' style='text-decoration: none; color: #cccccc; display:inline-block'><div  style='margin: 5px 5px 5px 5px'><img src='https://graph.facebook.com/".$p['from']['id']."/picture' align='absmiddle'/><h4>".$p['title']."</h4></div></a></td></tr>";
								
							}		
					
							if($notif != $fbnotif){
								$newnotif = $data["application"][$temp]["notification"] = $fbnotif;
								$fh = fopen("json/".$_GET['user'].".json", 'w+') or die("Error opening output file");
								fwrite($fh, json_encode($data));
								fclose($fh);
							}else{
								$newnotif = $data["application"][$temp]["notification"];
							}
				}
				if($_GET['app'] == 'Twitter'){
							$str_data = file_get_contents("json/".$_GET['user'].".json");
							$data = json_decode($str_data,true);
							 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
								if($data["application"][$i]["application_name"] == "Twitter"){								
									$user = $data["application"][$i]["application_user"];
									$temp = $i;
									$notif = $data["application"][$i]["notification"];
								}
							}

							
							$listfollow = "http://search.twitter.com/search.json?q=".$user;
							$response = file_get_contents($listfollow);
							$resp_a = json_decode($response,true);
							
							$twtmention = "";
							foreach($resp_a['results'] as $p){	
								if($p['from_user'] != $user){
									$twtmention = $twtmention."<tr><td onmouseover='javascript:this.style.background=\"#aaa\"' onmouseout='javascript:this.style.background=\"\" ' ><a href=\"https://twitter.com/i/connect\" target='_blank' style='text-decoration: none; color: #cccccc; display:inline-block'><div style='margin: 5px 5px 5px 5px'><img src='".$p['profile_image_url']."' align='absmiddle'/><h4>".$p['from_user'].": ".$p['text']."</h4></div></a></td></tr>";
								}	
								
							}
							
							if($notif != $twtmention){
									$newnotif = $data["application"][$temp]["notification"] = $twtmention;
									$fh = fopen("json/".$_GET['user'].".json", 'w+') or die("Error opening output file");
									fwrite($fh, json_encode($data));
									fclose($fh);
								}else{
									$newnotif = $data["application"][$temp]["notification"];
								}
				}
				
	}

?>