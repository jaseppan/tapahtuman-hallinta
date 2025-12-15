<?php 
session_start();
ini_set('display_errors', 0);

include ("create-connection.php");
include('login.php');

function checkPermission($minPrivileges) {
	if($_SESSION['privileges']<$minPrivileges) {
		die('Oikeutesi eivät riitä tämän sisällön tarkasteluun');
	}
}

include ("function-filter-text.php");
include ("function-profile-filter.php");


?>
<!DOCTYPE html>
<html lang="fi">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tapahtuman hallinta</title>
<link rel="shortcut icon" href="styles/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="styles/tables.css">
<script src="js/tableToExcel.js"></script>
<script src="js/jquery-latest.js"></script>
<script src="js/event-manager.js"></script> 
<script src="js/jquery.tablesorter.js"></script> 
<script src="js/jquery.doubleScroll.js"></script>
<script src="https://cdn.tiny.cloud/1/jfv3r4bce5515fz06kn6g7nhrl9xjrsae6i43fmghzsjr9y9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
	function waitingMsg() {
		confirm("Synkronointi voi kestää hetken. Oottele rauhassa, että saat ilmoituksen tehtävän valmistumisesta. Älä poistu sivulta kesken prosessoinnin. Aloita synkronointi klikkaamalla OK.");
	}
</script>
<script>
	// Slider
	$(document).ready(function(){
		$("#flip-1").click(function(){
		    $("#panel-1").slideToggle("fast");
		});
	});
	$(document).ready(function(){
		$("#flip-2").click(function(){
		    $("#panel-2").slideToggle("fast");
		});
	});
	$(document).ready(function(){
		$("#flip-3").click(function(){
		    $("#panel-3").slideToggle("fast");
		});
	});
	$(document).ready(function(){
		$("#flip-4").click(function(){
		    $("#panel-4").slideToggle("fast");
		});
	});
	$(document).ready(function(){
		$("#flip-5").click(function(){
		    $("#panel-5").slideToggle("fast");
		});
	});
	$(document).ready(function(){
		$("#flip-6").click(function(){
		    $("#panel-6").slideToggle("fast");
		});
	});
</script>
<script>
	// Go Back Button
	function goBack() {
	    window.history.back();
	}
</script>
<script>
	// Table sorter
	$(document).ready(function() 
	    { 
		$("#table").tablesorter(); 
	    } 
	); 
</script>
<script>
	// Scrollbar bottom
	$(document).ready(function() {
		$('.table-container').doubleScroll();
		onlyIfScroll: true; // top scrollbar is not shown if the bottom one is not present
		resetOnWindowResize: false; // recompute the top ScrollBar requirements when the window is resized
	});
</script>
<script>
	// TinyMCE Editor
	tinymce.init({
	  selector: "textarea",
	  <?php if(isset($_GET['textareasize']) && $_GET['textareasize'] == 'small') { ?>
		  height: 100,
		  width: 300,
	  <?php } elseif(isset($_GET['textareasize']) && $_GET['textareasize'] == 'medium') { ?>
		  height: 400,
		  width: 300,
	  <?php } elseif(isset($_GET['textareasize']) && $_GET['textareasize'] == 'large') { ?>
		  height: 400,
	  <?php } ?>
	  
	  toolbar: false,
	  menubar: "tools",
	  forced_root_block : "",
	  content_css : "styles/tiny-mce-style.css",
	  entity_encoding : "named",
	  language : 'pl',
	  language_url : 'http://www.sommelo.net/tapahtuman-hallinta/tinymce/languages/langs/pl.js',
	});
</script>
</head>
<body>
	<div id="wrapper">
		<div id="top">
			<h1>Tapahtuman hallinta</h1>
			<div class="main-menu"> 
				<?php 
				include ('function-create-menu.php');
				include ('main-menu.php');
				echo createMenu($menuData,$_SESSION['privileges']);
				?>
			</div>
			<div class="logout"><p>Käyttäjä: <?php echo $_SESSION['name']; ?> <br/><a href="logout.php">Kirjaudu ulos</a></div>

		</div>
		<div id="content">
			<?php if(isset($_GET['page']) && $_GET['page']) {
				include ($_GET['page'] . ".php"); 
			} else {
				include ('search.php');
			}
			?>
		</div>
	</div>
</body>
<div id="footer">
	<p>Festival Management System &copy; Janne Seppänen <?php echo date("Y"); ?></p>
<div>
<?php mysqli_close($mysqli); ?>
