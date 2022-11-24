<?php

// config =================================================================================

	// requires $GLOBALS['t_module']
	
// config =================================================================================




class module{
	
	// model start ===========================================================================
	
	// model stop ===========================================================================
	
	
	
	
	// view start ============================================================================
	
        public function stringtable() {
            $GLOBALS['stringtable']['de']['module'] = 'module';
        }
		
	// view stop ============================================================================
	
	
	
	
	// controller start =========================================================================
	
		public function execute(){

		}

		public function __construct() {
			$GLOBALS['console'] .= '<br />module_login initialized<br />';
		}

    // controller stop ==========================================================================
		
} // class module




$GLOBALS['module'] = new module();
$GLOBALS['module']->execute();



