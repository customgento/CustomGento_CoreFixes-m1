# Description
This file is part of CustomGento_CoreFixes for Magento 1.9.3.9. Since `Mage_Core_Model_Session_Abstract_Varien` is an abstract class, we cannot override it, but have to overload it.

This overload fixes a bug, which causes 404 pages on product pages when using a full page cache.

# Resources
* https://magento.stackexchange.com/q/167616/142
* https://maxchadwick.xyz/blog/magento-1-enterprise-random-404s-on-the-product-detail-page-catalog-product-view

# Diff
    --- app/code/core/Mage/Core/Model/Session/Abstract/Varien.php	2018-03-07 15:52:25.022788063 +0100
    +++ app/code/local/Mage/Core/Model/Session/Abstract/Varien.php	2018-03-26 13:09:31.666056522 +0200
    @@ -428,6 +428,16 @@
             else {
                 if (!$this->_validate()) {
                     $this->getCookie()->delete(session_name());
    +                // make sure that the 404 page caused by session invalidation is not cached!
    +                // full_page for EE FPC
    +                // turpentine_pages for Nexcessnet_Turpentine
    +                $fullPageCacheTypeCodes = array('full_page', 'turpentine_pages');
    +                $cache                  = Mage::app()->getCacheInstance();
    +                foreach ($fullPageCacheTypeCodes as $typeCode) {
    +                    if ($cache->canUse($typeCode)) {
    +                        $cache->banUse($typeCode);
    +                    }
    +                }
                     // throw core session exception
                     throw new Mage_Core_Model_Session_Exception('');
                 }
