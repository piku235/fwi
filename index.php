<!DOCTYPE html>
<html>
<head>
<title>Friendly Web Installator</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="lib/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
	var req = new XMLHttpRequest();
	req.open('GET', 'downloader.php', true);
	
	req.addEventListener("progress", log, false); 
	req.addEventListener("load", log, false); 
	req.send(null);
		
	function log(event) {
		var arr = $.trim(event.currentTarget.responseText).split("\n");
		console.log(arr.pop());
		var entry = jQuery.parseJSON(arr.pop());
		console.log(entry);
		if (!entry) {
			return;
		}
		
		$('#download .progress-text .percent').text(entry.percent + '%');
		$('#download .progress .bar').css('width', entry.percent + '%');
		$('#download .under-bar').html('Transfer <b>' + Math.round(entry.transfer_speed / 1024) + 'kB/s</b>');
	}
</script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span12">
				<br />
				<div id="download">
					<p class="progress-text">
						The progress:
						<span class="percent"></span>
					</p>
					<div class="progress progress-striped active">
				    	<div class="bar" style="width: 0%;"></div>
				    </div>
				    <div class="under-bar" style="width: 100%; text-align: right">
			    		
			   		</div>
			   	</div>
			</div>
		</div>
	</div>
</body>
</html>