<?php

if ( !isset( $_SESSION ) ) {
    session_start();
}

// dependencies
require_once 'config/db_conn.php';
require_once 'lib/AltoRouter.php';
require_once 'lib/SwirkzController.php';
require_once 'lib/LocationController.php';

// new instances
$router   = new AltoRouter();
$swirkz   = new Swirkz;
$location = new LocationController;

// homepage
$router->map( 'GET', '/', function( ) {
    require_once 'views/home.php';
} );

// help page
$router->map( 'GET', '/help', function( ) {
    require_once 'views/help.php';
} );
<<<<<<< HEAD


=======


>>>>>>> origin/d
// redirect to valid URL
$router->map( 'POST', '/new', function( ) use ($location) {
    if ( isset( $_POST[ 'room_id' ] ) && !empty( $_POST[ 'room_id' ] ) ) {
        $location::go( "/" . $_POST[ 'room_id' ] );
    } else {
        header( "Location: /" . time() . 'r' . rand( 1, 100 ) );
        exit;
    }
} );
<<<<<<< HEAD

// 404 error - all other matches
$router->map( 'GET', '/[*]/[*]', function( ) {
    echo 'Eror 404';
    exit;
} );

=======

// 404 error - all other matches
$router->map( 'GET', '/[*]/[*]', function( ) {
    echo 'Eror 404';
    exit;
} );

>>>>>>> origin/d
// go to exist chat
$router->map( 'GET', '/[*:room_id]', function( $room_id ) use ($mysqli, $swirkz, $location) {
    if ( $swirkz->roomExist( $mysqli, $room_id ) ) {
        require_once 'views/chat.php';
    } else {
        $room_id = htmlspecialchars( $room_id );
        if ( $swirkz->roomExist( $mysqli, $room_id ) ) {
            header( "Location: /" . $room_id );
        } else {
            if ( $swirkz->createRoom( $mysqli, $room_id, $_POST ) ) {
                $location::go( "/$room_id" );
            }
        }
    }
} );

// redirect to room again
$router->map( 'POST', '[*:room_id]/login', function( $room_id ) use ($mysqli, $location) {
    if ( isset( $_POST[ 'login_input' ] ) && !empty( $_POST[ 'login_input' ] ) ) {
        if ( $user->findUser( $mysqli, $room_id ) ) {
            $_SESSION[ 'currentRoom' ] = substr( $room_id, 1 );
            $location::go( "$room_id" );
        } else {
            if ( $user->createUser( $mysqli, $room_id, $_POST[ 'login_input' ] ) ) {
                $_SESSION[ 'currentRoom' ] = substr( $room_id, 1 );
                $location::go( "$room_id" );
            } else {
                $location::go( "$room_id" );
            }
        }
    } else {
        $location::go( "$room_id" );
    }
} );

