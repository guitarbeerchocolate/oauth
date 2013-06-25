<?php
class oauth
{
	private $client;	
	private $oauth2;
	public $authUrl;
	public $user;
	private $id;
	private $username;
	function __construct()
	{
		$config = new config;
		require_once 'lib/google-api-php-client/src/Google_Client.php';
		require_once 'lib/google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		$this->client = new Google_Client();
		$this->client->setApplicationName('Google PHP Starter Application');
		$this->client->setClientId($config->values->OAUTH_CLIENT_ID);
		$this->client->setClientSecret($config->values->OAUTH_CLIENT_SECRET);
		$this->client->setRedirectUri($config->values->OAUTH_CLIENT_REDIRECT_URI);
		$this->client->setDeveloperKey($config->values->OAUTH_CLIENT_DEVELOPER_KEY);
		$this->oauth2 = new Google_Oauth2Service($this->client);

		if(isset($_GET['code']))
		{
			$this->client->authenticate();
			$_SESSION['token'] = $this->client->getAccessToken();
			$redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
			header('Location:'.filter_var($redirect, FILTER_SANITIZE_URL));
			return;
		}

		if(isset($_SESSION['token']))
		{
			$this->client->setAccessToken($_SESSION['token']);
		}

		if(isset($_REQUEST['logout']))
		{
			unset($_SESSION['token']);
			$this->client->revokeToken();
			$this->destroySession();
		}

		$this->getState();
	}

	private function getState()
	{
		if($this->client->getAccessToken())
		{
  			$this->user = (object) $this->oauth2->userinfo->get();
  			$db = new database;  			
    		$q = "SELECT * FROM `users` WHERE `username`='{$this->user->email}'";
    		$result = $db->singleRow($q);    		
  			if(!$result->id)	
  			{
  				$q = "INSERT INTO `users` VALUES (NULL,'{$this->user->email}','')";		
  				$result = $db->query($q);  				
  				$q = "SELECT * FROM `users` WHERE `username`='{$this->user->email}'";
    			$result = $db->singleRow($q);    			
  			}
  			$this->id = $result->id;
  			$this->username = $this->user->email;  			
  			$this->createSession();
			$_SESSION['token'] = $this->client->getAccessToken();			
		}
		else
		{
			$this->authUrl = $this->client->createAuthUrl();			
		}
	}

	private function createSession()
	{
		$_SESSION['AUTH_ID'] = $this->id;   
		$_SESSION['AUTH_USERNAME'] = $this->username;
	}

	private function destroySession()
	{
		unset($_SESSION['AUTH_ID']);
		unset($_SESSION['AUTH_USERNAME']);
		session_destroy();    
	}

	function __destruct()
	{
	
	}
}
?>