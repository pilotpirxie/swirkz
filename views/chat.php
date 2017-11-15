<?php
if ( !isset($_SESSION) ){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css?family=Cutive+Mono|Roboto+Mono" rel="stylesheet">
	<link rel="stylesheet"  href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Swirkz</title>
</head>
<style>
		.options
		{
			display: none; 
			position: fixed; 
			z-index: 1; 
			padding-top: 100px; 
			left: 0;
			top: 0;
			width: 100%; 
			height: 100%; 
			background-color: rgba(0,0,0,0.4); 
		}
		.options-content
		{
			position: relative;
			background-color: #fefefe;
			margin: auto;
			padding: 5px;
			width: 30%;
			animation-name: animatetop;
			animation-duration: 0.5s;
			background-color: #2196F3; 
			border-radius: 6px;
			text-align:center;
			border:
			border: 10px dotted #888888;
		}
		#login_input{
			background-color: white; 
			color: #2196F3;
			border-radius: 6px;
			text-align:center;
		}
		#login_button{
			border-radius: 6px;
			padding:3px;
			margin:6px;
			margin-top:15px;
			color:#2196F3;
			-border: none;
		}
		@keyframes animatetop
			{
			from {top:200px; opacity:0}
			to {top:0; opacity:1}
		}
</style>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
	  function close_pop() {
    document.getElementById('options_window').style.display = "none";
	}

	function options() {

		document.getElementById('options_window').style.display = "block";
	}
	
	
	
	
	
	
/*$("#login_form").submit(function(e) {

    var url = "chat.php"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           data: $("#login_form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               alert(data); // show response from the php script.
           }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
	});*/
</script>
<body>
	<div id="options_window" class="options">
	  <div class="options-content">
		<form method="post" action="<?=$room_id?>/login">
		  <!--<span class="close" onclick="close_pop();">X</span>-->
		  <h2>Login</h2>
		  <input autofocus type="text" name="login_input" id="login_input"/>
		  <button type="submit" id="login_button"><span style="float:left;text-align:center;width:240px;padding-top:24px;">Became the mighty owner of this login</span><img style="height:100px;float:left" src="assets/img/login_input.png"/></button>
		  </form>
</button>
		
	  </div>
	</div>
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
					<li><a href="/help">HELP</a> </li>
					<li><a><code><b>ROOM ID: <?=$room_id?></b></code></a>
					<li><a><code><b>LOGIN: <?=@$_SESSION['login']?></b></code></a>
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
				<div class="panel panel-default" style="background-image: url('assets/img/bg4.png'); border: none;">
					<div class="panel-body">
						<div class="list-group">
							<div id="messages" style="height: 540px; overflow-y: scroll; padding-right: 10px;">
								<div class="list-group-item">
									<h6 class="list-group-item-heading" style="color: #fff; font-weight: 600;">User name <span class="label label-success">💎 Admin</span></h6>
									<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
								</div>
								<div class="list-group-item">
									<h6 class="list-group-item-heading" style="color: #fff; font-weight: 600;">User name <span class="label label-warning">❌ Banned</span></h6>
									<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
								</div>
								<div class="list-group-item">
									<h6 class="list-group-item-heading" style="color: #fff; font-weight: 600;">User name <span class="label label-info">💼 Moderator</span></h6>
									<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
								</div>
								<div class="list-group-item">
									<h6 class="list-group-item-heading" style="color: #fff; font-weight: 600;">User name </h6>
									<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
								</div>
							</div>
							<div id="userInput" style="margin-top: 20px;">
								<textarea id="userInputText" class="form-control" style="background-color: #111; color: #fff;" placeholder="Type your message here."></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<p style="text-align: center; color: #eee;"> [b]<b>bold</b>[/b] [i]<i>italic</i>[/i] [u]<u>underline</u>[/u] [code]<code>code</code>[/code] <code>Type /help for more</code> </p>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>

</html>
<?php	
	if(!isset($_SESSION['login']))
	{
		?>
		<script>
		document.getElementById('options_window').style.display = "block";
		</script>
		<?php
	}
?>
