<?php

class m140710_052604_add_role_to_tbl_project_user_assignment extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_project_user_assignment', 'role', 'varchar(64)');
		
		//the tbl_project_user_assignment.role is a reference to tbl_auth_item.name
		$this->addForeignKey("fk_project_user_role", "tbl_project_user_assignment", "role", "tbl_auth_item", "name", "CASCADE", "CASCADE");
	}

	public function down()
	{
		//echo "m140710_052604_add_role_to_tbl_project_user_assignment does not support migration down.\n";
		//return false;
		
		$this->dropForeignKey("fk_projecy_user_role", "tbl_project_user_assignment");
		$this->dropColumn("tbl_project_user_assignment", "role");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}