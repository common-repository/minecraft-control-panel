=== Minecraft Control Panel ===
Contributors: Liath
Donate link:
Tags: minecraft,control,settings,jsonapi,info,server,user,groups,chat,api,widget
Requires at least: 3.6.1
Tested up to: 3.8.1
Stable tag: /trunk/0.7
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Zeigt Informationen &uuml;ber Deinen Minecraftserver im Front- und Backend an. Mit User- und Gruppenverwaltung, Pluginsteuerung und Serverkontrolle.



== Description ==

= Beschreibung =
Dieses Plugin erm&ouml;glicht Dir die komplette Steuerung Deines Minecraftservers, deren Spielern und Gruppen. Man hat die M&ouml;glichkeit ein Widget anzeigen zu lassen, welches die Informationen direkt im Frontend wiedergibt.

= Geplante Funktionen =
* Starten/Stoppen/Resetten des Servers
* ent/laden einzelner Plugins
* Benutzerverwaltung inkl. der Vergabe von Items (Shop?!?)
* Gruppenverwaltung
* Chat mit Verbindung ins Spiel f&uuml;r erlaubte Benutzer

= Achtung = 
Um das Plugin nutzen zu k&ouml;nnen, muss auf Deinem Minecraft-Server "JSONAPI" von Alec Gorge installiert und funktionsf&auml;hig sein.

= Download JSONAPI = 
https://github.com/alecgorge/jsonapi/releases

= Feedback =
Damit das Plugin stetig verbessert werden kann, m&ouml;chte ich Dich bitten es hier zu bewerten und ein Review zu hinterlassen, damit ich mir Deine Anregungen anschauen und bei Bedarf umsetzen kann.

= Support = 
Hilfe zu diesem Plugin bekommst Du hier: http://play4pain.tk/forum/?mingleforumaction=viewtopic&t=4 Beachte bitte, dass ich Dir keinen Support zu dem Minecraftplugin "JSONAPI" geben kann.

= Information =
Derzeit werden die Berechtigungen f&uuml;r den Chat noch &uuml;ber den Userlevel geregelt. Eine M&ouml;glichkeit diese zu beeinflussen, bietet das Wordpressplugin "User Role Editor". Ich arbeite aber an einer anderen L&ouml;sung.


== Installation ==

= JSONAPI =
1. Lade das Plugin f&uuml;r Deine Serverversion runter von: https://github.com/alecgorge/jsonapi/releases
2. kopiere das Plugin auf Deinen Minecraftserver in den Pluginordner
3. starte den Server neu, damit die Konfigurationsdateien erstellt werden und stoppe ihn wieder
4. editiere die Dateien config.yml und user.yml nach Deinen Bed&uuml;rfnissen
5. starte Deinen Server neu

= Minecraft Control Panel =
1. lade das Plugin in folgenden Ordner ../wp-content/plugins/minecraft-control-panel
2. gehe in Dein Dashboard in die Pluginliste
3. aktiviere das Plugin
4. &uuml;bernehme im Dashboard die Daten, die Du vorher in der config.yml und user.yml eingegeben hast
5. Bei Bedarf setze das Widget in eine Deiner Sidebars

== Screenshots ==

1. Das Widget zeigt Informationen &uuml;ber Deinen Minecraftserver an. Vollst&auml;ndig konfigurierbar.
2. Das Men&uuml; erlaubt direkten Zugriff auf alle noch kommenden Funktionen.
3. Die Informationssseite zeigt die Grundeinstellungen und geladenen Plugins des Servers.
4. Die Serverseite bietet die M&ouml;glichkeit den Server zu starten/stoppen/resetten und verschafft eine &Uuml;bersicht &uuml;ber die Konsole und den Chat. Dort kann man auch direkt Befehle oder Nachrichten ins Spiel senden.
5. Die Gruppenverwaltung erlaubt die vorhandenen Gruppen zu editieren oder neue zu erstellen.
6. Die Einstellungsseite gibt die M&ouml;glichkeit die API zu steuern und h&auml;lt die wichtigsten Einstellungen des Plugins und der Widgets bereit.

== Frequently Asked Questions ==
= kommt noch =

== Upgrade Notice ==
= Nichts zu berichten =

== Changelog ==

= Version 0.1 Pre =
* Das Widget wurde fertiggestellt
* Informationen im Dashboard einsehbar
* Broadcasting verf&uuml;gbar

= Version 0.2 =
* Adminbereich aufger&auml;umt und Stylesheet angepasst
* JSONAPI-Einstellungen auf eigene Seite verlagert
* Pluginliste erweitert (Plugins sind anklickbar mit weiteren Informationen)

= Version 0.3 =
* kleinere Anpassungen
* Screenshots angepasst

= Version 0.4 =
* Servereinstellungen erweitert
* Routinen zum Resetten/Starten/Stoppen des Servers integriert
* Chat und Konsole eingef&uuml;gt

= Version 0.5 =
* Infowidget um Einstellungen erg&auml;nzt
* Chatwidget hinzugef&uuml;gt
* Dateistruktur aufger&auml;umt

= Version 0.6 =
* Widgetoptionen hinzugef&uuml;gt
* Gruppenverwaltung hinzugef&uuml;gt
* Ajax-Funktionalit&auml;t vorbereitet (noch nicht fertig)

= Version 0.7 =
* Tooltips in Infowidget implementiert (daf&uuml;r wurde Opentip ins System integriert: http://www.opentip.org/)
* Das Infowidget wurde um eine Pluginliste erweitert
* Einstellungen der Widgets angepasst