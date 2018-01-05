<?php
namespace MyApp\Http\Controllers;

use MyApp\Http\Models\User;
use Illuminate\Http\Request;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Performs preliminary actions that authenticates and authorizes user access to the application. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class LoginController
{
	protected $currentRequest;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request = null)
    {
       isset($request) ? $this->currentRequest = $request : $currentRequest = null;
    }

    public function login()
    {
		if(!isset($_POST['username'])){
			echo "Please enter the username</br>";
			return;
		}
			
		if(!isset($_POST['password'])){
			echo "Please enter the password</br>";
			return;
		}
		
		$user = new User;
		$user->login();
        return "Hello from Home";
    }
	
	public function logout()
	{
		header("Location:http://".$_SERVER['HTTP_HOST']."/musicPlaylistStore/views/logoff.php", true, 302);
		die();
	}
	
	/**
	 * Validates elements used in username credentials during registration
     * @param  \Validation\Validator
     * @return void
     */
	public function register()
	{
		$username = $_POST['registerusername'];
		
		$validity = new Validator;
		
		$usernameValidator = $validity::alnum()->notEmpty()->noWhitespace()->length(1, 15);
		$usernameValidator->validate($username); // true
		
		try {
            $usernameValidator->setName('username')->assert($username);
        } catch(NestedValidationException $exception) {
			$errors = $exception->findMessages([
                'alnum' => '{{name}} must contain only letters and digits',
                'length' => '{{name}} must not have more than 15 chars',
				'notEmpty' => '{{name}} must not be empty',
                'noWhitespace' => '{{name}} cannot contain spaces'
            ]);
			
			foreach($errors as $error){
				if(!empty($error))
					echo "</br>".$error;
			}
			
			return;
        }
		
		$user = new User;
		$user->register();
	}
	
	public function isLoggedIn()
	{
		$user = new User;
		return $user->isLoggedIn();
	}
	
	
}