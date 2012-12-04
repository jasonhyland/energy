<?php

function energy_item_set($userid, $tag, $year, $data)
{
  $data = json_encode($data);

  $result = db_query("SELECT `id` FROM energy WHERE `userid` = '$userid' AND `tag` = '$tag' AND `year` = '$year' ");
  $row = db_fetch_object($result);

  if (!$row)
  {
    db_query("INSERT INTO energy (`userid`, `tag`, `year`, `data`) VALUES ('$userid','$tag','$year','$data')");
  }
  else
  {
    $id = $row->id;
    db_query("UPDATE energy SET `data` = '$data' WHERE `id` = '$id'");
  }
}

function energy_item_remove($userid, $tag, $year)
{
  $result = db_query("DELETE FROM energy WHERE `userid` = '$userid' AND `tag` = '$tag' AND `year` = '$year' ");
}

function energy_get_year($userid, $year)
{
  $entries = array();
  $result = db_query("SELECT `id`, `tag`, `year`, `data` FROM energy WHERE `userid` = '$userid' AND `year` = '$year' ");
  while ($row = db_fetch_object($result)) 
  {
    $entries[] = $row;
  }
  return $entries;
}

?>
