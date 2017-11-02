<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css?family=Cutive+Mono|Roboto+Mono" rel="stylesheet">
	<link rel="stylesheet"  href="../assets/css/bootstrap.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Swirkz</title>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="/" style="color: #000; font-weight: 600;">Swirkz</a> </div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
				<ul class="nav navbar-nav">
					<!-- <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ROOM <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">CREATE NEW</a> </li>
							<li><a href="#">JOIN</a> </li>
							<li><a href="#">EXPORT</a> </li>
							<li class="divider"></li>
							<li><a href="#">EXIT</a> </li>
						</ul>
					</li>
					<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">EDIT <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">SEARCH FOR</a> </li>
							<li><a href="#">MUTE USER</a> </li>
							<li><a href="#">CHANGE THEME</a> </li>
						</ul>
					</li>-->
					<li><a href="/">HOME</a> </li>
					<li><a href="help">HELP</a> </li>
					<li><a><code><b>ROOM ID: <?=$room_id?></b></code></a>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="https://github.com/pilotpirxie/swirkz"><i class="fa fa-github" aria-hidden="true"></i> CONTRIBUTE</a> </li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default" style="background-image: url('../assets/img/bg4.png'); border: none;">
					<div class="panel-body">
						<div class="list-group">
							<div style="height: 540px;">

                                <form method="post" action="/auth/create_new.php">
                                    <label>Link to your chat</label>
                                    <input type="text" name="description" class="form-control" style="background-color: #111; color: #fff;" placeholder="" readonly onclick="this.select()" value="<?="http://$_SERVER[HTTP_HOST]/$room_id"?>">
                                    <hr>
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" style="background-color: #111; color: #fff;" placeholder="(Optional) Description">
                                    <label>Password</label>
                                    <input type="text" name="password" class="form-control" style="background-color: #111; color: #fff;" placeholder="(Optional) Password">
                                    <label>Show country flags</label>
                                    <select class="form-control" name="flags" style="background-color: #111; color: #fff; display: block;">
                                        <option value="0">Hide</option>
                                        <option value="1">Show</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary form-control" style="margin-top: 20px; background-color: #0073ff; border: none;">Create new chat</button>
                                </form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>

</html>
