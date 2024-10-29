<?php
/**
 * Plugin Name: Click4Time
 * Plugin URI: http://www.click4time.com
 * Description: <strong>Click4Time Online Scheduling and Appointment Booking System.</strong> To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for a <a href="https://book.click4time.com/signup?ase4_ref=PIN151" target="_blank">Vendor Business Account</a>, and 3) Go to your Click4Time configuration page and save your embedded calendar code as instructed.
 * Version: 2.0.1
 * Author: Click4Time Software Inc.
 * Author URI: http://www.click4time.com
 */
$cal_content;

// Enable internationalisation
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'click4time','wp-content/plugins/'.$plugin_dir, $plugin_dir);

// Define the tables used in Click4Time
global $wpdb;
define('WP_C4T_CALENDAR_TABLE', $wpdb->prefix . 'c4t_calendar');

// Check ensure calendar is installed and install it if not - required for
// the successful operation of most functions called from this point on
check_c4t_calendar();
add_action('admin_menu', 'click4time_menu');
add_filter('the_content','c4t_calendar_insert');

// Function to check what version of Calendar is installed and install if needed
function check_c4t_calendar()
{
  // Checks to make sure Calendar is installed, if not it adds the default
  // database tables and populates them with test data. If it is, then the 
  // version is checked through various means and if it is not up to date 
  // then it is upgraded.

  // Lets see if this is a first run and create us a table if it is
  global $wpdb, $initial_style;

  // Assume this is not a new install until we prove otherwise
  $new_install = true;
  $wp_c4t_calendar_exists = false;

  // Determine the calendar version
  $tables = $wpdb->get_results("show tables");
  foreach ( $tables as $table ){
      foreach ( $table as $value ){
		  if ( $value == WP_C4T_CALENDAR_TABLE ){
			  $wp_c4t_calendar_exists = true;
			  $new_install = false;
		   }
	  }
  }
  // Now we've determined what the current install is or isn't 
  // we perform operations according to the findings
  if ( $new_install == true ){
	  $sql = "CREATE TABLE " . WP_C4T_CALENDAR_TABLE . " (
								c4t_id INT(11) NOT NULL AUTO_INCREMENT ,
								embedded_code TEXT ,
								PRIMARY KEY (c4t_id)
						)";
	  $wpdb->get_results($sql);

	  $sql = "INSERT INTO ".WP_C4T_CALENDAR_TABLE." SET embedded_code='<script src=\"https://book.click4time.com/js/frontend/widget.js\"></script>
<script>Click4Time.Calendar.step1(''n3sHCDfuRwPxxMFGIQMe1EoLCuDGyMzjV6kYnO2'', 1);</script>'";
	  $wpdb->get_results($sql);
    }
}

// Function to deal with adding the Click4Time menus
function click4time_menu()
{
	// Add the admin panel pages for Click4Time.
	if(function_exists('add_menu_page')){
		add_menu_page('Click4Time', 'Click4Time', 'manage_options', 'click4time', 'edit_click4time_config');
	}
}

// Actually do the printing of the calendar
function c4t_calendar($cat_list = '')
{
	global $wpdb;
    $sql = "SELECT embedded_code FROM " . WP_C4T_CALENDAR_TABLE;
	$cal = $wpdb->get_row($sql);
    $cal_body = $cal->embedded_code;
    return $cal_body;
}

// Function to deal with loading the calendar into pages
function c4t_calendar_insert($content)
{
  if (preg_match('/\[CLICK4TIME_CAL*.+\]/',$content))
    {
      $cat_list = preg_split('/\[CLICK4TIME_CAL\;/',$content);
      if (sizeof($cat_list) > 1) {
	$cat_list = preg_split('/\]/',$cat_list[1]);
        $cat_list = $cat_list[0];
        $cal_output = c4t_calendar($cat_list);
      } else {
	$cal_output = c4t_calendar();
      }
      $content = preg_replace('/\[CLICK4TIME_CAL*.+\]/',$cal_output,$content);
    }
  return $content;
}

function edit_click4time_config(){
	global $wpdb;
	if(isset($_POST['cal_content']) && !empty($_POST['cal_content'])){
		$cal_content = $_POST['cal_content'];
		// Proceed with the save
       $sql = "UPDATE " . WP_C4T_CALENDAR_TABLE . " SET embedded_code='".$cal_content."'";
	   $wpdb->get_results($sql);
	}
	$sql = "SELECT embedded_code FROM " . WP_C4T_CALENDAR_TABLE;
	$cal = $wpdb->get_row($sql);
    $cal_content = $cal->embedded_code;
	?>
<div class="wrap">
<h2>Click4Time</h2>
<form name="quoteform" id="quoteform" class="wrap" method="post"
	action="<?php echo bloginfo('wpurl'); ?>/wp-admin/admin.php?page=click4time">
<div id="linkadvanceddiv" class="postbox" style="padding:10px;line-height:25px;width:600px;" >
<img src="https://book.click4time.com/apple-touch-icon.png" width="80" height="80" style="float:left;padding:10px 10px 10px 0;" />
<p><a href="https://book.click4time.com/signup?ase4_ref=PIN151">
Click Here</a> - to create a New Account and start your free trial!</p>
<p>After logging into the <strong>Click4Time Online Scheduling and Appointment Booking
System</strong>, from Dashboard, go to the "Marketing / Book Now Button" tab to generate and copy the Embedded Calendar code.  Paste this code into the <strong>Embedded Code</strong> box in the Click4Time WordPress plugin page.</p>
<table cellspacing="5" cellpadding="5">
	<tbody>
		<tr>
			<td style="vertical-align: top;width: 100px;"><b>Embedded Code</b></td>
			<td><textarea class="input" cols="80" rows="4" name="cal_content"><?php echo $cal_content; ?></textarea>
			</td>
		</tr>
	</tbody>
</table>
<input type="submit" name="save" class="button bold" value="Save" />
</div>
</form>
<p>To add the Click4Time Booking Calendar into your post or page, simply insert the [CLICK4TIME_CAL] tag where you want the calendar to display.</p>
<p>You can also place a "Book Now" button in the Sidebar of your site by going to Appearance / Widgets in your WordPress admin and dragging the Book Now widget to the desired Sidebar location. Once added to the Sidebar, click the widget to add a Title and choose the button size & color. Alternatively you can add a Book Now Button to a page or post by going to your Click4Time Dashboard "Marketing / Book Now Button" tab, selecting a button style and copying either the Full Screen or Integrated View code, then pasting it in a Post or Page where you want the button to appear.</p>
</div>
	<?php
}
?>