<?php
require __DIR__ . '/vendor/autoload.php'; // Chemin vers l'autoloader de Composer

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage; // Stockage des clients
    }

    public function onOpen(ConnectionInterface $conn) {
        // Ajoute le nouveau client à la liste des connexions
        $this->clients->attach($conn);
        echo "Nouvelle connexion: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Envoie le message à tous les clients
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Supprime le client de la liste
        $this->clients->detach($conn);
        echo "Connexion {$conn->resourceId} fermée\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Une erreur s'est produite: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Crée le serveur WebSocket
$server = new Ratchet\App('10.61.55.5', 8080);
$server->route('/ws', new WebSocketServer, ['*']);
$server->run();
