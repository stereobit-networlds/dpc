<?php
/**
* SmbWebClient: web frontend to smbclient (www.samba.org)
*
* by Victor M. Varela <vmvarela@nivel0.net>
*
* {$Id: smbwebclient.php,v 1.21 2004/02/13 12:48:59 vmvarela Exp $}
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* ---
* You can download the latest version at:
*  http://www.nivel0.net/SmbWebClient
*
* You can edit your translation at:
*  http://www.nivel0.net/SmbWebClientTranslation
*
* Please send suggestions, bugs etc to: vmvarela@nivel0.net
*
* Quick install:
*
* 1) To get SMB Web Client to work, make sure that you
*    have Apache and PHP 4.1.x installed.
*
* 2) Download from http://www.nivel0.net/SmbWebClient
*
* 3) Untar compressed file
*  $ tar xzf smbwebclient-XX.XX.tgz
*  $ cp smbwebclient.php /var/www
*
* 4) Change your settings (editing smbwebclient.php) (see below)
*
* 5) go to http://your-server-url/smbwebclient.php and
*    enter a valid Windows user and password
*/

// CONFIGURATION

define ('cfgSambaRoot',       '');   // DOMAIN/SERVER/SHARE/path
define ('cfgCachePath',       '');   // path to cache files ('' = disabled)
define ('cfgDefaultLanguage', 'en'); // en, es, fr
define ('cfgLogFile',         '');   // path to log file ('' = disabled)
define ('cfgDefaultServer',   'localhost'); // default browse server
define ('cfgSmbClient',       'smbclient'); // path to smbclient command
define ('cfgSocketOptions',   'TCP_NODELAY IPTOS_LOWDELAY SO_KEEPALIVE SO_RCVBUF=8192 SO_SNDBUF=8192');

// ----> end of configuration, you don't need to edit after this line !!! <----


