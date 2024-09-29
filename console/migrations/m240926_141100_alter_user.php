<?php
use yii\db\Migration;

class m240926_141100_alter_user extends Migration
{
    public function safeUp() {
        $this->addColumn('user', 'role', $this->integer()->defaultValue(1)->after('email'));
        $this->update('user', ['role' => 1024], ['username' => 'admin']);
    }

    public function safeDown() {
        $this->dropColumn('user', 'role');
    }
}