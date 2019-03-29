
<!DOCTYPE html>
<html>
<head>
	<title>Invoice</title>
</head>

<body>
	<div>
	<p>
	 {{$subject}}
	</p>
	
	<ul style="padding-left:0">
		<?php foreach($content as $line) { ?>
			<li style="list-style:none">{{$line}}</li>
		<?php } ?>
	</ul>
	<hr>
	
</div>
</body>

</html>