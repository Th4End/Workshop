import websockets


url = "ws://http://10.61.55.5/phpmyadmin/index.php?route=/database/structure&db=badgeit"

def on_open(ws):
    print("connexion établie")
def on_close(ws, close_status_code, close_msg):
    print("connexion fermée")
def on_error(ws, error):
    print(f"Erreur de connexion :{error}")

ws = websockets.WebSocketApp(url, on_open=on_open,on_close=on_close, on_error=on_error)

try:
    ws.run_forever()
except KeyboardInterrupt:
    print("Arrêt du client websocket.")