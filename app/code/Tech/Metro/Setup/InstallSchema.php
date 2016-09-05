<?php
namespace Tech\Metro\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('tech_metro_post')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('tech_metro_post')
            )
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Post ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Post Name'
            )
            ->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Post Image'
            )
            ->addColumn(
                'color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Post Color'
            )
            ->addColumn(
                'link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Post Link'
            )
            ->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Post Position'
            )

            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Post Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Post Updated At'
            )
            ->setComment('Post Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('tech_metro_post'),
                $setup->getIdxName(
                    $installer->getTable('tech_metro_post'),
                    ['name','image','link','position'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','image','link','position'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
