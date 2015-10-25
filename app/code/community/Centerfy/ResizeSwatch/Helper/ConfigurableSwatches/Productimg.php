<?php
class Centerfy_ResizeSwatch_Helper_ConfigurableSwatches_Productimg extends Mage_ConfigurableSwatches_Helper_Productimg
{
	
	protected function _resizeSwatchImage($filename, $tag, $width, $height)
	{
		// Form full path to where we want to cache resized version
		$destPathArr = array(
				self::SWATCH_CACHE_DIR,
				Mage::app()->getStore()->getId(),
				$width . 'x' . $height,
				$tag,
				trim($filename, '/'),
		);
	
		$destPath = implode('/', $destPathArr);
	
		// Check if cached image exists already
		if (!file_exists(Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $destPath)) {
			// Check for source image
			if ($tag == 'product') {
				$sourceFilePath = Mage::getSingleton('catalog/product_media_config')->getBaseMediaPath() . $filename;
			} else {
				$sourceFilePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
				. DS . self::SWATCH_FALLBACK_MEDIA_DIR . DS . $filename;
			}
	
			if (!file_exists($sourceFilePath)) {
				return false;
			}
	
			// Do resize and save
			$processor = new Varien_Image($sourceFilePath);
			$processor->backgroundColor(array(255, 255, 255));
			// Set background color to white
			$processor->constrainOnly(TRUE);
			//Constrain to proportions
			$processor->keepAspectRatio(TRUE);
			//keep existing aspect ratio
			$processor->keepFrame(TRUE);
			//Kinda what it says
			$processor->keepTransparency(true);
			
			$processor->resize($width, $height);			
			$processor->save(Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $destPath);
			Mage::helper('core/file_storage_database')->saveFile($destPath);
		}
	
		return $destPath;
	}
}
		