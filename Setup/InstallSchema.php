<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'gomage_feed_attribute'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('gomage_feed_attribute'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attribute id'
            )
            ->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false, 'unique' => true],
                'Attribute code'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'Attribute name'
            )
            ->addColumn(
                'default_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => true],
                'Default value'
            )
            ->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Attribute condition'
            )
            ->setComment('GoMage Feed attributes');
        $installer->getConnection()->createTable($table);


        /**
         * Create table 'gomage_feed'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('gomage_feed'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Feed id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'Feed name'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Store Id'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Feed type'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Feed status'
            )
            ->addColumn(
                'filename',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Feed file name'
            )
            ->addColumn(
                'file_ext',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Feed file ext'
            )
            ->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Feed content'
            )
            ->addColumn(
                'conditions_serialized',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Feed Conditions'
            )
            ->addColumn(
                'generated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Generated at'
            )
            ->addColumn(
                'generation_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'Generate time'
            )
            ->addColumn(
                'uploaded_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Uploaded at'
            )
            ->addColumn(
                'is_header',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Show feed header'
            )
            ->addColumn(
                'is_addition_header',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Is Addition header'
            )
            ->addColumn(
                'addition_header',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Addition header'
            )
            ->addColumn(
                'enclosure',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Feed enclosure'
            )
            ->addColumn(
                'delimiter',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Feed delimiter'
            )
            ->addColumn(
                'is_ftp',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Is ftp active'
            )
            ->addColumn(
                'ftp_host',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'FTP host'
            )
            ->addColumn(
                'ftp_user_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'FTP user name'
            )
            ->addColumn(
                'ftp_password',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'FTP password'
            )
            ->addColumn(
                'ftp_dir',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'FTP directory'
            )
            ->addColumn(
                'is_ftp_passive',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Is FTP passive mode'
            )
            ->addColumn(
                'ftp_port',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                [],
                'FTP port'
            )
            ->addColumn(
                'ftp_protocol',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'FTP protocol'
            )
            ->addColumn(
                'limit',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Limit'
            )
            ->addColumn(
                'is_out_of_stock',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Export Out of Stock Products'
            )
            ->addColumn(
                'is_disabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Export Disabled Products'
            )
            ->addColumn(
                'visibility',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Products Visibility'
            )
            ->addColumn(
                'is_generate',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Generate Status'
            )
            ->addColumn(
                'generate_day',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Generate days'
            )
            ->addColumn(
                'generate_hour',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Generate hour'
            )
            ->addColumn(
                'generate_hour_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Generate hour to'
            )
            ->addColumn(
                'generate_interval',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Generate interval'
            )
            ->addColumn(
                'cron_generated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'Cron generated at'
            )
            ->addColumn(
                'is_upload',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0', 'nullable' => false],
                'Upload Status'
            )
            ->addColumn(
                'upload_day',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Upload days'
            )
            ->addColumn(
                'upload_hour',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Upload hour'
            )
            ->addColumn(
                'upload_hour_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Upload hour to'
            )
            ->addColumn(
                'upload_interval',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Upload interval'
            )
            ->addColumn(
                'cron_uploaded_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Cron uploaded at'
            )
            ->addIndex(
                $installer->getIdxName('gomage_feed', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('gomage_feed', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
            )
            ->setComment('GoMage Feeds');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
