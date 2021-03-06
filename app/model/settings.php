<?php

// if(!defined('social')){
//     die("Access denied");
// }

$dbhost = 'localhost'; // Usually localhost
$dbuser = 'root';      // MySQL User
$dbpass = '';          // User's Password
$dbname = 'socialnetwork';   // Name of the database
$appURL = 'http://localhost/socialnetwork/public/'; // base domain configuration
$appName = 'social'; //appName configuration
$appControllers = '../app/controllers/'; //app controllers folder
$appView = '../app/view/'; //app view folder
$appModel = '../app/model/'; //app view folder
$appAjax = '../app/controllers/ajax/'; //app ajax controllers folder
$appAvatars = '../files/assets/img/avatars/'; // app avatars folder
$appIncludes = '../public/includes/'; // app includes folder
$appPublic = '../../public/'; // app public folder
$cdnURL = '../files/'; // app files folder
$appFiles = '../files/'; // app files folder
date_default_timezone_set('Europe/Athens');



class DB extends SQL{
    public $pdo, $i='`';
    static $q = array();
    function __construct($c){
        extract($c);
        $this->pdo = new PDO($dsn, $user, $pass, $args);
    }

    function column($q, $p = NULL, $k = 0){
        return($s = $this->query($q, $p)) ? $s -> fetchColumn($k) : 0;
    }

    function row($q, $p = NULL){
        return($s = $this->query($q, $p)) ? $s -> fetch(PDO::FETCH_OBJ) : 0;
    }

    function fetch($q, $p = NULL){
        return($s = $this->query($q,$p)) ? $s->fetchAll(PDO::FETCH_OBJ) : 0;
    }

    function query($q, $p = NULL){
        $s = $this->pdo -> prepare(self::$q[] = str_replace('"', $this->i, $q));
        $s -> execute($p);
        return $s;
    }

}
class SQL {
    function delete($t, $w = 0){
        $q = "DELETE FROM $t";
        list($w, $p) = $this->where($w);
        if($w)$q.=" WHERE $w";
        return($s = $this->query($q, $p)) ? $s -> rowCount() : 0;
    }

    function select($c = 0, $t, $w = 0, $l = 0, $o = 0, $s = 0){
        $c = $c?:'*';
        $q = "SELECT $c FROM \"$t\"";
        list($w, $p) = $this->where($w);
        if($w)$q.=" WHERE $w";
        return array($q.($s?" ORDER BY $s":'').($l?" LIMIT $o,$l":''), $p);
    }

    function count($t, $w = 0){
        list($q, $p) = $this->select('COUNT(*)', $t, $w);
        return $this->column($q, $p);
    }

    function insert($t, $d){
        $q = "INSERT INTO $t (\"".implode('","',array_keys($d)).'")VALUES('.rtrim(str_repeat('?,',count($d)), ',').')';
        return $this->query($q, array_values($d)) ? $this->pdo->lastInsertId() : 0;
    }

    function update($t, $d, $w = NULL){
        $q = "UPDATE $t SET \"".implode('"=?,"',array_keys($d)).'"=? WHERE ';
        list($a, $b) = $this->where($w);
        return(($s = $this->query($q.$a, array_merge(array_values($d), $b))) ? $s->rowCount() : NULL);
    }

    function where($w = 0){
        $a = $s = array();
        if($w){
            foreach($w as $c => $v){
                if(is_int($c))$s[] = $v;
                else{
                    $s[] = "\"$c\"=?";
                    $a[] = $v;
                }
            }
        }
        return array(join(' AND ',$s), $a);
    }
}
$config = array(
        'dsn' => 'mysql:host='.$dbhost.';dbname='.$dbname.'',
        'user' => $dbuser,
        'pass' => $dbpass,
        'args' => array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" // If using MySQL, force UTF-8
        )
);
$db = new DB($config);
function db($config = array()){
	static $db = NULL;
	if($db === NULL)
		$db = new DB($config);
	return $db;
}

?>