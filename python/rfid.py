import RPi.GPIO as GPIO
from mfrc522 import SimpleMFRC522
# Initialisation du lecteur RFID
reader = SimpleMFRC522()
userr = "eliazid"
etudiant_id = 1
classe = 205
try:
    print("Approchez votre carte RFID")

    # Tente de lire une carte
    id, text = reader.read()

    # Si une carte est scannée, affiche "OK"
    if id:
        print(f"{userr}\n{etudiant_id}\n{classe}")
    else:
        print("Non")

except Exception as e:
    # Gestion des exceptions
    print(f"Une erreur s'est produite : {e}")

finally:
    GPIO.cleanup()