BitTicket = {
	// constants
	'FORM_DIV_ID':'edit_comments',
	'FORM_ID':'editheader-form',
	'REPLY_ID':null,
	'FEEDBACK_DIV_ID':'editheader-feedback',
	'SUBMIT_HEADER_CHANGES_DIV_ID':'submitHeaderChanges',
	
	'attach': function(reply_id){
		BitTicket.REPLY_ID=reply_id;
	},
	
	'setLibertyComment': function() {
		if( typeof(LibertyComment.prepRequestSrvc) != 'undefined' ) {
			LibertyComment.prepRequestSrvc.push( BitTicket.prepRequest );
		}
	},
	
	'prepRequest': function() {
		var f = MochiKit.DOM.formContents( $(BitTicket.FORM_ID) );
		for (n in f[0]){
			MochiKit.DOM.appendChildNodes(LibertyComment.FORM_ID, INPUT({"name": f[0][n], "value": f[1][n]}));
		}
	},
	
	'headerChanged': function(){
		var div=$(BitTicket.SUBMIT_HEADER_CHANGES_DIV_ID);
		
		if(div==null || div==undefined || div.style.display=='block')
			return;
		
		MochiKit.Visual.blindDown( BitTicket.SUBMIT_HEADER_CHANGES_DIV_ID);
		
	},
	
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
		
		//display feedback regardless of status
		BitTicket.displayHeader(rslt);
	},
	'displayHeader': function(rslt){
		var xml = rslt.responseXML;
		$(BitTicket.FEEDBACK_DIV_ID).innerHTML = xml.documentElement.getElementsByTagName('formfeedback')[0].firstChild.nodeValue;
		Fat.fade_all();//fade_element(BitTicket.FEEDBACK_DIV_ID,null,null,null);
		
		var comment =  DIV(null, null);
		comment.innerHTML = xml.documentElement.getElementsByTagName('content')[0].firstChild.nodeValue;
		comment.style.display = 'none';
		if (LibertyComment.SORT_MODE == "commentDate_asc"){
			MochiKit.DOM.insertSiblingNodesBefore( $('comment_'+BitTicket.REPLY_ID+'_footer'), comment );
		}else{
			MochiKit.DOM.insertSiblingNodesAfter( $('comment_'+BitTicket.REPLY_ID), comment );
		}

		MochiKit.Visual.blindUp( BitTicket.FORM_DIV_ID, {afterFinish: function(){
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
