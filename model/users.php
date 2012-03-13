<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/database.php';
require_once ROOT_DIR.'/model/facebook.php';
require_once ROOT_DIR.'/model/jsonEncodable.php';

class User implements JSONEncodable
{
	// These are all private to provide read only access for some vars
	private $id;
	private $alias;
	private $banned;
	private $joinDate;
	private $lastLoginDate;
	private $adminPrivileges;
	private $facebookName;
	private $facebookInfo;
	private $firstLogin;
	
	#
	# Used internally to construct a user from either table data, or user
	# specified data.
	#
	private function __construct($_id,
								 $_alias,
								 $_banned,
								 $_joinDate,
								 $_lastLoginDate,
								 $_facebookName,
								 $_adminPrivileges,
								 $_firstLogin)
	{
		$this->id              = $_id;
		$this->alias           = $_alias;
		$this->banned          = $_banned;
		$this->joinDate        = $_joinDate;
		$this->lastLoginDate   = $_lastLoginDate;
		$this->facebookName    = $_facebookName;
		$this->adminPrivileges = $_adminPrivileges;
		$this->firstLogin      = $_firstLogin;
		$this->facebookInfo    = null;
	}
	
	#
	# Returns a new user object created from the specified associative row
	# data.
	#
	private static function FromRowData($row)
	{
		return new User(
			$row['userID'],
			$row['alias'],
			$row['banned'],
			$row['joined'],
			$row['lastLogin'],
			$row['facebookName'],
			$row['adminPrivileges'],
			false
		);
	}
	
	#
	# Returns a new user object created from the user specified data.
	#
	public static function Create($_id,
								  $_alias,
								  $_banned,
								  $_facebookName,
								  $_adminPrivileges,
								  $dbName='default')
	{
		$usr = new User(
			$_id,
			$_alias,
			$_banned,
			null,
			null,
			$_facebookName,
			$_adminPrivileges,
			false
		);
		$usr->CommitChanges($dbName);
		$usr->GetFacebookInfo(true, $dbName);
		// This is done to populate vars set by the database
		return self::FetchByID($_id, $dbName);
	}
	
