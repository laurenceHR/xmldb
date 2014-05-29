<?php
/******************************************************************
** XMLDB Class.
** Usa tus archivos XML como base de datos, un xml para cada tabla
** te evita tener que trabajar con conexiones SQL y está destinado
** para usos simples de pequeñas BD
** CopyRight: laurenceHR - 2011
*********************************************************************/

class xmldb{
	var $xml;
	var $xmldir;
	var $headers;
	var $results;
	var $msg;
	var $error = false;
	
	function xmldb(){
		$this->xml = new DomDocument;
		$this->xml->formatOutput = true;
	}
	
	function loadXML($dir){
		$this->xmldir = $dir;
		if(@$this->xml->Load($dir) ){
			$this->msg = "XML Leido Correctamente";
			$this->error = false;
			return true;
		}else{
			$this->msg = "Error al leer XML";
			$this->error = true;
			return false;
		}
	}
	
	function tableHTML(){
		return $this->xml->saveXML()."\n";
	}
	
	function countRows(){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');//->item(0)->getElementsByTagName('td');
		$this->msg = "Consulta Exitosa";
		return $tbody->length;
	}
	
	function returnHeads(){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$thead = $this->xml->getElementsByTagName('thead')->item(0)->getElementsByTagName('tr')->item(0)->getElementsByTagName('th');
		$fields = Array();
		$i = 0;
		while( $th = $thead->item($i++) ){
			$fields[] = $th->nodeValue;
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
		
	}
	
	/******************************
	****** SELECT functions *******
	******************************/
	
	/*
	**	SELECT * FROM xml
	*/
	
	function selectAll(){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$fields = Array();
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $tbody->item($i++) ){
			$td = $tr->getElementsByTagName('td');
			$j=0;
			while($tds = $td->item($j++) ){
				$td_at = $tds->getAttribute('th');
				$atds[$td_at] = $tds->nodeValue;
			}
			$fields[] = $atds;
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
	}
	
	/*
	** 	SELECT a,b,c FROM xml // $atr[] = [a,b,c]
	*/
	
	function selectAtr($atr){//$atr is Array();
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$fields = Array();
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $tbody->item($i++) ){
			$td = $tr->getElementsByTagName('td');
			$j=0;
			while($tds = $td->item($j++) ){
				$td_at = $tds->getAttribute('th');
				$atds[$td_at] = $tds->nodeValue;
			}
			foreach($atr as $atr_txt){
					$rows[$atr_txt] = $atds[$atr_txt];
			}
			$fields[] = $rows;
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
	}
	
	/*
	** 	SELECT * FROM xml WHERE key = value 
	*/
	
	function selectAllWhereIs($key,$value){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$fields = Array();
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $tbody->item($i++) ){
			$td = $tr->getElementsByTagName('td');
			$j=0;
			while($tds = $td->item($j++) ){
				$td_at = $tds->getAttribute('th');
				$atds[$td_at] = $tds->nodeValue;
			}
			if($atds[$key] == $value){
				$fields[] = $atds;
			}
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
	}
	
	/*
	** 	SELECT a,b,c FROM xml WHERE key = value // $atr[] = [a,b,c]
	*/
	
	function selectAtrWhereIs($atr,$key,$value){//$atr is Array();
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$fields = Array();
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $tbody->item($i++) ){
			$td = $tr->getElementsByTagName('td');
			$j=0;
			while($tds = $td->item($j++) ){
				$td_at = $tds->getAttribute('th');
				$atds[$td_at] = $tds->nodeValue;
			}
			if($atds[$key] == $value){
				foreach($atr as $atr_txt){
					$rows[$atr_txt] = $atds[$atr_txt];
				}
				$fields[] = $rows;
			}
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
	}
	
	function selectRowN($id){ // 06/05/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$fields = Array();
		$tbody = $this->xml->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');	
				
		$tr = $tbody->item($id);
		$td = $tr->getElementsByTagName('td');
		$j=0;
		while($tds = $td->item($j++) ){
			$td_at = $tds->getAttribute('th');
			$atds[$td_at] = $tds->nodeValue;
		}
		$fields[] = $atds;
		//$fields = $atds;
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return $fields;
	}
	/******************************
	****** INSERT functions *******
	******************************/
	
	function insertInto($ins){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$tr = $xml->createElement('tr');
		foreach($ins as $ikey => $ivalue){
			//echo $ikey." => ".$ivalue."<br />";
			
			$td = $xml ->createElement('td');	
			$td_atr = $xml -> createAttribute('th');
			$td_atr_txt = $xml -> createTextNode($ikey);
			
			$td_atr ->appendChild($td_atr_txt);
			$td -> appendChild($td_atr);
			
			$td_txt = $xml -> createTextNode($ivalue);
			$td ->appendChild($td_txt);
			
			
			$tr -> appendChild($td);
		}
			$tbody -> appendChild($tr);
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Fila Insertada Correctamente";
	}
	
	/******************************
	****** CREATE functions *******
	******************************/
	
	function createTable($dir,$ins){ // 04/06/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$this->xmldir = $dir;
		$xml = $this->xml;
		$table = $xml->createElement('table');
		
		$thead = $xml->createElement('thead');
		$tbody = $xml->createElement('tbody');
		
		$tr = $xml->createElement('tr');
		
		foreach($ins as $ivalue){
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
		
		@$xml->save($this->xmldir);
		$this->msg = "Tabla Creada Correctamente";
	}
	
	/******************************
	****** UPDATE functions *******
	******************************/
	
	function updateRowN($id,$ins){ // 06/05/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$itr = $tbody->getElementsByTagName('tr')->item($id);
		$tr = $xml->createElement('tr');
		foreach($ins as $ikey => $ivalue){
			//echo $ikey." => ".$ivalue."<br />";
			
			$td = $xml ->createElement('td');	
			$td_atr = $xml -> createAttribute('th');
			$td_atr_txt = $xml -> createTextNode($ikey);
			
			$td_atr ->appendChild($td_atr_txt);
			$td -> appendChild($td_atr);
			
			$td_txt = $xml -> createTextNode($ivalue);
			$td ->appendChild($td_txt);
			
			
			$tr -> appendChild($td);
		}
			$itr->parentNode->replaceChild($tr,$itr); 
			//$tbody -> appendChild($tr);
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Fila Actualizada Correctamente";
	
	}
	
	/******************************
	****** DELETE functions *******
	******************************/
	
	function deleteRowN($id){
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$tr = $tbody->getElementsByTagName('tr')->item($id);
		$tbody->removeChild($tr);
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Fila Borrada Correctamente";
	}
	
}

?>