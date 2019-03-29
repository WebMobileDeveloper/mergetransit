
<!DOCTYPE html>
<html>
<head>
	<title>customer email </title>
</head>

<body>
	<div>
	<p>
			A new customer has registered {{ $company }} and {{ $phone }} 
	</p>
	<p>
	  Here are the details:
	</p>
	<ul>
	  <li>User Name: <strong>{{ $username }}</strong></li>
	  <li>Email: <strong>{{ $email }}</strong></li>
	  <li>Phone: <strong>{{ $phone }}</strong></li>
	</ul>
	<hr>	
	<hr>
</div>
</body>

</html>