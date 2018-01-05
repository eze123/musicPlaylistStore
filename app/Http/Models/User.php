<?php
namespace MyApp\Http\Models;
if(!isset($_SESSION))
    session_start();

use MyApp\Http\Models\Model;
use PDO;

/**
 * User basic login routine. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class User extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function register()
	{
		$_SESSION['username'] = $_POST['registerusername'];
        $_SESSION['password'] = $_POST['registerpassword'];
		$this->hash_credentials();
		
		/** check whether the username has already been assigned. **/
		if($this->userExists()){
			echo "The chosen username already exists";
			return;
		}
		
		$stmt = $this->conn->prepare("INSERT INTO user(username, password)VALUES(:username, :password)");
		$stmt->bindParam(':username', $_SESSION['username']);
		$stmt->bindParam(':password', $_SESSION['password']);
		
		$result = $stmt->execute();
			
	}
	
	private function hash_credentials()
	{
        $salt = hash('sha256', rand());
        $_SESSION['password'] = hash('sha256', $_SESSION['password']);
        $_SESSION['password'] = substr($salt, 0, 16) . substr($_SESSION['password'], 0, 32) . substr($salt, 16, 32) . substr($_SESSION['password'], 32, 32) . substr($salt, 48, 16);
	}
	
	private function userExists()
	{
		$stmt = $this->conn->prepare("SELECT username FROM user WHERE username = :username");
		$stmt->bindParam(':username', $_SESSION['username']);
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	private function session_vars(string $sessionParam = null)
	{			
		switch($sessionParam){
			case "logged":
			    /** Returning to client-side via Ajax, therefore needs to be passed-back through echoing **/
			    echo isset($_SESSION['login'])? $_SESSION['login']:false;
				break;
			case "isActive":
			    return isset($_SESSION['login'])? $_SESSION['login']:false;
				break;
		}
	}
	
	public function login(){
		
		/** Authentication: verify the identity of the user **/
		if($this->verification()){	
		    /* Authorization, sort of */
			$_SESSION['login'] = true;
			header("Location: /musicPlaylistStore");
		}
	}
	
	/**
     * Authenticates the user 
     * @return bool true|false;
     */
	private function verification()
	{
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];
		$stmt = $this->conn->prepare("SELECT id, password from user WHERE username = :username");
		$stmt->bindParam(':username', $_SESSION['username']);		
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		foreach($result as $pass){
			$password = $result['password'];
			$userId = $result['id'];
		}
		
		$this->hash_credentials();
		/** check the password in the session against the password in the database **/
        //if ((substr($_SESSION['password'], 16, 32) . substr($_SESSION['password'], 80, 32)) === (substr($password, 16, 32) . substr($password, 80, 32)))
		if ((substr($_SESSION['password'], 16, 32) ) === (substr($password, 16, 32))){
			$_SESSION['userId'] = $userId;
			return true;
		}
		
		return false;
	}
	
	public function resetPassword()
	{
		
	}
	
	public function getUser()
	{
		
	}
	
	public function deactivateUser()
	{
		
	}
	
	public function removeUser()
	{
		
	}
	
	/** This is for Ajax authentication **/
	public function isLoggedIn()
	{
		$this->session_vars("logged");
	}
	
	/** Similar to isLoggedIn function, but it is for verifying authentication server-side **/
	public function isActiveSession()
	{
		return $this->session_vars("isActive");
	}
	
}






