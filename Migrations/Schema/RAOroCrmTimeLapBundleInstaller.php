<?php

namespace RA\OroCrmTimeLapBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class RAOroCrmTimeLapBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Generate table timelap_tracker **/
        $table = $schema->createTable('timelap_tracker');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('task_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('date_started', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'IDX_36D2AA8AA76ED395', []);
        $table->addIndex(['task_id'], 'IDX_36D2AA8A8DB60186', []);
        /** End of generate table timelap_tracker **/

        /** Generate table timelap_worklog **/
        $table = $schema->createTable('timelap_worklog');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('task_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('time_spent', 'integer', []);
        $table->addColumn('date_started', 'datetime', []);
        $table->addColumn('description', 'string', ['notnull' => false, 'length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['task_id'], 'IDX_797F12A38DB60186', []);
        $table->addIndex(['user_id'], 'IDX_797F12A3A76ED395', []);
        /** End of generate table timelap_worklog **/

        /** Generate foreign keys for table timelap_tracker **/
        $table = $schema->getTable('timelap_tracker');
        $table->addForeignKeyConstraint($schema->getTable('orocrm_task'), ['task_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null]);
        $table->addForeignKeyConstraint($schema->getTable('oro_user'), ['user_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null]);
        /** End of generate foreign keys for table timelap_tracker **/

        /** Generate foreign keys for table timelap_worklog **/
        $table = $schema->getTable('timelap_worklog');
        $table->addForeignKeyConstraint($schema->getTable('orocrm_task'), ['task_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null]);
        $table->addForeignKeyConstraint($schema->getTable('oro_user'), ['user_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null]);
        /** End of generate foreign keys for table timelap_worklog **/
    }
}
