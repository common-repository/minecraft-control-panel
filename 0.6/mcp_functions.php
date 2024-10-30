<?php
    
if( !class_exists( 'MCP_Functions' ) ) {
    class MCP_Functions {
        function stripColor($string, $type) {
            if ($type == 'hex') {
                
                $rep = array('[0;30;1m', '[0;30;22m', '[0;31;1m', '[0;31;22m', '[0;32;1m', '[0;32;22m', '[0;33;1m', '[0;33;22m', '[0;34;1m', '[0;34;22m', '[0;35;1m', '[0;35;22m', '[0;36;1m', '[0;36;22m', '[0;37;1m', '[0;37;22m', '[m');
                return str_replace($rep, '', $string);    
            }
            
            else {
                
                $rep = array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f', '§k', '§l', '§m', '§n', '§o', '§r');
                return str_replace($rep, '', $string);
            }
        }
        
        function template($file) {
            include_once('templates/' . $file . '.php');
        }    
    }
}
?>