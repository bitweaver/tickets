{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}
<div class="display tickets">
	<div class="floaticon">
		{if $print_page ne 'y'}
			{if $gContent->hasUpdatePermission()}
				<a title="{tr}Edit this tickets{/tr}" href="{$smarty.const.TICKETS_PKG_URL}edit.php?ticket_id={$gContent->mInfo.ticket_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="Edit Tickets"}</a>
			{/if}
			{if $gBitUser->hasExpungePermission()}
				<a title="{tr}Remove this tickets{/tr}" href="{$smarty.const.TICKETS_PKG_URL}remove_tickets.php?ticket_id={$gContent->mInfo.ticket_id}">{biticon ipackage="icons" iname="edit-delete" iexplain="Remove Tickets"}</a>
			{/if}
		{/if}<!-- end print_page -->
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{tr}Ticket{/tr} {$gContent->mTicketId} - {$gContent->mInfo.title|escape}</h1>
		
		<div class="date">
			{tr}Created by{/tr}: {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, {tr}Last modification by{/tr}: {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name}, {$gContent->mInfo.last_modified|bit_long_datetime}
		</div>
		
		<div class="display ticket">
		
			{form action="`$comments_return_url`#editheader" id="editheader-form"}
			
			<input type="hidden" name="ticket[ticket_id]" value="{$gContent->mInfo.ticket_id}" />
			
			{formfeedback hash=$formfeedback}
			
	        <ul>
		        {include file="edit_header_inc.tpl" fieldDefinitions=$fieldDefinitions fieldValues=$fieldValues milestones=$milestones gContent=$gContent}
	        </ul>
	        
	        {* small trick so if ul is all float it widens up to this point *}
			<div class="clear" id="editheader-feedback"></div>
	        <div class="clear" id="asd"></div>
	        
	        {if $gBitUser->hasPermission( 'p_tickets_update' )}
			<div class="row submit" id="submitHeaderChanges" style="display:none">
				<input type="submit" name="post_header_request" value="{tr}Submit header changes{/tr}" onclick="BitTicket.attach('{$gContent->mContentId}');BitTicket.postHeader();return false;"/>
			</div>
			{/if}
			
			{/form}
	        
		</div>
	</div><!-- end .header -->

	<div class="body">
		<div class="content">
			
			{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
			
			<h3 style="clear:both">{$gContent->mInfo.title|escape}</h3>
			
			{$gContent->mInfo.parsed_data}
			{if !$preview}
				{include file="bitpackage:tickets/ticket_comments.tpl"}
			{/if}
		</div><!-- end .content -->
	</div><!-- end .body -->
</div><!-- end .tickets -->
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
