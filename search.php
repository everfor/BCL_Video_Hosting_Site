<?php
    $pageTitle = 'Index Page';
    $scripts = '<script src="js/apps/search/controllers.js" type="text/javascript"></script>
    			<script src="js/apps/search/router.js" type="text/javascript"></script>';
    include 'header.php';
?>
<div id="search" ng-app="searchRouter">
	<div ng-view></div>
</div>

</div>

</body>
</html>