/*
  Minecraft Control Panel - JavaScript Funktionen
*/


setInterval(function(){ 
    jQuery("#mcp_chatArea").load(MCPF.file);
}, (MCPF.interval * 1000));
