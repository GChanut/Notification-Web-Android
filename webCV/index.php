<?php 
session_start();
mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
mysql_select_db("webcv") or die("erreur de connexion au serveur 2");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>WebCV - Put Your Vitae On</title>
</head>

<body>
<div id="connexion" align="center"><div id="info2"><a id="info2">Bienvenue sur WebCV</a></div><div><br /><br /><div id = "nom"> <?php require "index2.php"; ?> </div><input type='submit' value="Créer formulaire" onclick="self.location.href='cvs/formulaire.php'" ></div></div>
<div id="container_global" align="center">
		<div id="title">
			WebCV
		</div>
		  <div id="recherche"><form method="post">
		Recherche un CV<input type="text" name="recherche" class="in" value="Nom, Prénom"><input type="submit" value="Valider" class="button">
	</form></div>
          <br /><br />
               <?php if (isset($_POST['recherche']))
					{	
				$recherche= $_POST['recherche'];
	
				//Recherche dans la BDD
				  ?><div id="info"><a id="info">CV correspondant à votre recherche</a></div>
<ul class="gallery ingrid in-sixths">
         <?php
		$req2 = mysql_query("SELECT * FROM webcv_user WHERE (nomUser='$recherche') OR (prenomUser='$recherche')");
		$i=0;
		while(($result2 = mysql_fetch_array($req2)))
		{?>
      <li class="unit"><a href="<?php $link=$result2['lienCV']; echo $link;?>"><div id="cv"><img src="img/cv.png" alt="cv" id="cv"/><div id="nomCv"><a id="nomCv"><?php $nom=$result2['nomUser']; $prenom=$result2['prenomUser']; echo "$nom $prenom";?></a></div>
      </li>
      <?php 
	  $i=$i+1;
	  } 
	  if($i==0)
	  {
		  echo "</ul> <br/> <br/>Aucun CV ne semble correspondre <br/> <br/> <br/> <a href='index.php'>Retour</a>";
	  }
}
else
{?><div id="info">
			<a id="info">Derniers CV postés</a>
            </div>
			<ul class="gallery ingrid in-sixths">
			<?php 
		$req2 = mysql_query("SELECT * FROM webcv_user");
		$i=0;
		while(($result2 = mysql_fetch_array($req2)) && ($i<3))
		{?>
      <li class="unit"><a href="<?php $link=$result2['lienCV']; echo $link;?>"><div id="cv"><img src="img/cv.png" alt="cv" id="cv"/><div id="nomCv"><a id="nomCv"><?php $nom=$result2['nomUser']; $prenom=$result2['prenomUser']; echo "$nom $prenom";?></a></div>
      </li>
      <?php 
	  $i=$i+1;
	  } 
	  ?>
    </ul>
    <br />
    <div id="info"><a id="info">CV les plus vus</a></div>
<ul class="gallery ingrid in-sixths">
         <?php
		$req2 = mysql_query("SELECT * FROM webcv_user");
		$i=0;
		while(($result2 = mysql_fetch_array($req2)) && ($i<3))
		{?>
      <li class="unit"><a href="<?php $link=$result2['lienCV']; echo $link;?>"><div id="cv"><img src="img/cv.png" alt="cv" id="cv"/><div id="nomCv"><a id="nomCv"><?php $nom=$result2['nomUser']; $prenom=$result2['prenomUser']; echo "$nom $prenom";?></a></div>
      </li>
      <?php 
	  $i=$i+1;
	  } 
}?>
    </ul>
	</div>
	

</body>
</html>
