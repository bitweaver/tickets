{strip}
{form}
	{jstabs}
		{jstab title="Home Tickets"}
			{legend legend="Home Tickets"}
				<input type="hidden" name="page" value="{$page}" />
				<div class="row">
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

				<div class="row submit">
					<input type="submit" name="homeTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

		{jstab title="List Settings"}
			{legend legend="List Settings"}
				<input type="hidden" name="page" value="{$page}" />
				{foreach from=$formTicketsLists key=item item=output}
					<div class="row">
						{formlabel label=`$output.label` for=$item}
						{forminput}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
							{formhelp note=`$output.note` page=`$output.page`}
						{/forminput}
					</div>
				{/foreach}

				<div class="row submit">
					<input type="submit" name="listTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

        {jstab title="Field Settings"}
            {legend legend="Field Settings"}
				<input type="hidden" name="page" value="{$page}" />
				<table class="data">
					<caption>{tr}Fields{/tr}</caption>
					<tr>
						<th title="Sort order">{tr}Sort order{/tr}</th>
						<th title="Name and description">{tr}Name and description{/tr}</th>
						<th title="Use at creation">{tr}Use at creation{/tr}</th>
						<th title="Default">{tr}Default{/tr}</th>
						<th title="Actions">{tr}Actions{/tr}</th>
					</tr>
				</table>
                {foreach from=$fieldDefinitions item=fieldDef}
                <div class="{cycle values="even,odd"}">
                    <div class="row">
                        {formlabel label="Title" for="field_title_($fieldDef.def_id)"}
                        {forminput}
                            <input type="text" id="field_title_{$fieldDef.def_id}" name="fieldDef[{$fieldDef.def_id}]['title']" value="{$fieldDef.title}" />
                        {/forminput}
                    </div>
                    <div class="row">
                        {formlabel label="Description" for=""}
                        {forminput}
                            <input type="text" name="fieldDef[{$fieldDef.def_id}]['description']" value="{$fieldDef.description}" />
                        {/forminput}
                    </div>
                    <div class="row">
                        {formlabel label="Use at creation" for=""}
                        {forminput}
                            {html_checkboxes values="1" name="fieldDef[$fieldDef.def_id]['use_at_creation']" checked=$fieldDef.use_at_creation}
                        {/forminput}
                    </div>
                    <div class="row">
                        {formlabel label="Is enabled" for=""}
                        {forminput}
                            {html_checkboxes values="1" name="fieldDef[$fieldDef.def_id]['is_enabled']" checked=$fieldDef.is_enabled}
                        {/forminput}
                    </div>

                    <div class="row">
                        <input type="hidden" id="cant_{$fieldDef.def_id}" value="$fieldValues|count" />
                        {foreach from=$fieldValues[$fieldDef.def_id] item=fieldValue}
	                        {formlabel label=$fieldValue.sort_order for="fieldValue[$fieldValue.field_id]['field_value']"}
	                        {forminput}
	                            <input type="text" name="fieldValue[$fieldValue.field_id]['field_value']" value="{$fieldValue.field_value}" />
	                            {html_checkboxes values="1" name="fieldValue[$fieldValue.field_id]['is_default']" checked=$fieldValue.is_default}
	                            {html_checkboxes values="1" name="fieldValue[$fieldValue.field_id]['is_enabled']" checked=$fieldValue.is_enabled}
	                                {biticon iforce=icon ipackage=liberty iname="spacer" iexplain="" style="float:right"}
	
	                                <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_item=s&amp;tab=organise">{biticon iforce=icon ipackage="icons" iname="go-down" iexplain="move down" style="float:right"}</a>
	                                <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_items=n&amp;tab=organise">{biticon iforce=icon ipackage="icons" iname="go-up" iexplain="move up" style="float:right"}</a>
	
	                                {biticon iforce=icon ipackage=liberty iname="spacer" iexplain=""}
	                        {/forminput} 

                        {/foreach}
                        
						<a href="#" onclick="addFieldValue($fieldDef.def_id);return false;">{biticon iforce=icon ipackage="icons" iname="list-add" iexplain="Add new item" style="float:right"}</a>
						
                    </div>
                </div>
                {/foreach}
            {/legend}
        {/jstab}
        
        {jstab title="Field List"}
            {legend legend="Field List"}
				<input type="hidden" name="page" value="{$page}" />
				<table class="data">
					<caption>{tr}Fields{/tr}</caption>
					<tr>
						<th title="Sort order">{tr}Sort order{/tr}</th>
						<th title="Title">{tr}Title{/tr}</th>
						<th title="Description">{tr}Description{/tr}</th>
						<th title="Use at creation">{tr}Use at creation{/tr}</th>
						<th title="Values">{tr}Values{/tr}</th>
						<th title="Actions">{tr}Actions{/tr}</th>
					</tr>
                {foreach from=$fieldDefinitions item=fieldDef}
                	<tr class="{cycle values="even,odd"}">
                		<td>{$fieldDef.sort_order}</td>
                		<td><input type="text" id="field_title_{$fieldDef.def_id}" name="fieldDef[{$fieldDef.def_id}]['title']" value="{$fieldDef.title}" /></td>
                		<td><input type="text" name="fieldDef[{$fieldDef.def_id}]['description']" value="{$fieldDef.description}" /></td>
                		<td>{html_checkboxes values="1" name="fieldDef[$fieldDef.def_id]['use_at_creation']" checked=$fieldDef.use_at_creation}</td>
                		<td><ol>
                		{foreach from=$fieldValues[$fieldDef.def_id] item=fieldValue}
                		<li>{if $fieldValue.is_default}<strong>{/if}{$fieldValue.field_value}{if $fieldValue.is_default}</strong>{/if}</li>
                		{foreachelse}{tr}No values defined{/tr}
                		{/foreach}
                		</ol></td>
                		<td>
	
	                                <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_item=s&amp;tab=organise">{biticon iforce=icon ipackage="icons" iname="go-down" iexplain="move down" style="float:right"}</a>
	                                <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_items=n&amp;tab=organise">{biticon iforce=icon ipackage="icons" iname="go-up" iexplain="move up" style="float:right"}</a>
	                    </td>
	                 </tr>
                {/foreach}
               	</table>
            {/legend}
        {/jstab}
	{/jstabs}
{/form}
{/strip}
