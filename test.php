<?php
require_once 'dxs-xmldb/dxs.xmldb.table.class.php';
require_once 'dxs-xmldb/dxs.xmldb.dbms.class.php';

$XMLDB = new xmldb_dbms();
$XMLTB = new xmldb_table();

//$XMLDB->dropTable('test');
$XMLDB->executeQuery('DROP TABLE test');

//$cols = Array('user','pass');
//$XMLDB->createTable('test',$cols);
$XMLDB->executeQuery('CREATE TABLE test(user,pass)');

$rs = $XMLDB->executeQuery('SELECT ALL FROM test');
if( is_array($rs) ){
	echo '<pre>';print_r($rs);echo '</pre>';echo '<br />';
}else{
	echo 'ERROR : ';echo $XMLDB->gtMSG();echo '<br />';
}

//$XMLDB->executeQuery('INSER INTO test (admin,pass) values');

$XMLTB->loadXML('test.xml');
$XMLTB->InsertInto( Array( 'user' => 'lau' , 'pass' => 'test' ) );

$rs = $XMLDB->executeQuery('SELECT ALL FROM test');
if( is_array($rs) ){
	echo '<pre>';print_r($rs);echo '</pre>';echo '<br />';
}else{
	echo 'ERROR : ';echo $XMLDB->gtMSG();echo '<br />';
}
?>