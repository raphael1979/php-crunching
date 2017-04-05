<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>php-crunching</title>
</head>
<body>
	<?php 
	$string = file_get_contents("../dictionnaire.txt", FILE_USE_INCLUDE_PATH);
	$dico = explode("\n", $string);
	echo "<h1>Exercice Dictionnaire</h1>"."\n";
	echo "Ce dictionnaire contient ". count($dico)."\n"."mots."."<br>";
	$str15=0;
	foreach ($dico as $mot) {
		if(strlen($mot)==15)
			$str15 ++;
	} echo "Ce dictionnaire contient ". $str15."\n"."mots de quinze caractères."."<br>";
	$w = 0;
	foreach ($dico as $mot) {
		if(strpos($mot, 'w')>-1)
			$w ++;
	}echo "Le nombre de mots contenant la lettre w est de ". $w."."."\n"."<br>";
	$q = 0;
	foreach ($dico as $mot) {
		if (substr($mot, -1)=='q') {
			$q ++;
		}
	}echo "le nombre de mots se terminant par la lettre q est de " .$q."\n"."<br>";
	?>
	<?php 

	$string = file_get_contents("../films.json", FILE_USE_INCLUDE_PATH);
	$brut = json_decode($string, true);
	$top = $brut["feed"]["entry"]; # liste de films
	
	echo "<h1>Exercice Film</h1>";
	echo "<h3>Top 10 Films</h3>";

	for ($i = 1; $i < 11; $i++){
		$titre = $top[$i]['im:name']['label'];
		echo $i  . "\n" . $titre ."<br>";
	}

	echo "<h3>Classement de GRAVITY</h3>";
	for ($i; $i<100; $i++){
		$titre = $top[$i]['im:name']['label'];
		if($titre === 'Gravity'){
			echo "Le film Gravity est à la  ".$i. " ème position.";
		}
	}

	echo "<h3>The LEGO Movie</h3>";
	for($i = 0 ; $i < count($top); $i++){
		$titre = $top[$i]['im:name']['label'];
		if ($titre == "The LEGO Movie"){
			echo "Les réalisateurs du film The LEGO Movie sont ". $top[$i]['im:artist']['label'];
		}
	}

	echo "<h3>film sorties avant 2000</h3>";
	for ($i = 0; $i < count($top)+1; $i ++){
		$date = $top[$i]['im:releaseDate']['label'];
		if(date_parse($date)['year']<2000){
			$nbFilm++;
		}
	}echo "Le nombre de film sorties avant l'an 2000 est de ".$nbFilm.'.';
	echo "<h3>Les films les plus récent et les plus anciens</h3>";
	$toptri = $top;
	function cmp($a, $b) {
		if ($a['im:releaseDate']['label'] == $b['im:releaseDate']['label']) {
			return 0;
		}
		return ($a['im:releaseDate']['label'] < $b['im:releaseDate']['label']) ? -1 : 1;
	}
	usort($toptri, "cmp");
	echo " le film le plus ancien est ".$toptri[0]['im:name']['label']. " et le plus récent est ".$toptri[count($toptri)-1]['im:name']['label'].".";

	echo "<h3>Catégorie de film la plus représentés</h3>";
	$categorie = array();
	foreach ($top as $key => $film) {
		$catFilm = $film['category']['attributes']['label'];
		$categorie[$catFilm]++;
	}
	$plusRepresente = array('category'=>'', 'nombre'=> 0);
	foreach ($categorie as $cat => $nb) {
		if($nb > $plusRepresente['nombre']){
			$plusRepresente = array('category' => $cat, 'nombre' => $nb);
		}
	}echo "la categorie la plus représentée est ".$plusRepresente['category'];

	echo "<h3>Réalisateur le plus representé</h3>";
	foreach ($top as $key => $film) {
		$real = $film['im:artist']['label'];
		$realList[$real]++;
	}
	$realPlus = array('realisteur' => '', 'nombre' => 0);
	foreach ($realList as $real => $nombre) {
		if($nombre > $realPlus['nombre']){
			$realPlus = array('realisateur' => $real, 'nombre' => $nombre);
		} 
	}echo "le réalisateur le plus représenté est ".$realPlus['realisateur'];

	echo"<h3>Prix du top 10</h3>";

	$achat = 0;
	$location = 0;
	for ($i=0; $i < 10; $i++){
		$prixA = $top[$i]['im:price']['attributes']['amount'];
		$prixL = $top[$i]['im:rentalPrice']['attributes']['amount'];
		$achat += $prixA;
		$location += $prixL;
	}

	echo "le prix d'achat est de " .$achat.   "€";
	echo '<br>';
	echo "le prix de la location  est de " .$location. "€";

	echo "<h3>Le mois des sorties</h3>";
	
	$famous = [];
	$famous1 = [];
	foreach ($top as $key => $film) {
		$famous[explode(' ', $film['im:releaseDate']['attributes']['label'])[0]]++;
	}
	arsort($famous);
	$famous1 = array_keys($famous, max($famous));
	if($famous1 > 1){
		foreach ($famous1 as $key => $value) {
			echo $value . "<br>";
		}
	}else{
		echo $famous1[0];
	}
	echo "<h3>10 meilleurs films à voir avec un budget limité</h3>";

	foreach ($top as $key => $film) {
		$price = $film['im:price']['label'];		
		echo $price." ";
		//pas fini 
	}


	?>
</body>
</html>