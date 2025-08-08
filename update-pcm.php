<?php
/**
 * Magento 1.9.3.2 script to update 'Model' custom option or attribute from "Cirrus" to "Cirrus PCM"
 * Place in Magento root as update-product.php
 * Access via https://www.solopcms.com/update-product.php
 * @author Grok
 */

ini_set('display_errors', 1);
ini_set('memory_limit', '1024M');
set_time_limit(0);

// Load Magento
require_once 'app/Mage.php';
umask(0);
Mage::app('admin');

// Start
echo "Starting update...<br>";

// Define search and replace
$searchValue = 'Stratus';
$replaceValue = 'Stratus PCM';

// Load product collection with batch processing
$productCollection = Mage::getModel('catalog/product')
    ->getCollection()
    ->setPageSize(100); // Process 100 products at a time

$updatedProducts = 0;
$pages = $productCollection->getLastPageNumber();

for ($page = 1; $page <= $pages; $page++) {
    $productCollection->setCurPage($page);
    echo "Processing page $page of $pages<br>";

    foreach ($productCollection as $product) {
        $productId = $product->getId();
        $sku = $product->getSku();
        $product = Mage::getModel('catalog/product')->load($productId);

        // Step 1: Check custom options
        $options = $product->getOptions();
        $optionsFound = false;
        $optionsChanged = false;

        if ($options) {
            echo "Product ID $productId (SKU: $sku): Found custom options<br>";
            foreach ($options as $option) {
                $optionTitle = $option->getTitle();
                echo "Product ID $productId (SKU: $sku): Option '$optionTitle'<br>";

                $optionValues = $option->getValues();
                if ($optionValues) {
                    foreach ($optionValues as $value) {
                        $valueTitle = $value->getTitle();
                        echo "Product ID $productId (SKU: $sku): Value '$valueTitle' for option '$optionTitle'<br>";

                        if ($valueTitle === $searchValue) {
                            try {
                                $value->setTitle($replaceValue)->save();
                                $resource = Mage::getSingleton('core/resource');
                                $write = $resource->getConnection('core_write');
                                $table = $resource->getTableName('catalog_product_option_type_title');
                                $write->update(
                                    $table,
                                    ['title' => $replaceValue],
                                    ['option_type_id = ?' => $value->getId(), 'title = ?' => $searchValue]
                                );
                                $optionsChanged = true;
                                echo "Updated product ID $productId (SKU: $sku) option value from '$searchValue' to '$replaceValue' for option '$optionTitle'<br>";
                            } catch (Exception $e) {
                                echo "Error updating product ID $productId (SKU: $sku) value '$valueTitle': " . $e->getMessage() . "<br>";
                            }
                        }
                    }
                } else {
                    echo "Product ID $productId (SKU: $sku): No values for option '$optionTitle'<br>";
                }
                $optionsFound = true;
            }
        } else {
            echo "Product ID $productId (SKU: $sku): No custom options found<br>";
        }

        if ($optionsChanged) {
            try {
                $product->save();
                $updatedProducts++;
                echo "Saved product ID $productId (SKU: $sku) with updated options<br>";
            } catch (Exception $e) {
                echo "Error saving product ID $productId (SKU: $sku): " . $e->getMessage() . "<br>";
            }
        }

        // Step 2: Check product attribute (fallback if custom option not found)
        $attributeCode = 'model'; // Adjust if different (e.g., 'pcm_model', 'pcm_options_model')
        $attributeValue = $product->getData($attributeCode);
        if ($attributeValue === $searchValue) {
            try {
                $product->setData($attributeCode, $replaceValue)->save();
                $updatedProducts++;
                echo "Updated product ID $productId (SKU: $sku) attribute '$attributeCode' from '$searchValue' to '$replaceValue'<br>";
            } catch (Exception $e) {
                echo "Error updating product ID $productId (SKU: $sku) attribute '$attributeCode': " . $e->getMessage() . "<br>";
            }
        } elseif ($attributeValue) {
            echo "Product ID $productId (SKU: $sku): Attribute '$attributeCode' has value '$attributeValue'<br>";
        }
    }

    $productCollection->clear(); // Clear memory
}

echo "Finished. Total updated products: $updatedProducts<br>";
?>