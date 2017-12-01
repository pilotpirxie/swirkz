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
<body style="height:100%">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="/" style="color: #000; font-weight: 600;">Swirkz</a> </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
            <ul class="nav navbar-nav">
                <li><a href="/">HOME</a> </li>
                <li><a href="/help">HELP</a> </li>
                <li><a><code><b>ROOM ID: <?= $room_id ?></b></code></a>
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
                        <div id="chatWindow" style="height: 540px; overflow-y: scroll; padding-right: 10px;">
                            <h3 style="text-align: center; margin-top: 100px;">Please login to view and respond to the chat</h3>
                        </div>
                        <div id="userLogin" style="display:block">
                            <div style="background:transparent; border-bottom:0;" class="input-group-addon" id="login_form" >
                                <p style="text-align: left;">Login</p>
                                <input class="form-control" autofocus id="user_nickname" type="text" name="login_input" placeholder="e.g. Bananowy Janusz">
                                <br><button class="btn btn-info form-control" type="submit" id="login_button" onclick="saveNickname()">Became the mighty owner of this login</button>
                            </div>
                            <div style="display: none; background:transparent; border-bottom:0;" class="input-group-addon" id="content_form" >
                                <textarea class="form-control" autofocus id="content_input" type="text" name="content_input" placeholder="e.g. Welcome Darkness, my old friend!"></textarea>
                                <br><button class="btn btn-info form-control" type="submit" id="login_button" onclick="sendMessage()">Send</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <p style="text-align: center; color: #eee;"> [b]<b>bold</b>[/b] [i]<i>italic</i>[/i] [u]<u>underline</u>[/u] [code]<code>code</code>[/code]</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="../assets/scripts/convertContent.js"></script>
    <script>ROOM_ID = "<?= $room_id ?>";</script>
    <script src="../assets/scripts/chat.js"></script>
</body>
</html>
