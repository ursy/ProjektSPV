<?php
class Users {
	public $table_name = 'users';

	function __construct(){
		//database configuration
		$dbServer = '127.0.0.1'; //Define database server host
		$dbUsername = 'projektSPV'; //Define database username
		$dbPassword = 'bankaZnamk123'; //Define database password
		$dbName = 'projektSPV'; //Define database name

		//connect databse
		$con = mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbName);
		if(mysqli_connect_errno()){
			die("Failed to connect with MySQL: ".mysqli_connect_error());
		}else{
			$this->connect = $con;
		}
	}

	function checkUser($oauth_provider,$oauth_uid,$fname,$lname,$email,$gender,$locale,$picture){
		$query = "SELECT * FROM ".$this->table_name." WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'";
                $result = mysqli_query($this->connect, $query);


		if(mysqli_num_rows($result)>0){
			$update = "UPDATE $this->table_name SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fname = '".$fname."', lname = '".$lname."', email = '".$email."', gender = '".$gender."', locale = '".$locale."', picture = '".$picture."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'";
                        mysqli_query($this->connect, $update);
                }else{
			/*$insert = mysqli_query($this->connect,"INSERT INTO facebook-users (oauth_provider , oauth_uid , fname , lname , email , gender , locale , picture , created , modified )
                                VALUES ('".$oauth_provider."','".$oauth_uid."','".$fname."','".$lname."','".$email."','".$gender."','".$locale."','".$picture."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");*/
                        $sql = "INSERT INTO $this->table_name (oauth_provider , oauth_uid , fname , lname , email , gender , locale , picture , created , modified) VALUES ('".$oauth_provider."','".$oauth_uid."','".$fname."','".$lname."','".$email."','".$gender."','".$locale."','".$picture."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
                        mysqli_query($this->connect, $sql);
                }

		$query = mysqli_query($this->connect,"SELECT * FROM $this->table_name WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");
		$result = mysqli_fetch_array($query);
		return $result;
	}
}
?>
