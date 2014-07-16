<?php
/**
 * ProjectUserForm Class
 * ProjectUserForm for assigning a user as a role in a project. 
 * used by 'actionAdduser' action of 'ProjectController'
 * @author CY
 * @property string $username
 * @property string $role
 * @property string $project
 *
 */

class ProjectUserForm extends CFormModel
{
	/**
	 * @var string username of the user being added to the project
	 */
	public $username;
	
	/**
	 * @var string the role to which the user will be associated within the project
	 */
	public $role;
	
	/**
	 * @var object an instance of the Project AR model class
	 */
	public $project;
	
	private $_user;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and $role are required,
	 */
	public function rules()
	{
		return array(
				// username and role are required
				array('username, role', 'required'),
				//username needs to be checked for existence
				array('username', 'exist', 'className'=>'User'),
				// username needs to be verified
				array('username', 'verify'),
		);
	}
	
	/**
	 * Verify the username to be valid.
	 * This is the 'verify' validator as declared in rules().
	 */
	public function verify($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$user = User::model()->findByAttributes(array('username'=>$this->username));
			if($this->project->isUserInProject($user))
			{
				$this->addError('username','This user has already been added to the project.');
			}
			else
			{
				$this->_user = $user;
			}
		}
	}
	
	public function assign()
	{
		if($this->_user instanceof User)
		{
				
			//assign the user, in the specified role, to the project
			//$this->project->removeUser($this->_user->id);
			$this->project->assignUser($this->_user->id, $this->role);
			//add the association, along with the RBAC biz rule, to our RBAC hierarchy
			$auth = Yii::app()->authManager;
			if(!$auth->isAssigned($this->role, $this->_user->id)){
				$bizRule='return isset($params["project"]) && $params["project"]->allowCurrentUser("'.$this->role.'");';
				$auth->assign($this->role, $this->_user->id, $bizRule);
			}
			return true;
		}
		else
		{
			$this->addError('username','Error when attempting to assign this user to the project.');
			return false;
		}
	
	}
	
	/**
	 * Generates an array of usernames to use for the autocomplete
	 */
	public function createUsernameList()
	{
		$sql = "SELECT username FROM tbl_user";
		$command = Yii::app()->db->createCommand($sql);
		$rows = $command->queryAll();
		//format it for use with auto complete widget
		$usernames = array();
		foreach($rows as $row)
		{
			$usernames[]=$row['username'];
		}
		return $usernames;
	
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'username'=>'Username',
				'role'=>'Role',
				'project'=>'Project',
		);
	}
	
}

?>