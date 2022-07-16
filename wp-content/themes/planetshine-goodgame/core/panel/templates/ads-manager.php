<?php
    $head = GOODGAME_SETTINGS_INSTANCE()->admin_head;
    $body = GOODGAME_SETTINGS_INSTANCE()->admin_body;
    $view = goodgame_get($_GET, 'view', $head[key($head)]['slug']);   //get view; defaults to first element of header
    $section = goodgame_get($_GET, 'section', 'ads_manager');   //get view; defaults to first element of header

    if($section == 'ads_manager')
    {
        goodgame_get_admin_template('ads-edit');
    }
    else
    {
        goodgame_get_admin_template('ads-locations');
    }
?>
