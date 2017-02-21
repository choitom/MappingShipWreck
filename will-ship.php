<?php
require("phpsqlajax_dbinfo.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysql_connect('localhost', $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected){
  die('Can\'t use db: ' . mysql_error());
}

// Select all the rows in the ships table
$query = "SELECT ships.Ship_ID, ships.Name, ships.Lattitude, ships.Longitude, ships.Start_Date, ships.End_Date, ships.Depth, cargos.c1_Type, cargos.c2_Type, cargos.c3_Type, ships.Comments, ships.Length, ships.Width
FROM ships
JOIN (
SELECT A.Cargo_ID AS c1_ID, B.Cargo_ID AS c2_ID, C.Cargo_ID AS c3_ID, A.Cargo_Type AS c1_Type, B.Cargo_Type AS c2_Type, C.Cargo_Type AS C3_Type
FROM cargo AS A, cargo AS B, cargo AS C
) cargos
ON ships.Cargo1_ID = cargos.c1_ID
AND ships.Cargo2_ID = cargos.c2_ID
AND ships.Cargo3_ID = cargos.c3_ID";

$result = mysql_query($query);
if (!$result){
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<ships>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // Add to XML document node
  
  echo '<ship ';
  echo 'Ship_ID="' . $row['Ship_ID'] . '" ';
  echo 'Name="' . parseToXML(utf8_encode($row['Name'])) . '" ';
  echo 'Lattitude="' . $row['Lattitude'] . '" ';
  echo 'Longitude="' . $row['Longitude'] . '" ';
  echo 'Start_Date="' . $row['Start_Date'] . '" ';
  echo 'End_Date="' . $row['End_Date'] . '" ';
  echo 'Depth="' . $row['Depth'] . '" ';
  echo 'c1_Type="' . $row['c1_Type'] . '" ';
  echo 'c2_Type="' . $row['c2_Type'] . '" ';
  echo 'c3_Type="' . $row['c3_Type'] . '" ';
  echo 'Comments="' . parseToXML(utf8_encode($row['Comments'])) . '" ';
  echo 'Length="' . $row['Length'] . '" ';
  echo 'Width="' . $row['Width'] . '" ';
  echo ' />';
}

// End XML file
echo '</ships>';

?>