<?php
/**
 * Created by PhpStorm.
 * User: dimasik
 * Date: 15.10.18
 * Time: 13.47
 */

namespace GoMage\Feed\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        if (version_compare($context->getVersion(), '1.2.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('gomage_feed'),
                'currency_code',
                [
                    'length' => 10,
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Currency code'
                ]
            );
        }
        $setup->endSetup();
    }
}