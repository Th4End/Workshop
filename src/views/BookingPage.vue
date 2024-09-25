<script setup>
import { ref, onMounted } from "vue";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

// Liste des salles
const rooms = ref([]); // Initialiser comme tableau vide

// Créneaux horaires disponibles
const timeSlots = [
  "08:00 - 10:00",
  "10:00 - 12:00",
  "12:00 - 14:00",
  "14:00 - 16:00",
  "16:00 - 18:00",
];

// Variables pour stocker les messages d'erreur
const errorMessage = ref(null);
const successMessage = ref(null);

const fetchRooms = async () => {
  try {
    const response = await fetch(
      "http://localhost/back-end/routes/routes.php?action=getRooms",
      {
        method: "GET",
        mode: "cors",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();
    rooms.value = data.map(room => ({ ...room, currentOccupancy: 0 })); // Assurez-vous que currentOccupancy commence à 0
  } catch (error) {
    errorMessage.value = "Erreur lors de la récupération des salles : " + error.message;
    console.error(errorMessage.value);
  }
};

onMounted(() => {
  fetchRooms();
});

// Fonction pour gérer la réservation avec requête POST
const reserveRoom = async (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  console.log(`Room ID: ${roomId}, Current Occupancy: ${room.currentOccupancy}, Capacity: ${room.capacity}`); // Log ici
  if (room && room.selectedTimeSlot) {
    if (room.currentOccupancy < room.capacity) {
      try {
        const response = await fetch(
          "http://localhost/back-end/routes/routes.php?action=reserveRoom",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              user_id: 1,
              course_id: 1,
              room_id: roomId,
              reservation_date: new Date().toISOString().slice(0, 10),
              start_time: room.selectedTimeSlot.split(" - ")[0],
              end_time: room.selectedTimeSlot.split(" - ")[1],
            }),
          }
        );

        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();
        console.log(data); // Log de la réponse de l'API

        if (data.message === "Réservation réussie") {
          room.currentOccupancy += 1; // Met à jour l'occupation
          successMessage.value = `Réservation réussie : Vous avez réservé une place dans la ${room.room_name} pour le créneau ${room.selectedTimeSlot}.`;
          room.selectedTimeSlot = null;
        } else {
          errorMessage.value = data.message; // Affiche le message d'erreur
        }
      } catch (error) {
        errorMessage.value = "Erreur lors de la réservation : " + error.message;
        console.error(errorMessage.value);
      }
    } else {
      errorMessage.value = "La salle est pleine. Veuillez sélectionner un autre créneau.";
    }
  } else {
    errorMessage.value = "Veuillez sélectionner un créneau horaire.";
  }
};

// Fonction pour annuler la réservation avec requête POST
const cancelReservation = async (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  if (room && room.currentOccupancy > 0) {
    try {
      const response = await fetch(
        "http://localhost/back-end/routes/routes.php?action=cancelReservation",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            reservation_id: roomId, // Ajuster selon la logique backend
          }),
        }
      );

      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }
      const data = await response.json();
      if (data.message === "Annulation réussie") {
        room.currentOccupancy -= 1; // Met à jour l'occupation
        successMessage.value = `Annulation réussie : Vous avez annulé une place dans la ${room.room_name}.`;
        room.selectedTimeSlot = null;
      } else {
        errorMessage.value = data.message; // Affiche le message d'erreur
      }
    } catch (error) {
      errorMessage.value = "Erreur lors de l'annulation : " + error.message;
      console.error(errorMessage.value);
    }
  } else {
    errorMessage.value = "Aucune réservation à annuler.";
  }
};
</script>

<template>
  <div class="main-container">
    <Header />
    <section class="booking-page">
      <h2>Réservation de salles</h2>
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
      <div class="room-grid">
        <div v-for="room in rooms" :key="room.id" class="room-card">
          <div class="room-info">
            <h3>{{ room.room_name }}</h3>
            <p>Capacité : {{ room.capacity }} personnes</p>
            <p>Occupée par : {{ room.currentOccupancy }} personnes</p>

            <!-- Sélection du créneau horaire -->
            <label for="time-slot">Choisissez un créneau :</label>
            <select
              v-model="room.selectedTimeSlot"
              :disabled="room.currentOccupancy >= room.capacity"
            >
              <option value="" disabled>-- Sélectionner un créneau --</option>
              <option v-for="slot in timeSlots" :key="slot" :value="slot">
                {{ slot }}
              </option>
            </select>

            <div class="button-group">
              <button
                @click="reserveRoom(room.id)"
                :disabled="
                  room.currentOccupancy >= room.capacity ||
                  !room.selectedTimeSlot
                "
              >
                Réserver
              </button>
              <button
                @click="cancelReservation(room.id)"
                :disabled="room.currentOccupancy === 0"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <Footer />
  </div>
</template>

<style scoped>
.main-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.booking-page {
  padding: 20px;
  text-align: center;
  flex-grow: 1;
}

.error-message {
  color: red;
  margin-bottom: 20px;
}

.success-message {
  color: green;
  margin-bottom: 20px;
}

.room-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 10px;
}

.room-card {
  border-radius: 8px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 200px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.button-group {
  margin-top: 10px;
  display: flex;
  justify-content: center;
  gap: 10px;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
}

button:disabled {
  background-color: #ccc;
}

@media (min-width: 768px) {
  button {
    cursor: pointer;
  }

  button:disabled {
    cursor: not-allowed;
  }

  button:hover:not(:disabled) {
    background-color: var(--bg-color);
  }
}
</style>
  