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
            <p style="text-align: center; color: #eee;"> [b]<b>bold</b>[/b] [i]<i>italic</i>[/i] [u]<u>underline</u>[/u] [code]<code>code</code>[/code] <code>Type /help for more</code> </p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="../assets/scripts/convertContent.js"></script>
    <script>

        var LOCAL_SETTINGS;
        var LATEST_MESSAGE_COUNT = 0;
        const INTERVAL = 500;

        // bind user_nickname to enter key
        $('#user_nickname').on('keyup', function(e) {
            if (e.which == 13 && ! e.shiftKey) {
                saveNickname();
            }
        });
		checkUser();
		// check user
		function checkUser() {
			let userObj = $.cookie();
		
			let dataArray = {
				nickname: userObj.nickname,
				user_token: userObj.userToken,
				room_url: userObj.roomURL,
				room_token: userObj.roomToken
			};
			// send signal to check user and save nickname if needed
			$.post("<?= $room_id ?>/check-user", dataArray, function (data) {
				console.log(data);
				let response = JSON.parse(data);
				if (response.status == true) {
					console.log('Logged in');
					console.log(response);
					LOCAL_SETTINGS = response;
					
					//save user settings into cookies
					var date = new Date();
					date.setTime(date.getTime() + (1 * 24 * 60 * 60 * 1000));
					var expires = "expires="+date.toUTCString();
					document.cookie = "nickname" + "=" + LOCAL_SETTINGS.userData.nickname + ";" + expires + ";path=/";
					document.cookie = "roomURL" + "=" + LOCAL_SETTINGS.userData.room_url + ";" + expires + ";path=/";
					document.cookie = "roomToken" + "=" + LOCAL_SETTINGS.roomData.room_token + ";" + expires + ";path=/";
					document.cookie = "userToken" + "=" + LOCAL_SETTINGS.userData.user_token + ";" + expires + ";path=/";
					
					// hide login form and show message form
					$('#login_form').hide();
					$('#content_form').show();
					startListening();
				} else {
					console.log('Something wrong');
				}
			});
		}
	
				
		
		
        // send signal to add user
        function saveNickname () {
            // check if settings was not declared (in this case - saved)
            if (typeof(LOCAL_SETTINGS) === "undefined") {
                let dataArray = {
                    nickname: $('#user_nickname').val(),
                    room_name: "<?= $room_id ?>"
                };
                // send signal to add user and save nickname if needed
                $.post("<?= $room_id ?>/save-nickname", dataArray, function (data) {
                    let response = JSON.parse(data);
                    if (response.status == true) {
                        console.log('Logged in');
                        console.log(response);
                        LOCAL_SETTINGS = response;
						
						//save user settings into cookies
						var date = new Date();
						date.setTime(date.getTime() + (1 * 24 * 60 * 60 * 1000));
						var expires = "expires="+date.toUTCString();
						document.cookie = "nickname" + "=" + LOCAL_SETTINGS.userData.nickname + ";" + expires + ";path=/";
						document.cookie = "roomURL" + "=" + LOCAL_SETTINGS.userData.room_url + ";" + expires + ";path=/";
						document.cookie = "roomToken" + "=" + LOCAL_SETTINGS.roomData.room_token + ";" + expires + ";path=/";
						document.cookie = "userToken" + "=" + LOCAL_SETTINGS.userData.user_token + ";" + expires + ";path=/";
						
                        // hide login form and show message form
                        $('#login_form').hide();
                        $('#content_form').show();
                        startListening();
                    } else {
                        if ( response.status == 'failed-2' ){
                            alert('User already exist');
                        } else {
                            console.log('Something wrong');
                        }
                    }
                });
            }
        }

        // listen for differences on database
        function startListening () {
            // if user download configuration
            if (typeof(LOCAL_SETTINGS) !== "undefined") {
                // start loop for downloading messages
                setInterval(function (){
                    let dataArray = {
                        latest_message_id: LATEST_MESSAGE_COUNT,
                        room_name: LOCAL_SETTINGS.roomData.url,
                        room_token: LOCAL_SETTINGS.roomData.room_token
                    };
                    // download messages from current room if number of messages is different on client and server
                    $.post("<?= $room_id ?>/get-messages", dataArray, function (data) {
                        let response = JSON.parse(data);
                        // console.log(response);
                        if (response.status === 'new-messages') {
                            // if messages was changed
                            LATEST_MESSAGE_COUNT = response.messages_count;

                            // clear before append all messages
                            $('#chatWindow').html('');

                            // foreach messages in array from JSON
                            response.messages.forEach(function(element, index, array){
                                let userType = '';
                                if ( element.user_id == LOCAL_SETTINGS.roomData.admin_id ){
                                    userType = "<span class='label label-success'>💎 Admin</span>";
                                }
                                if ( element.user_id == 0 ){
                                    userType = "<span class='label label-success'>💡 System</span>";
                                }

                                // some magic with message content
                                let messageContent = element.content;

                                $('#chatWindow').append("<div class='list-group-item'><h6 class='list-group-item-heading' style='color: #fff; font-weight: 600;'>"+userType + " " + element.user_nickname+" <i>"+element.create_date+"</i></h6><p class='list-group-item-text'><br>"+messageContent+"</p></div>");
                            });

                            console.log('Downloaded messages');
                        } else if ( response.status === 'no-new-messages' ) {
                            console.log('There is no new messages');
                        } else {
                            console.log('Something wrong');
                        }
                    });

                }, INTERVAL);
            }
        }

        // bind content_input to enter key
        $('#content_input').on('keyup', function(e) {
            if (e.which == 13 && ! e.shiftKey) {
                sendMessage();
            }
        });

        // send new message to the db
        function sendMessage(){
			let content_temp = $('#content_input').val();
			content_temp = convertContent(content_temp);
			
            let dataArray = {
                content: content_temp,
                room_name: LOCAL_SETTINGS.roomData.url,
                room_token: LOCAL_SETTINGS.roomData.room_token,
                user_id: LOCAL_SETTINGS.userData.id,
                user_token: LOCAL_SETTINGS.userData.user_token,
                nickname: LOCAL_SETTINGS.userData.nickname
            };

            // clear textarea and focus it
            $('#content_input').val('');
            $('#content_input').focus();

            // send new message
            $.post("<?= $room_id ?>/send-message", dataArray, function (data) {
                let response = JSON.parse(data);
                console.log(response);
            });
        }


    </script>
</body>
</html>
