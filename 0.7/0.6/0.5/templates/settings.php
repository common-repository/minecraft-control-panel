<?php

global $JSONAPI;

if ($_POST['action'] == 'plugin_settings') {    
    $broadcast = false;        
    if ($_POST['mcp_stream-amount']) { update_option('mcp_stream-amount', $_POST['mcp_stream-amount']); }    
}

if ($_POST['action'] == 'JSONAPI') {
  update_option("JSONAPI-Host",$_POST['JSONAPI-Host']);
  update_option("JSONAPI-Port",$_POST['JSONAPI-Port']);
  update_option("JSONAPI-User",$_POST['JSONAPI-User']);
  update_option("JSONAPI-Pass",$_POST['JSONAPI-Pass']);
  update_option("JSONAPI-Salt",$_POST['JSONAPI-Salt']);
}

$call = $JSONAPI->call("getPlayerLimit");

if (empty($call)) {
    $server = '<img src="' . plugins_url('../images/offline.png', __FILE__) . '" title="Server Offline" alt="Server Offline" />';
} else {
    $server = '<img src="' . plugins_url('../images/online.png', __FILE__) . '" title="Server Online" alt="Server Online" />';
}    
?>
<div class="mcpanel wrap">
    <h2 class="mcp">Einstellungen</h2>
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td class="title"><h4>JSONAPI</h4></td>
                <td style="text-align: right;"><?php echo $server; ?></td>
            </tr>
        </table>
        <form name="JSONAPI-Settings" method="post" action="<?php echo $location; ?>">
            <input name="action" value="JSONAPI" type="hidden" />
            <table>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4"><small>Die notwendigen Informationen findest Du in '.../plugins/JSONAPI/config.yml' und '.../plugins/JSONAPI/user.yml'</small></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="text">JSONAPI-Host:</td>
                    <td class="input"><input type="text" name="JSONAPI-Host" size="40" value="<?php echo get_option('JSONAPI-Host'); ?>" /></td>
                    <td class="text">JSONAPI-Port:</td>
                    <td class="input"><input type="text" name="JSONAPI-Port" size="40" value="<?php echo get_option('JSONAPI-Port'); ?>" /></td>
                </tr>
                <tr>
                    <td class="text">JSONAPI-User:</td>
                    <td class="input"><input type="text" name="JSONAPI-User" size="40" value="<?php echo get_option('JSONAPI-User'); ?>" /></td>
                    <td class="text">JSONAPI-Pass:</td>
                    <td class="input"><input type="text" name="JSONAPI-Pass" size="40" value="<?php echo get_option('JSONAPI-Pass'); ?>" /></td>
                </tr>
                <tr>
                    <td class="text">JSONAPI-Salt:</td><td class="input"><input type="text" name="JSONAPI-Salt" size="40" value="<?php echo get_option('JSONAPI-Salt'); ?>" /></td>
                    <td class="text" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text">&nbsp;</td>
                    <td class="text" colspan="2">
                        <?php if($_POST['action'] == 'JSONAPI') { ?>
                            <strong style="padding: 5px; background: #f50; border-radius: 5px">Einstellungen wurden aktualisiert.</strong>
                        <?php } ?>
                    </td>
                    <td class="input"><div style="text-align: right; padding: 5px 10px;"><input type="submit" name="Speichern" value="Speichern" onclick="this.value='Speichere'"></div></td>
                </tr>
            </table>
        </form>
    </div>
    
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td class="title"><h4>Plugin</h4></td>
            </tr>
        </table>
        
            <form name="plugin_settings" method="post" action="<?php echo $location; ?>">
                <input name="action" value="plugin_settings" type="hidden" />
                <table style="width: 100%">
                    <tr>
                        <td style="width: 150px">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 150px">Anzahl Nachrichten im Stream</td>
                        <td><input type="text" name="mcp_stream-amount" size="40" value="<?php echo get_option('mcp_stream-amount', 10); ?>" /></td>
                    </tr>
                </table>
                <div style="text-align: right; padding: 5px 10px;"><input type="submit" name="Speichern" value="Speichern" onclick="this.value='Speichere'"></div>
            </form>
            
    </div>
    
    <div class="JSONAPI">    
        <table class="header">
            <tr>
                <td class="title"><h4>Widget</h4></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Text</td>
                <td>Setting</td>
            </tr>
        </table>
    </div>
    
</div>