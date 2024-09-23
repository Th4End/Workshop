# Début du code car compliquer de continuer sans la raspberry
#import du package RPI.GPIO qui permet d'utiliser les broches GPIO de la raspberry
import RPi.GPIO as GPIO
#import du package MFRC522 qui permet d'interagir avec les lecteurs RFID
from MFRC522 import SimpleMFRC255

# initialisation du lecteur RFID
reader = SimpleMFRC255()

# lit la carte RFID et affiche les différentes informations et nettoie les ressources GPIO
try:
    print("Approcher votre carte RFID")
    id, text = reader.read()
    print(f"Id de la carte : {id}")
    print(f"Données : {text}")

except Exception as e:
    # Gestion des exceptions, vous pouvez personnaliser le message selon vos besoins
    print(f"Une erreur s'est produite : {e}")

finally:
    GPIO.cleanup()  # Nettoyage des ressources GPIO
