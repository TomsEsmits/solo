<?php
require_once 'app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$csvFile = 'var/import/products.csv';
$handle = fopen($csvFile, "r");

if ($handle === FALSE) {
    die("Error opening CSV file.\n");
}

$headers = fgetcsv($handle, 0, ",");

$offset = 0; // start from the first row
$limit  = 3; // only process first 3 products for testing

$rowCount = 0;
$processed = 0;

/**
 * Get or create option ID for a select attribute
 */
function getOrCreateOptionId($attributeCode, $label) {
    $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
    if ($attribute->usesSource()) {
        $optionId = $attribute->getSource()->getOptionId($label);
        if (!$optionId) {
            // Sanitize label
            $label = trim($label);
            $option = array(
                'attribute_id' => $attribute->getId(),
                'value' => array(
                    'option' => array($label)
                )
            );
            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
            $setup->addAttributeOption($option);

            // Reload attribute to get the new option ID
            $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
            $optionId = $attribute->getSource()->getOptionId($label);
        }
        return $optionId;
    }
    return null;
}

while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
    if ($rowCount < $offset) { 
        $rowCount++; 
        continue; 
    }
    if ($processed >= $limit) break;

    $row = array_combine($headers, $data);

    try {
        $sku = trim($row['sku']);
        if (!$sku) { $rowCount++; continue; }

        $productId = Mage::getModel('catalog/product')->getIdBySku($sku);
        if ($productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
        } else {
            $product = Mage::getModel('catalog/product');
            $product->setSku($sku);
            $product->setTypeId($row['_type'] ?: 'simple');

            $attributeSetName = $row['_attribute_set'] ?: 'Default';
            $attributeSetId = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->addFieldToFilter('attribute_set_name', $attributeSetName)
                ->getFirstItem()
                ->getId();
            if (!$attributeSetId) {
                $attributeSetId = Mage::getModel('catalog/product')->getDefaultAttributeSetId();
            }
            $product->setAttributeSetId($attributeSetId);
            $product->setWebsiteIds(array(1));
        }

        $product->setName($row['name']);
        $product->setDescription($row['description']);
        $product->setShortDescription($row['short_description']);
        $product->setPrice($row['price'] ?: 0);
        $product->setWeight($row['weight'] ?: 1);
        $product->setStatus($row['status'] ?: 1);
        $product->setVisibility($row['visibility'] ?: 4);
        $product->setTaxClassId($row['tax_class_id'] ?: 0);

        $stockData = array(
            'use_config_manage_stock' => 0,
            'manage_stock' => 1,
            'is_in_stock' => $row['is_in_stock'] ?: 1,
            'qty' => $row['qty'] ?: 0
        );
        $product->setStockData($stockData);

        if (!empty($row['image'])) {
            $imageUrl = $row['image'];
            $imageFile = basename(parse_url($imageUrl, PHP_URL_PATH));
            $localPath = "media/import/" . $imageFile;

            if (!file_exists($localPath)) {
                $imageData = @file_get_contents($imageUrl);
                if ($imageData) {
                    file_put_contents($localPath, $imageData);
                }
            }

            if (file_exists($localPath)) {
                $product->addImageToMediaGallery(
                    $localPath,
                    array('image','small_image','thumbnail'),
                    false,
                    false
                );
            }
        }

        // Set PCM Options and create missing select options if needed
        // Handle PCM Options properly (dropdown vs text)
$pcmAttributes = [
    'make'        => 'select',
    'model'       => 'text',
    'year'        => 'select',
    'engine_type' => 'select',
    'engine_size' => 'text',
    'condition'   => 'select'
];
echo "<pre>";
print_r($row);
echo "</pre>";
foreach ($pcmAttributes as $attr => $type) {
    if (!empty($row[$attr])) {
        $value = trim($row[$attr]);

        if ($type === 'select') {
            $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attr);
            if ($attribute->usesSource()) {
                $optionId = $attribute->getSource()->getOptionId($value);

                if (!$optionId) {
                    // Create option if missing
                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $setup->addAttributeOption([
                        'attribute_id' => $attribute->getId(),
                        'value' => [ 'option' => [$value] ]
                    ]);
                    // Reload attribute to fetch new option
                    $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attr);
                    $optionId = $attribute->getSource()->getOptionId($value);
                }

                if ($optionId) {
                    $product->setData($attr, $optionId);
                    echo "✅ Set '{$attribute->getFrontendLabel()}' : '{$value}'<br>";
                } else {
                    echo "⚠️ Could not set option '{$value}' for attribute '{$attr}'<br>";
                }
            }
        } else {
            // Text attribute
            $product->setData($attr, $value);
            $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attr);
            echo "✅ Set '{$attribute->getFrontendLabel()}' : '{$value}'<br>";
        }
    }
}


        $product->save();
        echo "✅ Imported SKU: {$sku}<br>";

    } catch (Exception $e) {
        echo "❌ Error SKU {$row['sku']} - " . $e->getMessage() . "<br>";
    }

    $rowCount++;
    $processed++;
}

fclose($handle);
echo "<br>=== Imported {$processed} products (for testing) ===";
