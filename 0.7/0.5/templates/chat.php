<?php 
 
global $JSONAPI,$current_user;

get_currentuserinfo();          
$nick = $current_user->display_name;
$users = $JSONAPI->call("players.online.names");            
$online_user = $users[0]['success'];

$broadcast = false;

if ($_POST['action'] == 'mcp_chat') {    
    if (in_array($nick, $online_user)) {
        if($JSONAPI->call("chat.with_name", array($_POST['mcp_chat'], $nick))) { $broadcast = true; }        
    }        
    else { if($JSONAPI->call("chat.broadcast", array($nick . ' > ' . $_POST['mcp_chat']))) { $broadcast = true; } }    
}

$data = $JSONAPI->call("streams.chat.latest", array(get_option('mcp_chat-amount', 15)));

$str_remove = array('[0;31;1m', '[0;32;1m', '[0;33;22m', '[0;36;1m', '[0;37;1m', '[m');    


if (!empty($data[0]['success'])) {
    foreach($data[0]['success'] as $chatK => $chatV) {
        $chatLines .= '
            <tr>
                <td>
                    ' . date("H:i:s", $data[0]['success'][$chatK]['time']) . ' [' . ($data[0]['success'][$chatK]['player'] ? $data[0]['success'][$chatK]['player'] : 'Web'). '] ' . str_replace($str_remove, '', $data[0]['success'][$chatK]['message']) . '
                </td>
            </tr>
        ';    
    }    
}

?>
<div class="chat_block">
    <?php 
        if (!empty($chatLines)) echo '<table>' . $chatLines . '</table>';
        else echo '<table><tr><td>Keine Nachrichten in den letzten 24Std.</td></tr></table>';
     
        if (current_user_can(get_option('mcp_chat-capability'))) {                
            echo '
                <form name="mcp_chat_form" method="post" action="' . $location . '">
                    <table>
                        <tr>
                            <td style="padding: 0 5px 5px 5px;">                                    
                                <input name="action" value="mcp_chat" type="hidden" />
                                <input type="text" name="mcp_chat" size="20" value="" />
                            </td>
                            <td style="padding: 0 5px 5px 0;">
                                <input type="submit" value="Ok" />
                            </td>
                        </tr>
                    </table>
                </form>
            ';                
        }
    ?>
</div>