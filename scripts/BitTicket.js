BitTicket = {
	// constants
	'FORM_DIV_ID':'edit_comments',
	'FORM_ID':'editheader-form',
	'REPLY_ID':null,
	'FEEDBACK_DIV_ID':'editheader-feedback',
	
	'postHeader': function(){
		var f = MochiKit.DOM.formContents( $(BitTicket.FORM_ID) );
		for (n in f[0]){
			if (f[0][n] == 'post_comment_preview' || f[0][n] == 'post_comment_cancel'){ f[1][n] = null; }
		}
		var url = bitRootUrl+"tickets/ajax_header.php";
		var data = queryString(f);		
		var req = getXMLHttpRequest();
		req.open('POST', url, true);
		req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		req.setRequestHeader('Content-Length',data.length);
		var post = sendXMLHttpRequest(req,data);
		post.addCallbacks(BitTicket.checkRslt); 
	},
	'checkRslt': function(rslt){
		var xml = rslt.responseXML;
		var status = xml.documentElement.getElementsByTagName('code')[0].firstChild.nodeValue;
		if (status == '200'){
			BitTicket.displayHeader(rslt);
		}else{
			//if status is 400, 401, or 405 still call preview - allowing someone to save their typed text.
			LibertyComment.displayPreview(rslt);
		}
	},
	'displayHeader': function(rslt){
		var xml = rslt.responseXML;
		$(BitTicket.FEEDBACK_DIV_ID).innerHTML = xml.documentElement.getElementsByTagName('content')[0].firstChild.nodeValue;
		return;
		
		var comment =  DIV(null, null);
		comment.innerHTML = xml.documentElement.getElementsByTagName('content')[0].firstChild.nodeValue;
		comment.style.marginLeft = (LibertyComment.REPLY_ID != LibertyComment.ROOT_ID)?"20px":'0';
		comment.style.display = 'none';
		if (LibertyComment.SORT_MODE == "commentDate_asc"){
			MochiKit.DOM.insertSiblingNodesBefore( $('comment_'+LibertyComment.REPLY_ID+'_footer'), comment );
		}else{
			MochiKit.DOM.insertSiblingNodesAfter( $('comment_'+LibertyComment.REPLY_ID), comment );
		}

		LibertyComment.cancelPreview( true );
		MochiKit.Visual.blindUp( LibertyComment.FORM_DIV_ID, {afterFinish: function(){
			LibertyComment.detachForm();
			LibertyComment.resetForm();
			MochiKit.Visual.blindDown( comment, {afterFinish: function(){
				if ( LibertyComment.BROWSER != "ie" ){
					MochiKit.Visual.ScrollTo( comment );				
				}else{
					//self.scrollTo( comment.offsetLeft, comment.offsetTop );
				}
			}});
		}});
	}
}
