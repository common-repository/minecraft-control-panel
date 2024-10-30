<?php

// Das Aussehen dieses Widgets ist angelehnt an "Minestatus" von Jeroen Weustink
global $JSONAPI,$current_user;
            
get_currentuserinfo();          
$nick = $current_user->display_name;

if (isset($JSONAPI)) {
    
    $call = $JSONAPI->callMultiple(
        array("worlds.names", "server.settings.ip", "server.settings.port", "server.bukkit.version", "server.settings.motd", "server.performance.memory.total", "server.performance.memory.used", "getPlayerLimit", "getPlayerCount", "players.online.names", "getBukkitVersion", "plugins",),
        array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(),)
    );
    
    if ($call[8]['success'] > 0) {
        foreach($call[9]['success'] as $k => $v) {        
            $max = $call[8]['success']-1;
            if ($k != $max) $sep = ', ';
            $names .= ' <img title="'.$call[9]['success'][$k].'" src="'.plugins_url('../images/head.png', __FILE__).'" height="'.get_option('mcp_widget_image_size', 16).'" width="'.get_option('mcp_widget_image_size', 16).'" /> ';        
        }
    }
    $plugin_count = count($call[11]['success']);
    
}

if (!empty($call[0]['success'][0])) {
     
    // MotD
    if (get_option('mcp_widget_motd') == 'aktiv') {
        
        $motd = $call[4]["success"];
        $str_remove = array('§b', '§e', '§l', '§r', '§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0');
        
        echo '
            <div class="stats_block">
                <span class="data"><strong>' . str_replace($str_remove, "", $motd) . '</strong></span>
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
    if (get_option('mcp_widget_player') == 'aktiv' || get_option('mcp_widget_plugins') == 'aktiv') {
        
        echo '<div class="stats_block">';
        
        if (get_option('mcp_widget_player') == 'aktiv') {
            echo '
                <span class="item">Player:</span>
                    <span class="data">    
            ';
            
        if (get_option('mcp_widget_avatars') == 'aktiv') {
            $num_players = $call[8]['success'] . ' / ' . $call[7]['success'];
            if ($call[8]['success'] > 0) {
                echo '
                    <label for="showNicksButton" id="showNicks"><strong>' . $call[8]['success'] . ' / ' . $call[7]['success'] . '</strong></label>
                    <input type="checkbox" id="showNicksButton" />
                    <div id="showNicks">' . $names . '</div>
                ';
            }
            
            else {
                echo '<strong>' . $call[8]['success'] . ' / ' . $call[7]['success'] . '</strong>';            
            }
        }
        
        else {
            echo '<strong>' . $call[8]['success'] . ' / ' . $call[7]['success'] . '</strong>';            
        }    
            
            echo '
                    </strong>
                </span>
                <br />    
            ';
        }
        
        if (get_option('mcp_widget_plugins') == 'aktiv') {
            echo '
                <span class="item">Plugins:</span>
                <span class="data"><strong>' . $plugin_count . '</strong></span>    
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