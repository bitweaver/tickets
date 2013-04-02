{strip}
{form}
	{jstabs}
		{jstab title="Home Tickets"}
			{legend legend="Home Tickets"}
				<input type="hidden" name="page" value="{$page}" />
				<div class="control-group">
					{formlabel label="Home Tickets (main tickets)" for="homeTickets"}
					{forminput}
						<select name="homeTickets" id="homeTickets">
							{section name=ix loop=$ticketss}
								<option value="{$ticketss[ix].ticket_id|escape}" {if $ticketss[ix].ticket_id eq $home_tickets}selected="selected"{/if}>{$ticketss[ix].title|escape|truncate:20:"...":true}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select>
					{/forminput}
				</div>

				<div class="control-group submit">
					<input type="submit" name="homeTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

		{jstab title="List Settings"}
			{legend legend="List Settings"}
				<input type="hidden" name="page" value="{$page}" />
				{foreach from=$formTicketsLists key=item item=output}
					<div class="control-group">
						{formlabel label=`$output.label` for=$item}
						{forminput}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
							{formhelp note=`$output.note` page=`$output.page`}
						{/forminput}
					</div>
				{/foreach}

				<div class="control-group submit">
					<input type="submit" name="listTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

	{/jstabs}
{/form}
{/strip}
