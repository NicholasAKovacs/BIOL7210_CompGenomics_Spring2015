<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel = "stylesheet" href = "css/styles.css">
	<link rel = "stylesheet" href = "css/styling.css">
	<title>Strains | Genome Typing</title>

	<div id="banner" align="center">
		<a href="./Home.html"><span><img src="./images/LogoFinal5.jpg" alt="Logo" title="Genome Typing Tool" width="650" style="padding-right: 2em"></a></span>
	</div>

	<div class="container">
	<div id="cssmenu">
	<ul>
	<li><a href="./Home.html"><span>Home</span></a></li>
	<li><a href="./About.html"><span>About</span></a></li>
	<li><a href="./Typer.html"><span>Typer</span></a><li>
	<li><a href="./Browser.html"><span>Browser</span></a></li>
	<li><a href="./Blast.html"><span>Blast</span></a></li>
	<li><a href="./Downloads.html"><span>Downloads</span></a></li>
	</ul>
	</div>
	</div>
</head>

<br>
<p>Sequences producing significant alignments:</p>
<hr>

<body>

<?php
$DATABASE = htmlspecialchars($_POST["DATALIB"]);

// Generate unique filename and write user pasted sequence to new file on server
$UNIQUE = uniqid() . '.fasta';

$INPUT_STR = fopen("uploads/$UNIQUE", "w") or die("Unable to open file!");
fwrite($INPUT_STR, htmlspecialchars($_POST["SEQUENCE"]));
fclose($INPUT_STR);

passthru("/usr/local/ncbi/blast/bin/blastn -db 'db/$DATABASE' -query 'uploads/$UNIQUE'");
?>

</body>

<br><br>
<hr>

<footer>
<div id="footer" align="center" style="padding-top: 1em">
@Georgia Institute of Technology 2015
</div>
</footer>
</html>