{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/edit_header_inc.tpl,v 1.2 2008/11/26 18:22:03 pppspoonman Exp $ *}
{strip}
    {foreach from=$fieldDefinitions item=fieldDef}
        {if (($gContent->mInfo.ticket_id) || ($fieldDef.use_at_creation == 1)) }
            <div class="row">
            {formlabel label=$fieldDef.title|capitalize for=$fieldDef.def_id}
            {forminput}
                <select name="ticket[attributes][{$fieldDef.def_id}]" id="{$fieldDef.def_id}">
                {foreach from=$fieldValues[$fieldDef.def_id] item=fieldRow}
                    <option value="{$fieldRow.field_id}"
                    {if ($gContent->mInfo.ticket_id && $gContent->mAttributes[$fieldDef.def_id].field_id == $fieldRow.field_id) || (empty($gContent->mInfo.ticket_id) && $fieldRow.is_default == 1)} selected="selected"{/if}
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
    <div class="row">
    	{formlabel label="Milestone" for=""}
    	{forminput}
    		<select name="ticket[milestone]">
    		{foreach from=$milestones item=milestone}
    			<option value="{$milestone.milestone_id}"
    			{if ($gContent->mInfo.ticket_id && array_key_exists( $gContent->mInfo.ticket_id, $milestone ) )} selected="selected" {/if}
    			>{$milestone.title}</option>
    		{/foreach}
    	{/forminput}
    </div>
{/strip}
