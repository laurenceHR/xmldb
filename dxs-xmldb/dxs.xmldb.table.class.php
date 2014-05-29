<?php
/******************************************************************
** XMLDB Concept.
** Usa tus archivos XML como base de datos, un xml para cada tabla
** te evita tener que trabajar con conexiones SQL y está destinado
** para usos simples de pequeñas BD
** CopyRight: @laurenceHR - 2011
*********************************************************************/

class xmldb_table{
	var $xml;
	var $xmldir;
	var $headers;
	var $results;
	var $msg;
	var $error = false;
	
	function xmldb_table(){
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
	**	SELECT * FROM [table]
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
	** 	SELECT [a,b,c] FROM [table] // $atr[] = [a,b,c]
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
	** 	SELECT * FROM [table] WHERE [$key] = [$value]
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
	** 	SELECT [a,b,c] FROM [table] WHERE [$key] = [$value] // $atr[] = [a,b,c]
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
	
	function selectRowN($id){ // 05/06/2011
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
	
	function getRowNWhereIs($key,$value){ // 26/01/2012
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
				return $i-1;
			}
		}
		$results = $fields;
		$this->msg = "Consulta Exitosa";
		return false;
	}
	
	/******************************
	****** INSERT functions *******
	******************************/
	
	function InsertInto($ins){
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
		if( @$xml->save($this->xmldir) ){
			$this->msg = "Fila Insertada Correctamente";
			return true;
		}else{
			$this->msg = "Error Insertando Fila";
			return false;
		}
	}
	
	/******************************
	****** UPDATE functions *******
	******************************/
	
	function updateRowN($id,$ins){ // 05/06/2011
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
	
	function updateRowWhereIs(){ // 26/01/2012
	
	}
	
	/******************************
	****** DELETE functions *******
	******************************/
	
	function deleteRowN($id){ // 05/06/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$tr = $tbody->getElementsByTagName('tr')->item($id);
		$tbody->removeChild($tr);
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Fila Borrada Correctamente";
	}
	
	/******************************
	*** ALTER TABLE functions *****
	******************************/
	
	/*
	**	ALTER TABLE [table] ADD COLUMN [$th] DEFAULT [$dth]
	*/
	
	function addColumn($th,$dth){ // 13/06/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		
		$xml = $this->xml;
		
		$thead = $xml->getElementsByTagName('thead')->item(0);	
		$trh = $thead->getElementsByTagName('tr')->item(0);
		
		$ith = $xml ->createElement('th');	
		$ith_txt = $xml -> createTextNode($th);
		$ith -> appendChild($ith_txt);
		
		$trh -> appendChild($ith);
		
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$trs = $tbody->getElementsByTagName('tr');
		
		foreach($trs as $tr){
			$td = $xml ->createElement('td');	
			$td_atr = $xml -> createAttribute('th');
			$td_atr_txt = $xml -> createTextNode($th);
			
			$td_atr ->appendChild($td_atr_txt);
			$td -> appendChild($td_atr);
			
			$td_txt = $xml -> createTextNode($dth);
			$td ->appendChild($td_txt);
			
			$tr -> appendChild($td);
		}
		
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Columna Insertada Correctamente";
	}
	
	/*
	**	ALTER TABLE [table] DROP COLUMN [$th]
	*/
	
	function dropColumn($th){ // 13/06/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		
		$thead = $xml->getElementsByTagName('thead')->item(0);	
		$trh = $thead->getElementsByTagName('tr')->item(0);
		$ths = $trh->getElementsByTagName('th');
		$k=0;
		while( $ith = $ths->item($k++) ){
			if( $ith -> nodeValue == $th ){
				$trh -> removeChild($ith);
			}
		}
		
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$trs = $tbody->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $trs ->item($i++) ){
			$tds = $tr->getElementsByTagName('td');
			$j=0;
			while($td = $tds->item($j++) ){
				$td_at = $td->getAttribute('th');
				if($td_at == $th){
					$tr -> removeChild($td);
				}
			}
		}
		
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Columna Borrada Correctamente";
	}
	
	/* 
	**	ALTER TABLE [table] RENAME COLUMN [$oldn] TO [$newn]
	*/
	
	
	function renameColumn($oldn,$newn){ // 14/06/2011
		if($this->error){$this->msg = "Error en lectura XML";return false;}
		$xml = $this->xml;
		
		$thead = $xml->getElementsByTagName('thead')->item(0);	
		$trh = $thead->getElementsByTagName('tr')->item(0);
		$ths = $trh->getElementsByTagName('th');
		
		$k=0;
		while( $ith = $ths->item($k++) ){
			if( $ith -> nodeValue == $oldn ){
				$thn = $xml ->createElement('th');	
				$thn_txt = $xml -> createTextNode($newn);
				$thn -> appendChild($thn_txt);
				
				$ith ->parentNode->replaceChild($thn,$ith); 
				//$tr->replaceChild($thn,$ith); 
				
			}
		}
		
		$tbody = $xml->getElementsByTagName('tbody')->item(0);	
		$trs = $tbody->getElementsByTagName('tr');	
		$i = 0;
		while( $tr = $trs ->item($i++) ){
			$tds = $tr->getElementsByTagName('td');
			$j=0;
			while($td = $tds->item($j++) ){
				$td_at = $td->getAttribute('th');
				if($td_at == $oldn){
					
					$tdn = $xml ->createElement('td');	
					$tdn_atr = $xml -> createAttribute('th');
					$tdn_atr_txt = $xml -> createTextNode($newn);
					
					$tdn_atr ->appendChild($tdn_atr_txt);
					$tdn -> appendChild($tdn_atr);
					
					$tdn_txt = $xml -> createTextNode($td->nodeValue);
					$tdn ->appendChild($tdn_txt);
					
					$td ->parentNode->replaceChild($tdn,$td); 
					
				}
			}
		}
		
		$xml->formatOutput = true;
		@$xml->save($this->xmldir);
		$this->msg = "Columna Renombrada Correctamente";
	}
}
?>