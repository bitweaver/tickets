{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/edit_header_inc.tpl,v 1.3 2008/11/30 19:42:55 pppspoonman Exp $ *}
{strip}
    {foreach from=$fieldDefinitions item=fieldDef}
        {if (($gContent->mInfo.ticket_id) || ($fieldDef.use_at_creation == 1)) }
            <li>
            {formlabel label=$fieldDef.title|capitalize for=$fieldDef.title}
            {forminput}
                <select name="ticket[attributes][{$fieldDef.def_id}]" id="{$fieldDef.title}">
                {foreach from=$fieldValues[$fieldDef.def_id] item=fieldRow}
                    <option value="{$fieldRow.field_id}"
                    {if ($gContent->mInfo.ticket_id && $gContent->mAttributes[$fieldDef.def_id].field_id == $fieldRow.field_id) || (empty($gContent->mInfo.ticket_id) && $fieldRow.is_default == 1)} selected="selected"{/if}
                    >{$fieldRow.field_value}</option>
                {/foreach}
                </select>
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
    	{/forminput}
    </li>
{/strip}
