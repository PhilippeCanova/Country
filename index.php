<html>
<head>
<style>

#content ul { width:100% }
#content li { font-size:130%; display: block; padding:10px; }
#menu ul li a { padding-left:20px; }

.video { display:inline-block; width:30px; background-image:url('video.png'); padding-left:20px; }
.step { display:inline-block; width:25px; background-image:url('step.png') }
#content li > a > img { margin-left:5px; }

.comment { margin-left: 20px; font-size: 80%; color:green; }
.musique { margin-left: 20px; font-size: 80%; color:blue; }


</style>
</html>

<body>
<div id="menu">
<ul>
<?php 
	$groupe = '*';
	$user = '*'; 
	$fichier = './base.csv';
	$baseurl = './index.php';
	$baseurl_step = 'http://canova.philippe.free.fr/country/steps/';
	
	
	$groupes = array("*" => "Liste générale",
					"w" => "A travailler",				
					"d" => "Débutants 2023", 
					"i" => "Intermédiaires 2023", 
					"a" => "Avancés 2023",
					"m" => "Groupe démo",
					"r" => "Rocket's le 12 mars",
					);
	$users = array("*"=>"Toutes", "c" => "Cécile", "p" => "Philippe");
	foreach($groupes as $key => $groupe) {
		echo "<li>".$groupe." : ";
		foreach($users as $id => $user) {
			echo "<a href=\"".$baseurl."?groupe=".$key."&user=".$id."\">";
			echo $user;
			echo "</a>";	
		}	
		echo "</li>";
		
	}
?>
</ul> 
</div>
<div id="content">
<ul>
<?php
	$groupe = '*';
	$user = '*';
	if (isset($_GET['groupe']) && ($_GET['groupe']!="")) { $groupe = $_GET['groupe']; }
	if (isset($_GET['user']) && ($_GET['user']!="")) { $user = $_GET['user']; }
	
	$nb = 0;
	$lines = file($fichier);
	
	foreach ($lines as $lineNumber => $lineContent)
{
		if ($lineContent == '') { continue;}
		if (substr($lineContent,0,1) == '#') { continue;}
		
		$infos = explode(";", $lineContent);
		$title = trim($infos[0]);
		$liste_groupes = str_split(trim($infos[1]));
		$liste_users = str_split(trim($infos[2]));
		$steps = explode("|", trim($infos[3]));
		$videos = explode("|", trim($infos[4]));
		$comment = trim($infos[5]);
		$musique = '';
		if (count($infos)> 6) {
			$musique = trim($infos[6]);
		}
		
		$match_groupe = false;
		if ($groupe=='*') { $match_groupe = true; }
		else if (in_array($groupe, $liste_groupes)) { $match_groupe = true; };
		
		$match_user = false;
		if ($user=='*') { $match_user = true; }
		else if (in_array($user, $liste_users)) { $match_user = true; };
	
	
		if ($match_user && $match_groupe) {
			$nb = $nb + 1;
			echo "<li>";
			foreach ($liste_users as $key) { 
				if ($key != "") {
					echo "<img src=\"./img/".$key.".png\"/>";}
				}
			echo $title;
			
			foreach ($videos as $key => $lien) {
				if ($lien != "") {
					echo '<a href="'. $lien.'" target="parent"><img src="./img/video.png" /></a> ';
				}
			}
			
			foreach ($steps as $key => $lien) {
				if ($lien != "") {
					echo '<a href="'. $baseurl_step;
					echo $lien;
					echo '" target="parent"><img src="./img/step.png" /></a> ';
				}
			}
			
			if ($musique != "") {
				echo '<span class="musique">'.$musique.'</span>';
				
			}
			
			if ($comment != "") {
				echo '<span class="comment">'.$comment.'</span>';
				
			}
			
			echo "</li>";
		}
		
}

echo '</ul></div>';
echo $nb . ' danses';
?>
<br><br>
<a href="http://canova.philippe.free.fr/country/steps/__Lexique%20des%20pas%20de%20line%20dance.pdf"><img src="./img/pdf.png" />Lexique de pas</a><br>
<a href="http://canova.philippe.free.fr/country/steps/__Lexique_FFDanse.pdf"><img src="./img/pdf.png" />Lexique de la FFDanse</a><br>
<a href="http://canova.philippe.free.fr/country/steps/__Vocabulaire%20utilis%EF%BF%BD%20en%20country%20line%20dance.pdf"><img src="./img/pdf.png" />Vocabulaire line dance</a><br>


</body>
</html>