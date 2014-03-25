<?php
    $pageTitle = 'Index Page';
    $scripts = '<script src="js/apps/user/controllers.js" type="text/javascript"></script>
    			<script src="js/apps/user/router.js" type="text/javascript"></script>';
    include 'header.php';
?>

<div id="user" ng-app="userRouter">
	<div ng-view></div>
</div>

</div>

</body>
</html>