<?php
const RS="\033[0m";    # reset
const HC="\033[1m";    # hicolor
const UL="\033[4m";    # underline
const INV="\033[7m";   # inverse background and foreground
const FBLK="\033[30m"; # foreground black
const FRED="\033[31m"; # foreground red
const FGRN="\033[32m"; # foreground green
const FYEL="\033[33m"; # foreground yellow
const FBLE="\033[34m"; # foreground blue
const FMAG="\033[35m"; # foreground magenta
const FCYN="\033[36m"; # foreground cyan
const FWHT="\033[37m"; # foreground white
const BBLK="\033[40m"; # background black
const BRED="\033[41m"; # background red
const BGRN="\033[42m"; # background green
const BYEL="\033[43m"; # background yellow
const BBLE="\033[44m"; # background blue
const BMAG="\033[45m"; # background magenta
const BCYN="\033[46m"; # background cyan
const BWHT="\033[47m"; # background white

require_once(__DIR__.'/allocine.class.php');

define('ALLOCINE_PARTNER_KEY', '100043982026');
define('ALLOCINE_SECRET_KEY', '29d185d98c984a359e6e6f26a0474269');

$allocine = new Allocine(ALLOCINE_PARTNER_KEY, ALLOCINE_SECRET_KEY);

$movie = json_decode($allocine->search($argv[1]));
echo 'Titre : ' . $movie->feed->movie[0]->originalTitle . "\n";
echo 'Date de sortie : ' . $movie->feed->movie[0]->release->releaseDate . "\n";
echo 'Réalisateur(s) : ' . $movie->feed->movie[0]->castingShort->directors . "\n";
echo 'Acteurice(s) : ' . $movie->feed->movie[0]->castingShort->actors . "\n\n";
echo 'Lien : ' . $movie->feed->movie[0]->link[0]->href . "\n\n";
$result = $allocine->findMovieTime($movie->feed->movie[0]->code);

//echo $result;

$result = json_decode($result)->feed->theaterShowtimes;

foreach ($result as $theater) {
	echo '---';
	echo BCYN;
	echo $theater->place->theater->name;
	echo RS;
	echo '---';
	echo "\n";
	if (!isset($theater->movieShowtimes)) {
		echo "Pas de séance\n\n";
		continue;
	}
	foreach ($theater->movieShowtimes as $mst) {
		$VO = $mst->version->original;
		if ($VO == 'false') {
			echo "\033[01;31m";
		}
		echo 'Version : '; echo $mst->version->original == 'true' ? 'VO' : 'VF'; echo "\n";
		echo 'Langue : ' . $mst->version->{'$'}; echo "\n";
		echo 'Screen format : ' . $mst->screenFormat->{'$'} . "\n";
		echo $mst->display;
		if ($VO == 'false') {
			echo "\033[0m";
		}
		echo "\n\n";
	}
	echo "\n";
}
