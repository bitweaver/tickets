{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/list_tickets.tpl,v 1.8 2008/11/25 23:33:17 pppspoonman Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="listing tickets">
	<div class="header">
		<h1>{tr}Tickets Records{/tr}</h1>
	</div>

	<div class="body">
		{include file="list_tickets_inc.tpl" ticketsList=$ticketsList fieldDefinitions=$fieldDefinitions}
		
		{pagination}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
