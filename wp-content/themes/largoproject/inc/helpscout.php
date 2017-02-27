<?php
/**
 * function to add the HelpScout button on the site
 */
function largoproject_helpscout_beacon() {
	echo '<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!0,baseUrl:"//inn.helpscoutdocs.com/"},contact:{enabled:!0,formId:"b8258ff7-ef3a-11e6-8789-0a5fecc78a4d"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>';
}
add_action( 'wp_footer', 'largoproject_helpscout_beacon' );
