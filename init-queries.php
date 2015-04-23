<?php
	
	
	
	// blocks
	$block_SQL = "INSERT INTO blocks ('block_id' , 'block_type', 'block_title' , 'identifier', 'block_text' , 'image', 'alt_tag', 'islink', 'status') VALUES
		(1, 'N', 'Social Media Links', 'socialicons', '', '', '', 'N', 'Y'),
		(2, 'Y', 'block1', 'box1', '', '', 'Image description', 'Y', 'Y'),
		(3, 'Y', 'block2', 'box2', '', '', '', 'Y', 'Y'),
		(4, 'Y', 'block3', 'box3', '', '', '', 'Y', 'Y'),
		(5, 'Y', 'block4', 'box4', '', '', '', 'Y', 'Y')";
	
	// blocks pages
	$blockpages_SQL = "INSERT INTO ml_block_pages ('page_id' , 'page_title', 'page_text', 'page_url', 'head_title', 'head_keywords', 'head_description', 'date_created') VALUES
					(1, 'block#1', 'block#1', 'block#1', '', '', '', '2012-11-15 11:30:09'),
					(2, 'block#2', 'block#2', 'block2', '', '', '', '2012-11-15 11:31:07'),
					(3, 'block#3', 'block#3', 'block3', '', '', '', '2012-11-15 11:33:57'),
					(4, 'block#1', 'block#4', 'block4', '', '', '', '2012-11-15 11:34:27')";
	
	// module pages
	$modulepages_SQL = "INSERT INTO 'module_pages' ('page_id', 'page_title', 'page_text', 'head_title', 'head_keywords', 'head_description', 'date_modified') VALUES
					(1, 'Home Page', '', '', 'simple keywords', 'meta description will come here', '2012-11-22 00:00:00'),
					(2, 'Image Gallery', '', '', '', 'page description', '2012-11-22 17:44:30'),
					(3, 'Testimonials and Reviews', '', 'Testimonials ', '', 'place description here', '2012-11-22 00:00:00'),
					(4, 'News', '', 'News', 'Awards, events', 'page description', '2012-11-22 17:51:34')";
	
	
	// menus
	$menus_SQL = "INSERT INTO menus ('menu_id', 'menu_label' , 'menu_url', 'menu_types' , 'leftmenu_sort_order' , 'footermenu_sort_order' , 'status' ) VALUES
					(1, 'Home', 'index.php', 'left,footer', 1, 1, 'Y'),
					(2, 'Testimonials', 'testimonials.php', 'left,footer', 6, 4, 'Y'),
					(3, 'News', 'news-awards.php', 'left,footer', 5, 5, 'Y'),
					(4, 'Contact Us', 'contact-us.php', 'left,footer', 4, 6, 'Y'),
					(5, 'About Us', 'about-wsn', 'left,footer', 3, 2, 'Y'),
					(6, 'Portfolio', 'portfolio.php', 'left,footer', 2, 3, 'Y')";
	
	
	// content pages
	$contentpages_SQL = "INSERT INTO contents ('content_id', 'menu_id', 'page_title', 'page_text', 'head_title', 'head_keywords', 'head_description', 'date_created') VALUES
						(1 , 5, 'About Us', 'About us text', 'About Us', '', '', '2012-11-15 12:51:32')";
	