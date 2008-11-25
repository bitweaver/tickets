{strip}
	<ul>
		{if $gBitUser->hasPermission( 'p_tickets_view')}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}index.php">{tr}Tickets Home{/tr}</a></li>
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}list_tickets.php">{tr}List Tickets{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_tickets_create' )}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}edit.php">{tr}Create Ticket{/tr}</a></li>
		{/if}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}milestone_display.php">{tr}Milestones{/tr}</a></li>
		{if $gBitUser->hasPermission( 'p_tickets_milestone_create' )}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}edit_milestone.php">{tr}Create Milestone{/tr}</a></li>
		{/if}
	</ul>
{/strip}
