<?php
class SlickC_SettingsPage {
	// Holds the values to be used in the fields callbacks
	private $options;
			
	// Start up
	public function __construct() {
	    add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
	}
			
	// Add settings page
	public function add_plugin_page() {
		add_submenu_page(
            'edit.php?post_type=slickc',
            __('Settings', 'slick-carousel'),
            __('Settings', 'slick-carousel'),
            'manage_options',
            'slick-carousel',
            array($this, 'create_admin_page')
        );
	}
			
	// Options page callback
	public function create_admin_page() {
		// Set class property
		$this->options = get_option('slickc_settings');
		if (!$this->options) {
			slickc_set_options();
			$this->options = get_option('slickc_settings');
		}
		?>
		<div class="wrap">
		<h2>Slick Carousel <?php _e('Settings', 'slick-carousel') ?></h2>
		<p><?php printf(__('You can set the default behaviour of your carousels here.')) ?></p>
					 
				<form method="post" action="options.php">
				<?php
                settings_fields('slickc_settings');   
                do_settings_sections('slick-carousel');
                submit_button(); 
				?>
				</form>
		</div>
		<?php
	}
		
    private function registerSettings() {
        register_setting(
            'slickc_settings', // Option group
            'slickc_settings', // Option name
            array($this, 'sanitize') // Sanitize
		);
    }
    
    private function registerSections() { 
        // Sections
		add_settings_section(
            'slickc_settings_behaviour',
            __('Carousel Behaviour', 'slick-carousel'),
            array($this, 'slickc_settings_behaviour_header'),
            'slick-carousel'
		);
		add_settings_section(
            'slickc_settings_navigation',
            __('Carousel Navigation', 'slick-carousel'),
            array($this, 'slickc_settings_navigation_header'),
            'slick-carousel'
		);
		add_settings_section(
            'slickc_settings_misc',
            __('Miscellaneous', 'slick-carousel'),
            array( $this, 'slickc_settings_misc_header' ),
            'slick-carousel'
		);
    }
    
	// Register and add settings
	public function page_init() {
        $this->registerSettings();
		$this->registerSections();
        $this->registerFields();
    }
    
