{strip}
	<ul>
		{if $gBitUser->hasPermission( 'p_tickets_view')}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}index.php">{tr}Ticketss Home{/tr}</a></li>
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}list_ticketss.php">{tr}List Ticketss{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_tickets_create' )}
			<li><a class="item" href="{$smarty.const.TICKETS_PKG_URL}edit.php">{tr}Create Tickets{/tr}</a></li>
		{/if}
	</ul>
{/strip}
