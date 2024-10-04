<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class MessageQueueSimulationView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            ?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>

                
                <button id="startBtn">Start Simulation</button>
                <button id="stopBtn" disabled>Stop Simulation</button>
                <div id="messages"></div>
                
                <script>
                    const broker = 'wss://pti.unithe.hu:19001';
                    const options = {
                        username: 'VeresZLidar',
                        password: 'bolcseszmernok1'
                    };

                    const client = mqtt.connect(broker, options);
                    let simulationInterval = null;

                    // Variables to hold current orientation
                    let currentPitch = 0;
                    let currentRoll = 0;
                    let currentYaw = 0;

                    // Set the maximum change in degrees for each rotation per step
                    const maxRotationDegree = 5;  // Max change of 5 degrees per step

                    client.on('connect', function () {
                        console.log('Connected for simulation');
                    });

                    client.on('error', function (err) {
                        console.error('Error in simulation: ', err);
                    });

                    // Start Simulation Function
                    function startSimulation() {
                        if (!simulationInterval) {
                            simulationInterval = setInterval(() => {
                                // Generate random changes within the maxRotationDegree limit
                                const pitchChange = (Math.random() * maxRotationDegree * 2 - maxRotationDegree).toFixed(2);
                                const rollChange = (Math.random() * maxRotationDegree * 2 - maxRotationDegree).toFixed(2);
                                const yawChange = (Math.random() * maxRotationDegree * 2 - maxRotationDegree).toFixed(2);

                                // Update current values
                                currentPitch = (parseFloat(currentPitch) + parseFloat(pitchChange)) % 360;
                                currentRoll = (parseFloat(currentRoll) + parseFloat(rollChange)) % 360;
                                currentYaw = (parseFloat(currentYaw) + parseFloat(yawChange)) % 360;

                                const simulatedMessage = JSON.stringify({
                                    pitch: currentPitch.toFixed(2),
                                    roll: currentRoll.toFixed(2),
                                    yaw: currentYaw.toFixed(2),
                                    timestamp: new Date().toISOString()
                                });

                                client.publish('VeresZLidar', simulatedMessage);
                                //console.log('Simulated message sent:', simulatedMessage);
                            }, 1000);

                            document.getElementById('startBtn').disabled = true;
                            document.getElementById('stopBtn').disabled = false;
                        }
                    }

                    // Stop Simulation Function
                    function stopSimulation() {
                        if (simulationInterval) {
                            clearInterval(simulationInterval);
                            simulationInterval = null;

                            document.getElementById('startBtn').disabled = false;
                            document.getElementById('stopBtn').disabled = true;
                        }
                    }

                    // Add event listeners to buttons
                    document.getElementById('startBtn').addEventListener('click', startSimulation);
                    document.getElementById('stopBtn').addEventListener('click', stopSimulation);
                </script>

            <?php
		}

    }

?>