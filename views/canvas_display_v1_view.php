<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class CanvasDisplayV1View extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <main id="main"></main>
                <button id="save-btn">Save Image</button>
                <div id="messages" style="font-size:8px;"></div>
                <div>
                    <span style="color:red;">Red</span>: X - Pitch<br/>
                    <span style="color:green;">Green</span>: Y - Yaw<br/>
                    <span style="color:blue;">Blue</span>: Z - Roll<br/>
                </div>

                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch_v1.js"></script>
                
                <script>
                    const broker = 'wss://pti.unithe.hu:19001';
                    const options = {
                        username: 'VeresZCanvas',
                        password: 'bolcseszmernok1'
                    };

                    // Create a client connection
                    const client = mqtt.connect(broker, options);

                    // When the client connects
                    client.on('connect', function () {
                        console.log('Connected to MQTT broker via WebSocket');

                        // Subscribe to the 'VeresZLidar_3' topic
                        client.subscribe('VeresZLidar_3', function (err) {
                            if (!err) {
                                console.log('Subscribed to VeresZCanvas_1');
                            }
                        });
                    });

                    // When a message is received
                    client.on('message', function (topic, message) {
                        // Message is Buffer, so convert it to string
                        const msg = JSON.parse(message.toString());
                        //console.log('Message received:', JSON.stringify(msg));

                        // Display the message in the webpage
                        const messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML = `<p>Topic: ${topic}, Message: ${JSON.stringify(msg)}</p>`;
                        //console.log(msg);

                        updateSensorData(
                            parseFloat(msg.orientation.pitch),
                            parseFloat(msg.orientation.roll),
                            parseFloat(msg.orientation.yaw),
                            parseFloat(msg.position.x),
                            parseFloat(msg.position.y),
                            parseFloat(msg.position.z),
                            parseFloat(msg.distance)
                        );
                    });

                    // Handle errors
                    client.on('error', function (err) {
                        console.error('Connection error: ', err);
                    });
                </script>

                <br clear="all" />
			<?php
		}


        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayFooter() {
        }

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayLogs() {
		}

    }

?>