{strip}
	<ul>
		{if $gBitUser->hasPermission( 'p_tickets_view')}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}index.php">{biticon ipackage="icons" iname="go-home" iexplain="Tickets Home" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}list_tickets.php">{biticon ipackage="icons" iname="format-justify-fill" iexplain="List Tickets" ilocation=menu}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_tickets_create' )}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}edit.php">{biticon ipackage="icons" iname="document-new" iexplain="Create Ticket" ilocation=menu}</a></li>
		{/if}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}milestone_display.php">{biticon iname="folder" iexplain="Milestones" ilocation=menu}</a></li>
		{if $gBitUser->hasPermission( 'p_tickets_milestone_create' )}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}edit_milestone.php">{biticon iname="folder-new" iexplain="Create Milestone" ilocation=menu}</a></li>
		{/if}
	</ul>
{/strip}
