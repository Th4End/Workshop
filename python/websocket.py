import asyncio
import websockets
import json

# Simuler des données des capteurs
def get_sensor_data():
    rfid_data = {"rfid": "1234567890"}
    motion_data = {"motion": True}  # True si mouvement détecté, False sinon
    return json.dumps({"rfid": rfid_data, "motion": motion_data})

async def handler(websocket, path):
    while True:
        sensor_data = get_sensor_data()
        await websocket.send(sensor_data)
        await asyncio.sleep(5)  # Envoie des données toutes les 5 secondes

start_server = websockets.serve(handler, "localhost", 6789)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
