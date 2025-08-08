<?php
    if ( $_GET[ "action" ] == "get" ) {
        require_once( "app/Mage.php" );
        umask( 0 );
        Mage::app();
        
        if ( isset( $_GET[ "category" ] ) && $_GET[ "category" ] == "111" ) {
            $string = "Step 1. Select Auto Make";
        }
        if ( isset( $_GET[ "category" ] ) && $_GET[ "category" ] != "111" ) {
            $string = "Step 2. Select Auto Model";
        }
        if ( isset( $_GET[ "attribute" ] ) && $_GET[ "attribute" ] == "year" ) {
            $string = "Step 3. Select Auto Year";
        }
        if ( isset( $_GET[ "attribute" ] ) && $_GET[ "attribute" ] == "engine_type" ) {
            $string = "Step 4. Select Engine Type";
        }
        
        if ( $_GET[ "category" ] != "" && is_numeric( $_GET[ "category" ] ) && $_GET[ "attribute" ] == "" ) {
            $c = $_GET[ "category" ];
            $children = Mage::getModel( "catalog/category" )->getCategories( ( integer ) $c );
            
            echo '<option value="~" disabled="disabled" selected="selected">' . $string . '</option>';
            foreach ( $children as $category ) {
                if ( $_GET[ "category" ] != 111 ) {
                    $m = explode( " ", trim( $category->getName() ) );
                    array_pop( $m );
                    array_shift( $m );
                    $m = implode( " ", $m ); ?>
                    <option value="<?php echo $category->getID(); ?>" alt-value="<?php echo $m; ?>"><?php echo $category->getName(); ?></option>
                <?php }
                else { ?>
                    <option value="<?php echo $category->getID(); ?>" alt-value="<?php echo $category->getName(); ?>"><?php echo $category->getName(); ?></option>
                <?php }
            }
        }
        if ( $_GET[ "model" ] != "" ) {
            $a = $_GET[ "attribute" ];
            
            $product = Mage::getModel( "catalog/product" );
            $productCollection = Mage::getResourceModel( "eav/entity_attribute_collection" )
                ->setEntityTypeFilter( $product->getResource()->getTypeId() )
                ->addFieldToFilter( "attribute_code", $a );
                
            $attribute = $productCollection->getFirstItem()->setEntity( $product->getResource() );
            $options = $attribute->getSource()->getAllOptions( false );
            
            echo '<option value="~" disabled="disabled" selected="selected">' . $string . '</option>';
            
            foreach ( $options as $o ) {
                if ( $a == "year" ) {
                    $count = __getCountYear( $_GET[ "model" ], $o[ "value" ] );
                }
                if ( $a == "engine_type" ) {
                    $count = __getCountEngine( $_GET[ "model" ], $_GET[ "year" ], $o[ "value" ] );
                }
                if ( $count != 0 ) : ?>
                    <option value="<?php echo $o[ "value" ]; ?>"><?php echo $o[ "label" ]; ?></option>
                <?php endif;
            }
        }
    }
    
    function __getCountYear( $category_id, $year ) {
        $collection = Mage::getModel( "catalog/product" )->getCollection()->joinField( "category_id", "catalog/category_product", "category_id", "product_id = entity_id", null, "left" );
        $collection->addAttributeToSelect( "year" );
        $collection->addAttributeToFilter( "category_id",
            array(
                array( "finset" => $category_id )
            )
        );
        $collection->addFieldToFilter(
            array(
                array(
                    "attribute" => "year",
                    "eq" => $year
                )
            )
        );
        return count( $collection );
    }
    
    function __getCountEngine( $category_id, $year, $engine ) {
        $collection = Mage::getModel( "catalog/product" )->getCollection()->joinField( "category_id", "catalog/category_product", "category_id", "product_id = entity_id", null, "left" );
        $collection->addAttributeToSelect( "year" );
        $collection->addAttributeToFilter( "category_id",
            array(
                array( "finset" => $category_id )
            )
        );
        $collection->addFieldToFilter(
            array(
                array(
                    "attribute" => "year",
                    "eq" => $year
                )
            )
        );
        $collection->addFieldToFilter(
            array(
                array(
                    "attribute" => "engine_type",
                    "eq" => $engine
                )
            )
        );
        return count( $collection );
    }
    
?>