<?php
if ( !isset($_SESSION) ){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" style="height:100%">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css?family=Cutive+Mono|Roboto+Mono" rel="stylesheet">
	<link rel="stylesheet"  href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
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
<script>
	  function close_pop() {
    document.getElementById('options_window').style.display = "none";
	}

	function options() {

		document.getElementById('options_window').style.display = "block";
	}

</script>
<body style="height:100%">
 <!-- BUTTON
 <div class="container">

  <button type="button" id="login_buttonv2" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none">Login</button>


  <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="text-align:center">
      <div class="modal-content" >
		<form method="post" action="?=$room_id?>/login">
			<div class="modal-body">
			<br>
				  <input autofocus onFocus="this.select()" type="text" value="Login" name="login_input" id="login_input"/>
				<div class="input-group">
					<span  class="input-group-addon" name="login_input" >Login</span>
					<input autofocus id="msg" type="text" class="form-control" name="login_input" placeholder="Bananowy Janusz">
				</div>
		
			</div>
			<div class="modal-footer">
			<button type="submit" id="login_button"><span style="float:left;text-align:center;width:240px;padding-top:24px;">Became the mighty owner of this login</span><img style="height:100px;float:left" src="assets/img/login_input.png"/></button>
		<!--	  <button type="submit" class="btn btn-default" data-dismiss="modal" id="login_button"><span style="float:left;text-align:center;width:240px;padding-top:24px;">Became the mighty owner<br> of this login</span><img style="height:100px;float:left" src="assets/img/login_input.png"/></button>
			</div>
		</form>
      </div>
    </div>
  </div>
</div>


	<div id="options_window" class="options">
	  <div class="options-content">
		<form method="post" action="=?$room_id?>/login">
		  <h2>Login</h2>
		  <input autofocus onFocus="this.select()" type="text" value="Login" name="login_input" id="login_input"/>
		  <button type="submit" id="login_button"><span style="float:left;text-align:center;width:240px;padding-top:24px;">Became the mighty owner of this login</span><img style="height:100px;float:left" src="assets/img/login_input.png"/></button>
		  </form>
</button>
		
	  </div>
	</div>
	-->
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
							
							<form>

								<div class="input-group" id="userInput" style="margin-top: 20px;display:none">
									<textarea id="messageInput" class="form-control custom-control" rows="3" style="background-color: #111; color: #fff;resize:none"></textarea>     
									<span id="messageButton" class="input-group-addon btn btn-primary">Send</span>
								</div>
							</form>
						
							<div id="userLogin" style="margin-top: 20px;display:none">
								<form method="post" action="<?=$room_id?>/login">
									<div  style="background:transparent;border-bottom:0" class="input-group-addon" name="login_input" >Login
										<input autofocus style="width:300px" id="msg" type="text" name="login_input" placeholder="Bananowy Janusz"></input>	
										<button type="submit" id="login_button">Became the mighty owner of this login</button>
									</div>
								</form>
							</div>
						
					</div>
				</div>
			</div>
			<p style="text-align: center; color: #eee;"> [b]<b>bold</b>[/b] [i]<i>italic</i>[/i] [u]<u>underline</u>[/u] [code]<code>code</code>[/code] <code>Type /help for more</code> </p>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"  crossorigin="anonymous"></script>
	<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"                                                           -integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
<script>
   function loadDoc() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "demo_post2.asp", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("fname=Henry&lname=Ford");
}

</script>
<?php	
	if(!isset($_SESSION['login']) || $_SESSION['currentRoom']!=$room_id)
	{
		?>
		<script>
		//document.getElementById('login_buttonv2').click();
		$('#userLogin').show();
		</script>
		<?php
	}	else {
		?>
		<script>
		$('#userInput').show();
		</script>
		<?php
	}
?>
