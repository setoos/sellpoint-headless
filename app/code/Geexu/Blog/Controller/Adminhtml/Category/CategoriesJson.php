<?php

namespace Geexu\Blog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Geexu\Blog\Controller\Adminhtml\Category;
use Geexu\Blog\Model\CategoryFactory;

/**
 * Class CategoriesJson
 * @package Geexu\Blog\Controller\Adminhtml\Category
 */
class CategoriesJson extends Category
{
    /**
     * JSON Result Factory
     *
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * Layout Factory
     *
     * @var LayoutFactory
     */
    public $layoutFactory;

    /**
     * CategoriesJson constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CategoryFactory $categoryFactory
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CategoryFactory $categoryFactory,
        JsonFactory $resultJsonFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;

        parent::__construct($context, $coreRegistry, $categoryFactory);
    }

    /**
     * Get tree node (Ajax version)
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $this->_objectManager->get('Magento\Backend\Model\Auth\Session')->setIsTreeWasExpanded(
            (boolean)$this->getRequest()->getParam('expand_all')
        );

        $resultJson = $this->resultJsonFactory->create();
        if ($categoryId = (int)$this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            $category = $this->initCategory(true);
            if (!$category) {
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('geexu_blog/*/', ['_current' => true]);
            }

            $treeJson = $this->layoutFactory->create()
                ->createBlock('Geexu\Blog\Block\Adminhtml\Category\Tree')
                ->getTreeJson($category);

            /** @var Json $resultJson */
            return $resultJson->setJsonData($treeJson);
        }

        return $resultJson->setJsonData('[]');
    }
}
