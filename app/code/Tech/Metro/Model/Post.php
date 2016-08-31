<?php
namespace Tech\Metro\Model;

/**
 * @method Post setName($name)
 * @method Post setImage($image)
 * @method Post setLink($link)
 * @method Post setPosition($position)
 * @method mixed getName()
 * @method mixed getImage()
 * @method mixed getLink()
 * @method mixed getPosition()
 * @method Post setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Post setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 */
class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'tech_metro_post';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'tech_metro_post';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'tech_metro_post';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tech\Metro\Model\ResourceModel\Post');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
