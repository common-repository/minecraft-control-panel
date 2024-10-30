<?php
global $JSONAPI;

    echo '
        <div class="mcpanel wrap">
                <h2>Minecraft Control Panel - User Einstellungen</h2>
                <p>Hier kommen die Einstellungen f&uuml;r die User hin</p>
            </div>
    ';
    
    $player_data = $JSONAPI->callMultiple(
        array("players.name", "players.name.groups", "players.name.permissions",),
        array(array('ratamahatta'),array('ratamahatta'),array('ratamahatta'),)
    );
    //print_r($player_data[0]['success']);        
?>