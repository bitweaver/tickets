{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/edit_header_inc.tpl,v 1.5 2008/12/01 19:07:03 pppspoonman Exp $ *}
{strip}
    {foreach from=$fieldDefinitions item=fieldDef}
        {if (($gContent->mInfo.ticket_id) || ($fieldDef.use_at_creation == 1)) }
            <li>
            {formlabel label=$fieldDef.title|capitalize for=$fieldDef.title}
            {forminput}
            	{if $gBitUser->hasPermission( 'p_tickets_update' )}
	                <select name="ticket[attributes][{$fieldDef.def_id}]" id="{$fieldDef.title}">
	                {foreach from=$fieldValues[$fieldDef.def_id] item=fieldRow}
	                    <option value="{$fieldRow.field_id}"
	                    {if ($gContent->mInfo.ticket_id && $gContent->mAttributes[$fieldDef.def_id].field_id == $fieldRow.field_id) || (empty($gContent->mInfo.ticket_id) && $fieldRow.is_default == 1)} selected="selected"{/if}
	                    >{$fieldRow.field_value}</option>
	                {/foreach}
	                </select>
	            {else}
	            	{assign var="row" value=$gContent->mAttributes[$fieldDef.def_id]}
	            	{if $row}
	            		{$row.field_value}
	            	{else}
	            		{tr}None{/tr}
	            	{/if}
	            {/if}
                {formhelp note=$fieldDef.description}
            {/forminput}
            </li>
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
    <li>
    	{formlabel label="Milestone" for="milestone"}
    	{forminput}
    		<select name="ticket[milestone]" id="milestone">
    		{foreach from=$milestones item=milestone}
    			<option value="{$milestone.milestone_id}"
    			{if ( $gContent->mInfo.ticket_id && $gContent->mMilesone.milestone_id == $milestone.milestone_id )} selected="selected" {/if}
    			>{$milestone.title}</option>
    		{/foreach}
    		</select>
    		{formhelp note="Group of named tickets"}
    	{/forminput}
    </li>
{/strip}
