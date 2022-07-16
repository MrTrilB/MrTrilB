<?php
$github_repositories = get_option("github_repositories");
$readonly = ($github_repositories) ? "readonly" : "";
?>
<h1><?php echo esc_html(__("Github Hooks", "mycred-github")) ?></h1>
<div class="github-settings-wrapper">
  <div id="github-Hooks-setting" >
    <form method="post" action="options.php">
      <?php settings_fields('github-account-info-setting'); ?>
      <?php do_settings_sections('github-account-info-setting'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html(__("Github Token", "mycred-github")) ?></th>
          <td>
            <input type="text" name="github_account_info[token]" required value="<?php echo isset(get_option('github_account_info')["token"]) ? get_option('github_account_info')["token"] : '' ; ?>" <?php echo  $readonly; ?> />
          </td>
          <?php
          if ($github_repositories) {
          ?>   
            <td>
              <button id="btn-disconnect" class="button button-default"><?php echo esc_html(__("Disconnect", "mycred-github")) ?></button>
            </td>
          
          <?php } ?>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html(__("GitHub Username", "mycred-github")) ?></th>
          <td>
            <input type="text" name="github_account_info[username]" required value="<?php echo isset(get_option('github_account_info')["username"]) ? get_option('github_account_info')["username"] : '' ;      ?>" <?php echo  $readonly; ?> />
          </td>
        </tr>
        <?php
        if ($github_repositories) { ?>
          <tr valign='top'>
               <th scope='row'><?php echo esc_html(__("Repository", "mycred-github")); ?> </th> 
               <td> 
                  <select id='github_repositories' name='github_account_selected_repositories[]' multiple >
                  <?php
          $selected_repository = get_option("github_account_selected_repositories");
          foreach ($github_repositories as $key => $value) {
            $isSelected = in_array($value, $selected_repository) ? "selected" : ""; ?>
            <option value='<?php echo esc_attr($value) ?>' <?php echo esc_attr($isSelected) ?> ><?php echo esc_attr($value) ?> </option>
          <?php } ?>
            </select>
              </td>
              <td>
                <button id="btn-refresh-repositories" class="button button-default" ><?php echo esc_html(__("Refresh Repositories", "mycred-github")) ?></button>
              </td>
            </tr>
        <?php } ?>

      </table>
      <?php submit_button(); ?>

    </form>
  </div>
</div>
