<?php

class CustomGento_CoreFixes_Helper_ConfigurableSwatches_Productimg extends Mage_ConfigurableSwatches_Helper_Productimg
{
    public function indexProductImages($product, $preValues = null)
    {
        if ($product->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            return; // we only index images on configurable products
        }

        if (!isset($this->_productImagesByLabel[$product->getId()])) {
            $images = array();
            $searchValues = array();

            if (!is_null($preValues) && is_array($preValues)) { // If a pre-defined list of valid values was passed
                $preValues = array_map('Mage_ConfigurableSwatches_Helper_Data::normalizeKey', $preValues);
                foreach ($preValues as $value) {
                    $searchValues[] = $value;
                }
            } else { // we get them from all config attributes if no pre-defined list is passed in
                $attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);

                // Collect valid values of image type attributes
                foreach ($attributes as $attribute) {
                    if (Mage::helper('configurableswatches')->attrIsSwatchType($attribute->getAttributeId())) {
                        foreach ($attribute->getPrices() as $option) { // getPrices returns info on individual options
                            $searchValues[] = Mage_ConfigurableSwatches_Helper_Data::normalizeKey($option['label']);
                        }
                    }
                }
            }

            $mapping = $product->getChildAttributeLabelMapping();
            $mediaGallery = $product->getMediaGallery();
            $mediaGalleryImages = $product->getMediaGalleryImages();

            if (empty($mediaGallery['images']) || empty($mediaGalleryImages)) {
                $this->_productImagesByLabel[$product->getId()] = array();
                return; //nothing to do here
            }

            $imageHaystack = array_map(function ($value) {
                return Mage_ConfigurableSwatches_Helper_Data::normalizeKey($value['label']);
            }, $mediaGallery['images']);

            foreach ($searchValues as $label) {
                $imageKeys = array();
                $swatchLabel = $label . self::SWATCH_LABEL_SUFFIX;

                $imageKeys[$label] = array_search($label, $imageHaystack);
                if ($imageKeys[$label] === false && array_keys($mapping, $label)) {
                    $imageKeys[$label] = array_search($mapping[$label]['default_label'], $imageHaystack);
                }

                $imageKeys[$swatchLabel] = array_search($swatchLabel, $imageHaystack);
                if ($imageKeys[$swatchLabel] === false && array_keys($mapping, $label)) {
                    $imageKeys[$swatchLabel] = array_search(
                        $mapping[$label]['default_label'] . self::SWATCH_LABEL_SUFFIX, $imageHaystack
                    );
                }

                foreach ($imageKeys as $imageLabel => $imageKey) {
                    if ($imageKey !== false) {
                        $imageId = $mediaGallery['images'][$imageKey]['value_id'];
                        $images[$imageLabel] = $mediaGalleryImages->getItemById($imageId);
                    }
                }
            }
            $this->_productImagesByLabel[$product->getId()] = $images;
        }
    }
}
