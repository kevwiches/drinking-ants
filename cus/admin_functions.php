<?php


include 'query.php';
/*********************************
 *
 * 	Administrator View Functions
 *
 *
 * *******************************/
class AdminFunctions {
		
		
	var $runner;
	
	function AdminFunctions() {
		$this->runner = new Query();
	}

	function cleanUp($info) {

		//$conn = connect();
		$info = trim($info);
		$info = htmlspecialchars($info);
		$info = mysqli_escape_string($this->runner->getConn(), $info);

		return $info;
	}

	function getQuery() { return $this->runner; }

	/*** Functions for use throughout app ***/


	function updateTap($alc_id, $name, $brewery, $alcohol_content) {
		
		$query = "UPDATE alcohol a SET a.name = '$name', a.maker = '$brewery'," . 
			"a.alcohol_content = '$alcohol_content' WHERE a.alc_id = $alc_id";

		return $this->runner->queryRunner($query);
	}

	function addBeerBottle($name, $brewer, $alcohol_content) {
		$query = "INSERT INTO alcohol (name, maker, alcohol_content, type_code) " .
		"VALUES('$name', '$brewer', $alcohol_content, 2)";
		 return $this->runner->queryRunner($query);
	}

	function addWineBottle($name, $brewer, $alcohol_content) {
		$query = "INSERT INTO alcohol (name, maker, alcohol_content, type_code) " .
		"VALUES('$name', '$brewer', $alcohol_content, 3)";
		 return $this->runner->queryRunner($query);
	}


	function addMenuItem($name, $description, $price, $menuCode) {

		$name = $this->cleanUp($name);
		$description = $this->cleanUp($description);
		
		if(is_null($price) || $price == "") {
			$price = NULL;
		}
		else {
			$price = $this->cleanUp($price);
		}
		
		$query = "INSERT INTO menu (name, description, price, type_code) " . "VALUES('$name','$description', $price, $menuCode)";

		//return 
		return $this->runner->queryRunner($query);
		
	}

	function addEvent($title, $description, $startdate, $enddate, $time) {
		$title = $this->cleanUp($title);
		$description = $this->cleanUp($description);
		//$date = $this->cleanUp($date);
		$sDate = $startdate;
		$eDate = $enddate;
		$time = $this->cleanUp($time);

		if (is_null($eDate) || $eDate == "" || $eDate == 0 || $eDate == '0000-00-00') {
			$eDate == NULL;
		}


		$query = "INSERT INTO event (title, description, startdate, enddate, time) " . 
		"VALUES('$title', '$description', '$sDate', '$eDate', '$time')";

		return $this->runner->queryRunner($query);
	}

	function addPress($title, $publication, $description, $picture, $article) {
		$title = $this->cleanUp($title);
		$desc = $this->cleanUp($description);
		$publ = $this->cleanUp($publication);
		$pic = urlencode($this->cleanUp($picture));
		$link = $this->cleanUp($article);

		$query = "INSERT INTO press (title, publication, description, picture_location, article_link)" . 
		" VALUES ('$title', '$publ', '$desc', '$pic', '$link')";

		return $this->runner->queryRunner($query);
	}
	
	function isValidUser($username, $password) {
		$username = $this->cleanUp($username);
		$password = $this->cleanUp($password);

		$query = "SELECT username FROM user WHERE username = '$username'";

		return $this->runner->queryRunner($query);
	}

	function login($username) {
		$query = "SELECT password FROM user WHERE username = '$username'";
		return $this->runner->queryRunner($query);
	}

	function registration($username, $password) {
		
		$hashed = $this->hashIt($password);

		$query = "INSERT INTO user (username, password) VALUES('$username', '$hashed')";

		return $this->runner->queryRunner($query);

	}

	function kill_connection() {
		$this->runner->disconnect();
	}

	 function validate($pw, $hash) {
			
		$salt = substr($hash, 0, 64);
		$validHash = substr($hash, 64, 64);
		$testHash = hash("sha256", $salt . $pw);
		$checkHash=utf8_encode($testHash);
	//	echo "checkagainst: " . $checkHash . "<br />";
	//	echo "valid: " . $validHash;
		return $checkHash === $validHash;
	}

	   function hashIt($pw) {
		$saltysalt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		$hashsack = hash("sha256", $saltysalt . $pw);
		$dontthinkaboutithackers = $saltysalt . $hashsack;
		$lol = utf8_encode($dontthinkaboutithackers);
		return $lol;
	}

};
?>