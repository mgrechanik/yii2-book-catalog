<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%guest_subscribes}}`.
 */
class m250619_110925_create_guest_subscribes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%guest_subscribes}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string('12')->notNull()->comment('Телефон гостя'),
            'id_a' => $this->integer()->notNull()->comment('Id автора')
        ]);

        $this->createIndex('guest_subscribes_phone_id_a_idx', 'guest_subscribes', ['phone', 'id_a'], true);

        $this->addForeignKey('guest_subscribes_id_a_fk', 'guest_subscribes', 'id_a', 'authors', 'id' , 'CASCADE', 'CASCADE' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%guest_subscribes}}');
    }
}
