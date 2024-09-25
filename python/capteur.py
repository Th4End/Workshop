import RPi.GPIO as GPIO
import time

PiR_PIN = 17

GPIO.setmode(GPIO.BCM)
GPIO.setup(PiR_PIN, GPIO.IN)


try:
    print('capteur prêt')
    time.sleep(2)

    if GPIO.input(PiR_PIN):
        print("Mouvement détecté\n")
    else:
        print("Aucun mouvement\n")
except KeyboardInterrupt:
    print("Programme arrêté")

finally:
    GPIO.cleanup()