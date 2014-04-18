<?php
    $pageTitle = 'Watch';
    $scripts = '<script src="/js/controllers/watch.js" type="text/javascript"></script>';
    $styles = '<link href="/css/watch.css" rel="stylesheet" type="text/css">';
    include 'header.php';
?>

<!-- If user is not logged in -->
<!-- Then disable the video player -->
<?php if(isset($_SESSION['UID'])): ?>
	<div ng-view></div>
<?php else: ?>
	<div class="wrapper">
		<i>Sorry, content only available for logged in users</i>
	</div>
<?php endif;?>

</div>

<?php
	include 'footer.php';
?>