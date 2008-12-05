{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/list_history_inc.tpl,v 1.1 2008/12/05 23:03:48 pppspoonman Exp $ *}
{strip}
    <li>{$history.change_date|reltime} {displayname user=$history.creator_user real_name=$history.creator_real_name} changed <strong>{$history.def_title}</strong> from {$history.old_value} to {$history.new_value}
{/strip}
