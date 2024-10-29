<?php
error_reporting(E_ALL);
/*
Plugin Name: Click4Time Book Now Button
Plugin URI: http://www.click4time.com
Description: Display a Book Now button as a widget in your wordpress site. 
Version: 2.0.1
Author: Click4Time Software Inc.
Author URI: http://www.click4time.com
License:
*/
class Cft_booknow extends WP_Widget{

    function __construct()
    {
        $params = array(
            'description' => 'Display a button on your website',
            'name' => 'BookNow'
        );
        parent::__construct('Cft_booknow','',$params);
    }

    public function form($instance)
    {
       $plugin_path = plugins_url('/images/buttons/', __FILE__);
       extract($instance);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title')?>"><strong>Title</strong>  <span style="font-size: 9px"> &nbsp(Optional)</span></label>
            <input
                    class="widefat"
                    id="<?php echo $this->get_field_id('title')?>"
                    name="<?php echo $this->get_field_name('title')?>"
                    value="<?php if(isset($title)) echo esc_attr($title)?>" />

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('company_id')?>"><strong>Click4Time Unique URL</strong></label>
            <input
                class="widefat"
                id="<?php echo $this->get_field_id('company_id')?>"
                name="<?php echo $this->get_field_name('company_id')?>"
                value="<?php if(isset($company_id)){ echo $company_id; }else{ echo 'https://book.click4time.com/democlinic1/book/step1/'; } ?>" />
        </p>
		 <p>
            <label for="<?php echo $this->get_field_id('size')?>"><strong>Size</strong></label><br />
			<fieldset>
			<input
                type="radio"
                class="radio"
                id="<?php echo $this->get_field_id('size')?>"
                name="<?php echo $this->get_field_name('size')?>"
                value="small"
                <?php if(isset($size) && $size == "small") echo "checked='checked'"?>/> Small</p>

			<input
                type="radio"
                class="radio"
                id="<?php echo $this->get_field_id('size')?>"
                name="<?php echo $this->get_field_name('size')?>"
                value="medium"
                <?php if(isset($size) && $size == "medium") echo "checked='checked'"?>/> Medium</p>

			<input
                type="radio"
                class="radio"
                id="<?php echo $this->get_field_id('size')?>"
                name="<?php echo $this->get_field_name('size')?>"
                value="large"
                <?php if(isset($size) && $size == "large") echo "checked='checked'"?>/> Large</p>
			</fieldset>
        </p>
        <p><h4>Select Button</h4></p>
        <div>
            <p style="display: inline-block; vertical-align: top;">
            <input
                type="radio"
                class="radio"
                id="<?php echo $this->get_field_id('button')?>"
                name="<?php echo $this->get_field_name('button')?>"
                value="blue"
                <?php if(isset($button) && $button == "blue") echo "checked='checked'"?>/></p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_blue.png"></p>
        </div>
        <div>
            <p style="display: inline-block; vertical-align: top;">
                <input
                    type="radio"
                    class="radio"
                    id="<?php echo $this->get_field_id('button')?>"
                    name="<?php echo $this->get_field_name('button')?>"
                    value="gold"
                    <?php if(isset($button) && $button == "gold") echo "checked='checked'"?>/>
            </p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_gold.png"></p>
        </div>
        <div>
            <p style="display: inline-block; vertical-align: top;">
                <input
                     type="radio"
                     class="radio"
                     id="<?php echo $this->get_field_id('button')?>"
                     name="<?php echo $this->get_field_name('button')?>"
                     value="green"
                    <?php if(isset($button) && $button == "green") echo "checked='checked'"?>/>
            </p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_green.png"></p>
        </div>
        <div>
            <p style="display: inline-block; vertical-align: top;">
                <input
                        type="radio"
                        class="radio"
                        id="<?php echo $this->get_field_id('button')?>"
                        name="<?php echo $this->get_field_name('button')?>"
                        value="purple"
                    <?php if(isset($button) && $button == "purple") echo "checked='checked'"?>/>
            </p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_purple.png"></p>
        </div>
        <div>
            <p style="display: inline-block; vertical-align: top;">
                <input
                        type="radio"
                        id="<?php echo $this->get_field_id('button')?>"
                        name="<?php echo $this->get_field_name('button')?>"
                        value="red"
                    <?php if(isset($button) && $button == 5) echo "checked='checked'"?>/>
            </p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_red.png"></p>
        </div>
		<div>
            <p style="display: inline-block; vertical-align: top;">
                <input
                        type="radio"
                        id="<?php echo $this->get_field_id('button')?>"
                        name="<?php echo $this->get_field_name('button')?>"
                        value="wood"
                    <?php if(isset($button) && $button == "wood") echo "checked='checked'"?>/>
            </p>
            <p style="display: inline-block"><img src="<?php echo $plugin_path ?>small_wood.png"></p>
        </div>
        <?php
    }

    public function widget($args, $instance)
    {
        extract($args);
        extract($instance);

        echo $before_widget;
            if(isset($title))
                echo $before_title."<p>".$title."</p>".$after_title;
            if(isset($company_id))
            {
	       $plugin_path = plugins_url('/images/buttons/', __FILE__);
               echo '<script src="http://book.click4time.com/js/cp_window.js"></script><a onClick=open_new_window("https://book.click4time.com/'.$company_id.'/book/step1") href="#" title="Book Online"><img src="'.$plugin_path.$size.'_'.$button.'.png" alt="Book online now" border="0" /></a>';
            }
        echo $after_widget;
    }
}

add_action('widgets_init','cft_register_booknow');

function cft_register_booknow()
{
    register_widget('Cft_booknow');
}
