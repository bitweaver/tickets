{* $Header$ *}
{strip}
	<div class="header">
		<div class="date">
			{tr}by{/tr} {displayname user=$history.creator_user real_name=$history.creator_real_name}, {$history.change_date|reltime}
		</div>
		<div class="content">
			{tr}Change{/tr}: <strong>{tr}{$history.def_title}{/tr}</strong> {tr}from{/tr} {$history.old_value} {tr}to{/tr} {$history.new_value}.
		</div>
	</div>
{/strip}
