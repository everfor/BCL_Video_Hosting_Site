<!--Header of all pages-->
<?php
    session_start();
    // Help to check the autologin stuff
    // require_once('/lib/User/User.php');
    // $userAgent = new User();
?>
<!DOCTYPE html>
<html>
<head>
    <meta char-set="utf-8">
    <title><?=$pageTitle ?></title>
    <link href="/css/common.css" rel="stylesheet" type="text/css">
    <?=$styles ?>
    <script src="/js/lib/jquery-2.1.0.min.js" type="text/javascript"></script>
    <script src="/js/lib/angular.js" type="text/javascript"></script>
    <script src="/js/lib/angular-route.js" type="text/javascript"></script>
    <script src="/js/lib/angular-sanitize.min.js"></script>
    <!--Main AngularJS app-->
    <script src="/js/main.js" type="text/javascript"></script>
    <!--Controllers for searching-->
    <script src="/js/controllers/search.js" type="text/javascript"></script>
    <script src="/js/controllers/user.js" type="text/javascript"></script>
    <?=$scripts ?>
</head>
<body ng-app="main">
    <div class="header">
        <div class="left search" ng-controller="searchCntrl">
            <form ng-submit="search()">
                <input id="searchTerm" type="text" placeholder="hello!" ng-model="searchTerm" ng-bind="searchTerm"></input>
                <button type="submit"></button>
            </form>
        </div>
        <!-- Login info in the header -->
        <!-- http://webdevrefinery.com/forums/topic/3280-how-to-make-a-user-account-system/ -->
        <div class="right user-info" ng-controller="logoutCntrl">
            Welcome,
            <?php // print_r($_COOKIE); ?>
            <?php if (isset($_SESSION['UID']) || isset($_COOKIE['public_key'])): ?>
                <?php
                    require_once(dirname(__FILE__) . '/lib/User/User.php');
                    $user = new User();

                    if (isset($_SESSION['UID'])) {
                        // Check against current agent
                        if (md5($_SERVER['HTTP_USER_AGENT']) != $_SESSION['UAGENT']) {
                            $user->logout();
                            // Reload the current page
                            // This is a hacky way to reload the page and it's better
                            // To refresh using Javascript
                            header("Location: ".$_SERVER['PHP_SELF']);
                        }
                    } else {
                        $result = $user->autoLogin();
                        
                        if (!$result['success']) {
                            $user->logout();
                            // Reload the current page
                            // This is a hacky way to reload the page and it's better
                            // To refresh using Javascript
                            header("Location: ".$_SERVER['PHP_SELF']);
                        }
                    }

                    $userInfo = $user->checkID($_SESSION['UID']);

                    // Check if user id exists
                    if (!$userInfo['success']) {
                        $user->logout();
                        // Reload the current page
                        header("Location: ".$_SERVER['PHP_SELF']);
                    }
                ?>
                <span><?= $userInfo['user']['username'] ?></span>
                <a id="logout" ng-click="logout()">Logout</a>
            <?php else: ?>
                <a href="/login" target="_parent">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="content">