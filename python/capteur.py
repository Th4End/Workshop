import RPi.GPIO as GPIO
import time

pin = 17

GPIO.setmode(GPIO.BCM)
GPIO.setup(pin, GPIO.IN)
mouvement = False

def lire_capteur():
    try:
        if GPIO.input(pin):
            return True
        else:
            return False
    except Exception as e:
        print(f"Une erreur s'est produite : {e}")

try:
    while True:
        valeur = lire_capteur()
        print(f"Mouvement : {valeur}")
        time.sleep(1)
except KeyboardInterrupt:
    print("Arrêt du programme")
finally:
    GPIO.cleanup()  # Nettoie les GPIO quand le programme se termine

# try:
#     print('capteur prêt')
#     time.sleep(2)

#     if GPIO.input(PiR_PIN):
#         print("Mouvement détecté\n")
#     else:
#         print("Aucun mouvement\n")
# except KeyboardInterrupt:
#     print("Programme arrêté")

# finally:
#     GPIO.cleanup()

