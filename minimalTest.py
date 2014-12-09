#!/usr/bin/python

import sys
import time

def main_loop():
    while 1:
        print "kokot"
        time.sleep(1)


if __name__ == '__main__':
    try:
        main_loop()
    except KeyboardInterrupt:
        print >> sys.stderr, '\nExiting by user request.\n'
        sys.exit(0)
