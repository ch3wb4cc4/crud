<?php

// config =================================================================================

	// requires $GLOBALS['t_module']
	
// config =================================================================================




class login{
	
	// model start ===========================================================================
	
	        public function permission($permission = 0){
            $query = "
                SELECT 
                permission 
                FROM ".$GLOBALS['t_permissions']." 
                WHERE user = '".(int)$_SESSION['login_user']."' 
                AND permission = '".(int)$permission."' 
                LIMIT 1
                ;
            ";
            
            $result = $GLOBALS['app']->read($query);
            
            if(!empty($result)){
                // $GLOBALS['console'] .= '<br />user has permission '.$result[0]['permission'].'<br />';
                return true;
            }else{
                // $GLOBALS['console'] .= '<br />user does not have permission '.$permission.'<br />';
                return false;
            }
        }
		
		
		
		
        public function login(){
                
            if( (isset($_REQUEST['token'])) && (isset($_REQUEST['email'])) ){
				
				$_REQUEST['email'] = urldecode($_REQUEST['email']);
                
				$query = "
                    SELECT id from ".$GLOBALS['t_users']." WHERE token LIKE '".$_REQUEST['token']."' AND email LIKE '".$_REQUEST['email']."';
                ";
                $result = $GLOBALS['app']->read($query);
                
                if(!empty($result)){
                    $_SESSION['login_user'] = $result[0]['id'];
					// $GLOBALS['output'] .= 'login success<br />';
                    $GLOBALS['app']->security_log( 'login: '.((isset($_REQUEST['email'])) ? 'email = '.$_REQUEST['email'].'; ' : '').((isset($_REQUEST['token'])) ? 'token = '.$_REQUEST['token'].'; ' : ''), 'login');
                }else{
                    $_SESSION['login_user'] = '';
					// $GLOBALS['output'] .= 'login fail<br />';
                    $GLOBALS['app']->security_log( 'login: '.((isset($_REQUEST['email'])) ? 'email = '.$_REQUEST['email'].'; ' : '').((isset($_REQUEST['token'])) ? 'token = '.$_REQUEST['token'].'; ' : ''), 'error');
                }
            }
            
        }
		
		
		
		
        public function two_factor(){
                
            if( (isset($_REQUEST['email'])) && ($_REQUEST['email'] != '') ){
                $query = "
                    SELECT email, token from ".$GLOBALS['t_users']." WHERE email LIKE '".trim($_REQUEST['email'])."' LIMIT 1;
                ";
                $result = $GLOBALS['app']->read($query);
				
                // $GLOBALS['app']->_var_dump($query);
                
                if(!empty($result)){
                    
                $new_token = md5(md5(random_bytes(16)));
                $query = "
                    UPDATE ".$GLOBALS['t_users']." SET 
                    token = '$new_token' 
                    WHERE email LIKE '".$result[0]['email']."'
                    ;
                ";
                $result2 = $GLOBALS['app']->write($query);
                
                $to = $result[0]['email'];
                $subject = 'Authentication';
                $message = 'Login: 
'.$GLOBALS['url_root'].'?module=login&task=login&email='.urlencode($result[0]['email']).'&token='.$new_token.'#login';
                    $headers =  'MIME-Version: 1.0' . "\r\n" .
                                'Content-type: text/plain; charset=utf-8'."\r\n" .
                                'From: '.$GLOBALS['system_email']."\r\n" .
                                'Reply-To: '.$GLOBALS['system_email']."\r\n";
                    
                    mail($to, $subject, $message, $headers);
                    
                    
                    /*if( mail($to, $subject, $message, $headers) ){
                        $GLOBALS['output'] .= '<br />Mail gesendet<br />';
                    }else{
                        $GLOBALS['output'] .= '<br />Senden fehlgeschlagen. Bitte Adresse überprüfen;<br />';
                    }*/
                    
                    $GLOBALS['app']->security_log( 'two_factor: '.((isset($_REQUEST['email'])) ? 'email = '.$_REQUEST['email'].'; ' : ''), 'two_factor');
                    
                }else{
                    $GLOBALS['app']->security_log( 'two_factor: '.((isset($_REQUEST['email'])) ? 'email = '.$_REQUEST['email'].'; ' : ''), 'error');
                }
                
            }
            
            
            $GLOBALS['output'] .= 'Es wurde ein Link an die eingegebene Adresse gesendet, sofern diese korrekt war.<br />';
        }
		
		
		
		
        public function logout(){
            $_SESSION['login_user'] = 0;
            session_unset();
            session_destroy();
        }
		
	// model stop ===========================================================================
	
	
	
	
	// view start ============================================================================
	
        public function stringtable() {
            $GLOBALS['stringtable']['de']['module'] = 'module';
        }
        
        
        
        public function login_header() {
            return "
                <br /><hr class=\"m-0\" /><br />
				<h2 class=\"mb-5\"  id=\"login\">Login</h2>
            ";
        }
        
		
        
        public function login_form() {
            return "
                        <form method=\"POST\" action=\"".$GLOBALS['url_root']."#login\">
                            <input type=\"hidden\" name=\"module\" value=\"login\">
                            <input type=\"hidden\" name=\"task\" value=\"two_factor\">
                            <input type=\"text\" name=\"email\" placeholder=\"E-Mail-Adresse\" value=\"".( isset($_SESSION['email']) ? $_SESSION['email'] : '')."\" />
                            <input type=\"submit\" value=\"OK\">
                        </form>
                    <br />
            ";
        }
        
		
        
        public function logout_form() {
            return "
                        <form method=\"POST\">
                            <input type=\"hidden\" name=\"module\" value=\"login\">
                            <input type=\"hidden\" name=\"task\" value=\"logout\">
                            <input type=\"submit\" value=\"Logout\">
                        </form>
                    <br />
            ";
        }
        
		
        
        public function form() {
			$GLOBALS['output'] .= $this->login_header();
			if( (isset($_SESSION['login_user'])) && ($_SESSION['login_user'] > 0) ){
				$GLOBALS['output'] .= $this->logout_form();
			}else{
				$GLOBALS['output'] .= $this->login_form();
			}
        }
		
	// view stop ============================================================================
	
	
	
	
	// controller start =========================================================================
	
		public function execute(){
			if( (isset($_REQUEST['module'] )) && ($_REQUEST['module'] == 'login') ){
				switch($_REQUEST['task']){
				
					case 'two_factor':
						$this->two_factor();
						break;
				
					case 'login':
						$this->login();
						break;
				
					case 'logout':
						$this->logout();
						break;
						
				}
			}
		}

		public function __construct() {
			$GLOBALS['console'] .= '<br />module_login initialized<br />';
		}

    // controller stop ==========================================================================
		
} // class login




$GLOBALS['login'] = new login();
$GLOBALS['login']->execute();



