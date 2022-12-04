<?php
/*
web app with basic CRUD functions and bootstrap output


jumpmarks:
	config
	model start
	model stop
	view start
	view stop
	controller start
	controller stop


*/




// config ===================================================================================
include('config.php');
// config ===================================================================================



class app{
    
    
    // model start ==============================================================================
    
    
        // vars ===================
        protected $connection;
        // vars ===================
        
        
        
        
        public function read($query){
        	$res = $this->connection->query($query);
        	if($res){
            	if($res->num_rows > 0){
                	$i = 0;
                	while($r=$res->fetch_assoc()) {
                		$result[$i] = $r;
                		$i++;
                	}
                	return $result;
            	}else{
            	    return false;
            	}
        	}else{
                $GLOBALS['console'] .= '<br />read error<br />';
            }
        }
        
        
        
        
        public function write($query){
        	if(!$this->connection->query($query)){
        		$GLOBALS['console'] .= "Error: (" . $this->connection->errno . ") " . $this->connection->error;
        	}else{
        		return true;
        	}
        }
        
        
        
        
        protected function query($query){
        	if (!$this->connection->query($query)){
        		$GLOBALS['console'] .= "Error: (" . $this->connection->errno . ") " . $this->connection->error;
        	}
        }
        
        
        
        
        public function insert_id(){
        	return $this->connection->insert_id;
        }
        
        
        
        
        public function escape_string($string){
        	$string = $this->connection->real_escape_string($string);
        	return $string;
        }
        
        
        
        // sanitizes an array down to the depth of 5 and deletes everything below
        public function sanitize($input, $depth_counter = 1){
            
        	$originale = array('\'', '"', '\\', ';', '<', '>', '[', ']', '{', '}', '|', '$', '%' );
        	// $ersatz = array('', '', '', '', '', '', '', '', '', '', '', '', '', 'oder', 'Dollar', 'ist gleich', 'Prozent');
        	$ersatz = array('', '', ' Backslash ', ' Semicolon ', ' kleiner als ', ' groesser als ', ' eckige Klammer auf ', ' eckige Klammer zu ', ' geschweifte Klammer auf ', ' geschweifte Klammer zu ', ' OR ', ' Dollar ', ' Prozent ');
            
            if(is_array($input)){
            	$output = array();
            	if($depth_counter <= 5){
                	foreach( $input as $key => $element ) {
                	    if(is_array($element)){
                	        $depth_counter++;
                	        $output[$key] = $this->sanitize($element, $depth_counter);
                	    }else{
                	        $element = str_replace($originale, $ersatz, $element);
                    		$output[$key] = $this->escape_string($element);
                	    }
                	}
            	}
            }else{
                $element = str_replace($originale, $ersatz, $input);
                $output = $this->escape_string($element);
            }
            
            // $GLOBALS['console'] .= 'input sanitized <br />';
        	return $output;
        }
		
		
		
        public function replace_strings($input, $originals, $replacements){
            $output = str_replace($originals, $replacements, $input);
        	return $output;
        }
        
        
        
