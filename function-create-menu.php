<?php
function createMenu($menuData,$privileges) {
	$x = 0;
	$menu = '<ul>';
	do {
		if($menuData[$x]['access']<=$privileges) {
			if($menuData[$x]['type'] == 'single') {
				$menu .= '<li><a class="active" href="' . $menuData[$x]['url'] . '">' . $menuData[$x]['item'] . '</a></li>';
			} elseif ($menuData[$x]['type'] == 'dropbtn') {
				$parentId = $menuData[$x]['id'];
	  			$menu .= '<div class="dropdown">';
	   			$menu .= '<a href="' . $menuData[$x]['url'] . '" class="dropbtn">' . $menuData[$x]['item'] . '</a>';
    			$menu .= '<div class="dropdown-content">';
				do {
					$x++;
					$menu .= '<a href="' . $menuData[$x]['url'] . '">' . $menuData[$x]['item'] . '</a>';	
				} while(isset($menuData[$x+1]['parent']) && $menuData[$x+1]['parent'] == $parentId);		
				$menu .= '</div></div>';
			}			
		}
		$x++;
	} while ($x < count($menuData));
	$menu .= '</ul>';

	return $menu;
}
?>
