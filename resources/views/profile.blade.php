<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="description" content="Project Morpheus">
		<meta name="author" content="Ernesta Orlovaitė">
		<link rel="icon" href="icon.png">
		
		<title>Project Morpheus</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="styles.css">
		
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="{{ url() }}">Project Morpheus</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						@if (Auth::check())
							<li>
								<a href="{{ route("profile") }}">{{ Auth::user()->getUsername() }}</a>
							</li>
						@else
							<li>
								<a href="{{ route('login') }}">Login</a>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</nav>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="">All Games</a></li>
					</ul>
				</div>
				
				<div id="games" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h1 class="page-header">Profile</h1>
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					@if (session('error'))
						<div class="alert alert-danger">
							{{ session('error') }}
						</div>
					@endif
					<div class="row">
						<form role=form method="post" action="{{ route("logout") }}" class="">
							<button type="submit" class="btn btn-default col-md-2">Logout</button>
						</form>
						<form role=form method="post" action="{{ route("update_steam_data") }}" class="">
							<button type="submit" class="btn btn-default col-md-2">Update gaming data</button>
						</form>
						<form method="post" action="{{ route("delete_steam_data") }}" class="">
							<button type="submit" class="btn btn-default col-md-2">Delete gaming data</button>
						</form>
						<form method="post" action="{{ route("wipe_game_data") }}" class="">
							<button type="submit" class="btn btn-default col-md-3">Delete all game data from server</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
		<!-- <script src="scripts.js"></script> -->
	</body>
</html>