<?php
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
	    
	$servername = "localhost";
	$username = "pi_user";
	$password = "arthas4259";
	$dbname = "rpi_db";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT id, device, baudrate, parity, data_bits, stop_bits, communication_mode FROM configurationData";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {	
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
	    echo"<Form Name='form4' Method='POST' id='form4' ACTION='".($_SERVER['REQUEST_URI'])."'>
		<div id='contentDiv'>

			<table id='configTable'>
			<tr>
			    <td>
				<p><span class='error'>* required field.</span></p>
			    </td>
			</tr>
			<tr>
				<td width='30%'>
					<label id='dataBitsLabel'> Number of Data Bits</label>
				</td>
				<td width='70%'>
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
					<input type='text' name='Text1' id='textBaudrate'>
				</td>
			</tr>
			<tr>	
			    <td>
				<input type='submit' name='Submit4' id='ConfigSave' value='Save Configuration'>
			    </td>
			</tr>
			</table>
		</div>
	    <form>";

	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
?>
