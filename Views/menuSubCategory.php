<?php 
$spaceCaractere = '';

foreach ($subs as $sub) {
    for ($i=0; $i < $level; $i++) { 
        $spaceCaractere .= '--';
    }

    if ($to == 'menu') {
        echo "
        <li><a href='".BASE_URL."category/enter/".$sub['id']."'>$spaceCaractere ".$sub['name']."</a></li>
        ";
    } else if ($to == 'search') {
        $selected = (!empty($viewData['category']) && $category == $sub['id']? 'selected="selected"' : '');

        echo "
        <option $selected value='".$sub['id']."'>$spaceCaractere ".$sub['name']."</option>
        ";
    }

    $spaceCaractere = '';

    if (count($sub['subs_category']) > 0) {
        $this->loadView('menuSubCategory', array(
            'subs' => $sub['subs_category'],
            'level' => $level + 1,
            'to' => $to,
            'category' => (!empty($category) ? $category : '')
        ));
    }
}
