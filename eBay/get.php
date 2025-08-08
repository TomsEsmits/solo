<?php
    function __currency( $x, $echo = true, $transform = true ) {
        $value = $x;
        $number = "$" . number_format( $x, 2, '.', ',' );
        if ( $transform == true ) {
            if ( $echo == true ) {
                echo $number;
            }
            elseif ( $echo == false ) {
                return $number;
            }
        }
        elseif ( $transform == false ) {
            if ( $echo == true ) {
                echo $value;
            }
            elseif ( $echo == false ) {
                return $value;
            }
        }
    }
    $url = "http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=Pacific5-61a6-4ebc-8f91-a9f158dbf0e2&siteid=0&version=515&ItemID=" . $_GET[ 'id' ];
    $string = file_get_contents( $url );
    $json = json_decode( $string, true );
    
    $category = "";
    
    if ( strpos( $json[ "Item" ][ "Title" ], 'Chrysler' ) !== false && $category == "" ) {
        $category = "chrysler";
    }
    if ( strpos( $json[ "Item" ][ "Title" ], 'Dodge' ) !== false && $category == "" ) {
        $category = "dodge";
    }
    if ( strpos( $json[ "Item" ][ "Title" ], 'Cummins' ) !== false && $category == "" ) {
        $category = "cummins";
    }
    if ( strpos( $json[ "Item" ][ "Title" ], 'Lexus' ) !== false && $category == "" ) {
        $category = "lexus";
    }
    if ( strpos( $json[ "Item" ][ "Title" ], 'Honda' ) !== false && $category == "" ) {
        $category = "honda";
    }
    if ( strpos( $json[ "Item" ][ "Title" ], 'Jeep' ) !== false && $category == "" ) {
        $category = "jeep";
    }
?>
__({
    "title": "<?php echo $json[ "Item" ][ "Title" ]; ?>",
    "price": "<?php __currency( $json[ "Item" ][ "ConvertedCurrentPrice" ][ "Value" ] ); ?>",
    "category": "<?php echo $category; ?>"
});