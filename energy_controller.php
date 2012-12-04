<?php

  /*

  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org

  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function energy_controller()
  {
    include "Modules/energy/energy_model.php";
    include "Modules/energy/energytypes.php";
    global $session, $route;

    $output['content'] = "";
    $output['message'] = "";

    if ($route['action'] == "list" && $session['read'])
    {
      $energyitems = energy_get_year($session['userid'], 2012);
      $output['content'] = view("energy/list_view.php", array('energyitems' => $energyitems, 'energytypes'=>$energytypes));
    }

    if ($route['action'] == "items" && $session['read'])
    {
      $energyitems = energy_get_year($session['userid'], 2012);
      $output['content'] = json_encode($energyitems);
    }

    if ($route['action'] == "types" && $session['read'])
    {
      $output['content'] = json_encode($energytypes);
    }

    if ($route['action'] == "item" && $route['subaction'] == "add" && $session['write'])
    {
      $tag = preg_replace('/[^\w\s-]/','',get('tag'));
      $data = preg_replace('/[^\w\s-.",:{}\[\]]/','',get('data'));
      
      $energyitems = energy_add_item($session['userid'], $tag, 2012, $data);
    }

    if ($route['action'] == "item" && $route['subaction'] == "remove" && $session['write'])
    {
      $tag = preg_replace('/[^\w\s-]/','',get('tag'));
      $energyitems = energy_item_remove($session['userid'], $tag, 2012);
    }

    if ($route['action'] == "save" && $session['write'])
    {
      $data = preg_replace('/[^\w\s-.",:{}\[\]]/','',get('data'));
      $data = json_decode($data);
      
      foreach ($data as $item)
      {
        energy_item_set($session['userid'], $item->tag, $item->year, $item->data);
      }

      $output['content'] = "saved";
    }

    return $output;
  }
