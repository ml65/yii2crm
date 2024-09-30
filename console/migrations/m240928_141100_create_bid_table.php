<?php
use yii\db\Migration;

class m240928_141100_create_bid_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bid}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'title' => $this->string(255)->notNull(),
            'product_name' => $this->string()->notNull(),
            'phone' => $this->string(11),
            'comment' => $this->text(),
            'price' => $this->float()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%bid}}');
    }
}
