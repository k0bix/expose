<?php
namespace Tech\Slider\Model;

/**
 * @method Post setName($name)
 * @method Post setImg($img)
 * @method Post setLink($link)
 * @method Post setPosition($position)
 * @method mixed getName()
 * @method mixed getImg()
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
    const CACHE_TAG = 'tech_slider_post';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'tech_slider_post';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'tech_slider_post';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tech\Slider\Model\ResourceModel\Post');
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
