<?php
/*
Plugin Name: Social Web Widgets
Plugin URI: https://socialwebwidgets.com/
Description: SWW WordPress Plugin for Instagram. We are working to make your Instagram account appear on your website as well. Your Instagram widgets add to your website, we make you look more professional.
Version: 1.0
*/

function social_web_widgets_sww() {
    $Content = "<div id=\"SWW\"></div>";
    return $Content;
}

if (function_exists('social_web_widgets_sww'))
{
    add_shortcode('instagram_sww', 'social_web_widgets_sww');
}
if (function_exists('xSWWx_ScriptAddtoFooter'))
{
    add_action( 'wp_footer', 'xSWWx_ScriptAddtoFooter' );
}
function xSWWx_ScriptAddtoFooter(){
?>
    <script id="SWWJS" BaseUrl="https://www.socialwebwidgets.com/" ApiKey="<?php echo get_option("sww_api_key"); ?>" WidgetID="<?php echo get_option("sww_widget_id"); ?>" type="text/javascript" src="https://www.socialwebwidgets.com/assets/CDN/sww.min.js"></script>
<?php
}

function xSWWx_LoadJquery() {
    if ( ! wp_script_is( 'jquery', 'enqueued' )) {
        wp_enqueue_script( 'jquery' );
    }
}
if (function_exists('xSWWx_LoadJquery'))
{
    add_action( 'wp_enqueue_scripts', 'xSWWx_LoadJquery' );
}

function xSWWx_jQueryVersion() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', "https://code.jquery.com/jquery-3.4.1.min.js", array(), '3.4.1' );
}
if (function_exists('xSWWx_jQueryVersion'))
{
    add_action( 'wp_enqueue_scripts', 'xSWWx_jQueryVersion' );
}

if (function_exists('sww_menu'))
{
    add_action('admin_menu', 'sww_menu');
}
function sww_menu(){
    add_menu_page('SWW Options','SWW Options', 'manage_options', 'sww-options', 'sww_options_page');
}

function sww_options_page(){
    if($_POST["action"] == "sww_save_settings"){
        if (!isset($_POST['sww_updatex']) || ! wp_verify_nonce( $_POST['sww_updatex'], 'sww_updatex' ) ) {
            print 'You do not have permission to access this page.';
            exit;
        }else{
            $sww_api_key = sanitize_text_field($_POST['sww_api_key']);
            $sww_widget_id = sanitize_text_field($_POST['sww_widget_id']);
            update_option('sww_api_key', $sww_api_key);
            update_option('sww_widget_id', $sww_widget_id);
            echo'<div class="updated"><p><strong>Settings have been saved.</strong></p></div>';
        }
    }
?>
    <div class="wrap">
        <h1>SWW Options</h1>
        <div class="card">
            <h2 class="title">Social Web Widgets</h2>
            <p>Are you ready for new experience with Social Web Widgets?<br>You can get an <strong>API KEY</strong> and <strong>WIDGET ID</strong> by cliking the button below.</p>
            <p><a href="https://www.socialwebwidgets.com/" class="button button-primary" target="_new">Set up your Social Web Widget account</a></p>
        </div>
        <div class="card">
            <form method="post">
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th>
                            <label for="sww_api_key">API KEY</label>
                        </th>
                        <td>
                            <input name="sww_api_key" id="sww_api_key" type="text" pattern="[a-zA-Z0-9-]{32}" maxlength="32" value="<?php echo get_option("sww_api_key"); ?>" class="regular-text code" required="" title="Please enter 32 characters.">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="sww_widget_id">WIDGET ID</label>
                        </th>
                        <td>
                            <input name="sww_widget_id" id="sww_widget_id" type="number" min="1" value="<?php echo get_option("sww_widget_id"); ?>" class="regular-text code" required="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <input type="hidden" name="action" value="sww_save_settings">
                <?php wp_nonce_field('sww_updatex','sww_updatex'); ?>
                <p>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings">
                </p>
            </form>
        </div>
    </div>
    <?php
}