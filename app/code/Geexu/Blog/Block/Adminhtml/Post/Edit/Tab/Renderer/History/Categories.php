<?php

namespace Geexu\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer\History;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\Text;
use Magento\Framework\DataObject;
use Geexu\Blog\Helper\Data;

/**
 * Class Action
 * @package Geexu\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer
 */
class Categories extends Text
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Categories constructor.
     *
     * @param Context $context
     * @param Data $_helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $_helperData,
        array $data = []
    ) {
        $this->_helperData = $_helperData;
        parent::__construct($context, $data);
    }

    /**
     * @param DataObject $row
     *
     * @return string
     */
    public function render(DataObject $row)
    {
        if (!empty($row->getData($this->getColumn()->getIndex()))) {
            $text = '';
            $CategoryIds = explode(',', $row->getData($this->getColumn()->getIndex()));
            foreach ($CategoryIds as $categoryId) {
                $category = $this->_helperData->getFactoryByType('category')->create()->load($categoryId);
                $text .= $category->getName() . ',';
            }
            $row->setData($this->getColumn()->getIndex(), trim($text, ','));
        }

        return parent::render($row);
    }
}
