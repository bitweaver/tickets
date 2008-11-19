{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/list_tickets.tpl,v 1.1 2008/11/19 23:33:37 pppspoonman Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="listing tickets">
	<div class="header">
		<h1>{tr}Tickets Records{/tr}</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}

		{form id="checkform"}
			<input type="hidden" name="offset" value="{$control.offset|escape}" />
			<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

			<table class="data">
				<tr>
					{if $gBitSystem->isFeatureActive( 'tickets_list_tickets_id' ) eq 'y'}
						<th>{smartlink ititle="Tickets Id" isort=tickets_id offset=$control.offset iorder=desc idefault=1}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'tickets_list_title' ) eq 'y'}
						<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'tickets_list_description' ) eq 'y'}
						<th>{smartlink ititle="Description" isort=description offset=$control.offset}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'tickets_list_data' ) eq 'y'}
						<th>{smartlink ititle="Text" isort=data offset=$control.offset}</th>
					{/if}

					{if $gBitUser->hasPermission( 'p_tickets_update' )}
						<th>{tr}Actions{/tr}</th>
					{/if}
				</tr>

				{foreach item=tickets from=$ticketssList}
					<tr class="{cycle values="even,odd"}">
						{if $gBitSystem->isFeatureActive( 'tickets_list_tickets_id' )}
							<td><a href="{$smarty.const.TICKETS_PKG_URL}index.php?tickets_id={$tickets.tickets_id|escape:"url"}" title="{$tickets.tickets_id}">{$tickets.tickets_id}</a></td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'tickets_list_title' )}
							<td>{$tickets.title|escape}</td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'tickets_list_description' )}
							<td>{$tickets.description|escape}</td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'tickets_list_data' )}
							<td>{$tickets.data|escape}</td>
						{/if}

						{if $gBitUser->hasPermission( 'p_tickets_update' )}
							<td class="actionicon">
								{smartlink ititle="Edit" ifile="edit.php" ibiticon="icons/accessories-text-editor" tickets_id=$tickets.tickets_id}
								<input type="checkbox" name="checked[]" title="{$tickets.title|escape}" value="{$tickets.tickets_id}" />
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
						document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form.id,'checked[]','switcher')\" /><br />");
					/* ]]> */</script>

					<select name="submit_mult" onchange="this.form.submit();">
						<option value="" selected="selected">{tr}with checked{/tr}:</option>
						{if $gBitUser->hasPermission( 'p_tickets_update' )}
							<option value="remove_ticketss">{tr}remove{/tr}</option>
						{/if}
					</select>

					<noscript><div><input type="submit" value="{tr}Submit{/tr}" /></div></noscript>
				</div>
			{/if}
		{/form}

		{pagination}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
