<!doctype html>
<html>
	<head>
        <meta charset="utf-8">
        <!--
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
        -->
        <title><?=$site_name?>[<?=$admin->stf_nickname?>]</title>

		<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
        <link rel="stylesheet" href="/assets/css/lib/all.css">
        <link rel="stylesheet" href="/assets/css/main.css">
        <!-- JQuery 1.12.4 --> 
	    <script src="/assets/js/lib/jquery-1.12.4.min.js"></script>
        
        <script src="/assets/js/lib/fontawesome.js"></script>
        <script src="/assets/js/lib/worker.js"></script>
        
        <script src="/assets/js/util.js?v=1"></script>
        <script src="/assets/js/header.js"></script>        
        <script src="/assets/js/window.js"></script>

    </head>

    <body>

        <div class="main-header">
            <div class="navbar-menu">
                <ul class="menu-ul">
                    <?php if($admin->stf_level > LEVEL_EMPLOYEE) {  ?>
                    <li class="<?=$menu_item_1?>">
                        <a href="/staff/employee">매장관리</a>
                    </li>
                    <?php } ?>
                    <?php if($admin->stf_level >= LEVEL_EMPLOYEE) {
                        foreach ($arrCat as $objCat): ?>
                            <?php if($objCat->cat_selected) { ?>
                                <li class="menu-item-active">
                            <?php } else { ?>
                                <li class="menu-item-li">
                            <?php } ?>
                                    <a href="/<?=$objCat->cat_name?>/member"><?=$objCat->cat_title?></a>
                                </li>
                    <?php endforeach;
                        } ?>
                </ul>
            </div>
            <div class="right-menu">
                <ul class="menu-ul">
                    <li class="<?=$menu_item_2?>">
                        <a href="/staff/password">비번변경</a>
                    </li>
                    <li class="menu-item-li">
                        <a href="/pages/logout"><span><?=$admin->stf_nickname?></span> 로그아웃</a>
                    </li>
                </ul>
            </div>
        </div>
        




