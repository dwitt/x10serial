x10serial
=========

x10serial is a group of utilities that run on a Raspberry Pi and and Arduino to
control x10 modules.

Copyright (c) 2013 David C. Witt

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.

Hardware Configuration
----------------------

The intended hardware configuration is to have a Raspberry Pi connected to
an Arduino using a USB port on each device.  The Arduino is then connected to 
and x10 interface like the TW523/PSC05, PL513/PCS04 or in my case the XTB-IIR.
More on the XTB-IIR later.

The Arduino runs the x10serial.ino file and requires the X10 transmission library 
developed by Tom Igoe. This library is available on GitHub at
https://github.com/tigoe/x10

The x10serial.ino recieves commands over USB used as a serial port from the
Raspberry Pi and sends them in x10 format to the x10 interface. The Raspberry Pi
could do this itself but I felt it was better to off load the low level interface
to the Arduino in order to run a webserver an other struff on the Raspberry Pi.

x10serial.c is a commandline program that is used to send commands out the USB
serial port.  It was created so that it could be call by cron or using at commands.

Daily.php is run a few minutes after midnight each day to do sunrise and sunset
calculations and then to schedule the x10 commands using the at command.

The XTB-IIR can be found at http://jvde.us/xtb.  This site provides kits.  I chose 
the XTB-IIR because it is also a boster and repeater and I have alot of noise on 
my powerline.  This interface works great and has provided reliable service for 
several years.
 