// save nickname
$router->map( 'POST', '[*:room_name]/save-nickname', function( $room_name ) use ($mysqli, $location, $swirkz) {
    if ( isset( $_POST[ 'room_name' ], $_POST[ 'nickname' ] ) && !empty( $_POST[ 'room_name' ] ) && !empty( $_POST[ 'nickname' ] ) ) {

        // grab info
<<<<<<< HEAD
        $room_name = $_POST[ 'room_name' ];
        $nickname  = $_POST[ 'nickname' ];
=======
        $room_name = htmlspecialchars($_POST[ 'room_name' ]);
        $nickname  = htmlspecialchars($_POST[ 'nickname' ]);
>>>>>>> origin/d

        if ( $swirkz->createUser( $mysqli, $room_name, $nickname ) ) {
            if ( ( $settings = $swirkz->getSettings( $mysqli, $room_name, $nickname ) ) !== false ) {
                echo $settings;
                exit;
            } else {
                echo '{"status":"failed-3"}';
                exit;
            }
        } else {
            echo '{"status":"failed-2"}';
            exit;
        }
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
} );

<<<<<<< HEAD
// download messages
$router->map( 'POST', '[*:room_name]/get-messages', function( $room_name ) use ($mysqli, $location, $swirkz) {
    if ( isset( $_POST[ 'room_name' ], $_POST[ 'room_token' ], $_POST[ 'latest_message_id' ] ) && !empty( $_POST[ 'room_name' ] ) && !empty( $_POST[ 'room_token' ] ) && ( !empty( $_POST[ 'latest_message_id' ] ) || $_POST[ 'latest_message_id' ] == 0 ) ) {

        // grab info
        $room_name         = $_POST[ 'room_name' ];
        $room_token        = $_POST[ 'room_token' ];
        $latest_message_id = $_POST[ 'latest_message_id' ];

        if ( $swirkz->roomExist( $mysqli, $room_name ) ) {
            if ( ( $messages = $swirkz->getMessages( $mysqli, $room_name, $room_token, $latest_message_id ) ) !== false ) {
                echo $messages;
            } else {
                echo '{"status":"failed-2"}';
                exit;
            }
        } else {
            echo '{"status":"failed-3"}';
            exit;
        }

    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
} );

// send message
$router->map( 'POST', '[*:room_name]/send-message', function( $room_name ) use ($mysqli, $location, $swirkz) {
    if ( isset( $_POST[ 'room_name' ], $_POST[ 'room_token' ], $_POST[ 'content' ], $_POST[ 'user_id' ], $_POST[ 'user_token' ], $_POST[ 'nickname' ] ) && !empty( $_POST[ 'room_name' ] ) && !empty( $_POST[ 'room_token' ] ) && !empty( $_POST[ 'content' ] ) && !empty( $_POST[ 'user_id' ] ) && !empty( $_POST[ 'user_token' ] ) && !empty( $_POST[ 'nickname' ] ) ) {

        // grab info and make the secure
        $room_name  = $_POST[ 'room_name' ];
        $room_token = $_POST[ 'room_token' ];
        $content    = $_POST[ 'content' ];
        $user_id    = $_POST[ 'user_id' ];
        $user_token = $_POST[ 'user_token' ];
        $nickname   = $_POST[ 'nickname' ];

        if ( $swirkz->roomExist( $mysqli, $room_name ) ) {
            if ( ( $status = $swirkz->sendMessage( $mysqli, $room_name, $room_token, $content, $user_id, $user_token, $nickname ) ) !== false ) {
                echo '{"status":"success"}';
            } else {
                echo '{"status":"failed-2"}';
                exit;
            }
        }

=======
//check user
$router->map( 'POST', '[*:room_name]/check-user', function( $room_name ) use ($mysqli, $location, $swirkz) {
	 if ( isset( $_POST[ 'room_url' ], $_POST[ 'nickname' ] , $_POST[ 'user_token' ] , $_POST[ 'room_token' ] ) && !empty( $_POST[ 'room_url' ] ) && !empty( $_POST[ 'nickname' ] )  && !empty( $_POST[ 'user_token' ] )  && !empty( $_POST[ 'room_token' ] ) ) {

        // grab info
        $room_token = htmlspecialchars($_POST[ 'room_token' ]);
        $nickname  = htmlspecialchars($_POST[ 'nickname' ]);
        $room_url  = htmlspecialchars($_POST[ 'room_url' ]);
        $user_token  = htmlspecialchars($_POST[ 'user_token' ]);
		$room_url_current = ltrim($room_name,'/');
		if($room_url==$room_url_current){
			if ( ( $settings = $swirkz->getSettings( $mysqli, $room_url_current, $nickname ) ) !== false ) {
                echo $settings;
                exit;
            } else {
                echo '{"status":"failed-3"}';
                exit;
            }
		} else {
			if($nickname_true = $swirkz->findUser($mysqli,$room_url_current)) {
				if ( ( $settings = $swirkz->getSettings( $mysqli, $room_url_current, $nickname_true ) ) !== false ) {
					echo $settings;
					exit;
				} else {
					echo '{"status":"failed-3"}';
					exit;
				}
			} else {
				echo '{"status":"failed-2"}';
				exit;
			}
		}
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
});


// download messages
$router->map( 'POST', '[*:room_name]/get-messages', function( $room_name ) use ($mysqli, $location, $swirkz) {
    if ( isset( $_POST[ 'room_name' ], $_POST[ 'room_token' ], $_POST[ 'latest_message_id' ] ) && !empty( $_POST[ 'room_name' ] ) && !empty( $_POST[ 'room_token' ] ) && ( !empty( $_POST[ 'latest_message_id' ] ) || $_POST[ 'latest_message_id' ] == 0 ) ) {

        // grab info
        $room_name         = htmlspecialchars($_POST[ 'room_name' ]);
        $room_token        = htmlspecialchars($_POST[ 'room_token' ]);
        $latest_message_id = htmlspecialchars($_POST[ 'latest_message_id' ]);

        if ( $swirkz->roomExist( $mysqli, $room_name ) ) {
            if ( ( $messages = $swirkz->getMessages( $mysqli, $room_name, $room_token, $latest_message_id ) ) !== false ) {
                echo $messages;
            } else {
                echo '{"status":"failed-2"}';
                exit;
            }
        } else {
            echo '{"status":"failed-3"}';
            exit;
        }

>>>>>>> origin/d
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
} );
<<<<<<< HEAD
=======

// send message
$router->map( 'POST', '[*:room_name]/send-message', function( $room_name ) use ($mysqli, $location, $swirkz) {
    if ( isset( $_POST[ 'room_name' ], $_POST[ 'room_token' ], $_POST[ 'content' ], $_POST[ 'user_id' ], $_POST[ 'user_token' ], $_POST[ 'nickname' ] ) && !empty( $_POST[ 'room_name' ] ) && !empty( $_POST[ 'room_token' ] ) && !empty( $_POST[ 'content' ] ) && !empty( $_POST[ 'user_id' ] ) && !empty( $_POST[ 'user_token' ] ) && !empty( $_POST[ 'nickname' ] ) ) {

        // grab info and make the secure
        $room_name  = htmlspecialchars($_POST[ 'room_name' ]);
        $room_token = htmlspecialchars($_POST[ 'room_token' ]);
        $content    = $_POST[ 'content' ];
        $user_id    = htmlspecialchars($_POST[ 'user_id' ]);
        $user_token = htmlspecialchars($_POST[ 'user_token' ]);
        $nickname   = htmlspecialchars($_POST[ 'nickname' ]);

        if ( $swirkz->roomExist( $mysqli, $room_name ) ) {
            if ( ( $status = $swirkz->sendMessage( $mysqli, $room_name, $room_token, $content, $user_id, $user_token, $nickname ) ) !== false ) {
                echo '{"status":"success"}';
            } else {
                echo '{"status":"failed-2"}';
                exit;
            }
        }

    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
} );
>>>>>>> origin/d

$match = $router->match();

if ( $match && is_callable( $match[ 'target' ] ) ) {
    call_user_func_array( $match[ 'target' ], $match[ 'params' ] );
} else {
    // no route was matched
    header( $_SERVER[ "SERVER_PROTOCOL" ] . ' 404 Not Found' );
    echo 'Error 404 - file not found';
    exit;
}