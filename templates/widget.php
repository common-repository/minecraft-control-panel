<?php

// Das Aussehen dieses Widgets ist angelehnt an "Minestatus" von Jeroen Weustink
global $JSONAPI,$current_user,$mcpf;
            
get_currentuserinfo();          
$nick = $current_user->display_name;

if (isset($JSONAPI)) {
    
    $call = $JSONAPI->callMultiple(
        array("worlds.names", "server.settings.ip", "server.settings.port", "server.bukkit.version", "server.settings.motd", "server.performance.memory.total", "server.performance.memory.used", "getPlayerLimit", "getPlayerCount", "players.online.names", "server.version", "plugins", "server.performance.tick_health",),
        array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(),)
    );
    
    if ($call[8]['success'] > 0) {
        foreach($call[9]['success'] as $k => $v) {
                
            $max = $call[8]['success']-1;
            $nick = $call[9]['success'][$k];
            
            if ($k != $max) $sep = ', ';
            if (get_option('mcp_widget_avatartooltips') == 'aktiv') {
                $player_data = $JSONAPI->callMultiple(
                    array("players.name", "players.name.groups", "players.name.permissions",),
                    array(array($nick),array($nick),array($nick),)
                );                
                $player_info = '
                    Login: '.date('d.m.Y H:i:s', $player_data[0]['success']['lastPlayed']).'<br /><br />
                    
                    Welt: '.$player_data[0]['success']['worldInfo']['name'].'<br />
                    Schwierigkeit: '.$player_data[0]['success']['worldInfo']['difficulty'].'<br />
                    Position: '.round($player_data[0]['success']['location']['z'],0).':'.round($player_data[0]['success']['location']['y'],0).':'.round($player_data[0]['success']['location']['x'],0).'<br /><br />
                    
                    Level: '.$player_data[0]['success']['level'].'<br />
                    Erfahrung: '.$player_data[0]['success']['experience'].'<br />
                    Gesundheit: '.$player_data[0]['success']['health'].'<br />
                    Nahrung: '.$player_data[0]['success']['foodLevel'].'<br /><br />                    
                ';                
                $tooltip = 'data-ot-style="dark" data-ot="<h3><u>'.($player_data[0]['success']['op'] == 1 ? '[Op]' : '').' '.$nick.' '.($player_data[0]['success']['sleeping'] == 1 ? 'schl&aumlft;' : '').'</u></h3><span>'.$player_info.'</span>" ';
            } else { $tooltip = 'title="'.$nick.'" '; }
            $names .= ' <img '.$tooltip.' src="'.plugins_url('../images/head.png', __FILE__).'" height="'.get_option('mcp_widget_image_size', 16).'" width="'.get_option('mcp_widget_image_size', 16).'" /> ';        
        }
    }
    
    if (count($call[11]['success']) > 0) {
        foreach($call[11]['success'] as $pK => $pV) {
            
            $enabled      = $call[11]['success'][$pK]['enabled'];
            $author       = $call[11]['success'][$pK]['authors'][0];
            $website      = $call[11]['success'][$pK]['website'];
            $description  = $call[11]['success'][$pK]['description'];
            $name         = $call[11]['success'][$pK]['name'];
            $version      = $call[11]['success'][$pK]['version'];
            
            $plugin_enabled = '<img height="16" width="16" src="'.plugins_url('../images/dot_disabled.png', __FILE__).'" title="Plugin deaktiviert" />';
            
            if ($enabled == '1') {
                $plugin_enabled = '<img height="16" width="16" src="'.plugins_url('../images/dot_enabled.png', __FILE__).'" title="Plugin aktiviert" />';
            }
            
            if (!empty($website)) {
                if (!preg_match('/http/',$website)) { $website = 'http://'.$website; }  
            }
            
            if (get_option('mcp_widget_plugintooltips') == 'aktiv') {
                $tooltip = '';
                $plugs = '<table>';
                $count = 0;
                foreach($call[11]['success'][$pK]['commands'] as $_k => $_v) {
                    $count += 1;
                    $command = '/' . $_k;
                    $descrip = $call[11]['success'][$key]['commands'][$_k]['description'];    
                    $plugs .= '<tr><td>'.$command.'</td><td>'.(!empty($description) ? $mcpf->truncate($description,20) : 'Keine Beschreibung').'</td></tr>';
                }
                $plugs .= '</table>';
                if ($count > 0) $tooltip = 'data-ot-style="dark" data-ot="<div>'.$plugs.'</div>" ';
            } else { $tooltip = ''; }
            $plugins .= '<div '.$tooltip.' style="margin: 3px 0; padding: 3px; background: #777; color: #000; border-radius: 5px;"><span>'.$name.' V.'.$version.'</span><br /><span style="font-size: 9px;">'.$mcpf->truncate($description,40).'</span></div>';
                    
        }
    }
    
    $ticks = round($call[12]['success']['clockRate'],3) . ' / ' . round($call[12]['success']['expectedClockRate'],3);
    $plugin_count = count($call[11]['success']);
    
}

