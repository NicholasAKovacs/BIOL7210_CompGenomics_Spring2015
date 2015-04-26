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
// Generate unique filename and write user-pasted sequence to new file on server
function SEQUENCE() {
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

    $UNIQUE = uniqid() . '.fasta';
    $INPUT_STR = fopen("uploads/$UNIQUE", "w") or die("Unable to open file!");
    fwrite($INPUT_STR, htmlspecialchars($_POST["SEQUENCE"]));
    fclose($INPUT_STR);
    // Write user pasted sequence to unique new file on server
    $DATABASE = htmlspecialchars($_POST["DATALIB"]);
    exec("/usr/local/ncbi/blast/bin/blastn -db 'db/$DATABASE' -query 'uploads/$UNIQUE' -outfmt '6'", $rawResults);
    
	// Create the output table and print formatted results  
    echo "<p>";
	echo "<table border='1' style='width=100%' align='center'>";
		echo "<thead>";
			echo "<tr align='center'>"; 
				foreach ($DATAFIELDS as $FIELD) {
					echo "<td><b>" . $FIELD . "</b></td>";
				}
			echo "</tr>";
		echo "</thead>";
	
		echo "<tbody>";
			foreach ($rawResults as $eachResult) {
				$result = preg_split('/\t/', $eachResult);
					echo "<tr>";
						echo "<td align='center'>" . $result[0] . "</td>";
						echo "<td align='center'>" . $result[1] . "</td>";
						echo "<td align='center'>" . $result[2] . "</td>";
						echo "<td align='center'>" . $result[3] . "</td>";
						echo "<td align='center'>" . $result[4] . "</td>";
						echo "<td align='center'>" . $result[5] . "</td>";
						echo "<td align='center'>" . $result[6] . "</td>";
						echo "<td align='center'>" . $result[7] . "</td>";
						echo "<td align='center'>" . $result[8] . "</td>";
						echo "<td align='center'>" . $result[9] . "</td>";
						echo "<td align='center'>" . $result[10] . "</td>";
						echo "<td align='center'>" . $result[11] . "</td>";
					echo "</tr>";
 			}
 		echo "</tbody>";
 	echo "</table>";
 	echo "</p>";		
}

// Append unique filename prefix to user-upload and save file to server
function SEQFILE() {
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

    $DATABASE = htmlspecialchars($_POST["DATALIB"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . uniqid() . basename($_FILES["SEQFILE"]["name"]);
    $fileFormat = pathinfo($target_file,PATHINFO_EXTENSION);        // holds the extension of the file
    $uploadOK = 1;                                                  // used to stop the program if file error occurs
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOK = 0;
    }
    // Check file size
    if ($_FILES["SEQFILE"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOK = 0;
    }
    // Check file type
    if ($fileFormat != "fasta" && $fileFormat != "fa") {
        echo "Sorry, only .fasta & .fa extensions are accepted.";
        $uploadOK = 0;
    }
    // Check if $uploadOK is set to 0 by error-checks
    if ($uploadOK == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["SEQFILE"]["tmp_name"], $target_file)) {
            exec("/usr/local/ncbi/blast/bin/blastn -db 'db/$DATABASE' -query '$target_file' -outfmt '6'", $rawResults);
        } else {
            echo "Sorry, there was an error uploading you file.";
        }
    }
    // Create the output table and print formatted results  
    echo "<p>";
	echo "<table border='1' style='width=100%' align='center'>";
		echo "<thead>";
			echo "<tr align='center'>"; 
				foreach ($DATAFIELDS as $FIELD) {
					echo "<td><b>" . $FIELD . "</b></td>";
				}
			echo "</tr>";
		echo "</thead>";
	
		echo "<tbody>";
			foreach ($rawResults as $eachResult) {
				$result = preg_split('/\t/', $eachResult);
					echo "<tr>";
						echo "<td align='center'>" . $result[0] . "</td>";
						echo "<td align='center'>" . $result[1] . "</td>";
						echo "<td align='center'>" . $result[2] . "</td>";
						echo "<td align='center'>" . $result[3] . "</td>";
						echo "<td align='center'>" . $result[4] . "</td>";
						echo "<td align='center'>" . $result[5] . "</td>";
						echo "<td align='center'>" . $result[6] . "</td>";
						echo "<td align='center'>" . $result[7] . "</td>";
						echo "<td align='center'>" . $result[8] . "</td>";
						echo "<td align='center'>" . $result[9] . "</td>";
						echo "<td align='center'>" . $result[10] . "</td>";
						echo "<td align='center'>" . $result[11] . "</td>";
					echo "</tr>";
 			}
 		echo "</tbody>";
 	echo "</table>";
 	echo "</p>";
}

// Main Program //
if (!empty($_POST["SEQUENCE"])) { SEQUENCE(); } 
elseif (is_uploaded_file($_FILES["SEQFILE"]["tmp_name"])) { SEQFILE(); } 
else { echo "neither file or sequence active"; }

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
