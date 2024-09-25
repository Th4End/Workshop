Raspberry : 
    
    username : eliaz
    passwd : test1
    ip : 10.61.55.3
    connexion ssh (faut se connecter au switch sinon vlan différent(sous réseau différent(pas sur le même réseau)))
    ssh : utiliser la power shell et faire:
        ssh eliaz@10.61.55.3

    Python sur Raspberry :
        pour les dependencies python faire :
            si pip non existant sur la Raspberry faire :
                sudo apt update
                sudo apt install python3 python3-pip
            sinon faire :
                sudo pip3 install mfrc522
                sudo pip3 install python-rpi.gpio

        ce que doit faire le code python : 
        
        lire la carte rfid dire ce qui sait et donner des informations.

        ce que doit faire le code python capteur : 
        
        détecter du mouvement :
            si mouvement :
                salle remplie (bool:true)
            sinon :
                salle vide(bool:false)


        ce que doit faire le code websocket : 
        
        envoie ce que lit le badge et le capteur et l'envoyer en temps réel à la base de données pour que ça s'affiche sur le site web de manière dynamique 
