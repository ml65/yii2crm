<?php
use yii\db\Migration;

class m240930_124300_create_log_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'level' => $this->integer()->notNull(),
            'category' => $this->string(255)->notNull(),
            'log_time' => $this->double()->notNull(),
            'prefix' => $this->string(255),
            'message' => $this->text(),
            //'created_at' => $this->timestamp()->defaultValue(null)->append('ON INSERT CURRENT_TIMESTAMP'),
            //'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%bid}}');
    }
}
