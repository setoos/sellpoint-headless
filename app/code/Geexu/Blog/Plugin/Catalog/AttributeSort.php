<?php


namespace Geexu\Blog\Plugin\Catalog;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\RequestInterface;
use Geexu\Blog\Helper\Data;

/**
 * Class AttributeSort
 * @package Geexu\Blog\Plugin\Catalog
 */
class AttributeSort
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * AttributeSort constructor.
     *
     * @param RequestInterface $request
     * @param Data $helper
     */
    public function __construct(
        RequestInterface $request,
        Data $helper
    ) {
        $this->helper  = $helper;
        $this->request = $request;
    }

    public function aroundAddAttributeToSort(
        Collection $productCollection,
        callable $proceed,
        $attribute,
        $dir
    ) {
        if ($attribute === 'position' &&
            in_array(
                $this->request->getFullActionName(),
                ['mageplaza_blog_post_products', 'mageplaza_blog_post_productsGrid'],
                true
            )
        ) {
            $productCollection->getSelect()->order('position ' . $dir);

            return $productCollection;
        }

        return $proceed($attribute, $dir);
    }
}
