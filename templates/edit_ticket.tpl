{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/edit_ticket.tpl,v 1.6 2008/11/22 12:29:54 pppspoonman Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin tickets">
	{if $smarty.request.preview}
		<h2>Preview {$gContent->mInfo.title|escape}</h2>
		<div class="preview">
			{include file="bitpackage:tickets/ticket_display.tpl" page=`$gContent->mInfo.ticket_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.ticket_id}
				{tr}Edit {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New Record{/tr}
			{/if}
		</h1>
	</div>

	<div class="body">
		{form enctype="multipart/form-data" id="editticketsform"}
			{jstabs}
				{jstab}
					{legend legend="Edit/Create Tickets Record"}
						<input type="hidden" name="ticket[ticket_id]" value="{$gContent->mInfo.ticket_id}" />
						
						<div class="row">
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="60" maxlength="200" name="ticket[title]" id="title" value="{$gContent->mInfo.title|escape}" />
								{formhelp note="Brief and meaningful summary of a ticket."}
							{/forminput}
						</div>

						{foreach from=$fieldDefinitions item=fieldDef}
                            {if (($gContent->mInfo.ticket_id) || ($fieldDef.use_at_creation == 1)) }
                                <div class="row">
                                {formlabel label=$fieldDef.title|capitalize for=$fieldDef.def_id}
                                {forminput}
                                    <select name="ticket[attributes][{$fieldDef.def_id}]" id="{$fieldDef.def_id}">
                                    {foreach from=$fieldValues[$fieldDef.def_id] item=fieldRow}
                                        <option value="{$fieldRow.field_id}"
                                        {if ($gContent->mInfo.ticket_id && $gContent->mInfo.attributes[$fieldDef.def_id] == $fieldRow.field_id) || $fieldRow.is_default eq 1} selected="selected"{/if}
                                        >{$fieldRow.field_value}</option>
                                    {/foreach}
                                    </select>
                                    {formhelp note=$fieldDef.description}
                                {/forminput}
                                </div>
                            {else}
                            	<input type="hidden" name="ticket[attributes][{$fieldDef.def_id}]" id="{$fieldDef.def_id}"
                            	{foreach from=$fieldValues[$fieldDef.def_id] item=fieldRow}
                            		{if ($fieldRow.is_default == 1) }
                            			value="{$fieldRow.field_id}"
                            		{/if}
                            	{/foreach}
                            	>
                            {/if}
						{/foreach}

						{textarea name="ticket[edit]"}{/textarea}

						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_mini_tpl}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" name="save_ticket" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}

				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_tab_tpl}
			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .tickets -->

{/strip}
