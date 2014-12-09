import serial
ser = serial.Serial('/dev/ttyUSB0', 19200, timeout=1)
print ser

ser.write(':010310010001EA\r\n')
print ser.read(1000) # Read 1000 bytes, or wait for timeout
