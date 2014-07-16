<?php

class m140708_032406_create_project_table extends CDbMigration
{
	public function up()
	{
		// With transaction support, same as safeUp()
		$transaction = $this->getDbConnection()->beginTransaction();
		try {
			$this->createTable('tbl_project',
					array('id' => 'pk',
							'name' => 'string NOT NULL',
							'desctiption' => 'text NOT NULL',
							'create_time' => 'datetime DEFAULT NULL',
							'create_user_id' => 'int(11) DEFAULT NULL',
							'update_time' => 'datetime DEFAULT NULL',
							'update_user_id' => 'int(11) DEFAULT NULL'
					),
					'ENGINE=InnoDB'
			);
			$transaction->commit();
		} catch (Exception $e) {
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollback();
			return false;
		}
		
	}

	public function down()
	{
		//echo "m140708_032406_create_project_table does not support migration down.\n";
		//return false;
		$this->dropTable('tbl_project');
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