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
	$.post(ROOM_ID + "/check-user", dataArray, function (data) {
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
            room_name: ROOM_ID + ""
        };
        // send signal to add user and save nickname if needed
        $.post(ROOM_ID + "/save-nickname", dataArray, function (data) {
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
            $.post(ROOM_ID + "/get-messages", dataArray, function (data) {
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
    content_temp = content_temp.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");

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
    $.post(ROOM_ID + "/send-message", dataArray, function (data) {
        let response = JSON.parse(data);
        console.log(response);
    });
}