	#
	# Returns a new user object created from the current (new) Facebook user.
	#
	public static function CreateFromCurrent($dbName='default')
	{
		$id = FacebookHelper::GetUserID();
		
		$usr = new User(
			$id,
			"",
			false,
			null,
			null,
			"",
			0,
			true
		);
		$usr->CommitChanges($dbName);
		$usr->GetFacebookInfo(true, $dbName);
		// This is done to populate vars set by the database
		return self::FetchByID($id, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions modify / return internal object data.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Returns a boolean indicating if this is the user's first login.
	#
	public function IsFirstLogin()
	{
		return $this->firstLogin;
	}
	
	#
	# Returns a boolean indicating if this user is banned.
	#
	public function IsBanned()
	{
		return ($this->banned == 1);
	}
	
	#
	# Bans a user.
	#
	public function Ban()
	{
		$this->banned = 1;
	}
	
	#
	# Unbans a user.
	#
	public function UnBan()
	{
		$this->banned = 0;
	}
	
	#
	# Gets the user's ID (Facebook).
	#
	public function GetID()
	{
		return $this->id;
	}
	
	#
	# Gets the user's current alias.
	#
	public function GetAlias()
	{
		return $this->alias;
	}
	
	#
	# Sets the user's current alias.
	#
	public function SetAlias($newAlias)
	{
		$this->alias = $newAlias;
	}
	
	#
	# Gets the date the user first played the Turing Game.
	#
	public function GetJoinDate()
	{
		return $this->joinDate;
	}
	
	#
	# Gets the date of the user's last login.
	#
	public function GetLastLoginDate()
	{
		return $this->lastLoginDate;
	}
	
	#
	# Gets the user's cached Facebook name.
	#
	public function GetFacebookName()
	{
		return $this->facebookName;
	}
	
	#
	# Sets the user's Facebook name;
	#
	public function SetFacebookName($newName)
	{
		$this->faceBookName = $newName;
	}
	
	#
	# Returns a boolean indicating whether or not the user has admin rights.
	#
	public function HasAdminPrivileges()
	{
		return ($this->adminPrivileges > 0);
	}
	
	#
	# Sets the user's admin privileges.
	#
	public function SetAdminPrivileges($privs)
	{
		$this->adminPrivileges = $privs;
	}
	
	#
	# Gets basic Facebook information about the user and caches it. Set update
	# to true to update the cache. Avoid this when possible since fetching user
	# data from Facebook takes a non-negligible amount of time (users will
	# notice).
	#
	# The user's cached Facebook name is automatically updated in the user table
	# if it is different from the fetched Facebook user information.
	#
	public function GetFacebookInfo($update=false, $dbName='default')
	{
		if($this->facebookInfo == null || $update == true)
			$this->facebookInfo = FacebookHelper::GetUserInfo();
		
		if($this->facebookName != $this->facebookInfo->{'name'})
		{
			$this->facebookName = $this->facebookInfo->{'name'};
			$this->CommitChanges($dbName);
		}
		
		return $this->facebookInfo;
	}
	
	#
	# Gets the user's Facebook picture URL.
	#
	public function GetFacebookPictureURL()
	{
		return FacebookHelper::GetFacebookPictureURL($this->id);
	}
	
	#
	# Gets the user's Facebook profile link.
	#
	public function GetFacebookProfileLink()
	{
		return FacebookHelper::GetFacebookProfileLink($this->id);
	}
	
	#
	# Returns a JSON encoded version of the current object
	#
	public function JSONEncode(){
	
		$JSON = array(
			'userID' => $this->id,
			'alias' => $this->alias,
			'banned' => $this->banned,
			'joined' => $this->joinDate,
			'lastLogin' => $this->lastLoginDate,
			'adminPrivileges' => $this->adminPrivileges,
			'facebookName' => $this->facebookName
		);
		
		//TODO: Add FB Info to JSON encode
		return json_encode($JSON);
	}
	
	#
	# Returns if a user alias is taken.
	#
	public static function IsAliasTaken($alias, $dbName='default')
	{
		if($alias == "")
			return false;

		$query = "SELECT * FROM `users` WHERE `alias`=?";
		$params = array($alias);
		
		$results = Database::SafeQuery($query, $params, $dbName);
		return (Database::CountRows($results) > 0);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions move object data to the database.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Removes the user from the database.
	#
	public function Remove($dbName='default')
	{
		$query = "DELETE FROM `users` WHERE `userID`=?";
		$params = array($this->id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Updates this user's row in the table to reflect the current
	# state of this object. If no row exists, the user is inserted.
	#
	public function CommitChanges($dbName='default')
	{
		if($this->id <= 0)
			return;
		
		$query = "INSERT INTO `users` ".
				 "(`userID`,`alias`,`banned`,`adminPrivileges`,`facebookName`) ".
				 "VALUES (?,?,?,?,?) ".
				 "ON DUPLICATE KEY UPDATE ".
				 "`alias`=?,`banned`=?,`adminPrivileges`=?,`facebookName`=?";
		$params = array($this->id, $this->alias, $this->banned, $this->adminPrivileges, $this->facebookName,
						$this->alias, $this->banned, $this->adminPrivileges, $this->facebookName);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions provide Facebook related user calls.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Returns the current user using Facebook magic, or null if no user is
	# logged in. If this is a user's first login, they will be inserted into
	# the user table.
	#
	public static function FetchCurrentUser($dbName='default')
	{
		$id = FacebookHelper::GetUserID();
		if($id > 0)
		{
			$user = self::FetchByID($id, $dbName);
			if($user == null)
				$user = User::CreateFromCurrent();
			
			return $user;
		}
		
		return null;
	}
	
	#
	# Returns if the user is logged in. Avoids a MySQL hit when used instead
	# of FetchCurrentUser() != null (if condition is true).
	#
	public static function IsCurrentUserLoggedIn()
	{
		return (FacebookHelper::GetUserID() > 0);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions provide user-related database access
	// without requiring a user object.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Fetches one or more users from the database. If there is only one
	# user, it returns the user object. If there is more than one,
	# it returns an array of user objects. If there are no users, it
	# returns null.
	#
	private static function Fetch($query, $params, $dbName='default')
	{
		$allRows = Database::SafeQuery($query, $params, $dbName);
		$rowCount = Database::CountRows($allRows);
		
		if($rowCount == 1)
		{
			$rowData = Database::FetchAssoc($allRows);
			return self::FromRowData($rowData);
		}
		else if($rowCount > 1)
		{
			$users = array();
			while($rowData = Database::FetchAssoc($allRows))
				array_push($users, self::FromRowData($rowData));
			
			return $users;
		}
		
		return null;
	}
	
	#
	# Removes a user by ID.
	#
	public static function RemoveByID($id, $dbName='default')
	{
		$query = "DELETE FROM `users` WHERE `id`=?";
		$params = array($id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Looks up a user by ID.
	#
	public static function FetchByID($id, $dbName='default')
	{
		$query = "SELECT * FROM `users` WHERE `userID`=?";
		$params = array($id);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all users.
	#
	public static function FetchAll($dbName='default')
	{
		$query = "SELECT * FROM `users`";
		$params = array();
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all admin users.
	#
	public static function FetchAllAdmins($dbName='default')
	{
		$query = "SELECT * FROM `users` WHERE `adminPrivileges`>0";
		$params = array();

		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Searches the user table for any users that contain $str in
	# either their alias or Facebook name.
	#
	public static function Find($str, $dbName='default')
	{
		$escapedStr = Database::Escape($str);
		$query = "SELECT * FROM `users` WHERE ".
				 "facebookName LIKE '%${escapedStr}%' OR ".
				 "alias LIKE '%${escapedStr}%'";
		
		return self::Fetch($query, false, $dbName);
	}
}

?>