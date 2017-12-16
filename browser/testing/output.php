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
// Create array of data fields generated from blast's tab delimited output format
$DATAFIELDS = array(
                    "QUERY_ID",
                    "SUBJECT_ID",
                    "%_IDENTITY",
                    "ALIGNMENT_LENGTH",
                    "MISMATCHES",
                    "GAP_OPENS",
                    "Q._START",
                    "Q._END",
                    "S._START",
                    "S._END",
                    "E_VALUE",
                    "BIT_SCORE",
                   );

// Generate unique filename
$UNIQUE = uniqid() . '.fasta';
$INPUT_STR = fopen("uploads/$UNIQUE", "w") or die("Unable to open file!");
fwrite($INPUT_STR, htmlspecialchars($_POST["SEQUENCE"]));
fclose($INPUT_STR);

// Write user pasted sequence to unique new file on server
$DATABASE = htmlspecialchars($_POST["DATALIB"]);
exec("/usr/local/ncbi/blast/bin/blastn -db 'db/$DATABASE' -query 'uploads/$UNIQUE' -outfmt '6'", $rawResults);
    
?>

<p>
<table border="1" style="width:100%">
	<thead>
		<tr>
			<?php foreach ($DATAFIELDS as $FIELD) {
				echo "<td><b>" . $FIELD . "</b></td>";
			}
			?>
		</tr>	
	</thead>
	<tbody>
		<?php foreach ($rawResults as $eachResult) {
				$result = preg_split('/\t/', $eachResult);
					echo "<tr>";
						echo "<td>" . $result[0] . "</td>";
						echo "<td>" . $result[1] . "</td>";
						echo "<td>" . $result[2] . "</td>";
						echo "<td>" . $result[3] . "</td>";
						echo "<td>" . $result[4] . "</td>";
						echo "<td>" . $result[5] . "</td>";
						echo "<td>" . $result[6] . "</td>";
						echo "<td>" . $result[7] . "</td>";
						echo "<td>" . $result[8] . "</td>";
						echo "<td>" . $result[9] . "</td>";
						echo "<td>" . $result[10] . "</td>";
						echo "<td>" . $result[11] . "</td>";
					echo "</tr>";
 			  }	
		?>
	</tbody>
</table>
</p>		

</body>

<br><br>
<hr>

<footer>
<div id="footer" align="center" style="padding-top: 1em">
@Georgia Institute of Technology 2015
</div>
</footer>
</html>