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
	if (isset($_POST['Submit4'])) {
	    $baudratePosted = htmlspecialchars(test_input($_POST['baudText']));
	    $dataSelect = test_input($_POST['dataSelect']);
	    $modeSelect = test_input($_POST['modeSelect']);
	    $paritySelect = test_input($_POST['paritySelect']);
	    $deviceSelect = test_input($_POST['deviceSelect']);
	    $stopSelect = test_input($_POST['stopBitsSelect']);


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
		    SET device=?,baudrate=?,parity=?,data_bits=?,stop_bits=?,communication_mode=?
		    WHERE id=?";
		$q = $conn->prepare($sql);
		$q->execute(array($deviceSelect,$baudratePosted,$paritySelect,$dataSelect,$stopSelect,$modeSelect,$id));
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
		$dataBitsArray =array('5','6','7','8');
		$parityArray = array('N','E','O');
		$stopBitsArray = array('1','2');
		$modeArray = array('ascii','rtu');
		$deviceArray = array('/dev/ttyUSB0','/dev/ttyUSB1','/dev/ttyUSB2','/dev/ttyUSB3','/dev/ttyUSB4');
		$dataSelect = generateSelect('dataSelect', $dataBitsArray, $row["data_bits"],'styled-select','selectData');
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
		    <td>
		    <label id='dataBitsLabel'> Number of Data Bits</label>
		    </td>
		    <td >
		    $dataSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='parityLabel'> Parity</label>
		    </td>
		    <td>
		    $paritySelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='deviceLabel'> Device</label>
		    </td>
		    <td>
		    $deviceSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='stopBitsLabel'> Number of Stop Bits</label>
		    </td>
		    <td>
		    $stopBitsSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='modeLabel'>Communication Mode</label>
		    </td>
		    <td>
		    $modeSelect
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <label id='baudrateLabel'> Baudrate</label>
		    </td>
		    <td>
		    <input type='text' name='baudText' id='baudText' value='".$row["baudrate"]."'>
		    <span class='error'>* $baudEmptyError
		    </td>
		    </tr>
		    <tr>
		    <td>
		    <p><span class='error'>* required field</span></p>
		    </td>
		    </tr>
		    <tr>	
		    <td>
		    <input type='submit' name='Submit4' id='ConfigSave' value='Save Configuration'>
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
	
?>