    private function registerFields() {
        
		add_settings_field(
            'accessibility', // ID
            __('Enables tabbing and arrow key navigation', 'slick-carousel'), // Title
            array( $this, 'accessibility_callback'), // Callback
            'slick-carousel', // Page
            'slickc_settings_misc' // Section
		);
		add_settings_field(
            'orderby', // ID
            __('Order Slides By', 'slick-carousel'), // Title 
            array( $this, 'orderby_callback'), // Callback
            'slick-carousel', // Page
            'slickc_settings_behaviour' // Section		   
		);
		add_settings_field(
            'order', // ID
            __('Ordering Direction', 'slick-carousel'), // Title 
            array( $this, 'order_callback'), // Callback
            'slick-carousel', // Page
            'slickc_settings_behaviour' // Section		   
		);
		add_settings_field(
            'category', // ID
            __('Restrict to Category', 'slick-carousel'), // Title 
            array( $this, 'category_callback'), // Callback
            'slick-carousel', // Page
            'slickc_settings_behaviour' // Section		   
		);
        // add_settings_field(
        //     'adaptiveHeight',
        //     __('Enables adaptive height for single slide horizontal carousels', 'slick-carousel'),
        //     array($this, 'adaptiveHeight_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        add_settings_field(
            'autoplay',
            __('Autoplay slides', 'slick-carousel'),
            array($this, 'autoplay_callback'),
            'slick-carousel',
            'slickc_settings_behaviour'
        );
        add_settings_field(
            'autoplaySpeed',
            __('Autoplay speed', 'slick-carousel'),
            array($this, 'autoplaySpeed_callback'),
            'slick-carousel',
            'slickc_settings_behaviour'
        );
        add_settings_field(
            'arrows',
            __('Navigation arrows', 'slick-carousel'),
            array($this, 'arrows_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        // add_settings_field(
        //     'asNavFor',
        //     __('As navigation for...', 'slick-carousel'),
        //     array($this, 'asNavFor_callback'),
        //     'slick-carousel',
        //     'slickc_settings_navigation'
        // );
        // add_settings_field(
        //     'appendArrows',
        //     __('Append navigation arrows', 'slick-carousel'),
        //     array($this, 'appendArrows_callback'),
        //     'slick-carousel',
        //     'slickc_settings_navigation'
        // );
        add_settings_field(
            'prevArrow',
            __('Custom previous arrow HTML', 'slick-carousel'),
            array($this, 'prevArrow_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        add_settings_field(
            'nextArrow',
            __('Custom next arrow HTML', 'slick-carousel'),
            array($this, 'nextArrow_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        // add_settings_field(
        //     'centerMode',
        //     __('Center mode', 'slick-carousel'),
        //     array($this, 'centerMode_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        // add_settings_field(
        //     'centerPadding',
        //     __('Center Padding', 'slick-carousel'),
        //     array($this, 'centerPadding_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        add_settings_field(
            'cssEase',
            __('CSS3 Animation Easing', 'slick-carousel'),
            array($this, 'cssEase_callback'),
            'slick-carousel',
            'slickc_settings_misc'
        );
        add_settings_field(
            'easing',
            __('Easing', 'slick-carousel'),
            array($this, 'easing_callback'),
            'slick-carousel',
            'slickc_settings_misc'
        );
        add_settings_field(
            'dots',
            __('Dots', 'slick-carousel'),
            array($this, 'dots_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        // add_settings_field(
        //     'draggable',
        //     __('Draggable', 'slick-carousel'),
        //     array($this, 'dragabble_callback'),
        //     'slick-carousel',
        //     'slickc_settings_navigation'
        // );
        add_settings_field(
            'fade',
            __('Enable Fade', 'slick-carousel'),
            array($this, 'fade_callback'),
            'slick-carousel',
            'slickc_settings_misc'
        );
        // add_settings_field(
        //     'focusOnSelect',
        //     __('Focus On Select', 'slick-carousel'),
        //     array($this, 'focusOnSelect_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        add_settings_field(
            'infinite',
            __('Infinite scrolling', 'slick-carousel'),
            array($this, 'infinite_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        add_settings_field(
            'initialSlide',
            __('Initial Slide', 'slick-carousel'),
            array($this, 'initialSlide_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        // add_settings_field(
        //     'lazyLoad',
        //     __('Lazy Load', 'slick-carousel'),
        //     array($this, 'lazyLoad_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        add_settings_field(
            'pauseOnHover',
            __('Pause On Hover', 'slick-carousel'),
            array($this, 'pauseOnHover_callback'),
            'slick-carousel',
            'slickc_settings_behaviour'
        );
        add_settings_field(
            'pauseOnDotsHover',
            __('Pause On Dots Hover', 'slick-carousel'),
            array($this, 'pauseOnDotsHover_callback'),
            'slick-carousel',
            'slickc_settings_behaviour'
        );
        // add_settings_field(
        //     'respondTo',
        //     __('Respond To...', 'slick-carousel'),
        //     array($this, 'respondTo_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        // add_settings_field(
        //     'slidesToShow',
        //     __('Slides To Show', 'slick-carousel'),
        //     array($this, 'slidesToShow_callback'),
        //     'slick-carousel',
        //     'slickc_settings_behaviour'
        // );
        // add_settings_field(
        //     'slidesToScroll',
        //     __('Slides To Scroll', 'slick-carousel'),
        //     array($this, 'slidesToScroll_callback'),
        //     'slick-carousel',
        //     'slickc_settings_behaviour'
        // );
        add_settings_field(
            'speed',
            __('Speed', 'slick-carousel'),
            array($this, 'speed_callback'),
            'slick-carousel',
            'slickc_settings_behaviour'
        );
        add_settings_field(
            'swipe',
            __('Swipe', 'slick-carousel'),
            array($this, 'swipe_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        ); 
        add_settings_field(
            'swipeToSlide',
            __('Swipe To Slide', 'slick-carousel'),
            array($this, 'swipeToSlide_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        add_settings_field(
            'touchMove',
            __('Touch To Move', 'slick-carousel'),
            array($this, 'touchMove_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        add_settings_field(
            'touchThreshold',
            __('Touch Threshold', 'slick-carousel'),
            array($this, 'touchThreshold_callback'),
            'slick-carousel',
            'slickc_settings_navigation'
        );
        // add_settings_field(
        //     'useCSS',
        //     __('Use CSS', 'slick-carousel'),
        //     array($this, 'useCSS_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        // add_settings_field(
        //     'variableWidth',
        //     __('Variable Width', 'slick-carousel'),
        //     array($this, 'variableWidth_callback'),
        //     'slick-carousel',
        //     'slickc_settings_misc'
        // );
        // add_settings_field(
        //     'vertical',
        //     __('Vertical', 'slick-carousel'),
        //     array($this, 'vertical_callback'),
        //     'slick-carousel',
        //     'slickc_settings_behaviour'
        // );
        // add_settings_field(
        //     'rtl',
        //     __('Right-To-Left', 'slick-carousel'),
        //     array($this, 'rtl_callback'),
        //     'slick-carousel',
        //     'slickc_settings_behaviour'
        // );
        
	}
    
    private function addCheckbox($key, $trueValue = null, $falseValue = null, $description = null) {
        $true = ' selected="selected"';
        $false = '';
        
        if (!$trueValue) {
            $trueValue = 'Yes';
        }
        if (!$falseValue) {
            $falseValue = 'No';
        }
		if (isset($this->options[$key]) && !$this->options[$key]) {
			$true = '';
			$false = ' selected="selected"';
		}
		echo '<select id="slickc_' . $key . '" name="slickc_settings[' . $key . ']">
			<option value="true"' . $true . '>' . __($trueValue, 'slick-carousel') . '</option>
			<option value=""' . $false . '>' . __($falseValue, 'slick-carousel') . '</option>
		</select>';
        if (!$description) return;
        echo '<p class="description">' . __($description, 'slick-carousel') . '</p>';
    }
    
    public function swipe_callback() {
        $this->addCheckbox('swipe', null, null, 'Enable swiping');
    }
    
    public function swipeToSlide_callback() {
        $this->addCheckbox(
            'swipeToSlide',
            null,
            null,
            'Allow users to drag or swipe directly to a slide irrespective of slidesToScroll.'
        );
    }  
    
    public function touchMove_callback() {
        $this->addCheckbox(
            'touchMove',
            null,
            null,
            'Enable slide motion with touch'
        );
    }
    
    public function touchThreshold_callback() {
        $this->addTextbox(
            'touchThreshold',
            'To advance slides, the user must swipe a length ' .
            'of (1/touchThreshold) * the width of the slider.',
            '40px'
        );
    }
    
    public function useCSS_callback() {
        $this->addCheckbox(
            'useCSS',
            null,
            null,
            'Enable/Disable CSS Transitions'
        );
    }
    
    public function variableWidth_callback() {
        $this->addCheckbox(
            'variableWidth',
            null,
            null,
            'Variable width slides.'
        );
    }
    
    public function vertical_callback() {
        $this->addCheckbox(
            'vertical',
            null,
            null,
            'Vertical slide mode.'
        );
    }
    
    public function rtl_callback() {
        $this->addCheckbox(
            'rtl',
            null,
            null,
            'Change the slider\'s direction to become right-to-left.'
        );
    }
    
    public function slidesToShow_callback() {
        $this->addTextbox('slidesToShow', 'Number of slides to show', '40px');
    }
    
    public function slidesToScroll_callback() {
        $this->addTextbox('slidesToScroll', 'Number of slides to scroll', '40px');
    }
    
    public function speed_callback() {
        $this->addTextbox('speed', 'Slide/Fade animation speed (ms)', '40px');
    }
    
    public function respondTo_callback() {
        $this->addTextbox(
            'respondTo',
            'Width that responsive object responds to. Can be "window", ' .
            '"slider" or "min" (the smaller of the two).',
            '100px'
        );
    }
    
    public function lazyLoad_callback() {
        $this->addTextbox('lazyLoad', 'Set lazy loading technique. Accepts "ondemand" or "progressive".', '100px');
    }
    
    public function pauseOnHover_callback() {
        $this->addCheckbox('pauseOnHover', null, null, 'Pause autoplay on hover');
    }
    
    public function pauseOnDotsHover_callback() {
        $this->addCheckbox('pauseOnDotsHover', null, null, 'Pause autoplay on dots hover');
    }
    
    public function initialSlide_callback() {
        $this->addTextbox('initialSlide', 'Slide to start on', '40px');
    }
    
    public function focusOnSelect_callback() {
        $this->addCheckbox('focusOnSelect', null, null, 'Enable focus on selected element (click)');
    }
    
    public function infinite_callback() {
        $this->addCheckbox('focusOnSelect', null, null, 'Infinite loop sliding');
    }
    
    public function fade_callback() {
        $this->addCheckbox('fade', 'Enabled', 'Disabled', 'Disabled fade only for slider without slide caption text');
    }
    
    public function dragabble_callback() {
        $this->addCheckbox('draggable', 'Yes', 'No', 'Enable mouse dragging');
    }
            
    public function dots_callback() {
        $this->addCheckbox('dots', 'Yes', 'No', 'Show dot indicators');
    }

    public function cssEase_callback() {
        $this->addTextbox('cssEase');
    }
    
    public function easing_callback() {
        $this->addTextbox(
            'easing',
            'Add easing for jQuery animate. Use with easing libraries or default easing methods',
            '158px'
        );
    }
    
    public function arrows_callback() {
        $this->addCheckbox('arrows', 'Yes', 'No');
	}
    
    public function centerMode_callback() {
        $this->addCheckbox(
            'centerMode',
            null,
            null,
            'Enables centered view with partial prev/next slides. ' .
            'Use with odd numbered slidesToShow counts');
    }
    
    public function centerPadding_callback() {
        $this->addTextbox('centerPadding', 'Side padding when in center mode (px or %)', '80px');
    }
    
    public function accessibility_callback() {
        $this->addCheckbox('accessibility');
	}
    
    public function adaptiveHeight_callback() {
        $this->addCheckbox('adaptiveHeight');
	}
    
    public function autoplay_callback() {
        $this->addCheckbox('autoplay', 'Yes', 'No');
	}
    
    public function autoplaySpeed_callback() {
        $this->addTextbox('autoplaySpeed', 'Autoplay speed in milliseconds', '60px');
    }
    
    public function nextArrow_callback() {
        $this->addTextbox('nextArrow', 'Allows you to select a node or customize the HTML for the "Next" arrow');
    }
    
    public function prevArrow_callback() {
        $this->addTextbox('prevArrow', 'Allows you to select a node or customize the HTML for the "Previous" arrow');
    }
    
    public function appendArrows_callback() {
        $this->addTextbox('appendArrows', 'Change where the navigation arrows are attached (Selector, htmlString)');
    }
    
    public function asNavFor_callback() {
        $this->addTextbox(
            'asNavFor',
            'Set the slider to be the navigation of other slider (Class or ID Name)',
            '158px'
        );
    }
    
    private function addTextbox($key, $description = null, $width = null) {
        if (!$width) {
            $width = '60%';
        }
        $width = str_replace('%', '%%', $width);
        printf(
            '<input type="text" id="' .
            $key .
            '" name="slickc_settings[' .
            $key .
            ']" value="%s"  ' . 
            'style="width: ' . $width .
            '" />',
            isset($this->options[$key]) ? esc_attr($this->options[$key]) : ''
        );
        if (!$description) return;
        echo '<p class="description">' . __($description, 'slick-carousel') . '</p>';
	}

	public function sanitize($input) {
		$cleaned = array();
		foreach ($input as $key => $value) {
            switch ($key) {
                /* int */
                case 'autoplaySpeed':
                case 'initialSlide':
                case 'slidesToShow':
                case 'slidesToScroll':
                case 'speed':
                case 'touchThreshold':
                    if (!is_numeric($value)) break;
                    $cleaned[$key] = absint($value);
                    break;
                /* string */
                case 'asNavFor':
                case 'centerPadding':
                case 'cssEase':
                case 'easing':
                case 'lazyLoad':
                case 'respondTo':
                case 'orderby':
                case 'order':
                    $cleaned[$key] = sanitize_text_field($value);
                    break;
                /* html */
                case 'appendArrows':
                case 'prevArrow':
                case 'nextArrow':
                    $cleaned[$key] = $value;
                    break;
                /* boolean */
                default:
                    if (empty($value) || ('true' === $value)) {
                        $cleaned[$key] = $value;
                    }
                    break;
            }
		}
		return $cleaned;
	}
    
    public function orderby_callback() {
		$orderby_options = array(
			'menu_order' => __('Menu order, as set in Carousel overview page', 'slick-carousel'),
			'date' => __('Date slide was published', 'slick-carousel'),
			'rand' => __('Random ordering', 'slick-carousel'),
			'title' => __('Slide title', 'slick-carousel')	  
		);
		print '<select id="orderby" name="slickc_settings[orderby]">';
		foreach ($orderby_options as $val => $option){
			print '<option value="' . $val . '"';
			if (isset($this->options['orderby']) && ($this->options['orderby'] === $val)) {
				echo ' selected="selected"';
			}
			echo ">${option}</option>";
		}
		echo '</select>';
	}
    
	public function order_callback() {
		if (isset($this->options['order']) && ($this->options['order'] === 'DESC')) {
			$slickc_showcontrols_a = '';
			$slickc_showcontrols_d = ' selected="selected"';
		} else {
			$slickc_showcontrols_a = ' selected="selected"';
			$slickc_showcontrols_d = '';
		}
		print '<select id="order" name="slickc_settings[order]">
			<option value="ASC"' . $slickc_showcontrols_a.'>' .
            __('Ascending', 'slick-carousel') . '</option>
			<option value="DESC"' . $slickc_showcontrols_d . '>' .
            __('Decending', 'slick-carousel') . '</option>
		</select>';
	}
    
	public function category_callback() {
		$categories = get_terms('carousel_category');
		echo '<select id="orderby" name="slickc_settings[category]">
			<option value="">' . __('All Categories', 'slick-carousel') . '</option>';
		foreach ($categories as $category) {
			print '<option value="' . $category->name . '"';
			if (isset($this->options['category']) && ($this->options['category'] === $category->name)) {
				print ' selected="selected"';
			}
			print ">" . $category->name . "</option>";
		}
		print '</select>';
	}
			
	// Print the Section text
	public function slickc_settings_behaviour_header() {
        echo '<p>' . __('Define how the carousel behaves.', 'slick-carousel') . '</p>';
	}
	public function slickc_settings_navigation_header() {
        echo '<p>' . __('Change the slide navigation setup of the carousel.', 'slick-carousel') . '</p>';
	}
	public function slickc_settings_misc_header() {
        echo '<p>' . __('Additional miscellaneous settings.', 'slick-carousel') . '</p>';
	}

}