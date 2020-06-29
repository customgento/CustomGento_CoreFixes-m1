<?php

declare(strict_types=1);

class CustomGento_CoreFixes_Block_Adminhtml_Catalog_Product_Edit_Tab_Downloadable_Links
    extends Mage_Downloadable_Block_Adminhtml_Catalog_Product_Edit_Tab_Downloadable_Links
{
    public function getConfigJson($type='links')
    {

        $this->getUploaderConfig()
            ->setFileParameterName($type)
            ->setTarget(
                Mage::getModel('adminhtml/url')
                    ->getUrl('*/downloadable_file/upload', array('type' => $type, '_secure' => true))
            );
        $this->getMiscConfig()
            ->setReplaceBrowseWithRemove(true)
        ;
        return Mage::helper('core')->jsonEncode(parent::getJsonConfig());
    }
}
