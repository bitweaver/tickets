{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}
<div class="display tickets">
	<div class="floaticon">
		{if $print_page ne 'y'}
			{if $gContent->hasUpdatePermission()}
				<a title="{tr}Edit this milestone{/tr}" href="{$smarty.const.TICKETS_PKG_URL}edit_milestone.php?milestone_id={$gContent->mInfo.milestone_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="Edit Tickets"}</a>
			{/if}
			{if $gBitUser->hasExpungePermission()}
				<a title="{tr}Remove this milestone{/tr}" href="{$smarty.const.TICKETS_PKG_URL}remove_milestone.php?milesone_id={$gContent->mInfo.milestone_id}">{biticon ipackage="icons" iname="edit-delete" iexplain="Remove Tickets"}</a>
			{/if}
		{/if}<!-- end print_page -->
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{tr}Milestone{/tr} {$gContent->mInfo.title|escape}</h1>
		
		<div class="date">
			{tr}Created by{/tr}: {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, {tr}Last modification by{/tr}: {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name}, {$gContent->mInfo.last_modified|bit_long_datetime}
		</div>
		
		<div class="body">
			<div class="row">
				{tr}From{/tr} {$gContent->mDateFrom|bit_long_datetime} {tr}to{/tr} {$gContent->mDateTo|bit_long_datetime}
                {tr}Duration{/tr}: 3 days
                {tr}Time left{/tr}: 1 day
                {tr}Tickets finished{/tr} : 33 of 45
                {tr}Percentage{/tr} 80%
			</div>
			
			<div class="content">
				{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
				{$gContent->mInfo.parsed_data}
			</div><!-- end .content -->
		</div><!-- end .body -->
		
		<div class="listing tickets">
	        {include file="list_tickets_inc.tpl" ticketsList=$gContent->mTickets fieldDefinitions=$fieldDefinitions}
		</div>
	</div><!-- end .header -->


</div><!-- end .tickets -->
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
