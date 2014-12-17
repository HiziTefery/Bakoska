#!/usr/bin/python

import sys
import serial
import minimalmodbus

instrument = minimalmodbus.Instrument('/dev/ttyAMA0', 1)
instrument.serial.baudrate = 115200
instrument.serial.bytesize = 8
instrument.serial.parity = serial.PARITY_NONE
instrument.serial.stopbits = 1
instrument.serial.timeout = 0.05
temperature = instrument.read_register(1, 1)
print temperature
