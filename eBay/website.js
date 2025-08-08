load = function() {
    load.getScript( "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" );
}
load.getScript = function( filename ) {
    var script = document.createElement( 'script' );
    script.setAttribute( "type", "text/javascript" );
    script.setAttribute( "onreadystatechange", "DOMLoaded()" );
    script.setAttribute( "onload", "DOMLoaded()" );
    script.setAttribute( "src", filename );
    if ( typeof script!="undefined" ) {
        document.getElementsByTagName( "head" )[ 0 ].appendChild( script );
    }
}
load();
function DOMLoaded() {
    $.ajax({
        type: "GET",
        url: "http://www.solopcms.com/eBay/get.php?id=" + ebayItemID,
        dataType: "jsonp",
        async: true
    });
};
function __( data ) {
    $( "#banner-logo" ).attr( { "src": "http://www.solopcms.com/eBay/images/banners/" + data.category + "-logo.png" } );
    $( "#banner-image" ).attr( { "src": "http://www.solopcms.com/eBay/images/banners/" + data.category + ".png" } );
    $( "#price" ).text( data.price );
    $( "#image-text" ).text( data.title );
}