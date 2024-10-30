<?php

global $JSONAPI,$mcp_version;

if (isset($JSONAPI)) {
    /*
        0 groups.all                                  Retrieves all groups
        1 groups.group.players                        Gets all the players in a group
        
        2 players.name.groups                         Gets the groups for a specific player
        3 players.name.groups.add                     Add a group for a player
        4 players.name.groups.remove                  Remove a group from a player
        
        5 worlds.world.groups.group.prefix            Get group prefix
        6 worlds.world.groups.group.set_prefix        Set group prefix
        7 worlds.world.groups.group.set_suffix        Set group suffix
        8 worlds.world.groups.group.suffix            Get group suffix
    */
    
    $worlds = $JSONAPI->call("worlds.names");
    
    if ($_POST['action'] == 'mcp_groups') {
        $return = '';    
        if ($_POST['mcp_groupdel']) { 
            if ($JSONAPI->call("server.run_command", array('mangdel '.$_POST['mcp_groupdel']))) { $return .= 'Gruppe gel&ouml;scht<br />'; } 
        }           
        if ($_POST['mcp_groupname']) { if ($JSONAPI->call("server.run_command", array('mangadd '.$_POST['mcp_groupname']))) { $return .= 'Gruppe erstellt<br />'; } }       
        if ($_POST['mcp_prefix']) { if ($JSONAPI->call("worlds.world.groups.group.set_prefix", array($worlds[0]['success'][0], $_POST['mcp_groupname'], $_POST['mcp_prefix']))) { $return .= 'Pr&auml;fix gesetzt<br />'; }  }       
        if ($_POST['mcp_suffix']) { if ($JSONAPI->call("worlds.world.groups.group.set_suffix", array($worlds[0]['success'][0], $_POST['mcp_groupname'], $_POST['mcp_suffix']))) { $return .= 'Suffix gesetzt<br />'; }  }        
    }
        
    $groups = $JSONAPI->call("groups.all");
}

//print_r($worlds[0]['success']);    
?>
<div class="mcpanel wrap">
    <h2 class="mcp">Gruppenverwaltung</h2>
    
    <div class="JSONAPI">
        
        <table class="header">
            <tr>
                <td class="title"><h4>&Uuml;bersicht</h4></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td>
                    <table style="padding: 5px; line-height: 15px; font-weight: bold;">
                        <tr>
                            <td style="border-radius: 5px; background: #666; color: #FFF;">
                                <table>
                                    <tr>
                                        <td style="width: 25px; border-bottom: 1px solid #666; border-right: 1px solid #666;">#</td>
                                        <td style="border-bottom: 1px solid #666; border-right: 1px solid #666;">Gruppe</td>
                                        <td style="width: 100px; border-bottom: 1px solid #666; border-right: 1px solid #666;">Pr&auml;fix</td>
                                        <td style="width: 100px; border-bottom: 1px solid #666;">Suffix</td>
                                        <td style="width: 35px; border-bottom: 1px solid #666;">L&ouml;schen</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                        
                            foreach($groups[0]['success'] as $k => $v) {

                                $data = $JSONAPI->callMultiple(
                                    // Befehle
                                    array("worlds.world.groups.group.prefix", "worlds.world.groups.group.suffix",),
                                    // Parameter 
                                    array(array($worlds[0]['success'][0], $v), array($worlds[0]['success'][0], $v),)
                                );
                                
                                $group_count += 1;
                                echo '
                                    <tr>
                                        <td style="border-radius: 5px; background: #bbb; padding: 5px;">
                                            <style media="screen" type="text/css">
                                                div#showOptionsID_'.$group_count.' { display: none; }
                                                #showOptions_'.$group_count.':checked + div#showOptionsID_'.$group_count.' { display: block; width: 99%; background: #ddd; border-radius: 5px; }
                                                label#showOptionsID_'.$group_count.' { color: #000; cursor: pointer; }
                                                label#showOptionsID_'.$group_count.':hover { color: #000; font-weight: bold; cursor: pointer; }
                                                #showOptions_'.$group_count.' { opacity: 0.01; }
                                                div#showOptionsID_'.$group_count.' img { padding: 0 2px; }
                                                div.label_subMenu_'.$group_count.' { background: #fff; color: #000; margin: 10px 0; padding: 5px; }
                                            </style>
                                            
                                            <label for="showOptions_'.$group_count.'" id="showOptionsID_'.$group_count.'">
                                                <table>
                                                    <tr>
                                                        <td style="width: 25px;">'.$k.'</td>
                                                        <td>'.$v.'</td>
                                                        <td style="width: 100px;">'.$data[0]['success'].'</td>
                                                        <td style="width: 100px;">'.$data[1]['success'].'</td>
                                                        <td style="width: 35px;">
                                                        <form name="mcp_groups" method="post" action="' . $location . '">
                                                            <input name="action" value="mcp_groups" type="hidden" />
                                                            <input name="mcp_groupdel" value="'.$v.'" type="hidden" />
                                                            <input type="image" src="'.plugins_url('../images/delete.png', __FILE__).'" style="height: 32px; width: 32px;" title="Gruppe '.$v.' ohne Nachfrage l&ouml;schen" alt="Del" />
                                                        </form>
                                                    </tr>
                                                </table>                                        
                                            </label>
                                            <input style="position: absolute; float: left;" type="checkbox" id="showOptions_'.$group_count.'" />
                                            <div class="label_subMenu_'.$group_count.'" id="showOptionsID_'.$group_count.'">
                                                <form name="mcp_groups" method="post" action="' . $location . '">
                                                <table style="padding: 5px;">
                                                    <tr>
                                                        <td colspan="4">Gruppe bearbeiten ('.$v.') </td>
                                                        <td style="padding: 0 5px 5px 5px;">
                                                            <input name="action" value="mcp_groups" type="hidden" />                                                            
                                                            <input name="mcp_groupname" value="'.$v.'" type="hidden" />
                                                            Pr&auml;fix: <input type="text" name="mcp_prefix" size="20" value="'.$data[0]['success'].'" />
                                                        </td>
                                                        <td style="padding: 0 5px 5px 5px;">
                                                            Suffix: <input type="text" name="mcp_suffix" size="20" value="'.$data[1]['success'].'" />
                                                        </td>
                                                        <td colspan="2" style="text-align: right; padding: 0 5px 5px 0;">
                                                            <input type="submit" value="Speichern" />
                                                        </td>
                                                    </tr>
                                                </table>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                ';        
                            }                        
                        ?>                    
                    </table>                
                </td>
            </tr>
        </table>        
    </div>
    
    <div class="JSONAPI">
        
        <table class="header">
            <tr>
                <td class="title"><h4>Gruppe erstellen</h4></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td>
                    <form name="mcp_groups" method="post" action="<?php echo $location; ?>">
                        <table style="padding: 5px;">
                            <tr>
                                <td colspan="4">Gruppe erstellen: </td>
                                <td style="padding: 0 5px 5px 5px;">
                                    <input name="action" value="mcp_groups" type="hidden" />
                                    Name: <input type="text" name="mcp_groupname" size="20" value="" />
                                </td>
                                <td style="padding: 0 5px 5px 5px;">
                                    Pr&auml;fix: <input type="text" name="mcp_prefix" size="20" value="" />
                                </td>
                                <td style="padding: 0 5px 5px 5px;">
                                    Suffix: <input type="text" name="mcp_suffix" size="20" value="" />
                                </td>
                                <td colspan="2" style="text-align: right; padding: 0 5px 5px 0;">
                                    <input type="submit" value="Speichern" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    
</div>