if (!empty($call[0]['success'][0])) {
     
    // MotD
    if (get_option('mcp_widget_motd') == 'aktiv') {        
        echo '
            <div class="stats_block">
                <span class="data"><strong>' . $mcpf->stripColor($call[4]["success"]) . '</strong></span>
            </div>
        ';        
    }
     
    // Version
    if (get_option('mcp_widget_version') == 'aktiv') {
        echo '
            <div class="stats_block">
                <span class="data"><strong>' . $call[3]["success"] . '</strong></span>
            </div>
        ';        
    }
    
    // Serverinfo
    if (get_option('mcp_widget_name') == 'aktiv' || get_option('mcp_widget_host') == 'aktiv' || get_option('mcp_widget_port') == 'aktiv') {
        
        echo '<div class="stats_block">';
        
        if (get_option('mcp_widget_name') == 'aktiv') {
            echo '
                <span class="item">Welt:</span>
                <span class="data"><strong>' . $call[0]["success"][0] . '</strong></span>                
                <br />
            ';
        }
        
        if (get_option('mcp_widget_host') == 'aktiv') {
            echo '
                <span class="item">Host:</span>
                <span class="data"><strong>' . get_option('mcp_widget_hostname') . '</strong></span>
                <br />
            ';
        }
        
        if (get_option('mcp_widget_port') == 'aktiv') {
            echo '
                <span class="item">Port:</span>
                <span class="data"><strong>' . $call[2]['success'] . '</strong></span>
            ';
        }
        
        echo '</div>';    
        
    }
    
    // Player- und Plugininfo
    if (get_option('mcp_widget_player') == 'aktiv' || get_option('mcp_widget_plugins') == 'aktiv' || get_option('mcp_widget_ticks') == 'aktiv') {
        
        echo '<div class="stats_block">';
        
        if (get_option('mcp_widget_ticks') == 'aktiv') {
            echo '
                <span class="item">Ticks:</span>
                <span class="data"><strong>' . $ticks . '</strong></span>
                <br />    
            ';        
        }
        
        if (get_option('mcp_widget_player') == 'aktiv') {
            echo '
                <span class="item">Player:</span>
                <span class="data">    
            ';
    
            if (get_option('mcp_widget_avatars') == 'aktiv') {
                if ($call[8]['success'] > 0) {
                    echo '
                        <label for="showContentBox" id="showContent"><strong>' . $call[8]['success'] . ' / ' . $call[7]['success'] . '</strong></label>
                        <input type="checkbox" id="showContentBox" />
                        <div id="showContent">' . $names . '</div>
                    ';
                }
                
                else {
                    echo '<strong>' . $call[8]['success'] . ' / ' . $call[7]['success'] . '</strong>';            
                }
            }    
            
            echo '
                </span>
                <br />    
            ';
        }
        
        if (get_option('mcp_widget_plugins') == 'aktiv') {
            echo '
                <span class="item">Plugins:</span>
                <span class="data">    
            ';
            if ($plugin_count > 0) {                
                echo '
                    <label for="showContentBox" id="showContent"><strong>' . $plugin_count . '</strong></label>
                    <input type="checkbox" id="showContentBox" />
                    <div id="showContent" style="padding: 3px 0;">' . $plugins . '</div>
                ';
            }            
            else {
                echo '<strong>' . $plugin_count . '</strong>';            
            }
            
            echo '
                </span>
                <br />    
            ';        
        
            echo '</div>';
        }        
    }
}
    
else {
    echo'
        <div class="mcpanel_widget stats_block offline">
            <div class="stats_block offline"><h4>Server Offline</h4s></div>
        </div>
    ';
}    
    
?>