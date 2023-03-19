<?php

// RESULT FOR SPECIFIC CATEGORY
// SAMPLE URL: localhost/fobi/app/results/category.php?dance

// apply authentication
const LOGIN_PAGE_PATH = '../crud/';
require_once '../crud/auth.php';

// get $category_slug from the URL
$category_slug = '';
$arr_keys = array_keys($_GET);
if(sizeof($arr_keys) > 0)
    $category_slug = strtolower(trim($arr_keys[0]));

// require database.php and Category class
require_once '../config/database.php';
require_once '../models/Category.php';

// instantiate Category object
$category = Category::findBySlug($category_slug);

// define $result globally
$result = [];

if(!$category) {
    echo "Invalid Category [slug = '" . $category_slug . "']";
    exit();
}
else {
    // require Admin class
    require_once '../models/Admin.php';

    // instantiate an Admin object
    $admin = new Admin();

    // tabulate $category
    $result = $admin->tabulate($category);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Results in <?= $category->getTitle() ?></title>
</head>
<body>

    <br>
    <h1><?= $category->getTitle() ?></h1>
    <br>

<table border = '1' cellspacing = '0' width = '75%' height = '25%' align = 'center'>
    <thead>
    <tr height = '45px' align = 'center'>
        <th rowspan="2">TEAMS</th>
        <?php foreach($result['events'] as $event_slug => $event_data) { ?>
            <th colspan="2"><?= $event_data['title'] ?></th>
        <?php } ?>
        <th rowspan="2">TOTAL</th>
        <th rowspan="2">RANK</th>
    </tr>
    <tr height = '50px' align = 'center'>
        <?php foreach($result['events'] as $event_slug => $event_data) { ?>
            <th>Rank</th>
            <th>Points</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($result['teams'] as $team_key => $team_data) { ?>
        <tr height = '50px' align = 'center'>
            <!-- team name -->
            <td height = '50px' align = 'center'><?= $team_data['name'] ?></td>

            <!-- events -->
            <?php foreach($result['events'] as $event_slug => $event_data) { ?>
                <td><?= number_format($team_data['inputs'][$event_slug]['rank']['fractional'], 2) ?></td>
                <td><?= number_format($team_data['inputs'][$event_slug]['points'], 2) ?></td>
            <?php } ?>

            <!-- total points -->
            <td height = '50px' align = 'center'><b><?= number_format($team_data['points'], 2) ?></b></td>

            <!-- rank -->
            <td height = '50px' align = 'center'><?= number_format($team_data['rank']['fractional'], 2) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

    <!-- Footer Section Start -->
    <footer>
        <div class="text">
            <span>© ACLC IRIGA | 2023</span>
        </div>
    </footer>

</body>
</html>