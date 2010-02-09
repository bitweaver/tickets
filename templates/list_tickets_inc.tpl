{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/list_tickets_inc.tpl,v 1.3 2010/02/09 17:21:22 wjames5 Exp $ *}
{strip}
	{minifind sort_mode=$sort_mode}

	{form id="checkform"}
		<input type="hidden" name="offset" value="{$control.offset|escape}" />
		<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

		<table class="data">
			<tr>
				{if $gBitSystem->isFeatureActive( 'tickets_list_ticket_id' ) eq 'y'}
					<th>{smartlink ititle="Ticket Id" isort=ticket_id offset=$control.offset iorder=desc idefault=1}</th>
				{/if}

				{if $gBitSystem->isFeatureActive( 'tickets_list_title' ) eq 'y'}
					<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>
				{/if}
				
				<th>{tr}Creator{/tr}</th>

				{foreach from=$fieldDefinitions item=field}
					<th>{tr}{$field.title}{/tr}</th>
				{/foreach}

				{if $gBitSystem->isFeatureActive( 'tickets_list_data' ) eq 'y'}
					<th>{smartlink ititle="Text" isort=data offset=$control.offset}</th>
				{/if}

				{if $gBitUser->hasPermission( 'p_tickets_update' )}
					<th>{tr}Actions{/tr}</th>
				{/if}
			</tr>
			
			{foreach item=ticket from=$ticketsList}
				<tr class="{cycle values="even,odd"} {foreach from=$fieldDefinitions item=field}tk-row-{$ticket.attributes[$field.def_id].field_value|lower|strip:'-'} {/foreach}">
					{if $gBitSystem->isFeatureActive( 'tickets_list_ticket_id' )}
						<td><a href="{$smarty.const.TICKETS_PKG_URL}index.php?ticket_id={$ticket.ticket_id|escape:"url"}" title="{$ticket.ticket_id}">{$ticket.ticket_id}</a></td>
					{/if}

					{if $gBitSystem->isFeatureActive( 'tickets_list_title' )}
						<td>{$ticket.title|escape}</td>
					{/if}
					
					<td>{displayname login=$ticket.creator_user real_name=$ticket.creator_real_name}</td>
					
					{foreach from=$fieldDefinitions item=field}
						<td class="tk-cell-{$ticket.attributes[$field.def_id].field_value|lower|strip:'-'}">{$ticket.attributes[$field.def_id].field_value}</td>
					{/foreach}
					
					{if $gBitSystem->isFeatureActive( 'tickets_list_data' )}
						<td>{$ticket.data|escape}</td>
					{/if}

					{if $gBitUser->hasPermission( 'p_tickets_update' )}
						<td class="actionicon">
							{smartlink ititle="Edit" ifile="edit.php" ibiticon="icons/accessories-text-editor" ticket_id=$ticket.ticket_id}
							<input type="checkbox" name="checked[]" title="{$ticket.title|escape}" value="{$ticket.ticket_id}" />
						</td>
					{/if}
				</tr>
			{foreachelse}
				<tr class="norecords"><td colspan="16">
					{tr}No records found{/tr}
				</td></tr>
			{/foreach}
		</table>

		{if $gBitUser->hasPermission( 'p_tickets_update' )}
			<div style="text-align:right;">
				<script type="text/javascript">/* <![CDATA[ check / uncheck all */
					document.write("<label for=\"switcher\">{tr}Select All{/tr}</label> ");
					document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"BitBase.switchCheckboxes(this.form.id,'checked[]','switcher')\" /><br />");
				/* ]]> */</script>

				<select name="submit_mult" onchange="this.form.submit();">
					<option value="" selected="selected">{tr}with checked{/tr}:</option>
					{if $gBitUser->hasPermission( 'p_tickets_update' )}
						<option value="remove_tickets">{tr}remove{/tr}</option>
					{/if}
				</select>

				<noscript><div><input type="submit" value="{tr}Submit{/tr}" /></div></noscript>
			</div>
		{/if}
	{/form}
{/strip}
