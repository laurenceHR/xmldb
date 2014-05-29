<?php
/******************************************************************
** XMLDB Class.
** Usa tus archivos XML como base de datos, un xml para cada tabla
** te evita tener que trabajar con conexiones SQL y está destinado
** para usos simples de pequeñas BD
** CopyRight: @laurenceHR - 2011
*********************************************************************/

class xmldb_dbms{
	var $dir;
	private $msg;
	
	function xmldb_dbms(){
		
	}
	function loadXML($dir){
		$this->dir = $dir;
	}
	
	
	function gtMSG($i = null){
		if( $i != null && isset($this->msg[$i]) ){ return $this->msg[$i];}
		return $this->msg;
	}
	/******************************
	****** CREATE functions *******
	******************************/
	
	function createTable($tname,$ins = Array()){ // 04/06/2011
		$this->msg = ""; // 17/09/2013
		$tdir = $this->dir.$tname.".xml";
		if( is_file($tdir) ){
			//$ret[] = "TABLA ".$tname." YA EXISTE";
			//return $ret;				
			$this->msg = "TABLA ".$tname." YA EXISTE";
			return false;
		}else{
			$xml = new DomDocument;
			$xml->formatOutput = true;
			
			$table = $xml->createElement('table');
			
			$thead = $xml->createElement('thead');
			$tbody = $xml->createElement('tbody');
			
			$tr = $xml->createElement('tr');
		
			foreach($ins as $ivalue){
				if($ivalue == ""){
					$ret[] = "Column Error";
					return $ret;
				}
				
				$th = $xml ->createElement('th');	
				$th_txt = $xml -> createTextNode($ivalue);
				$th -> appendChild($th_txt);
				$tr -> appendChild($th);
			}
			
			$thead -> appendChild($tr);
			$table -> appendChild($thead);
			
			$tbody_txt = $xml -> createTextNode("");//fix tbody bug
			$tbody -> appendChild($tbody_txt);
			$table -> appendChild($tbody);
			
			$xml -> appendChild($table);
			$xml->formatOutput = true;
			
			@$xml->save($this->dir.$tname.".xml");
			
			//$ret[] = "TABLA ".$tname." CREADA";
			//return $ret;
			$this->msg = "TABLA ".$tname." CREADA";
			return true;
		}
		//$this->msg = "Tabla Creada Correctamente";
	}
	
	/******************************
	****** DROP functions *******
	******************************/
	
	function dropTable($tname){
		$tdir = $this->dir.$tname.".xml";
		$this->msg = ""; // 17/09/2013
		//echo $tdir."<br />";
		if( is_file($tdir) ){
				unlink($tdir);
				//$ret[] = "TABLA ".$tname." ELIMINADA";
				//return $ret;
				$this->msg = "TABLA ".$tname." ELIMINADA";
				return true;				
			}else{
				//$ret[] = "TABLA ".$tname." NO EXISTE";
				//return $ret;
				$this->msg = "TABLA ".$tname." NO EXISTE";
				return false;
		}
	}
	
	/*
	**
	*/
	
	function noQuotes($str){
		$strs = explode('"',$str);
		//print_r($atrr);
		if(count($strs)>1){
			return $strs[1];
		}else if(count($strs)==1){
			return $strs[0];
		}
	}
	
	function depur($str){
		$str = $this->noQuotes($str);
		$str = trim($str);
		return $str;
	}
	
	/*
	**  QUERYS FUNCTIONS
	*/
	
	function executeQuery($query){ // 25/06/2011
		//echo "[".$query."]<br />";
		$querys = explode(" ",$query);
		$this->msg = ""; // 17/09/2013
		///// SELECT /////
		if( strtoupper($querys[0]) == "SELECT"){ 
		
			if(@strtoupper($querys[4]) == "WHERE"){
				// SELECT * FROM table WHERE abc=xd
				$whr = true;
				$wheres = explode("=",$querys[5]);
				$key 	= $this->depur($wheres[0]);
				$value 	= $this->depur($wheres[1]);
			}else{$whr = false;}
			
			if($querys[1] == "*" || strtoupper($querys[1]) == "ALL"){ // SELECT ALL
				// SELECT * FROM table
				if(strtoupper($querys[2]) == "FROM"){
					//echo "FROM<br />";
					$table = $querys[3];
					$table = $this->depur($table);
					$tblXML = new xmldb_table();
					$drtbl = $this->dir.$table.".xml";
					if($tblXML -> loadXML($drtbl)){//echo $tblXML->msg;
						if($whr){
							return $tblXML->selectAllWhereIs($key,$value);
						}else{
							return $tblXML->selectAll();
						}
					}else{
						//$ret[] = "ERROR EN TABLA";
						$this->msg = "ERROR AL CARGAR TABLA : ".$table;
						return false;
					}
				}
			}else{ // SELECT COLS
				//SELECT col1,col2 FROM table
				$atrs = explode(",",$querys[1]);
				foreach($atrs as $atr){
					$atrr = explode('"',$atr);
					$cols[] = $this->depur($atr);

				}
				//print_r($cols);
				$table = $querys[3];
				$table = $this->depur($table);
				$tblXML = new xmldb_table();
				$drtbl = $this->dir.$table.".xml";
				if($tblXML -> loadXML($drtbl)){//echo $tblXML->msg;
					if($whr){
						return $tblXML->selectAtrWhereIs($cols,$key,$value);
					}else{
						return $tblXML->selectAtr($cols);
					}
				}else{
					//$ret[] = "ERROR EN TABLA";
					//return $ret;
					$this->msg = "ERROR AL CARGAR TABLA : ".$table;
					return false;
				}
			}
			//return $this->selectAtr($atr);
			
		}///// CREATE /////
		else if(strtoupper($querys[0]) == "CREATE"){ 
			// CREATE TABLE
			if(strtoupper($querys[1]) == "TABLE"){
				// CREATE TABLE table(val1,val2,val3)

				$table = explode("(",$querys[2]);
				$tname = $table[0];
				$tname = $this->depur($tname);
		
				$atrs = explode(")",$table[1]);
				$atrbs = $atrs[0];
				$atrb = explode(",",$atrbs);
				if($atrb[0] != ""){
					foreach($atrb as $atri){$atr[] = $this->noQuotes($atri);}
				}
				
				return $this->createTable($tname,$atr);
				
			} // CREATE DATABASE
			else if($querys[1] == "DATABASE"){
			
			}
			
		}///// DROP /////
		else if( strtoupper($querys[0]) == "DROP"){ 
			// DROP TABLE 
			if( $querys[1] == "TABLE" ){
				$tname = $querys[2];
				$tname = $this->depur($tname);
				return $this->dropTable($tname);
				
			} // DROP DATABASE
			else if( strtoupper($querys[1]) == "DATABASE" ){
			
			}
			
		}///// INSERT INTO /////
		else if($querys[0] == "INSERT" && $querys[1] == "INTO"){
		
		}
		
		//$ret[] = "ERROR EN CONSULTA";
		//return $ret;
		$this->msg = "ERROR EN CONSULTA";
		return false;
	}
	
	function executeAll($txt){
		$querys = explode("\n",$txt);
		foreach($querys as $query){
			$retu = $this->executeQuery($query);
			$ret[] = $retu;
		}
		return $ret;
	}
}

?>