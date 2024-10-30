<?php

global $JSONAPI,$mcp_version;

if (isset($JSONAPI)) {
    $call = $JSONAPI->callMultiple(
        // Befehle
        array("worlds.names", "server.settings.ip", "server.settings.port", "server.version", "server.settings.motd", "server.performance.memory.total", "server.performance.memory.used", "getPlayerLimit", "getPlayerCount", "players.online.names", "getBukkitVersion", "plugins","server.performance.tick_health",),
        // Parameter 
        array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(),)
    );
}

$host = get_option('JSONAPI-Host');
$port = get_option('JSONAPI-Port');
$user = get_option('JSONAPI-User');
$pass = get_option('JSONAPI-Pass');
$salt = get_option('JSONAPI-Salt');

//print_r($call[11]['success']);

foreach($call[11]['success'] as $key => $value) {
    
    $enabled      = $call[11]['success'][$key]['enabled'];
    $author       = $call[11]['success'][$key]['authors'][0];
    $website      = $call[11]['success'][$key]['website'];
    $description  = $call[11]['success'][$key]['description'];
    $name         = $call[11]['success'][$key]['name'];
    $version      = $call[11]['success'][$key]['version'];
    
    $plugin_count += 1; $plug_output = '';
    $plugin_enabled = '<img height="16" width="16" src="'.plugins_url('../images/dot_disabled.png', __FILE__).'" title="Plugin deaktiviert" />';
    $plugin_url = '<a href="'.str_replace('localhost',$call[1]['success'], $JSONAPI->makeURL('plugins.name.enable', array($name))).'" title="Plugin aktivieren">'.$plugin_enabled.'</a>';    
    
    if (!empty($website)) {
        if (!preg_match('/http/',$website)) { $website = 'http://'.$website; } 
        //$name = '<a href="'.$website.'" title="Besuche die Entwicklerhomepage">'.$name.'</a>'; 
    }
    
    if (is_array($call[11]['success'][$key]['commands'])) {        
        foreach($call[11]['success'][$key]['commands'] as $_k => $_v) {
            $command = '/' . $_k;
            $descrip = $call[11]['success'][$key]['commands'][$_k]['description'];
            
            $plug_output .= '<tr><td style="border-bottom: 1px dotted #000; border-right: 1px dotted #000;">'.$command.'</td><td style="border-bottom: 1px dotted #000; border-right: 1px dotted #000;">'.$descrip.'</td></tr>';
        }    
    } else {
        $plug_output = '<tr><td style="border-bottom: 1px dotted #000; border-right: 1px dotted #000;">nicht Verf&uuml;gbar</td><td style="border-bottom: 1px dotted #000; border-right: 1px dotted #000;">keine Beschreibung vorhanden</td></tr>';
    }
    
    if ($enabled == '1') {
        $plugin_enabled = '<img height="16" width="16" src="'.plugins_url('../images/dot_enabled.png', __FILE__).'" title="Plugin aktiviert" />';
        $plugin_url = '<a href="'.str_replace('localhost',$call[1]['success'], $JSONAPI->makeURL('plugins.name.disable', array($name))).'" title="Plugin deaktivieren">'.$plugin_enabled.'</a>';
    }

    $plugins .= '    
        <style media="screen" type="text/css">
            div#showPluginInfo_'.$plugin_count.' { display: none; }
            #showPluginInfoButton_'.$plugin_count.':checked + div#showPluginInfo_'.$plugin_count.' { display: block; width: 99%; background: #ddd; border-radius: 5px; }
            label#showPluginInfo_'.$plugin_count.' { color: #000; cursor: pointer; }
            label#showPluginInfo_'.$plugin_count.':hover { color: #000; font-weight: bold; cursor: pointer; }
            #showPluginInfoButton_'.$plugin_count.' { opacity: 0.01; }
            div#showPluginInfo_'.$plugin_count.' img { padding: 0 2px; }
            div.label_subMenu_'.$plugin_count.' { background: #fff; color: #000; margin: 10px 0; padding: 5px; }
        </style>       
            
        <div class="info_plugins">
            
            <span style="width: 100%;">            
                <label for="showPluginInfoButton_'.$plugin_count.'" id="showPluginInfo_'.$plugin_count.'" class="tooltips" title="'.$description.'">
                    <span style="width: 80px;">'.$plugin_url.'</span>
                    <span style="width: 180px;">'.($name ? $name : 'n/a').'</span>
                    <span style="width: 250px;">'.($version ? $version : 'n/a').'</span>
                    <span style="width: 150px;">'.($author ? $author : 'n/a').'</span>            
                    <span style="min-width: 200px;">'.($website ? $website : 'n/a').'</span>
                    
                </label>
                <input type="checkbox" id="showPluginInfoButton_'.$plugin_count.'" />
                <div class="label_subMenu_'.$plugin_count.'" id="showPluginInfo_'.$plugin_count.'">
                    <table>
                        <tr><td colspan="2">'.$description.'</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Befehl:</td><td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Beschreibung:</td></tr>
                        '.$plug_output.'
                    </table>
                </div>
            </span>
            
        </div>        
        <div class="clearfix"></div>
    ';
}
 
?>
<div class="mcpanel wrap">
    <h2 class="mcp">Minecraft Control Panel</h2>
    <div class="JSONAPI">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="header">
                        <tr>
                            <td class="title"><h3>Plugin Informationen:</h3></td>
                            <td style="text-align: right;">Version: <strong><?php echo $mcp_version; ?></strong></td>
                        </tr>
                    </table>
                    <div style="text-align: justify;">
                        Dieses Plugin stellt mittels einer API eine Verbindung zu Deinem Minecraftserver her und bietet Dir die M&ouml;glichkeit Deinen Server und viele weitere Einstellungen dadurch zu steuern.<br /><br />
                        Ver&auml;nderungen sind erlaubt. Ver&auml;nderte Dateien d&uuml;rfen aber nur mit meiner Genehmigung zum Download angeboten werden.<br /><br />                        
                        Dieses Plugin ist ausschliesslich &uuml;ber diesen Link erh&auml;ltlich: > <a href="https://wordpress.org/plugins/minecraft-control-panel" title="Download Minecraft Control Panel auf wordpress.org">Download</a>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                <?php if (empty($call[0]['success'])) { ?>
                    <table class="header">
                        <tr>
                            <td class="title"><h3>Server Informationen:</h3></td>
                            <td class="server_status"><img src="<?php echo plugins_url('../images/offline.png', __FILE__); ?>" title="Server Offline" alt="Offline" /></td>
                        </tr>
                    </table>
                <?php } else { ?>
                    <table class="header">
                        <tr>
                            <td class="title"><h3>Server Informationen:</h3></td>
                            <td class="server_status"><img src="<?php echo plugins_url('../images/online.png', __FILE__); ?>" title="Server Online" alt="Online" /></td>
                        </tr>
                    </table>       
                    <div>
                        <table class="info">
                            <tr>
                                <td class="text">Weltname:</td>
                                <td class="data"><?php echo $call[0]['success'][0]; ?> (<?php echo $call[0]['success'][2].', '.$call[0]['success'][3]; ?>)</td>                
                            </tr>
                            <tr>
                                <td class="text">Server IP/Port:</td>
                                <td class="data"><?php echo $call[1]['success']; ?> : <?php echo $call[2]['success']; ?></td>                
                            </tr>
                            <tr>    
                                <td class="text">Serverversion:</td>
                                <td class="data"><?php echo $call[3]['success']; ?></td>                
                            </tr>
                            <tr>
                                <td class="text">Bukkitversion:</td>
                                <td class="data"><?php echo $call[10]['success']; ?></td>                    
                            </tr>
                            <tr>
                                <td class="text">Ticks:</td>
                                <td class="data"><?php echo round($call[12]['success']['clockRate'],3); ?> / <?php echo round($call[12]['success']['expectedClockRate'],3); ?></td>                
                            </tr>
                            <tr>
                                <td class="text">RAM:</td>
                                <td class="data"><?php echo round($call[6]['success'],0); ?>MB / <?php echo round($call[5]['success'],0); ?>MB</td>                
                            </tr>
                            <tr>
                                <td class="text">Player:</td>
                                <td class="data"><?php echo $call[8]['success']; ?> von <?php echo $call[7]['success']; ?></td>
                            </tr>
                        </table>            
                    </div>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
    <div class="JSONAPI">
        <table class="header">
            <tr><td class="text" colspan="4"><h3>Plugins: <?php echo $plugin_count; ?></h3></td></tr>            
        </table>
        <div class="info">
            <div class="info_plugins" style="font-size: 15px; font-weight: bold; border-bottom: 1px solid #777">
                <span style="width: 80px;">Tools:</span>
                <span style="width: 180px;">Name:</span>
                <span style="width: 250px;">Version:</span>
                <span style="width: 150px;">Author:</span>
                <span style="min-width: 200px;">Website:</span>
            </div>
            <?php echo $plugins; ?>            
        </div>
    </div>
</div>