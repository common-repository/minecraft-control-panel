<?php
class mcp_templates {
    
    function mainpage() {        
        include_once('mainpage.php');        
    }
    
    function server() {
        include_once('server.php');
    }
    
    function user() {
        include_once('user.php');
    }
    
    function groups() {
        include_once('groups.php');
    }
    
    function settings() {
        include_once('settings.php');
    }
    
    function widget() {
        include_once('widget.php');
    }
    
    function chat() {
        include_once('chat.php');
    }    
        
}
?>