<?php
/*
Plugin Name: Minecraft Control Panel
Text Domain: minecraft-control-panel
Description: Dieses Plugin stellt eine Minecraft-Serververwaltung zur Verf&uuml;gung. Geplante Kontrollen &uuml;ber: User, Items, Inventar, Welt und Chat 
Author: Liath
Author URI: http://amfearliath.tk
Plugin URI: http://play4pain.tk/forum/?mingleforumaction=viewtopic&t=4
Version: 0.7
*/


global $current_user;

$mcp_version = 0.7;

require_once("API/JSONAPI.php");

include_once("templates/_templates.php");
$mcpt = new mcp_templates();

include_once('mcp_functions.php');
$mcpf = new MCP_Functions();
          
$host = get_option('JSONAPI-Host');
$port = get_option('JSONAPI-Port');
$user = get_option('JSONAPI-User');
$pass = get_option('JSONAPI-Pass');
$salt = get_option('JSONAPI-Salt'); 


    
if (isset($host) && isset($port) && isset($user) && isset($pass) && isset($salt)) {
    $JSONAPI = new JSONAPI($host, $port, $user, $pass, $salt);    
}

if( !class_exists( 'MCPanel' ) ) {    
    
    class MCPanel {

        function __construct() {
            register_activation_hook( __FILE__, array( &$this, 'activate' ) );
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
        }

        function activate() {
            
            add_action('admin_enqueue_scripts', array(&$this, 'mcp_scripts'));
            add_action('wp_enqueue_scripts', array(&$this, 'mcp_scripts'));
            add_action('admin_menu', array(&$this, 'mcp_menu'));
            add_filter('the_content', array(&$this, 'mcp_content'));
            
            add_action('admin_bar_menu', array(&$this, 'mcp_admin_bar'), 81);
            
            
            wp_register_sidebar_widget(
                'mcpwidget', 
                'MC Control Panel', 
                array(&$this, 'mcp_widget'), 
                array('description' => 'Zeigt die Informationen &uuml;ber Deinen Minecraft-Server im Frontend an.')
            );
            wp_register_widget_control(
                'mcpwidget',
                'mcpwidget',
                array(&$this, 'mcp_widget_control')
            );
            
            wp_register_sidebar_widget(
                'mcpchatwidget', 
                'MC Control Panel Chat', 
                array(&$this, 'mcp_chat_widget'), 
                array('description' => 'Zeigt eine Minecraft Ingame-Chatbox an.')
            );
            wp_register_widget_control(
                'mcpchatwidget',
                'mcpchatwidget',
                array(&$this, 'mcp_chat_widget_control')
            );

        }
        
        function deactivate() {
            
            remove_action('admin_enqueue_scripts', array(&$this, 'mcp_scripts'));
            remove_action('wp_enqueue_scripts', array(&$this, 'mcp_scripts'));
            remove_action('admin_menu', array(&$this, 'mcp_menu'));
            remove_filter('the_content', array(&$this, 'mcp_content'));
            
            delete_option('JSONAPI-Host');
            delete_option('JSONAPI-Port');
            delete_option('JSONAPI-User');
            delete_option('JSONAPI-Pass');
            delete_option('JSONAPI-Salt');
            
            delete_option('mcp_widget_title');
            delete_option('mcp_widget_hostname');
            delete_option('mcp_widget_avatar_size');
            
            delete_option('mcp_widget_status');
            delete_option('mcp_widget_motd');
            delete_option('mcp_widget_name');
            delete_option('mcp_widget_version');
            delete_option('mcp_widget_host');
            delete_option('mcp_widget_port');
            delete_option('mcp_widget_plugins');
            delete_option('mcp_widget_plugininfo');
            delete_option('mcp_widget_player');
            delete_option('mcp_widget_avatars');
            
        }
        
        function mcp_admin_bar($wp_admin_bar){
            if (is_admin()) {    
                $args = array(                
                    'id' => 'mcp_admin_bar', 'href' => '', 'meta' => array('class' => 'mcp_admin_bar',
                    'title' => '<img src="'.plugins_url("images/minecraft_logo.png" , __FILE__ ).'" title="Minecraft Control Panel" alt="MCP" style="margin: 5px;" />')                       
                );
                
                $wp_admin_bar->add_node($args);
            }
        }
                
        function mcp_scripts() {

            wp_enqueue_script('mcp_tooltips', plugins_url('js/opentip-jquery.js', __FILE__), array('jquery'));

            wp_enqueue_script('mcp_functions', plugins_url('js/functions.js', __FILE__), array('jquery'));
            wp_localize_script( 'mcp_functions', 'MCPF', array(
                'interval' => 5,
                'file' => plugins_url('templates/chat.php', __FILE__)
            ));
            
            wp_register_style( 'mcp_style_css', plugins_url('css/style.css', __FILE__), false, '1.0.0' );
            wp_enqueue_style( 'mcp_style_css' );
            
            wp_register_style( 'mcp_opentip_css', plugins_url('css/opentip.css', __FILE__), false, '1.0.0' );
            wp_enqueue_style( 'mcp_opentip_css' );
            
        }
        
        function mcp_menu() {
            global $mcpt;
            
            add_menu_page( "MC Control Panel", "MC Control Panel", "administrator", "mcp_mainpage", array(&$mcpt, "mainpage"), plugins_url('images/minecraft_logo.png' , __FILE__ ), "10.1");
            add_submenu_page("mcp_mainpage", "Minecraft Control Panel", "Informationen", "administrator", "mcp_mainpage", array(&$mcpt, "mainpage"));
            add_submenu_page("mcp_mainpage", "Server Verwaltung", "Server", "administrator", "mcp_server", array(&$mcpt, "server"));
            add_submenu_page("mcp_mainpage", "User Verwaltung", "User", "administrator", "mcp_user", array(&$mcpt, "user"));
            add_submenu_page("mcp_mainpage", "Gruppen Verwaltung", "Gruppen", "administrator", "mcp_groups", array(&$mcpt, "groups"));
            add_submenu_page("mcp_mainpage", "Einstellungen", "Einstellungen", "administrator", "mcp_settings", array(&$mcpt, "settings"));
        }
        
        function mcp_widget($args) {
            
            global $JSONAPI,$mcpt;
            $name = $JSONAPI->call("worlds.names");
            if ($name[0]['success'][0]) {
                $status = '<img style="float: right; width: 55px; padding: 4px 5px 0 0" src="'. plugins_url('images/online.png', __FILE__) .'" />';    
            } else {
                $status = '<img style="float: right; width: 55px; padding: 4px 5px 0 0" src="'. plugins_url('images/offline.png', __FILE__) .'" />';    
            }             
                  
            $title = get_option('mcp_widget_title');         
            extract($args);
                       
            echo $before_widget;            
            if ($title) { 
                echo $before_title;
                 
                echo get_option('mcp_widget_title').' ' . (get_option('mcp_widget_status') == 'aktiv' ? $status : ''); 
 
                echo $after_title; 
            }           
            echo $mcpt->widget();            
            echo $after_widget;
        }
        
        function mcp_chat_widget($args) {
            
            global $JSONAPI,$mcpt;            
                  
            $title = get_option('mcp_chat_widget_title');
         
            extract($args);
            
            echo $before_widget;
            if ($title) { 
                echo $before_title;
                 
                echo $title; 
 
                echo $after_title; 
            }           
            echo $mcpt->chat();            
            echo $after_widget;
        }
        
        function mcp_widget_control($args=array (), $params=array ()) {
            if (isset ($_POST['submitted'])) {
                
                update_option('mcp_widget_title', $_POST['mcp_widget_title']);
                update_option('mcp_widget_hostname', $_POST['mcp_widget_hostname']);
                update_option('mcp_widget_avatar_size', $_POST['mcp_widget_avatar_size']);
                
                update_option('mcp_widget_status', $_POST['mcp_widget_status']);
                update_option('mcp_widget_motd', $_POST['mcp_widget_motd']);
                update_option('mcp_widget_name', $_POST['mcp_widget_name']);
                update_option('mcp_widget_version', $_POST['mcp_widget_version']);
                update_option('mcp_widget_host', $_POST['mcp_widget_host']);
                update_option('mcp_widget_port', $_POST['mcp_widget_port']);
                update_option('mcp_widget_ticks', $_POST['mcp_widget_ticks']);
                update_option('mcp_widget_plugins', $_POST['mcp_widget_plugins']);
                update_option('mcp_widget_plugininfo', $_POST['mcp_widget_plugininfo']);
                update_option('mcp_widget_plugintooltips', $_POST['mcp_widget_plugintooltips']);
                update_option('mcp_widget_avatartooltips', $_POST['mcp_widget_avatartooltips']);
                update_option('mcp_widget_player', $_POST['mcp_widget_player']);
                update_option('mcp_widget_avatars', $_POST['mcp_widget_avatars']);
                
            }
            ?>
            
            <table>
                <tr>
                    <td colspan="2"><h3>Einstellungen</h3></td>                    
                </tr>
                <tr>
                    <td>Widget Titel:</td>
                    <td><input type="text" name="mcp_widget_title" value="<?php echo stripslashes(get_option('mcp_widget_title')); ?>" /></td>
                </tr>
                <tr>
                    <td>MC Server Host:</td>
                    <td><input type="text" name="mcp_widget_hostname" value="<?php echo stripslashes(get_option('mcp_widget_hostname')); ?>" /></td>
                </tr>
                <tr>
                    <td>Avatargr&ouml;&szlig;e:</td>
                    <td><input type="text" name="mcp_widget_avatar_size" value="<?php echo stripslashes(get_option('mcp_widget_avatar_size')); ?>" /></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="2"><h3>Anzeigeoptionen</h3></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt eine Serverstatusgrafik neben dem Widgettitle.">
                    <td>Zeige Statusgrafik: </td>
                    <td><input id="mcp_widget_status" name="mcp_widget_status" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_status') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Message of the Day des Minecraftservers im Widget an.">
                    <td>Zeige MotD: </td>
                    <td><input id="mcp_widget_motd" name="mcp_widget_motd" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_motd') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt den Weltnamen im Widget an.">
                    <td>Zeige Weltname: </td>
                    <td><input id="mcp_widget_name" name="mcp_widget_name" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_name') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Minecraftserversion im Widget an.">
                    <td>Zeige Versionsinfo: </td>
                    <td><input id="mcp_widget_version" name="mcp_widget_version" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_version') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt den oben definierten Server Hostnamen im Widget an.">
                    <td>Zeige Host: </td>
                    <td><input id="mcp_widget_host" name="mcp_widget_host" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_host') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt den Serverport im Widget an.">
                    <td>Zeige Port: </td>
                    <td><input id="mcp_widget_port" name="mcp_widget_port" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_port') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die aktuellen Ticks des Servers im Widget an.">
                    <td>Zeige Ticks: </td>
                    <td><input id="mcp_widget_ticks" name="mcp_widget_ticks" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_ticks') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Plugins im Widget an.">
                    <td>Zeige Plugins: </td>
                    <td><input id="mcp_widget_plugins" name="mcp_widget_plugins" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_plugins') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Plugininformationen im Widget an, wenn man auf die Plugins klickt.">
                    <td>Zeige Plugininfo: </td>
                    <td><input id="mcp_widget_plugininfo" name="mcp_widget_plugininfo" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_plugininfo') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Befehle und deren Beschreibung un einem Tooltip an, wenn man &uuml;ber ein Plugin f&auml;hrt.">
                    <td>Zeige Plugin Tooltips: </td>
                    <td><input id="mcp_widget_plugintooltips" name="mcp_widget_plugintooltips" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_plugintooltips') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Onlinespieler im Widget an.">
                    <td>Zeige Spieler: </td>
                    <td><input id="mcp_widget_player" name="mcp_widget_player" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_player') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt die Spieler als Avatar im Widget an, wenn man auf die Spieler klickt.">
                    <td>Zeige Spieleravatare: </td>
                    <td><input id="mcp_widget_avatars" name="mcp_widget_avatars" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_avatars') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr data-ot-style="dark" data-ot="Zeigt einen Tooltip mit Spielerinformationen im Widget an, wenn man &uuml;ber einen Avatar f&auml;hrt.">
                    <td>Zeige Avatar Tooltips: </td>
                    <td><input id="mcp_widget_avatartooltips" name="mcp_widget_avatartooltips" type="checkbox" value="aktiv" <?php echo (get_option('mcp_widget_avatartooltips') == 'aktiv' ? 'checked="checked"' : ''); ?>></td>
                </tr>
            </table>
            <br /><br />
            <input type="hidden" name="submitted" value="ok" />
            <?php
        }
        
        function mcp_chat_widget_control($args=array (), $params=array ()) {
            if (isset ($_POST['submitted'])) {                
                update_option('mcp_chat_widget_title', $_POST['mcp_chat_widget_title']);                
                update_option('mcp_chat-amount', $_POST['mcp_chat-amount']);                
                update_option('mcp_chat-capability', $_POST['mcp_chat-capability']);                
            }
            
            $chat_capabilitys = array('level_0','level_1','level_2','level_3','level_4','level_5','level_6','level_7','level_8','level_9','level_10');

            ?>            
            <table>
                <tr>
                    <td colspan="2"><h3>Einstellungen</h3></td>                    
                </tr>
                <tr>
                    <td>Widget Titel:</td>
                    <td><input type="text" name="mcp_chat_widget_title" value="<?php echo stripslashes(get_option('mcp_chat_widget_title')); ?>" /></td>
                </tr>
                <tr>
                    <td>Anzahl Zeilen:</td>
                    <td><input type="text" name="mcp_chat-amount" value="<?php echo stripslashes(get_option('mcp_chat-amount')); ?>" /></td>
                </tr>
                <tr>
                    <td>ben&ouml;tigte Berechtigung:</td>
                    <td>                    
                        <select name="mcp_chat-capability" id="mcp_chat-capability">';
                            <?php
                                foreach($chat_capabilitys as $k => $v){
                                    echo '<option value="' . $v . '" ' . (get_option('mcp_chat-capability') == $v ? 'selected="selected"' : "") .  '>' . $v . '</option>';
                                }
                            ?>
                        </select>                    
                    </td>
                </tr>
            </table>
            <br /><br />
            <input type="hidden" name="submitted" value="ok" />
            <?php
        }
        
        function mcp_content($content) {
            
          global $current_user;
          
          get_currentuserinfo();          
          $nick = $current_user->display_name;
          $tpl = '
            <div class="mcpanel_widget">
                Hallo '.$nick.'<br />
                In diesem Bereich werden die &uuml;ber JSONAPI abgerufenen Informationen wiedergegeben.
            </div>  
          ';
          
          $content = str_replace('[mcPanel]', $tpl, $content);

          return $content;
        }
                
    }
}
   

if( class_exists( 'MCPanel' ) ) {
   $mcp = new MCPanel();
}

if( isset( $mcp ) ) {
   add_action( 'init', array( &$mcp, 'activate' ) );
}

?>