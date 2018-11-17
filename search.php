<?php

if (! isset($_POST["keyword"]))
{ exit("Access Denied"); }

// $ -> \$
$dict = array();

$keyword = $_POST["keyword"];
$mode = $_POST["mode"];
$length = strlen($keyword);
if ($length > 20)
{ exit("Your keyword is invalid."); }
$result = "";
$total = 0;

// $line = craneur^crâneur [kranœr] (a, m) swanking, swanker | 炫耀的，炫耀者
// $fr = craneur^crâneur
// $en = swanking, swanker
// $ch = 炫耀的，炫耀者
// $french = craneur^crâneur|swanking|swanker|炫耀的|炫耀者

foreach ($dict as $line)
{ $pos1 = stripos($line, " [");
  $pos2 = stripos($line, ") ");
  $pos3 = stripos($line, " | ");

  $fr = substr($line, 0, $pos1);
  $en = substr($line, $pos2 + 2, $pos3 - $pos2 - 2);
  $ch = substr($line, $pos3 + 3);

  $french = $fr . "|" . $en . "|" . $ch;
  $french = str_ireplace(", ", "|", $french);
  $french = str_ireplace("，", "|", $french);
  $french = strtolower($french);

  $list = explode("|", $french);
  $lastdata = "";

  if ($mode == "smode")
  { foreach ($list as $word)
      if (strncasecmp($word, $keyword, $length) == 0)
      { $pos4 = stripos($line, "^");
        if ($pos4 > 0)
          $line = substr($line, $pos4 + 1);
        $data = "<li>" . $line . "</li>";
        if ($data != $lastdata)
        { $result .= $data;
          $lastdata = $data;
          $total++; } } }
  else
  { foreach ($list as $word)
      if (stripos($word, $keyword) > 0)
      { $pos4 = stripos($line, "^");
        if ($pos4 > 0)
          $line = substr($line, $pos4 + 1);
        $data = "<li>" . $line . "</li>";
        if ($data != $lastdata)
        { $result .= $data;
          $lastdata = $data;
          $total++; } } } }

if ($result != "")
{ echo $result;
  echo $total == 1 ? "<p>1 word found</p>" : "<p>$total words found</p>"; }
else
{ echo "<p>No word found</p>"; }

?>