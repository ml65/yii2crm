<?php
use yii\db\Migration;

class m240929_181100_alter_bid extends Migration
{
    public function safeUp() {
        $this->dropColumn('bid', 'product_name');
        $this->addColumn('bid', 'product_id', $this->integer()->defaultValue(1)->after('title'));
    }

    public function safeDown() {
        $this->dropColumn('bid', 'product_id');
        $this->addColumn('bid', 'product_name', $this->string(355)->defaultValue('')->after('title'));
    }
}