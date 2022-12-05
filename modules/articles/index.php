<?php



class articles{
	
	// model start ===========================================================================
		
		public function create(){
			$query = "
				INSERT 
				INTO ".$GLOBALS['t_articles']."
				SET 
				headline = 'NEW', 
				published = NOW()
				;
			";
			$GLOBALS['app']->write($query);
			return;
		}
		
		
		
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
				($id > 0 ? "WHERE a.id = '".(int)$id."' " : "")."
				ORDER BY a.published DESC
				;
			";
			$result = $GLOBALS['app']->read($query);
			return $result;
		}
		
		
		
		public function update(){
			if((isset($_REQUEST['input_rows'])) && (!empty($_REQUEST['input_rows']))){
				$GLOBALS['app']->bulk_update($GLOBALS['t_articles'], $_REQUEST['input_rows'], 'id', '', '', ($except_cols = array('id')) );
			}
			return;
		}
		
		
		
		public function delete($id = 0){
			if((int)$id > 0){
				$query = "
					DELETE 
					FROM ".$GLOBALS['t_articles']."
					WHERE id = '".(int)$id."'
					;
				";
				$GLOBALS['app']->write($query);
			}
			return;
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

			if( (isset($_SESSION['login_user'])) && ($_SESSION['login_user'] > 0) && (count($array_2D) > 1) ){
				$result .= '<p><a href="?task=create" title="create">&#128240;</a><br /><br /></p>';
			}

        	foreach ($array_2D as $row) {
        		$result .= '
					<div class="d-flex flex-column flex-md-row justify-content-between mb-5">
						<div class="flex-grow-1">
							<h3 class="mb-0">'.$row['headline'].'</h3>
							<div class="subheading mb-3">'.$row['author'].'</div>
								<p>';
								
								if(count($array_2D) == 1){
									$result .= $row['content'];
								}else{
									$result .= $row['teaser'].'<br /><a href="?id='.$row['id'].'" title="read">&#128270;</a>';
									if((isset($_SESSION['login_user'])) && ($_SESSION['login_user'] > 0)){
										$result .= ' &nbsp; <a href="?task=delete&id='.$row['id'].'" title="delete">&#128465;</a>';
									}
								}
								
								
				$result .= '</p>
							</div>
						<div class="flex-shrink-0"><span class="text-primary">'.date_format(date_create($row['published']),"d.m.Y").'</span></div>
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
			
			$result .= $GLOBALS['app']->table2form( $array_2D, $except_cols = array(), $hidden_cols = array('id'), $breakpoints = array() ).'<br />';
			
        	return $result;
        }
		
	// view stop ============================================================================
	
	
	
	
	// controller start =========================================================================
	
		public function execute(){
			
			if( (isset($_SESSION['login_user'])) && ($_SESSION['login_user'] > 0) && ($GLOBALS['login']->permission(1)) ){
				
				switch($_REQUEST['task']){
				
				case 'create':
					$this->create();
					break;
				
				case 'update':
					$this->update();
					break;
				
				case 'delete':
					if( (isset($_REQUEST['id'])) && ($_REQUEST['id'] > 0) ){
						$this->delete($_REQUEST['id']);
					}
					$_REQUEST['id'] = 0;
					break;
				
				}
				
			}
			
			if(!isset($_REQUEST['id'])){ $_REQUEST['id'] = 0; }
			$GLOBALS['articles2display'] = $this->read($_REQUEST['id']);
			$GLOBALS['output'] .= $this->list_articles($GLOBALS['articles2display']);
			
			if( (isset($_SESSION['login_user'])) && ($_SESSION['login_user'] > 0) && ($GLOBALS['login']->permission(1)) ){
				$GLOBALS['output'] .= $this->edit_articles($GLOBALS['articles2display']);
			}
			

		}
		
		
		
		public function __construct() {
			$GLOBALS['console'] .= '<br />module_articles initialized<br />';
		}

    // controller stop ==========================================================================
		
} // class articles




$GLOBALS['articles'] = new articles();
$GLOBALS['articles']->execute();



