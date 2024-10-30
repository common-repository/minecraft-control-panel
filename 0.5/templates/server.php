<?php

global $JSONAPI;



if ($_POST['action'] == 'Broadcast') {    
    $broadcast = false;        
    if ($_POST['Nick']) { if($JSONAPI->call("chat.with_name", array($_POST['Broadcast'], $_POST['Nick']))) { $broadcast = true; } } 
    else { if($JSONAPI->call("chat.broadcast", array($_POST['Broadcast']))) { $broadcast = true; } }    
}
 
else { $broadcast = 'empty'; }


if ($_POST['action'] == 'sendCommand') {    
    $sendCommand = false;        
    if ($_POST['sendCommand']) {
        if($JSONAPI->call("server.run_command", array($_POST['sendCommand']))) { $sendCommand = true; }        
    }     
} 

else { $sendCommand = 'empty'; }


if ($_POST['action'] == 'serverRestart') {    
    $s_restart = false;        
    if ($_POST['serverRestart']) {
        if($JSONAPI->call("server.power.restart", array())) { $s_restart = true; }        
    }     
} 

else { $s_restart = 'empty'; }





$data = $JSONAPI->callMultiple(
        array('streams.console.latest', 'streams.chat.latest', ), 
        array(array(get_option('mcp_stream-amount', 10)), array(get_option('mcp_stream-amount', 10)),)
    );
    
$str_remove = array('[0;31;1m', '[0;32;1m', '[0;33;22m', '[0;36;1m', '[0;37;1m', '[m');
    
if (!empty($data[0]['success'])) {
    foreach($data[0]['success'] as $consK => $consV) {
        $consLines .= '<tr><td>' . str_replace($str_remove, '', $data[0]['success'][$consK]['line']) . '</td></tr>';    
    }
}    

if (!empty($data[1]['success'])) {
    foreach($data[1]['success'] as $chatK => $chatV) {
        $chatLines .= '
            <tr>
                <td>
                    ' . date("Y-m-d H:i:s", $data[1]['success'][$chatK]['time']) . ' [' . ($data[1]['success'][$chatK]['player'] ? $data[1]['success'][$chatK]['player'] : 'Konsole'). '] ' . str_replace($str_remove, '', $data[1]['success'][$chatK]['message']) . '
                </td>
            </tr>
        ';    
    }    
}
?>

<div class="mcpanel wrap">
    <h2 class="mcp">Server Einstellungen</h2>
    
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td class="title"><h4>Serverkontrolle</h4></td>
                <td class="title" style="text-align: right;"><small><u>Beachte, dass derzeit nur das Neustarten des Servers funktioniert. F&uuml;r das Starten/Stoppen des Servers wird RemoteToolkit ben&ouml;tigt und wird erst sp&auml;ter implementiert.</u></small></td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 33.33%; text-align: center;">
                <br />
                    <?php
                        if ($s_restart != 'empty') { 
                            if ($s_restart == true) { echo '<div style="background: #0f0; padding 5px; color: #000;">Starte Server Neu</div>'; }
                            elseif ($s_restart == false) { echo '<div style="background: #f00; padding 5px; color: #fff;">Konnte Server nicht Neu starten</div>'; }
                        } else { echo '&nbsp;'; }
                    ?>
                <br />
                </td>
                <td style="width: 33.33%; text-align: center;">&nbsp;</td>
                <td style="width: 33.33%; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 33.33%; text-align: center;">Server neustarten</td>
                <td style="width: 33.33%; text-align: center;">Server starten</td>
                <td style="width: 33.33%; text-align: center;">Server stoppen</td>
            </tr>
            <tr>
                <td style="width: 33.33%; text-align: center;">
                    <form name="JSONAPI-serverRestart" method="post" action="<?php echo $location; ?>">
                        <input name="action" value="serverRestart" type="hidden" />
                        <input type="submit" name="Neustarten" value="Neustarten" onclick="this.value='Starte Neu'">
                    </form>
                </td>
                <td style="width: 33.33%; text-align: center;">
                    <form name="JSONAPI-serverStart" method="post" action="<?php echo $location; ?>">
                        <input name="action" value="serverStart" type="hidden" />
                        <input type="submit" name="Starten" value="Starten" onclick="this.value='Starte Server'">
                    </form>
                </td>
                <td style="width: 33.33%; text-align: center;">
                    <form name="JSONAPI-serverStop" method="post" action="<?php echo $location; ?>">
                        <input name="action" value="serverStop" type="hidden" />
                        <input type="submit" name="Stoppen" value="Stoppen" onclick="this.value='Stoppe Server'">
                    </form>
                </td>
            </tr>
            <tr><td><br /></td></tr>
        </table>
    </div>
    
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td style="width: 50%" class="title"><h4>Konsole</h4></td>
                <td style="width: 50%" class="title"><h4>Chat</h4></td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td>
                                <table>
                                    <?php echo $consLines; ?>
                                </table>                            
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td>
                                <table>
                                    <?php 
                                        echo $chatLines;
                                    ?>
                                </table>                            
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td class="title"><h4>Sende Befehl</h4></td>
                <td class="title"><h4>Broadcasting</h4></td> 
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <form name="JSONAPI-sendCommand" method="post" action="<?php echo $location; ?>">
                        <input name="action" value="sendCommand" type="hidden" />
                        <table>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>Befehl:</td><td><input type="text" name="sendCommand" size="40" value="" /></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td><td><input type="submit" value="Senden" /></td>
                            </tr>
                            <tr>
                            <td colspan="3">
                            <?php
                                if ($sendCommand != 'empty') { 
                                    if ($sendCommand == true) { echo 'Befehl gesendet'; }
                                    elseif ($sendCommand == false) { echo 'Fehler beim Senden des Befehls'; }
                                }
                            ?>                
                            </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td>
                    <form name="JSONAPI-Broadcast" method="post" action="<?php echo $location; ?>">
                        <input name="action" value="Broadcast" type="hidden" />
                        <table>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>Nick:</td><td><input type="text" name="Nick" size="20" value="" /> User muss Ingame online sein (optional)</td>
                            </tr>
                            <tr>
                                <td>Text:</td><td><input type="text" name="Broadcast" size="40" value="" /></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td><td><input type="submit" value="Senden" /></td>
                            </tr>
                            <tr>
                            <td colspan="3">
                            <?php
                                if ($broadcast != 'empty') { 
                                    if ($broadcast == true) { echo 'Message gesendet'; }
                                    elseif ($broadcast == false) { echo 'Fehler beim Senden der Nachricht'; }
                                }
                            ?>                
                            </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>