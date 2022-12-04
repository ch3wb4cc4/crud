<?php



class module{
	
	// model start ===========================================================================
	
		public function read($id = 0){
			$query = "
				SELECT 
				a.id, 
				a.published, 
				a.author, 
				a.headline, 
				a.teaser, 
				a.content 
				FROM ".$GLOBALS['t_articles']." a ".
				($id > 0 ? "WHERE a.id = '".(int)$id."' " : "").
				"LIMIT 3
				;
			";
			$result = $GLOBALS['app']->read($query);
			return $result;
		}
		
	// model stop ===========================================================================
	
	
	
	
	// view start ============================================================================
	
        public function stringtable() {
            $GLOBALS['stringtable']['de']['module'] = 'module';
        }
		
		
		
		public function list_articles($array_2D) {
			$result = '
				<br /><hr class="m-0" /><br />
				<h2 class="mb-5"  id="articles">Articles</h2>
			';
        	foreach ($array_2D as $row) {
        		$result .= '
					<div class="d-flex flex-column flex-md-row justify-content-between mb-5">
						<div class="flex-grow-1">
							<h3 class="mb-0">'.$row['headline'].'</h3>
							<div class="subheading mb-3">'.$row['author'].'</div>
								<p>'.
									(count($array_2D) == 1 ? $row['content'] : $row['teaser'].' &nbsp; <a href="?id='.$row['id'].'">>>></a>').
								'</p>
							</div>
						<div class="flex-shrink-0"><span class="text-primary">'.$row['published'].'</span></div>
					</div>
				';
        	}
        	return $result;
        }
		
		
		
		public function edit_articles($array_2D) {
			$result = '
				<br /><hr class="m-0" /><br />
				<h2 class="mb-5"  id="articles">Edit articles</h2>
			';
        	return $result;
        }
		
	// view stop ============================================================================
	
	
	
	
	// controller start =========================================================================
	
		// public function execute(){}

		public function __construct() {
			$GLOBALS['console'] .= '<br />module_articles initialized<br />';
		}

    // controller stop ==========================================================================
		
} // class module




$GLOBALS['articles'] = new module();
// $GLOBALS['articles']->execute();



