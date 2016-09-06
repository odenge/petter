/* smartRollover Modified_20110308 */
(function(onLoad) {
	try {
		window.addEventListener( 'load', onLoad, false );
	} catch (e) {
		window.attachEvent( 'onload', onLoad );
	}
})(function() {
	var sRM_tags = ["img","input"];
	var sRM_over = function() { this.src = this.src.replace( '_off.', '_on.' ); };
	var sRM_out  = function() { this.src = this.src.replace( '_on.', '_off.' ); };
	var sRM_preImages = new Array();
	for ( var sRM_i=0, sRM_len=sRM_tags.length; sRM_i<sRM_len; sRM_i++ ) {
		var sRM_el = document.getElementsByTagName(sRM_tags[sRM_i]);
		for ( var sRM_j=0, sRM_len2=sRM_el.length; sRM_j<sRM_len2; sRM_j++ ) {
			var sRM_attr = sRM_el[sRM_j].getAttribute('src');
			if ( sRM_attr === null ) continue;
			if ( !sRM_attr.match(/_off\./) ) continue;
			var sRM_preImage = new Image();
			sRM_preImage.src = sRM_el[sRM_j].src.replace( "_off.", "_on." );
			sRM_preImages.push( sRM_preImage );
			delete sRM_preImage;
			sRM_el[sRM_j].onmouseover = sRM_over;
			sRM_el[sRM_j].onmouseout = sRM_out;
			sRM_el[sRM_j].onclick = sRM_out;
		}
	}
});