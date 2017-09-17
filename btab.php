<html>
<head>
    <link rel="stylesheet" type="text/css" href="btab.css">
</head>

<body>
<?php
// Filling the matrix variables from the directories
/* The naming convention of the directories is the following: 
 * 
 * DD-MM-YYYY_EventTitle / Number-SongTitle.mp3
 */

$dir    = './musik';
$konzerte = array_slice(scandir($dir, 0), 2);

// building the matrix
$liedermatrix = array();
foreach ($konzerte as $konzert) {
    $newdir = $dir . "/" . $konzert;
    $newdircontent = array_slice(scandir($newdir, 0), 2);
    $liedermatrix[$konzert] = $newdircontent;
}

// function to extract the songname from an mp3 - filename
function getSongName($songfilename) { return trim(explode("-",substr($songfilename,0,-4))[1]); }

// generating the songlist-array
$inverseliedermatrix = array();
foreach ($liedermatrix as $konzert => $lieder) {
    foreach ($lieder as $lied) {
        $inverseliedermatrix[getSongName($lied)][$konzert] = $lied;
    }
}

// generating the songlist of every possible songname
$konzertliste = array();
foreach ($liedermatrix as $konzert => $lied) {
    $konzertliste[] = $konzert;
}

// create table header with konzert names
echo "<table><thead>";
echo "<tr>";
echo "<th><span style='font-size:1.5em;'>Stimmbänd</span></th>";
foreach ($konzertliste as $konzertnametext) {
    $konzertlink = "./konzerte/" . $konzertnametext . ".pdf";
    $konzertDatum = explode("_", $konzertnametext)[0];
    $konzertName = explode("_", $konzertnametext)[1];
    echo "<th>";
    if ( file_exists($konzertlink) ) {
        echo "<a target='_blank' href='" . $konzertlink . "'><span style='font-size:0.5em;'>".$konzertDatum."</span><br/><span style='font-size:0.5em;'>" . $konzertName . " ⛶</span></a>";
    } else {
        echo "<span style='font-size:0.5em;'>".$konzertDatum."</span><br/><span style='font-size:0.5em;'>" . $konzertName . "</span>";
    }
    echo "</th>";
}
echo "</tr></thead><tbody>";

ksort($inverseliedermatrix);

// let's add the table content
foreach ($inverseliedermatrix as $liedname => $konzertliederliste) {
    $notenlink = './noten/' . $liedname . '.pdf';
    if ( file_exists($notenlink) ) {
        echo "<tr><td style='background-color: #555;color: #FFF;padding-left: 5px;'><a target='_blank' href='" . $notenlink . "'>" . $liedname . " ♬</a></td>";
    } else {
        echo "<tr><td style='background-color: #555;color: #FFF;padding-left: 5px;'>" . $liedname . "</td>";
    }
    foreach($konzertliste as $konzertid => $konzert) {
        echo "<td>";
        if ($konzertliederliste[$konzert] != "") {
            $mp3link = $dir . "/" . $konzert . "/" . $konzertliederliste[$konzert];
            echo "<audio controls preload='none' style='width: 75px;'><source src='" . $mp3link . "' type='audio/mp3'><a href='" . $mp3link . "'</a></audio>";
        }
        echo "</td>";
    }
}

echo "</tbody></table>";
?>
</body>
<script src="jquery-3.2.1.min.js"></script>
<script src="jquery.stickytableheaders.min.js"></script>
<script src="btab.js"></script>
</html>