        public function bulk_update($db_table, $array_2D, $where_1, $where_2 = '', $where_3 = '', $except_cols = array()){
            
        	foreach ($array_2D as $rowIndex=>$row){
            
				_var_dump('What?');
                $row = $this->sanitize($row);
                $new = false;
        	    
        	    
                if( (isset($where_1)) && ($where_1 != '') && (array_key_exists($where_1, $row)) ){
                    $query = "
                        SELECT 
                        * 
                        FROM $db_table 
                        WHERE $where_1 LIKE '".$row[$where_1]."'
                        ;
                    ";
                    $result = $this->read($query);
                    if($result == false){
                        $new = true;
                    }
                }else{
                    $new = true;
                }
                
                
        	    $query = '';
                $query .= "
                    ".(($new == true) ? "INSERT INTO" : "UPDATE")." $db_table SET ";
                
                $i = 1;
                foreach ($row as $columnName=>$cell) {
                    
                    if( ($new == true) || (empty($except_cols)) || (!in_array ($columnName, $except_cols)) ){
                        if($i > 1){
                            $query .= ", 
                            "; 
                        }else{
                            $query .= " 
                            "; }
        				$query .= "$columnName = '$row[$columnName]'";
        				$i++;
                    }
    			}
    			
                
                if($new == false){
                    $query .= " 
                        WHERE $where_1 LIKE '".$row[$where_1]."'";
                    
                    if( (isset($where_2)) && ($where_2 != '') ){
                        $query .= " 
                            AND $where_2 LIKE '".$row[$where_2]."' ";
                    }
                    
                    if( (isset($where_3)) && ($where_3 != '') ){
                        $query .= " 
                            AND $where_3 LIKE '".$row[$where_3]."'";
                    }
                }
                
                $query .= ";";
                _var_dump($query);
                
                if( (isset($where_1)) && ($where_1 != '') ){
					$this->write($query);
                }
				
            }
            return true;
        }
        
        
        
        
        public function security_log($event = '', $type = ''){
            
            if($event == ''){
                $event = $this->sanitize($_SERVER['REQUEST_URI']);
            }
            
            $query = "
                INSERT INTO ".$GLOBALS['t_security_log']." 
                (datetime, ip, type, event) VALUES 
                (NOW(), '".$this->sanitize($_SERVER['REMOTE_ADDR'])."', '$type', '$event')
                ;
            ";
            
            $result = $this->write($query);
        }
        
        
        
        
        public function security_check(){
                                                
            $query = "
                SELECT 
                COUNT(id) AS 'errors' 
                FROM ".$GLOBALS['t_security_log']." 
                WHERE ip LIKE '".$this->sanitize($_SERVER['REMOTE_ADDR'])."' 
                AND datetime > DATE_SUB(NOW(),INTERVAL 360 MINUTE) 
                ;
            "; // 1440 = 1 Tag
            // AND ( (type LIKE '404') OR (type LIKE 'error') OR (type LIKE 'lockout') )
            
            $result = $this->read($query);
            
            if($result != NULL){
                $GLOBALS['console'] .= '<br />security_check errors: '.$result[0]['errors'].'<br />';
                
                if($result[0]['errors'] <= $GLOBALS['security_check_limit']){
                    $GLOBALS['security_check'] = true;
                }else{
                    $GLOBALS['security_check'] = false;
                }
            }else{
                $GLOBALS['console'] .= '<br />security_check error: read error<br />';
            }
        }
        
        
        
        
        public function logout(){
            $_SESSION['login_user'] = 0;
            session_unset();
            session_destroy();
        }
                
    
    // model stop ===============================================================================
    
    
    
    
    
    
    // view start ===============================================================================
        
        public function stringtable() {
            $GLOBALS['stringtable']['de']['save'] = 'OK';
        }
        
        
        
        
        public function language($key = '', $language = 'de') {
        	if(isset($GLOBALS['stringtable'][$language][$key])){
                return $GLOBALS['stringtable'][$language][$key];
        	}else{
        	    return $key;
        	}
        }
        
        
        
        
        public function table2form($array_2D, $except_cols = array(), $hidden_cols = array(), $breakpoints = array(), $language = 'de') {
        	if($array_2D){
            	$result = '
            	
            	<style type="text/css">
            	    .table2form_row{ width: 100%; display: block; overflow: hidden; margin-bottom: 16px; }
            	    .table2form_separator{ width: 100%; display: block; float: left; overflow: hidden; height: 1px; margin: 32px 0; border-top: 1px solid; }
            	    .table2form_col{ width: 96%; display: inline-block; float: left; margin: 0 1% 16px 0; }
            	    .table2form_col label{ display: block; width: auto; }
            	    .table2form_col input{ width: 90%; padding: 8px 1%; }
            	    .table2form_col textarea{ width: 90%; padding: 8px 1%; resize: both; }
            	    
            	    @media screen and (max-width: 555px) {
            	        .table2form_col{ width: 96%; }
            	    }
            	</style>
            	
            	<form method="POST">
                	<div class="table2form">';
                	
                	$result .= '
                	<input type="hidden" name="task" value="save" />
                	';
                	
                	$i = 1;
                	foreach ($array_2D as $rowIndex=>$row) {
                		
                		$result .= "
                		<div class=\"table2form_row\">";
                		foreach ($row as $columnName=>$cell) {
                		    if( (empty($except_cols)) || (!in_array ($columnName, $except_cols)) ){
                                if( (empty($hidden_cols)) || (!in_array ($columnName, $hidden_cols)) ){
                    		        if( (!empty($breakpoints)) && (in_array ($columnName, $breakpoints)) ){
                        			    $result .= "
                        			        <div class=\"table2form_separator\"></div>
                        			    ";
                    		        }
                    		        
                    		        // (DateTime::createFromFormat('Y-m-d H:i:s', $cell)
                    			    $result .= "
                    			        <div class=\"table2form_col\">
                        			        <label>".$this->language($columnName,$language)." <br />";
											
											if(DateTime::createFromFormat('Y-m-d', $cell)){
												 $result .= "<input type=\"date\" name=\"input_rows[$i][$columnName]\" value=\"$cell\" />";
											}else{
												$result .= "<textarea name=\"input_rows[$i][$columnName]\">$cell</textarea>";
											}
                        			        
									$result .= "
											</label>
                    			        </div>
                    			    ";
                		        }else{
                    			    $result .= "
                        			    <input type=\"hidden\" name=\"input_rows[$i][$columnName]\" value=\"$cell\" />
                    			    ";
                    		    }
                		    }
                		}
                		$result .= '</div><br /><br />';
                		$i++;
                	}
                	
                	$result .= '<input type="submit" value="'.$this->language('save',$language).'"></div>';
                	
            	$result .= '</form>';
                
            	return $result;
                
        	}else{
        	    return 'null';
        	}
        }
        
        
        
        
        public function table2form_table($array_2D, $except_cols = array(), $max = 0, $link = '', $key = '') {
        	if($array_2D){
            	$result = '
            	<form method="POST"'.( ($link != '') ? ' action="'.$link.'"' : '').'>
                	<table border="1" cellspacing="0" cellpadding="4">';
                	
                	$i = 1;
                	foreach ($array_2D as $rowIndex=>$row) {
                		if($rowIndex == 0) {
                			$result .= "
                			<thead><tr>";
                			foreach ($row as $columnName=>$cell) {
                			    if( (empty($except_cols)) || (!in_array ($columnName, $except_cols)) ){
                				    $result .= "<td>$columnName</td>";
                			    }
                			}
                			$result .= "</tr></thead>";
                		}
                		
                		$result .= "
                		<tr>";
                		foreach ($row as $columnName=>$cell) {
                		    if( (empty($except_cols)) || (!in_array ($columnName, $except_cols)) ){
                			    $result .= "<td><input type=\"text\" name=\"input_rows[$i][$columnName]\" value=\"$cell\" /></td>";
                		    }
                		}
                		$result .= '</td></tr>';
                		$i++;
                	}
                	
                	$result .= '</table>';
                	
                	$result .= '<br /><input type="submit" value="OK"><br />';
                	
            	$result .= '</form>';
                
            	return $result;
                
        	}else{
        	    return 'null';
        	}
        }
        
        
        
        
        public function table2table_table($array_2D, $except_cols = array(), $max = 0, $link = '', $key = '') {
        	$result = '<table border="1" cellspacing="0" cellpadding="4">';
        	
        	foreach ($array_2D as $rowIndex=>$row) {
        		if($rowIndex == 0) {
        			$result .= "
        			<thead><tr>";
        			
            		if($link != '') {
            		    $result .= '<td></td>';
            		}
        			
        			$i = 1;
        			foreach ($row as $columnName=>$cell) {
                		if( ((empty($except_cols)) || (!in_array ($columnName, $except_cols))) && (($max == 0) || ($i <= $max)) ){
        				    $result .= "<td>$columnName</td>";
                		}
                		$i++;
        			}
        			$result .= "</tr></thead>";
        		}
        		
        		$result .= "
        		<tr>";
        		
        		if($link != '') {
        		    $result .= '<td>';
        			$result .= '
        			<a href="'.$link.$row[$key].'" target="_blank">Edit</a>';
        		    $result .= '</td>';
        		}
        		
        		$i = 1;
        		foreach ($row as $columnName=>$cell) {
                	if( ((empty($except_cols)) || (!in_array ($columnName, $except_cols))) && (($max == 0) || ($i <= $max)) ){
        			    $result .= "<td>$cell</td>";
                	}
                	$i++;
        		}
        		
        		$result .= '</tr>';
        		
        	}
        	$result .= '</table>';
        	return $result;
        }
        
        
        
        
        public function table2text($array_2D, $except_cols = array()) {
        	if($array_2D){
            	$result = '';
                	
                	$i = 1;
                	foreach ($array_2D as $rowIndex=>$row) {
                		foreach ($row as $columnName=>$cell) {
                		    if( (empty($except_cols)) || (!in_array ($columnName, $except_cols)) ){
                			    $result .= $this->language($columnName).": $cell \n";
                		    }
                		}
                	    $result .= "\n";
                		$i++;
                	}
                	
            	return $result;
                
        	}else{
        	    return 'null';
        	}
        }
        
        
        
        
        public function mail_2_self($data2text = '[keine Daten vorhanden]') {
            
            $query = "
                SELECT email from ".$GLOBALS['t_users']." WHERE id = '".(int)$_SESSION['login_user']."' LIMIT 1;
            ";
            $result = $this->read($query);
            
            if(!empty($result)){
                    
                // $to = $result[0]['email'];
                $subject = 'Ihre Daten';
                $message = 'Hallo. 

Sie haben eine Kopie Ihrer Eingaben angefordert: 

'.$data2text.'

Falls Sie das nicht waren, schreiben Sie bitte eine E-Mail an ...@....de damit wir das überprüfen können.

Viele Grüße,
IT x AG
';
                $headers =  'MIME-Version: 1.0' . "\r\n" .
                            'Content-type: text/plain; charset=utf-8' . "\r\n" .
                            'From: ...@....de' . "\r\n" .
                            'Reply-To: ...@....de' . "\r\n";
                    
                @mail($to, $subject, $message, $headers);
        	    
        	    $GLOBALS['output'] .= '<br />MAIL GESENDET<br /><br />';
            }
            
        }
        
        
        
        
        public function js_modify_input() {
            
        	$result = "
            	<script type=\"text/javascript\">
            	    
                    function ready(fn) {
                      if (document.readyState != 'loading'){
                        fn();
                      } else {
                        document.addEventListener('DOMContentLoaded', fn);
                      }
                    }
                    
                    
                    ready(function() {
                    
                        // ^= start; *= any position; $= end <----------------------------------------- selectors !!!
                        document.querySelector(\"input[name*='input_name'\").disabled = true;
                        
                        document.querySelector(\"input[name*='input_name_1'\").onblur = function(){
                            document.querySelector(\"input[name*='input_name_2'\").value = (
                                parseInt(this.value, 10)
                                + 
                                parseInt(document.querySelector(\"input[name*='input_name_1'\").value, 10)
                            );
                        };
                    });
            	    
            	</script>
        	";
        	
        	return $result;
        }
        
        
        
        
        public function js() {
            
        	$result = "
            	<script type=\"text/javascript\">
                    
                    function mail_to_self(){
                        // alert('Button Clicked');
                        document.querySelector(\"input[name*='mail_self'\").value = 1;
                    }
                    
            	</script>
        	";
        	
        	return $result;
        }
        
        
        
        
        public function _var_dump($var){
            ob_start();
            var_dump($var);
            $GLOBALS['console'] .= '<pre>'.ob_get_clean().'</pre>';
        }
        
        
        
        
        public function header() {
            return '';
        }
        
        
        
        
        public function footer() {
            return '
                <div style="margin-top: 32px; text-align: center; font-size: 12px !important;">
                    <a style="font-size: 12px !important;" href="'.$GLOBALS['url_contact'].'">'.$this->language('Kontakt',$GLOBALS['language']).'</a> &nbsp; 
                    <a style="font-size: 12px !important;" href="'.$GLOBALS['url_dataprotection'].'">'.$this->language('Datenschutz',$GLOBALS['language']).'</a> &nbsp; 
                    <a style="font-size: 12px !important;" href="'.$GLOBALS['url_imprint'].'">'.$this->language('Impressum',$GLOBALS['language']).'</a>
                </div>
            ';
        }
        
		
        
        
        public function output_html(){
            include($GLOBALS['template_path'].'index.php');
        }
        
    // view stop ================================================================================
    
    
    
    
    
    
    // controller start =========================================================================
        
        public function execute(){
            
            session_start();
            
            
        	$this->connection = new mysqli($GLOBALS['host'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['database']);
        	if ($this->connection->connect_errno) {
        		 $GLOBALS['console'] .= 'Failed to connect to MySQL: ' . $this->connection->connect_error;
        	}else{
        	    $this->connection->set_charset("utf8");
        	}
        	
        	
            $this->security_check();
            
            
            if( (isset($GLOBALS['security_check'])) && ($GLOBALS['security_check'] == true) ){
            
                $GLOBALS['url_current'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$this->sanitize($_SERVER['REQUEST_URI']); // log 404 or attacks
                
                
                $_REQUEST = $this->sanitize($_REQUEST);
                
                
                $this->stringtable();
                
                
                if(!isset($_REQUEST['task'])){
                    $_REQUEST['task'] = '';
                }
                
                
				if( (isset($_REQUEST['module'])) && (!file_exists('modules/'.$_REQUEST['module'].'/index.php')) ){
					
					$GLOBALS['output'] .= '404 <br />';
					$this->security_log( $GLOBALS['url_current'], '404');
					
				}
				
				
				switch($_REQUEST['task']){
					
					case 'original':
						$GLOBALS['template_path'] = 'assets/templates/startbootstrap-resume-gh-pages-original/';
						break;
						
					default:
						
						include('modules/login/index.php');
						
						include('modules/articles/index.php');
						
						$GLOBALS['login']->form();
				}
                
                
            }else{
                $this->security_log( '', 'lockout');
                $GLOBALS['output'] .= "Zu viele Anfragen. Bitte wenden Sie sich direkt an uns: <a href=\"".$GLOBALS['url_contact']."\" target=\"_blank\">Kontakt</a><br />";
            }
            
            
            
            // $GLOBALS['output'] .= $this->header();
            
            // $GLOBALS['output'] .= $this->footer();
			
			
            if($GLOBALS['error_reporting'] == true){
               $GLOBALS['output'] .= '<br /><hr /><br />'.$GLOBALS['console'];
            }
            
            $this->output_html();
            
            
            
            session_write_close();
        }
        
        
        
        
        public function __construct() {
            $GLOBALS['console'] .= '<br />app initialized<br />';
        }
        
    // controller stop ==========================================================================
    
    
} // class app




$GLOBALS['app'] = new app();

function _var_dump($var = NULL){
    $GLOBALS['app']->_var_dump($var);
}

$GLOBALS['app']->execute();



