<?php
/**
*  topbarsyntax Plugin: topbar as syntax plugin to be placed anywhere on the page
*
* portions of code taken from Michael Klier <chi@chimeric.de> simple template
*
* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author     Taggic <taggic@t-online.de>
* 
* 
* 
*/
//session_start();
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');  
    
/******************************************************************************
* All DokuWiki plugins to extend the parser/rendering mechanism
* need to inherit from this class
*/
class syntax_plugin_topbarsyntax extends DokuWiki_Syntax_Plugin 
{
/******************************************************************************/
/* return some info
*/
    function getInfo(){
        return confToHash(dirname(__FILE__).'/plugin.info.txt');
    }

    function getType(){ return 'substition';}
    function getPType(){ return 'block';}
    function getSort(){ return 169;}
    
/******************************************************************************/
/* Connect pattern to lexer
*/   
    function connectTo($mode){
        $this->Lexer->addSpecialPattern('\{\{topbarsyntax>[^}]*\}\}',$mode,'plugin_topbarsyntax');
    }

/******************************************************************************/
/* handle the match
*/   
    function handle($match, $state, $pos, Doku_Handler &$handler) {
        global $ID;
        $match = substr($match,strlen('{{topbarsyntax>'),-2); //strip markup from start and end
        //handle params
        $data = array();
        // params can be a width and the bar orientation (h = horizontal, v = vertical)
        $params = explode(',',$match);  // if you will have more parameters and choose ',' to delim them
        return $params;        
     }
/******************************************************************************/
/* render output
* @author Michael Klier <chi@chimeric.de>
* modified by Taggic <taggic@t-online.de>
*/   
    function render($mode, Doku_Renderer &$renderer, $data) {
        $width = $data[0];   // width of the main bar
        $orient = $data[1];  // orientation of the menu

        if (!$width) { $width = "100%"; }
        if (!$orient) { $orient = "h"; }
        global $ID;
     
        $found = false;
        $tbar  = '';
        $path  = explode(':', $ID);
     
        while(!$found && count($path) >= 0) {
            $tbar = implode(':', $path) . ':' . 'topbar';
            $found = @file_exists(wikiFN($tbar));
            array_pop($path);
            // check if nothing was found
            if(!$found && $tbar == ':topbar') return;
        }
     
        if($found && auth_quickaclcheck($tbar) >= AUTH_READ) {
            if ($orient === "h") {                     
//              $renderer->doc .= "<div>orient = ".$orient.'</div><br />';
              $renderer->doc .= '<div id="tpl_smplbar_navi1" style="width:'.$width.'">'.p_wiki_xhtml($tbar,'',false).'</div>'.NL;
              }
            else if ($orient === "vl") {
//              $renderer->doc .= "<div>orient = ".$orient.'</div><br />';
              $renderer->doc .= '<div id="tpl_smplbar_navi2" style="width:'.$width.'">'.p_wiki_xhtml($tbar,'',true).'</div>'.NL;
            }
            else {
//              $renderer->doc .= "<div>orient = ".$orient.'</div><br />';
              $renderer->doc .= '<div id="tpl_smplbar_navi3" style="width:'.$width.'">'.p_wiki_xhtml($tbar,'',true).'</div>'.NL;
            }
            
        }
     }
}