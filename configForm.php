<?php

include "sessionControl.php";

    function generateSelect($name, $options, $optionToSelect, $classOfSelect, $idOfSelect) {
	$html = '<select id="'.$idOfSelect.'"class="'.$classOfSelect.'" name="'.$name.'">';
	foreach ($options as $option => $value) {
	    if($value == $optionToSelect)
		$html .= '<option value="'.$value.'" selected="selected">'.$value.'</option>';
	    else
		$html .= '<option value="'.$value.'">'.$value.'</option>';
	}
	$html .= '</select>';
	return $html;
    }
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
    $errorMessage = "pohoiska";
    $dataBitsArray =array('5','6','7','8');
    $parityArray = array('N','E','O');
    $baudrateArray = array('1200','2400','4800','9600','19200','38400','115200');
    $stopBitsArray = array('1','2');
    $modeArray = array('ascii','rtu');
    $deviceArray = array('/dev/ttyAMA0','/dev/ttyAMA1','/dev/ttyAMA2','/dev/ttyAMA3','/dev/ttyAMA4');

    if (isset($_POST['startSubmit'])) {
        if(file_exists('/home/hizi/Desktop/BakoskaZaloha-git/runningState.py')) {
            $errorMessage = "Aplication is already running!";
        }
        else {
            shell_exec('/usr/bin/python /home/hizi/Desktop/BakoskaZaloha-git/whileTest.py > /dev/null 2>/dev/null &');
            touch("/home/hizi/Desktop/BakoskaZaloha-git/runningState.py");
        }
    }
    if (isset($_POST['stopSubmit'])) {
        if(file_exists('/home/hizi/Desktop/BakoskaZaloha-git/runningState.py')) {
            unlink("/home/hizi/Desktop/BakoskaZaloha-git/runningState.py");
            touch("/home/hizi/Desktop/BakoskaZaloha-git/whileTestOff.py");
        }
        else {
            $errorMessage = "Aplication is already turned off!";
        }
    }

    if(file_exists('/home/hizi/Desktop/BakoskaZaloha-git/runningState.py')) {
        $currentState = "Running";
    }
    else {
        $currentState = "Stopped";
    }

	if (isset($_POST['configSubmit'])) {
        $slaveAddressPosted =(test_input($_POST['slaveAddress']));
        $humidityRaPosted =(test_input($_POST['humRegAddress']));
        $temperatureRaPosted =(test_input($_POST['tempRegAddress']));
        $pressureRaPosted =(test_input($_POST['pressureRegAddress']));
        $periodPosted =(test_input($_POST['period']));
        $baudrateSelect = test_input($_POST['baudrateSelect']);
	    $dataSelect = test_input($_POST['dataSelect']);
	    $modeSelect = test_input($_POST['modeSelect']);
	    $paritySelect = test_input($_POST['paritySelect']);
	    $deviceSelect = test_input($_POST['deviceSelect']);
	    $stopSelect = test_input($_POST['stopBitsSelect']);

      /*  if(in_array($modeSelect, $modeArray)) {
            $errorMessage =  "malicky";
        }
       */

	    try {
		// database connection
		try{
		    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
		    echo 'ERROR: ' . $e->getMessage();
		}
		$id = 1;
		// query
		$sql = "UPDATE configurationData  
		    SET device=?,baudrate=?,parity=?,data_bits=?,stop_bits=?,communication_mode=?,decimal_slave_address=?,humidity_rA=?,temperature_rA=?,barometric_pressure_rA=?,period_of_measurement=?
		    WHERE id=?";
		$q = $conn->prepare($sql);
		$q->execute(array($deviceSelect,$baudrateSelect,$paritySelect,$dataSelect,$stopSelect,$modeSelect,$slaveAddressPosted,$humidityRaPosted,$temperatureRaPosted,$pressureRaPosted,$periodPosted,$id));
		$conn = null;
	    }
	    catch(PDOException $e) {
            echo $e->getMessage();
	    }
	}
    
	try {
	    // database connection
	    try{
		$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    } catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	    }
	    $data = $conn->query("SELECT * FROM configurationData");
	    $rowsFound = $data->rowCount();
	    if($rowsFound > 0) {
	    foreach($data as $row) {	

        $baudrateSelect = generateSelect('baudrateSelect',$baudrateArray, $row["baudrate"],'styled-select','selectBaudrate');
		$dataSelect = generateSelect('dataSelect',$dataBitsArray, $row["data_bits"],'styled-select','selectData');
		$modeSelect = generateSelect('modeSelect',$modeArray,$row["communication_mode"],'styled-select','selectMode');
		$paritySelect = generateSelect('paritySelect',$parityArray, $row["parity"],'styled-select','selectParity');	
		$stopBitsSelect = generateSelect('stopBitsSelect',$stopBitsArray, $row["stop_bits"],'styled-select','selectStop');
		$deviceSelect = generateSelect('deviceSelect',$deviceArray, $row["device"],'styled-select','selectDevice');
		echo"<Form Name='form4' Method='POST' id='form4' ACTION='".htmlspecialchars($_SERVER['REQUEST_URI'])."' onsubmit= 'return validateForm()'>
		    <div id='contentDiv'>

		    <table id='configTable'>
		    <tr>
            <td>
            <h2>Configuration Data</h2>
		    <td >
		    <tr>
            <tr>
            <td>
		    <label id='appStatelabel'>Application state:</label>
            </td>
            <td>
		    <label id='appState'>$currentState</label>
            </td>
            </tr>
		    <td>
		    <label id='dataBitsLabel'> Number of Data Bits:</label>
		    </td>
		    <td >
		    $dataSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='parityLabel'> Parity:</label>
		    </td>
		    <td>
		    $paritySelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='deviceLabel'> Device:</label>
		    </td>
		    <td>
		    $deviceSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='stopBitsLabel'> Number of Stop Bits:</label>
		    </td>
		    <td>
		    $stopBitsSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='modeLabel'>Communication Mode:</label>
		    </td>
		    <td>
		    $modeSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='baudrateLabel'> Baudrate:</label>
		    </td>
		    <td>
            $baudrateSelect
		    </td>
		    </tr>
            <tr>
            <td>
		    <label id='slaveAddressLabel'>Slave address:</label>
            </td>
            <td>
		    <input type='text' name='slaveAddress' id='slaveAddress' value='".$row["decimal_slave_address"]."'>
		    <span class='error'>* $baudEmptyError
            <td>
            </tr>
            <tr>
            <td>
		    <label id='temperatureLabel'>Temperature register address:</label>
            </td>
            <td>
		    <input type='text' name='tempRegAddress' id='tempRegAddress' value='".$row["temperature_rA"]."'>
            <td>
            </tr>
            <tr>
            <td>
		    <label id='humidityLabel'>Humidity register address:</label>
            </td>
            <td>
		    <input type='text' name='humRegAddress' id='humRegAddress' value='".$row["humidity_rA"]."'>
            <td>
            </tr>
            <tr>
            <td>
		    <label id='pressureLabel'>Pressure register address:</label>
            </td>
            <td>
		    <input type='text' name='pressureRegAddress' id='pressureRegAddress' value='".$row["barometric_pressure_rA"]."'>
            <td>
            </tr>
            <tr>
            <td>
		    <label id='periodLabel'>Period of measurement:</label>
            </td>
            <td>
		    <input typ']))ext' name='period' id='period' value='".$row["period_of_measurement"]."'>
		    <span class='error'>* $baudEmptyError
            <td>
            </tr>
            <tr>
		    <td>
		    <input type='submit' name='configSubmit' id='configSubmit' value='Save Configuration'>
		    </td>
		    </tr>
		    <tr>	
		    <td>
		    <input type='submit' name='startSubmit' id='startSubmit' value='Start application'>
		    </td>
            <td>
		    <input type='submit' name='stopSubmit' id='stopSubmit' value='Stop application'>
            </td>
            </tr>
		    <tr>
		    <td>
		    <p><span class='error'>* required field</span></p>
		    </td>
		    </tr>
            <tr>
            <td>
            $errorMessage
            </td>
            </tr>
		    </table>
		    <form>";
	    }
	}
	else {
	    echo "DB configuration table is empty!";
	}
	$conn = null;
	}
	catch(PDOException $e) {
	    echo $e->getMessage();
	}
/*	
 */
?>