class SmbWebClient {

// VARIABLES
  
var $strings = array (
'en' => array ('Windows Network','Name','Size','Comments','Modified','Attributes','m/d/Y h:i','Print','Delete Selected','New File','Cancel Selected','User','Password','login','Overwrite this file?','Yes, overwrite','No, do not overwrite'),
'es' => array ('Red Windows','Nombre','Tamaño','Comentarios','Modificado','Atributos','d/m/Y h:i','Imprimir','Borrar Marcados','Nuevo Archivo','Cancelar Marcados','Usuario','Contraseña','identificarse','¿Sobreescribir este archivo?','Si, sobreescribir','No, no sobreescribir'),
'fr' => array ('Reseau Windows','Nom','Volume','Commentaires','Modifier','Attributs','(m/d/Y h:i)','Imprimer','L\'effacement a choisi','Nouveau Fichier','L\'annulation a choisi','Utilisateur','Mot de Passe','Connexion','Voulez-vous ecraser ce fichier ?','Oui, ecraser','Non, ne pas ecraser'),
'ru' => array ('&#1057;&#1077;&#1090;&#1100; Windows','&#1048;&#1084;&#1103;','&#1056;&#1072;&#1079;&#1084;&#1077;&#1088;','&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1081;','&#1048;&#1079;&#1084;&#1077;&#1085;&#1077;&#1085;','&#1040;&#1090;&#1088;&#1080;&#1073;&#1091;&#1090;&#1099;','d/m/Y h:i','&#1055;&#1077;&#1095;&#1072;&#1090;&#1100;','&#1059;&#1076;&#1072;&#1083;&#1080;&#1090;&#1100; &#1074;&#1099;&#1076;&#1077;&#1083;&#1077;&#1085;&#1085;&#1099;&#1077;','&#1053;&#1086;&#1074;&#1099;&#1081; &#1092;&#1072;&#1081;&#1083;','&#1054;&#1090;&#1084;&#1077;&#1085;&#1080;&#1090;&#1100; &#1074;&#1099;&#1076;&#1077;&#1083;&#1077;&#1085;&#1080;&#1077;','&#1055;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1100;','&#1055;&#1072;&#1088;&#1086;&#1083;&#1100;','&#1048;&#1084;&#1103; &#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1103;','?','?','?'),
'de' => array ('Windows Netzwerk','Name','Größe','Kommentar','Geändert','Attribute','(d.m.Y h:i)','Drucken','Gewählte Löschen','Neue Datei','Gewählte Abbrechen','Benutzer','Passwort','Login','?','?','?'),
'it' => array ('Rete Windows','Nome','Dimensione','Commento','Modificato','Attributi','m/d/Y h:i','Stampa','Rimuovi oggetto selezionato','Nuovo File','Cancella oggetto selezionato','Utente','Password','Accesso','?','?','?'),
'pt_br' => array ('Rede Windows','Nome','Tamanho','Comentários','Modificado','Atributos','m/d/Y h:i','Imprimir','Deletar Selecionados','Novo Arquivo','Cancelar Selecionados','Usuário','Senha','login','?','?','?')
);

var $mime_types = array (
  '%'=>'application/x-trash','ai'=>'application/postscript','aif'=>'audio/x-aiff',
  'aifc'=>'audio/x-aiff','aiff'=>'audio/x-aiff','asc'=>'text/plain','asf'=>'video/x-ms-asf',
  'asx'=>'video/x-ms-asf','au'=>'audio/basic','avi'=>'video/x-msvideo',
  'bak'=>'application/x-trash','bat'=>'application/x-msdos-program',
  'bin'=>'application/octet-stream','bmp'=>'image/x-ms-bmp','com'=>'application/x-msdos-program',
  'cpio'=>'application/x-cpio','css'=>'text/css','csv'=>'text/comma-separated-values',
  'deb'=>'application/x-debian-package','diff'=>'text/plain','dl'=>'video/dl',
  'dll'=>'application/x-msdos-program','doc'=>'application/msword','dot'=>'application/msword',
  'dvi'=>'application/x-dvi','eps'=>'application/postscript','exe'=>'application/x-msdos-program',
  'fli'=>'video/fli','gif'=>'image/gif','gl'=>'video/gl','gsm'=>'audio/x-gsm',
  'gtar'=>'application/x-gtar','htm'=>'text/html','html'=>'text/html','ief'=>'image/ief',
  'jpe'=>'image/jpeg','jpeg'=>'image/jpeg','jpg'=>'image/jpeg','js'=>'application/x-javascript',
  'kar'=>'audio/midi','lha'=>'application/x-lha','m3u'=>'audio/x-mpegurl',
  'mdb'=>'application/msaccess','mid'=>'audio/midi','midi'=>'audio/midi','mng'=>'video/x-mng',
  'mov'=>'video/quicktime','mp2'=>'audio/mpeg','mp3'=>'audio/mpeg','mpe'=>'video/mpeg',
  'mpeg'=>'video/mpeg','mpg'=>'video/mpeg','mpga'=>'audio/mpeg','msi'=>'application/x-msi',
  'ogg'=>'application/x-ogg','old'=>'application/x-trash','pbm'=>'image/x-portable-bitmap',
  'pcx'=>'image/pcx','pdf'=>'application/pdf','pgm'=>'image/x-portable-graymap',
  'pgp'=>'application/pgp-signature','pls'=>'audio/x-scpls','png'=>'image/png',
  'pnm'=>'image/x-portable-anymap','pot'=>'application/vnd.ms-powerpoint',
  'ppm'=>'image/x-portable-pixmap','pps'=>'application/vnd.ms-powerpoint',
  'ppt'=>'application/vnd.ms-powerpoint','ps'=>'application/postscript','qt'=>'video/quicktime',
  'ra'=>'audio/x-realaudio','ram'=>'audio/x-pn-realaudio','rgb'=>'image/x-rgb',
  'rm'=>'audio/x-pn-realaudio','rpm'=>'audio/x-pn-realaudio-plugin','rtf'=>'text/rtf',
  'rtx'=>'text/richtext','sid'=>'audio/prs.sid','sik'=>'application/x-trash','snd'=>'audio/basic',
  'svg'=>'image/svg+xml','svgz'=>'image/svg+xml','swf'=>'application/x-shockwave-flash',
  'swfl'=>'application/x-shockwave-flash','tar'=>'application/x-tar','taz'=>'application/x-gtar',
  'text'=>'text/plain','tgz'=>'application/x-gtar','tif'=>'image/tiff','tiff'=>'image/tiff',
  'tsv'=>'text/tab-separated-values','txt'=>'text/plain','vcf'=>'text/x-vcard',
  'vcs'=>'text/x-vcalendar','wav'=>'audio/x-wav','xbm'=>'image/x-xbitmap','xhtml'=>'text/html',
  'xlb'=>'application/vnd.ms-excel','xls'=>'application/vnd.ms-excel','xml'=>'text/xml',
  'xpm'=>'image/x-xpixmap','xsl'=>'text/xml','zip'=>'application/zip','~'=>'application/x-trash'
);

var $binary_files = array (
  'style.css' => "
    body { color: black; background: white; font-size: 12px; margin-top: 0px; margin-left: 0px; font-family: arial, helvetica, Verdana; }
    pre { font-size: 12px; margin-top: 0px; margin-bottom: 0px; line-height: 12px; }
    pre a { text-decoration: none; color: black; }
    pre a:hover { color: white; background-color: #424691; }
    pre a:visited { color: grey; }
    pre em { font-style: normal; font-weight: normal; background-color: #f7f7f7; }
    #columns { background-color: #d5d2c5; border-bottom: 1px solid black; width: 100%; margin-top: 0px; line-height: 20px }
    #columns a { background-color: #d5d2c5; color: black; }
    #columns a:hover { font-weight: bold; }
    #directory { width: 100% }",
  'images/a.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABcAAQEBAQAAAAAAAAAAAAAAAAAFAQf/xAAgEAACAQQBBQAAAAAAAAAAAAAAAQIDBBEhBRJRcYHR/8QAFgEBAQEAAAAAAAAAAAAAAAAAAAID/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A6gDJdTg1BpSxptZSfgm2HG3dndVKtTkHXjVeZwlTxl91vXz1jNamAAAAAAAD/9k=',
  'images/d.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABcAAQEBAQAAAAAAAAAAAAAAAAAFAQf/xAAhEAADAAEDBAMAAAAAAAAAAAAAAQIEAwUREhMhUSKB0f/EABYBAQEBAAAAAAAAAAAAAAAAAAACA//EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AOoAypm4cXKqaXDTXKaJuBseJt+VqZGmnVU/h1ee2vS/fX3zmtTAAAAAAAB//9k=',
  'images/directory.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABoAAQACAwEAAAAAAAAAAAAAAAADBgIEBQf/xAArEAACAQMDAQYHAQAAAAAAAAABAgMABBEFEiEGExQxMkFRIlRhcXKRkqH/xAAXAQADAQAAAAAAAAAAAAAAAAAAAQQF/8QAGxEBAAICAwAAAAAAAAAAAAAAAQACAyEEMVH/2gAMAwEAAhEDEQA/APa7zU+ymlQXVtbLEwRmuF3bmKhuPiXHBHvnngY50n6utLaINdIOCAzQTxSL44yBv3kevlz9K52p6tBofU9xNeyGFJUJiYqSGysQ4x+Lfr7VDL17pDBR34+dCcRv4bhn09qxr8vNTJY335KzFVCXWGaO4hSaF1eNxuVlOQRSq/0GHXorTu0VlYhzhhg4MjEf5ilbFVQWSumWCSKOXHaRo+PDcucVh3S2+Xi/gUpTikoAUAAAAcAClKUQn//Z',
  'images/disk.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABoAAQACAwEAAAAAAAAAAAAAAAAEBQIDBgf/xAAlEAABBAICAgAHAAAAAAAAAAABAgMEEQAFEiEGQSIxUWFxgvD/xAAXAQADAQAAAAAAAAAAAAAAAAAAAQQF/8QAHhEAAgEDBQAAAAAAAAAAAAAAAAERAhJBAwQUkfD/2gAMAwEAAhEDEQA/APbJksMOtNKUUBxKlckiz0UigKNk8v68hS9pIgxVSlNlUZv4luPBLVJ9ntQN/qLyP5NObgLiPBUdUqnAw087w5KPEWD7qx19+s51+TJ2+2Op3ezhwoxdSgRmHeS5PRUAT6BFWPlYqzmXq8h7m2lwse7KKVRZLO9jSG5cZuQyrk24kKSaqwcZmhCW20oQKSkAAfQYzUJzRNgRNiyGZkdt5sGwlYujldE8S0UGQH2Na0HQQQtRUsj8WTWMYoUyE4LrGMYwP//Z',
  'images/dotdot.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABkAAQACAwAAAAAAAAAAAAAAAAAGBwIDBP/EACIQAAIBBAICAwEAAAAAAAAAAAECAwAEEUEFIRITBjFRYf/EABYBAQEBAAAAAAAAAAAAAAAAAAIBBf/EAB8RAAICAQQDAAAAAAAAAAAAAAABAgMRBBIhcRMikf/aAAwDAQACEQMRAD8Auy+5GDj4JZJRK5jjaQpDGXYgfwfus1AeH+dcyLkvf2Ul3FPN4rb28BE0HRbAXGWAUZ7wd51U1uZWTl2USetTAjO28At9Z32KifHoOS+W30sZlCrcN65Hch2f1rGWGMdABzoHKgbrIu1dnn2J4w/ob6Z+jg++iwKUpWuI5p7C1uZhLPAsrAYHn2N6+tmt0cUcK+MUaov4owKUoqEU8pclbb4ZnSlKRD//2Q==',
  'images/file.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABkAAQADAQEAAAAAAAAAAAAAAAAEBQYHAv/EACwQAAICAQMCAwcFAAAAAAAAAAECAwQRAAUSBiETMUEHFBUWNlFhdJWy0uH/xAAVAQEBAAAAAAAAAAAAAAAAAAAAAv/EAB8RAAIBAgcAAAAAAAAAAAAAAAABEQISEyFBUWGR8P/aAAwDAQACEQMRAD8A61uu4pTvv71u0tCuEhROESvzkkaQAd1JyeIA/wB1m7XtB2eFqw2rqZd2tyzpClIImZSx4gDCrgliozk4znBxg2nVCV5LbrbpNcgKwho/h73VB4WeLNEgJZQ3E+np3HbWMi2yJr20rX6eiEqzbcWlh6amrPHMliIyv4ngqoUqJSSSoxxAVcMXujDh3zOm3ulyHkpOsUbce4bfWuxBljsRLKgcYIDAEZ/PfTUPpr6V2f8AQw/wGmoB7vbHR3GyLFhZ/FCBMxWZYsgEkZCMAfM6jfKu1fa7+4WP76aaAta1aKnUhqwJwhhRY41yThQMAZPfyGmmmgP/2Q==',
  'images/printjob.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABkAAQADAQEAAAAAAAAAAAAAAAAEBQYHAv/EACwQAAICAQMCAwcFAAAAAAAAAAECAwQRAAUSBiETMUEHFBUWNlFhdJWy0uH/xAAVAQEBAAAAAAAAAAAAAAAAAAAAAv/EAB8RAAIBAgcAAAAAAAAAAAAAAAABEQISEyFBUWGR8P/aAAwDAQACEQMRAD8A61uu4pTvv71u0tCuEhROESvzkkaQAd1JyeIA/wB1m7XtB2eFqw2rqZd2tyzpClIImZSx4gDCrgliozk4znBxg2nVCV5LbrbpNcgKwho/h73VB4WeLNEgJZQ3E+np3HbWMi2yJr20rX6eiEqzbcWlh6amrPHMliIyv4ngqoUqJSSSoxxAVcMXujDh3zOm3ulyHkpOsUbce4bfWuxBljsRLKgcYIDAEZ/PfTUPpr6V2f8AQw/wGmoB7vbHR3GyLFhZ/FCBMxWZYsgEkZCMAfM6jfKu1fa7+4WP76aaAta1aKnUhqwJwhhRY41yThQMAZPfyGmmmgP/2Q==',
  'images/printer.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABoAAQACAwEAAAAAAAAAAAAAAAAFBwEDBAb/xAAoEAABAwMDAgYDAAAAAAAAAAABAgMEAAUREiFBBhMUIzFRYXEiQpH/xAAVAQEBAAAAAAAAAAAAAAAAAAAAAf/EABwRAAMAAQUAAAAAAAAAAAAAAAABETECEiEiof/aAAwDAQACEQMRAD8AuW6TPClvEgt7ErCdOQODkg+xGOc/Fa41z0OaZKnO24R23Foxjb0JwBjnP3UTebnBbvjza5PZdQ2hpR7qDvuoeWTqUPz/AFGeBXmVyJElT6JtwajZSNIW6lpSweQCUjHBySfij1pdeL6I8lpUrlttxj3a3Mzoqiph0EpKhg7HB2+waUBiXa4E9YXLhsPLCdIU4gEge1Rsbo3p+I6XWrcgqIx5ri3B/FEilKm1WlrwTMeOzFYSzHaQ00nOlCBgDJz6UpSqQ//Z',
  'images/server.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABgAAQEBAQEAAAAAAAAAAAAAAAAFBwEC/8QAJRAAAgEEAQMEAwAAAAAAAAAAAQIDAAQFESESEzEGFCJhUZGh/8QAFgEBAQEAAAAAAAAAAAAAAAAABAAB/8QAHREAAgEEAwAAAAAAAAAAAAAAAAECAwQTMRIyQf/aAAwDAQACEQMRAD8A2vJXclnad2JUZuoD571qoWQzd3Z26XK3cb6dOqPtAAqWAPO9+D9VbzEXuMZNbhgrSjpUsdAHyN/qszy8Nxb5mDGXU4WydQZZ05byBofjyKXbwpSi+Ww1XNkiodfTWgdjYpXFACgDka4pRBJ5mhjniaKVA8bDTKRwakJ6TwqXSXJtGeVD1L3Z5HAP0rMR/KUrU2tEWqUpWEf/2Q==',
  'images/workgroup.jpg' => '/9j/4AAQSkZJRgABAQEASABIAAD//gAXQ3JlYXRlZCB3aXRoIFRoZSBHSU1Q/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAFAAUAwEiAAIRAQMRAf/EABkAAQADAQEAAAAAAAAAAAAAAAABBAYHBf/EACUQAAEEAgEDBAMAAAAAAAAAAAECAwQRAAUxBhIhIkFRgROR0f/EABcBAQEBAQAAAAAAAAAAAAAAAAADAQT/xAAbEQACAgMBAAAAAAAAAAAAAAAAAQIRIaHxUv/aAAwDAQACEQMRAD8A7vmMhdVPSOoImtQ6XXCopktqQAWxVg0BY9ufnJ6wmOR3HltNNuBLHYoKUAoK8nwD7UR5/mZ7XwdlqvzbGW3EceltNOOdqj6qSUg+omzQHgcVZu86IQWF610hOUrbSeN8Op4zxOkVuudLQXHzbigo83Q7zQ+hQ+sZBqmXLuw0+v2gSJsZL1VySP3XOTL1MGdFTGkRkrZSO0Jsih8ePbGMwFiNGZhx0R47aW2WxSUJ4AxjGAf/2Q=='
);

var $info = array();

// MAIN FUNCTION

function Run() {
    $this->ParseUrl();
    if (isset($this->binary_files[$this->path])) $this->InlineFile($this->path);
    else {
      $this->GetLanguage();
      $this->GetTarget();
      $this->UserAuth();
      if ('ListViewAction' == $_POST['m']) $this->ListViewAction();
      elseif ('ConfirmOverwrite' == $this->get['m'])
        $this->ConfirmOverwrite($this->get['file'], $this->get['name']);
      if ($this->Samba ('browse')) {
        if ($this->type == 'File') {
          if (! $_SESSION['SmbWebClient_Debug']) {
            $this->GetMimeFile($this->cachefile,$this->name);
            if (cfgCachePath == '') unlink ($this->cachefile);
          }
        } else {
          $page = $this->Page($this->name, $this->Block('directory', $this->ListView()));
          if (! $_SESSION['SmbWebClient_Debug']) print $page;
          else print_r($this->info);
        }
        $this->Log();
      } else switch ($this->info['error']) {
          case 'NT_STATUS_LOGON_FAILURE': header ("Location: ".$this->GetUrl()."?auth=1");
          default: if (! $_SESSION['SmbWebClient_Debug']) 
            print $this->Page("ERROR", $this->Block('errormessage',
              "<b>**error**</b> ".$this->info['error']
            ));
        }
    }
  }

// URL

/**
* Parse current url
*/
function ParseUrl() {
  ereg('^'.$_SERVER['SCRIPT_NAME'].'(.*)$', $_SERVER['REQUEST_URI'], $url);
  $a = split ('\?', ereg_replace('^/','',$url[1]));
  $this->path = urldecode(ereg_replace('/$', '', $a[0]));
  foreach (split('&',$a[1]) as $cmd) {
    $a = split('=', $cmd);
    $this->get[urldecode($a[0])] = urldecode($a[1]);
  }
}

/**
* Return current url without any params
*/
function GetUrl() {
  $a = split('\?', $_SERVER['REQUEST_URI']);
  return $a[0];
}

/**
* Set type and name of samba object
*/
function GetTarget() {
  if ('' == $path = ($this->path == '') ? cfgSambaRoot : ereg_replace('^/', '', cfgSambaRoot.'/'.$this->path)) {
    $this->type = 'Network';
    $this->name = $this->GetString(0);
  } else {
    $a = split('/',$path);
    $this->info = array('workgroup'=>$a[0],'server'=>$a[1],'share'=>$a[2]);
    for ($i=3; $i<count($a)-1; $i++) $this->info['path'] .= $a[$i].'/';
    $this->info['path'] .= $a[$i];
    switch (count($a)) {
      case 1:  $this->type = 'Workgroup'; break;
      case 2:  $this->type = 'Server'; break;
      default: $this->type = 'Share';
    }
    $this->name = basename($path);
  }
}

// LANGUAGES

/**
* Set current language
*/
function GetLanguage() {
  if (isset($this->get['lang']) AND is_array($this->strings[$this->get['lang']]))
    $_SESSION['SmbWebClient_Lang'] = $this->get['lang'];
  if (! isset($_SESSION['SmbWebClient_Lang'])) {
    foreach (split('[,;]',$_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang)
      if (is_array($this->strings[$lang])) { $_SESSION['SmbWebClient_Lang'] = $lang; break; }
    if (! isset($_SESSION['SmbWebClient_Lang'])) $_SESSION['SmbWebClient_Lang'] = cfgDefaultLanguage;
  }
  $this->lang = $_SESSION['SmbWebClient_Lang'];
}

/**
* Get a string
*/
function GetString($i) {
  $str = $this->strings[$this->lang][$i];
  return ($str == '') ? $this->strings[cfgDefaultLanguage][$i] : $str;
}

// SAMBA INTERFACE

/**
* Builds a samba command
*/
function SmbClient ($cmd, $path = '') {
  if ($path <> '') $path = "-D \"{$path}\"";
  return cfgSmbClient." \"//{$this->info['server']}/{$this->info['share']}\" {$path} -O \"".cfgSocketOptions."\" -N -U {$this->auth} -c \"{$cmd}\"";
}

/**
* smbclient interface (commands: browse or download)
*/
function Samba ($command, $path='') {
  $this->info['error'] = '';
  switch ($command) {
    case 'browse':
      $this->info['shares'] = $this->info['servers'] = $this->info['workgroups'] = $this->info['files'] = array();
      $server = ($this->info['server'] == '') ? cfgDefaultServer : $this->info['server'];
      if ($this->type <> 'Share') {
        if ($this->type == 'Workgroup') {
          // who is the master ? (browse network first)
          $this->type = 'Network';
          $auth = $_SESSION['SmbWebClient_Auth']['Network'][$this->GetString(0)];
          $oldauth = $this->auth;
          $this->auth = "{$auth['login']}%{$auth['password']}";
          $this->Samba('browse');
          $this->info['servers'] = array();
          $this->type = 'Workgroup';
          $this->auth = $oldauth;
          $server = $this->info['workgroups'][$this->info['workgroup']]['comment'];
        }
        $cmd = "smbclient -L {$server} -N -U {$this->auth}";
      } else {
        if ($path == '') $path = $this->info['path'];
        $cmd = $this->SmbClient("dir", $path);
      }
      break;
    case 'spool':
      $this->info['files'] = array();
      $this->type = 'Printer';
      $cmd = $this->SmbClient("queue");
      break;
    case 'get':
      $cmd = $this->SmbClient("dir \\\"".basename($this->info['path'])."\\\"", dirname($this->info['path']));
      break;
    case 'get2':
      $this->type = 'File';
      $this->size = $this->info['files'][$this->name]['size'];
      $this->time = $this->info['files'][$this->name]['time'];
      $this->cachefile = (cfgCachePath == '') ? tempnam("/tmp","swc") : cfgCachePath.'/'.$this->path;
      if ($this->time <> '') {
        if (cfgCachePath == '' OR (!file_exists($this->cachefile)) OR filemtime($this->cachefile) < $time) {
          if (cfgCachePathcache <> '' AND !is_dir(dirname($this->cachefile))) $this->MakeDirectory(dirname($this->cachefile));
          $path = str_replace('/','\\',$this->info['path']);
          $cmd = $this->SmbClient("get \\\"{$path}\\\" \\\"{$this->cachefile}\\\"");
        }
      }
      break;
    case 'file_exists':
      $this->info['files'] = array();
      $server = ($this->info['server'] == '') ? cfgDefaultServer : $this->info['server'];
      $cmd = $this->SmbClient("dir \\\"$path\\\"", $this->info['path']);
      break;
    case 'put':
      $cmd = $this->SmbClient("put \\\"{$_FILES['file']['tmp_name']}\\\" \\\"{$_FILES['file']['name']}\\\"", $this->info['path']);
      break;
    case 'print':
      $cmd = $this->SmbClient("print \\\"{$_FILES['file']['tmp_name']}\\\"");
      break;
    case 'cancel':
      $cmd = $this->SmbClient("cancel {$path}");
      break;
    case 'delete':
      $directory = $this->info['path'].'/'.dirname($path);
      $cmd = $this->SmbClient("del \\\"".basename($path)."\\\"", $directory);
      break;
    case 'deltree':
      $this->Samba('browse',$path);
      $files = $this->info['files'];
      foreach ($files as $filename => $info) $this->Samba('delete', $path.'/'.$filename);
      $cmd = $this->SmbClient("rmdir \\\"{$path}\\\"", $this->info['path']);
  }
  $this->Debug("\n$ $cmd\n",2);
  $ocmd = `{$cmd}`;
  $this->Debug($ocmd, 3);
  $ipv4 = "([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
  foreach (split("\n",$ocmd) as $line) {
    $regs = array();
    if (ereg("^added interface ip={$ipv4} bcast={$ipv4} nmask={$ipv4}$",$line,$regs)) {
      $this->info['interface'] = array($regs[1], $regs[2], $regs[3]);
    } else if ($line == "Anonymous login successful") {
      $this->info['anonymous'] = true;
    } else if (ereg("^Domain=\[(.*)\] OS=\[(.*)\] Server=\[(.*)\]$",$line,$regs)) {
      $this->info['description'] = array($regs[1], $regs[2], $regs[3]);
    } else if (ereg("^\tSharename[ ]+Type[ ]+Comment$",$line,$regs)) {
      $mode = 'shares';
    } else if (ereg("^\t---------[ ]+----[ ]+-------$",$line,$regs)) {
      continue;
    } else if (ereg("^\tServer   [ ]+Comment$",$line,$regs)) {
      $mode = 'servers';
    } else if (ereg("^\t---------[ ]+-------$",$line,$regs)) {
      continue;
    } else if (ereg("^\tWorkgroup[ ]+Master$",$line,$regs)) {
      $mode = 'workgroups';
    } else if (ereg("^\t(.*)[ ]+(Disk|IPC)[ ]+IPC.*$",$line,$regs)) {
      continue;
    } else if (ereg("^\tIPC\\\$(.*)[ ]+IPC",$line,$regs)) {
      continue;
    } else if (ereg("^\t(.*)[ ]+(Disk|Printer)[ ]+(.*)$",$line,$regs)) {
      if (trim($regs[1]) <> 'IPC$') $this->info['shares'][trim($regs[1])] = array ('type'=>$regs[2], 'comment'=>$regs[3]);
    } else if (ereg('([0-9]+) blocks of size ([0-9]+)\. ([0-9]+) blocks available', $line, $regs)) {
      $this->info['size'] = $regs[1] * $regs[2];
      $this->info['available'] = $regs[3] * $regs[2];
    } else if (ereg("Got a positive name query response from $ipv4",$line,$regs)) {
      $this->info['ip'] = $regs[1];
    } else if (ereg("^session setup failed: (.*)$", $line, $regs)) {
      $this->info['error'] = $regs[1];
    } else if ($line == 'session setup failed: NT_STATUS_LOGON_FAILURE' or ereg('^tree connect failed: ERRSRV - ERRbadpw', $line)) {
      $this->info['error'] = 'NT_STATUS_LOGON_FAILURE';
    } else if (ereg("^Error returning browse list: (.*)$", $line, $regs)) {
      $this->info['error'] = $regs[1];
    } else if (ereg("^tree connect failed: (.*)$", $line, $regs)) {
      $this->info['error'] = $regs[1];
    } else if (ereg("^Connection to .* failed$", $line, $regs)) {
      $this->info['error'] = 'CONNECTION_FAILED';
    } else if (ereg('^NT_STATUS_INVALID_PARAMETER', $line)) {
      $this->info['error'] = 'NT_STATUS_INVALID_PARAMETER';
    } else if (ereg('^NT_STATUS_DIRECTORY_NOT_EMPTY removing', $line)) {
      $this->info['error'] = 'NT_STATUS_DIRECTORY_NOT_EMPTY';
    } else if (ereg('ERRDOS - ERRbadpath \(Directory invalid.\)', $line) or ereg('NT_STATUS_NOT_A_DIRECTORY', $line)) {
      if ($this->type <> 'File') return $this->Samba('get');
      $this->info['error'] = 'NT_STATUS_NOT_A_DIRECTORY';
    } else if (ereg('^NT_STATUS_NO_SUCH_FILE listing ', $line)) {
      if ($command == 'delete') return $this->Samba('deltree', $path);
      if ($this->type == 'Share' AND $this->info['path'] == '') return $this->Samba('spool');
      $this->info['error'] = 'NT_STATUS_NO_SUCH_FILE';
    } else if (ereg('^NT_STATUS_ACCESS_DENIED listing ', $line)) {
      if ($this->type == 'Share' AND $this->info['path'] == '') return $this->Samba('spool');
      $this->info['error'] = 'NT_STATUS_ACCESS_DENIED';
    } else if (ereg('^cd (.*): NT_STATUS_OBJECT_PATH_NOT_FOUND$', $line)) {
      if ($this->type <> 'File') return $this->Samba('get');
      $this->info['error'] = 'NT_STATUS_OBJECT_PATH_NOT_FOUND';
    } else if (ereg('^cd (.*): NT_STATUS_OBJECT_NAME_NOT_FOUND$', $line)) {
      $this->info['error'] = 'NT_STATUS_OBJECT_NAME_NOT_FOUND';
    } else if (ereg("^\t(.*)$", $line, $regs)) {
      $this->info[$mode][trim(substr($line,1,21))] = array (
        'type'=>($mode == 'servers') ? 'Server' : 'Workgroup',
        'comment' => trim(substr($line,22))
      );
    } else if ($command == 'spool' AND ereg("^([0-9]+)[ ]+([0-9]+)[ ]+(.*)$", $line, $regs)) {
      $this->info['files'][$regs[1].' '.$regs[3]] = array('type'=>'PrintJob','id'=>$regs[1], 'size'=>$regs[2]);
    } else if (ereg("^[ ]+(.*)[ ]+([0-9]+)[ ]+(Mon|Tue|Wed|Thu|Fri|Sat|Sun)[ ](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[ ]+([0-9]+)[ ]+([0-9]{2}:[0-9]{2}:[0-9]{2})[ ]([0-9]{4})$", $line, $regs)) {
      if (ereg("^(.*)[ ]+([D|A|H|S|R]+)$", trim($regs[1]), $regs2)) {
        $attr = trim($regs2[2]);
        $name = trim($regs2[1]);
      } else {
        $attr = '';
        $name = trim($regs[1]);
      }
      if ($name <> '.' AND $name <> '..')
      $this->info['files'][$name] = array (
        'attr' => $attr,
        'size' => $regs[2],
        'time' => $this->ParseTime($regs[4],$regs[5],$regs[7],$regs[6]),
        'type' => (strpos($attr,'D') === false) ? 'File' : 'Directory'
      );
    }
  }
  if ($command == 'get') return $this->Samba('get2');
  return $this->info['error'] == '';
}

// MIME

/**
* Gets the mime type of a file (default: application/octet-stream)
*/
function GetMimeType($filename) {
  $pi = pathinfo(strtolower($filename));
  $mime_type = $this->mime_types[$pi['extension']];
  return ($mime_type == '') ? 'application/octet-stream' : $mime_type; 
}

/**
* Dumps a file with MIME headers
*/
function GetMimeFile($file='', $name='') {
  if ($name == '') $name = basename($file);
  $mime_type = $this->GetMimeType($name);
  header('MIME-Version: 1.0');
  header("Content-Type: $mime_type; name =\"$name\"");
  header("Content-Disposition: filename=\"$name\"");
  if ($file <> '' AND is_readable($file)) {
    header('Content-Length: '. filesize($file));
    $fp = fopen($file, "r");
    while (! feof($fp)) {
      print fread($fp,1024*32);
      flush();
    }
    fclose($fp);
  }
}

/**
* Inline file
*/
function InlineFile($file) {
  $this->GetMimeFile ('', basename($file));
  print ($file <> 'style.css') ? base64_decode($this->binary_files[$file]) : $this->binary_files[$file];
}

// LIST VIEW

function ListView() {
  switch ($this->type) {
    case 'Network':   $items = $this->info['workgroups']; break;
    case 'Workgroup': $items = $this->info['servers']; break;
    case 'Server':    $items = $this->info['shares']; break;
    default: $items = $this->info['files'];
             $items_are_files = true;
             break;
  }
  if (is_array($items)) {
    $index = $this->SortItems($items);
    $columns = $this->MakeColumns($items);
    // print columns headers
    if ($items_are_files) $headers .= $this->Checkbox('chkall','',false,'javascript:sel_all()');
    $headers .= $this->Image($_SERVER['SCRIPT_NAME'].'/images/'.strtolower($_SESSION['order'][1]).".jpg", '     ', 'align="absmiddle"');
    $headers .= $this->Link("?O=N",sprintf($columns[0],$this->GetString(1)));
    if ($items_are_files) {
      $headers .= $this->Link("?O=S",sprintf($columns[1], $this->GetString(2)));
      if ($this->type <> 'Printer') {
        $headers .= $this->Link("?O=M",sprintf($columns[3], $this->GetString(4)));
        $headers .= $this->Link("?O=A",sprintf($columns[4], $this->GetString(5)));
      }
    } else {
      $headers .= $this->Link("?O=M",sprintf($columns[2], $this->GetString(3)));
    }
    // back item
    if ($this->path <> '') {
      if ($items_are_files) $rows .= $this->Checkbox('back','1');
      $rows .= $this->Image($_SERVER['SCRIPT_NAME'].'/images/dotdot.jpg', '[DIR]', 'align="absmiddle"');
      $rows .= $this->Link($_SERVER['SCRIPT_NAME'].'/'.dirname($this->path), sprintf($columns[0], '..'))."\n";
    }
    // print rows
    foreach ($index as $file) {
      if ($items_are_files) $rows .= $this->Checkbox('selected[]',($this->type == 'Printer') ? $file['info']['id'] : $file['name']);
      $rows .= $this->Image($_SERVER['SCRIPT_NAME'].'/images/'.strtolower($file['info']['type']).".jpg", ($file['info']['type'] == 'File') ? '[   ]' :'[DIR]', 'align="absmiddle"');
      $path = ($this->type == 'Printer') ? '' : ereg_replace('/$','', $this->GetUrl()).'/'.urlencode($file['name']);
      $rows .= $this->Em($this->Link($path, sprintf($columns[0], $file['name'])),$_SESSION['order'][0] == 'N');
      if ($items_are_files) {
        $rows .= $this->Em(sprintf($columns[1], ($file['info']['type'] == 'File' OR $file['info']['type'] == 'PrintJob') ? $this->FormatBytes($file['info']['size']) : ''),$_SESSION['order'][0] == 'S');
        if ($this->type <> 'Printer') {
          $rows .= $this->Em(sprintf($columns[3], date($this->GetString(6),$file['info']['time'])),$_SESSION['order'][0] == 'M');
          $rows .= $this->Em(sprintf($columns[4], str_replace(array('A','D'),'',$file['info']['attr'])),$_SESSION['order'][0] == 'A');
        }
      } else {
        $rows .= $this->Em(sprintf($columns[2], $file['info']['comment']),$_SESSION['order'][0] == 'M');
      }
      $rows .= "\n";
    }
  }
  $this->javascript =
  "<script language=\"JavaScript\">\n".
  "  function sel_all() {\n".
  "    with (document.d_form) {\n".
  "      for (i=0; i<elements.length; i++) {\n".
  "        ele = elements[i];\n".
  "      if (ele.type==\"checkbox\")\n".
  "          ele.checked = ! ele.checked;\n".
  "      }\n".
  "    }\n".
  "  }\n".
  "</script>\n";
  if ($items_are_files) {
    $form .= $this->Input('m', 'ListViewAction', 'HIDDEN');
    $form .= '<p>'.$this->Input('do', $this->GetString(($this->type == 'Printer')? 10 : 8), 'SUBMIT').' '.$this->Input('file','','FILE').$this->Input('do', $this->GetString(($this->type == 'Printer') ? 7 : 9), 'SUBMIT').'</p>';
  }
  return $this->Form($this->Block('columns',$this->Pre($headers)).$this->Pre($rows).$form,'POST',$this->GetUrl());
}

/**
* Confirm file overwrite
*/
function ConfirmOverwrite ($file, $name) {
  print $this->Page($this->info['name'],
    $this->Form(
      $this->Input('m', 'ListViewAction', 'HIDDEN').
      '<p>'.$this->GetString(14).'</p>'.
      $this->Input('file', $file, 'HIDDEN').
      $this->Input('name', $name, 'HIDDEN').
      $this->Input('do', $this->GetString(15), 'SUBMIT'). ' '.
      $this->Input('do', $this->GetString(16), 'SUBMIT'),
      'POST',
      $this->GetUrl()
    )
  );
  exit();
}

/**
* Form action
*/
function ListViewAction () {
  if ($_POST['do'] == $this->GetString(9)) {
    $this->Samba('file_exists', $_FILES['file']['name']);
    if (! isset($this->info['files'][$_FILES['file']['name']])) $this->Samba('put');
    else {
      $file = tempnam('/tmp','SWC');
      copy($_FILES['file']['tmp_name'], $file);
      header("Location: ".$this->GetUrl()."?m=ConfirmOverwrite&file=".basename($file)."&name=".urlencode($_FILES['file']['name']));
      exit;
    }
  } else if ($_POST['do'] == $this->GetString(7)) $this->Samba('print');
  else if ($_POST['do'] == $this->GetString(15)) {
    $_FILES['file']['tmp_name'] = '/tmp/'.$_POST['file'];
    $_FILES['file']['name'] = $_POST['name'];
    $this->Samba('put');
  } else if ($_POST['do'] == $this->GetString(8))
    if (is_array($_POST['selected']))
      foreach ($_POST['selected'] as $filename) $this->Samba('delete', $filename);
  else if ($_POST['do'] == $this->GetString(10))
    if (is_array($_POST['selected']))
      foreach ($_POST['selected'] as $id) $this->Samba('cancel', $id);
  if (! $_SESSION['SmbWebClient_Debug']) {
    header("Location: ".$this->GetUrl());
    exit;
  }
}

/**
* Makes an index to show files
*/
function SortItems ($items) {
  // storing order
  if (! isset($_SESSION['order'])) {
    $_SESSION['order'] = 'NA';
  } elseif ($_GET['O']) {
    if ($_GET['O'] <> $_SESSION['order'][0]) {
      $_SESSION['order'] = $_GET['O'].'A';
    } else {
      if ($_SESSION['order'][1] == 'D')
        $_SESSION['order'] = $_GET['O'].'A';
      else
        $_SESSION['order'] = $_GET['O'].'D';
    }
  }
  $index = array();
  foreach ($items as $name => $info) $this->InsertItem ($index, $name, $info);
  return $index;
}

/**
* Insert a file in order
*/
function InsertItem(&$index, $name, $info) {
  if (count($index) == 0) {
    $index[] = array ('name' => $name, 'info' => $info);
  } else {
    $index2 = array();
    $inserted = false;
    for ($i = 0; $i < count($index); $i++) {
      if ((! $inserted) AND $this->GreaterThan($index[$i]['name'], $index[$i]['info'], $name, $info)) {
        $index2[] = array ('name' => $name, 'info' => $info);
        $inserted = true;
      }
      $index2[] = $index[$i];
    }
    if (! $inserted) $index2[] = array ('name' => $name, 'info' => $info);
    $index = $index2;
  }
}

/**
* Compares two file records
*/
function GreaterThan($name1, $info1, $name2, $info2) {
  switch ($_SESSION['order']) {
    case 'SA': return ($info1['size'] > $info2['size'] OR ($info1['size'] == $info2['size'] AND strtolower($name1) > strtolower($name2)));
    case 'SD': return ($info1['size'] < $info2['size'] OR ($info1['size'] == $info2['size'] AND strtolower($name1) < strtolower($name2)));
    case 'MA': return ($info1['time'] > $info2['time'] OR $info1 ['comment'] > $info2['comment']);
    case 'MD': return ($info1['time'] < $info2['time'] OR $info1 ['comment'] < $info2['comment']);
    case 'AA': return ($info1['attr'] > $info2['attr']);
    case 'AD': return ($info1['attr'] < $info2['attr']);
    case 'NA': return (strtolower($name1) > strtolower($name2));
    case 'ND': 
    default:   return (strtolower($name1) < strtolower($name2));
  }
}

/**
* Get columns width
*/
function MakeColumns ($items) {
  $max = array (10,10,10,strlen(date($this->GetString(6),time())),strlen($this->GetString(5)));
  foreach ($items as $name => $info) {
    if (strlen($name) > $max[0]) $max[0] = strlen($name);
    if (strlen($this->FormatBytes($info['size'])) > $max[1]) $max[1] = strlen($this->FormatBytes($info['size']));
    if (strlen($info['comment']) > $max[2]) $max[2] = strlen($info['comment']);
  }
  $fmt[0] = " %-{$max[0]}.{$max[0]}s ";
  $fmt[1] = " %{$max[1]}.{$max[1]}s ";
  $fmt[2] = " %-{$max[2]}.{$max[2]}s ";
  $fmt[3] = " %-{$max[3]}.{$max[3]}s ";
  $fmt[4] = " %-{$max[4]}.{$max[4]}s ";
  return $fmt;
}

/**
* Print KB
*/
function FormatBytes ($bytes) {
  if ($bytes < 1024) return "1 KB";
  elseif ($bytes < 10*1024*1024) return number_format($bytes / 1024,0) . " KB";
  elseif ($bytes < 1024*1024*1024) return number_format($bytes / (1024 * 1024),0) . " MB";
  else return number_format($bytes / (1024*1024*1024),0) . " GB";
}

/**
* Time from smbclient output format
*/
function ParseTime ($m, $d, $y, $hhiiss) {
  $his= split(':', $hhiiss);
  $im = 1 + strpos("JanFebMarAprMayJunJulAgoSepOctNovDec", $m) / 3;
  return mktime($his[0], $his[1], $his[2], $im, $d, $y);
}

// MISC.

/**
* Makes a directory recursively
*/
function MakeDirectory ($path, $mode = 0777) {
  if (strlen($path) == 0) return 0;
  if (is_dir($path)) return 1;
  elseif (dirname($path) == $path) return 1;
  return ($this->MakeDirectory(dirname($path), $mode) and mkdir($path, $mode));
}


/**
* Debugging
*/
function Debug ($message, $level = 1) {
  if (isset($this->get['debug'])) {
    $_SESSION['SmbWebClient_Debug'] = $this->get['debug'];
    unset($this->get['debug']);
  }
  if ($level <= $_SESSION['SmbWebClient_Debug']) {
    if (! isset($this->debug_header)) {
      $this->GetMimeFile('', 'debug.txt');
      $this->debug_header = 1;
    }
    print $message;
  }
}

/**
* Logging
*/
function Log () {
  if (cfgLogFile <> '' AND ($f = fopen(cfgLogFile, 'a'))) {
    fputs ($f, "{$_SERVER['REMOTE_ADDR']} - {$this->user} [".date('d/M/Y:h:i:s O')."] \"GET /{$this->path} HTTP/1.1\" 200 ".intval($this->size)." \"{$_SERVER['REQUEST_URI']}\" \"{$_SERVER['HTTP_USER_AGENT']}\"\n");
    fclose ($f);
  }
}

/**
* User authentification
*/
function UserAuth () {
  foreach (array('Share','Server','Workgroup', 'Network') as $mode) {
      $name = ($mode == 'Network' ? $this->GetString(0) : $this->info[strtolower($mode)]);
      $auth = $_SESSION['SmbWebClient_Auth'][$mode][$name];
      if (is_array($auth)) break;
  }
  if (is_array($auth)) {
    $this->auth = "{$auth['login']}%{$auth['password']}";
    $this->user = $auth['login']; 
  }
  if ($_POST['login']) {
    // form action
    $_SESSION['SmbWebClient_Auth'][$this->type][$this->name]['login'] = $_POST['login'];
    $_SESSION['SmbWebClient_Auth'][$this->type][$this->name]['password'] = $_POST['password'];
    $this->auth = "{$_POST['login']}%{$_POST['password']}";
    $this->user = $_POST['login'];
  } else if (isset($this->get['auth']) OR !isset($this->auth)) {
    // form
    print $this->Page ("", $this->Block('authform',$this->Form(
      $this->GetString(11).'<br />'.
      $this->Input('login').'<br />'.
      $this->GetString(12).'<br />'.
      $this->Input('password', '', 'PASSWORD').'<br />'.
      $this->Input('submit', $this->GetString(13), 'SUBMIT')
      ,'POST',$this->GetUrl())));
    exit;
  }
}

// HTML WIDGETS

function Page ($title, $content) {
  return
  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n".
  "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$_SESSION['lang']}\" lang=\"{$_SESSION['lang']}\">\n".
  "<head>\n".
  "  <title>{$title}</title>\n".
  "  <link rel=\"stylesheet\" type=\"text/css\" href=\"".$_SERVER['SCRIPT_NAME']."/style.css\" />\n".
  $this->javascript.
  "</head>\n".
  "<body>\n{$content}\n</body>\n</html>";
}

function Block ($id='', $content='') { return "\n<div id=\"$id\">".$content."</div>"; }
function Form ($content, $method="POST", $action=".", $name="d_form", $enctype="multipart/form-data") { return "<form name=\"$name\" method=\"$method\" action=\"$action\" enctype=\"$enctype\">$content</form>\n";  }
function Input ($name, $value="", $type="text") {  return "<input type=\"$type\" name=\"$name\" value=\"$value\" />";  }
function CheckBox ($name, $value="", $checked=false, $onclick="") {
  $checked = ($checked) ? "CHECKED" : "";
  $onclick = (trim($onclick) <> '') ? "onclick=\"$onclick\"" : "";
  return "<input type=\"checkbox\" name=\"$name\" value=\"$value\" $checked $onclick />";
}
function Image ($src, $alt="", $ext = '') {  return "<img src=\"$src\" alt=\"$alt\" $ext />";  }
function Link ($url, $text, $onclick="") {
  $onclick = (trim($onclick) <> '') ? "onclick=\"$onclick\"" : "";
  return ($url == '') ? $text : "<a href=\"$url\" $onclick>$text</a>";
}
function Em ($text, $optional=true) { return ($optional) ? "<em>{$text}</em>" : $text;  }
function Pre ($text) { return "<pre>{$text}</pre>\n";  }

}

session_start();
set_time_limit(1200);
clearstatcache();

$smb = new SmbWebClient;
$smb->Run();
?>