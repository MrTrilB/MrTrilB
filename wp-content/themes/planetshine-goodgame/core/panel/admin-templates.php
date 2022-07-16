<?php

function goodgame_sidebar()
{
    goodgame_get_admin_template('sidebar');
}

function goodgame_option_list()
{
    goodgame_get_admin_template('option-list');
}

function goodgame_sidebar_manager()
{
    goodgame_get_admin_template('sidebar-manager');
}

function goodgame_ads_manager()
{
    goodgame_get_admin_template('ads-manager');
}

function goodgame_admin()
{
    goodgame_get_admin_template('admin-layout');
}

function goodgame_support_iframe()
{
    ?>
        <iframe class="support-iframe" src="<?php echo goodgame_gs('support_url') ?>" height="100%" border="none"></iframe>
    <?php
}

function goodgame_google_fonts()
{
	goodgame_get_admin_template('google-fonts');
}

function goodgame_setup_section()
{
    $section_key = goodgame_get($_GET, 'section', 'status');

    if($section_key == 'demo_import')
    {
        goodgame_get_admin_template('demo-import');
    }
    elseif($section_key == 'backup_reset')
    {
        goodgame_get_admin_template('backup-reset');
    }
    elseif($section_key == 'load_preset')
    {
        goodgame_get_admin_template('load-preset');
    }
    elseif($section_key == 'install_pages')
    {
        goodgame_get_admin_template('install-pages');
    }
    else
    {
        goodgame_get_admin_template('status');
    }
}
?>
