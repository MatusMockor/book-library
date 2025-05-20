<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title') / </title>
    @vite(['resources/js/app.js'])
</head>
<body>

	<header class="container">

	</header>

	<main>
		<div class="container">
			@yield('content')
		</div>
	</main>
</body>
</html>