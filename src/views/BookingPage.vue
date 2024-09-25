<script setup>
import { ref, onMounted } from "vue";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

const rooms = ref([
  {
    id: 1,
    name: "Salle 101",
    capacity: 20,
    currentOccupancy: 0,
    selectedTimeSlot: null,
  },
  {
    id: 2,
    name: "Salle 102",
    capacity: 15,
    currentOccupancy: 0,
    selectedTimeSlot: null,
  },
  {
    id: 3,
    name: "Salle 103",
    capacity: 25,
    currentOccupancy: 0,
    selectedTimeSlot: null,
  },
]);

// Créneaux horaires disponibles
const timeSlots = [
  "08:00 - 10:00",
  "10:00 - 12:00",
  "12:00 - 14:00",
  "14:00 - 16:00",
  "16:00 - 18:00",
];

const fetchRooms = async () => {
  try {
    const response = await fetch(
      "http://localhost/back-end/routes.php?action=getRooms"
    );
    const data = await response.json();
    rooms.value = data; // Remplit la liste des salles avec les données du backend
  } catch (error) {
    console.error("Erreur lors de la récupération des salles :", error);
  }
};

onMounted(() => {
  fetchRooms();
});

// Fonction pour gérer la réservation avec requête POST
const reserveRoom = async (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  if (room && room.selectedTimeSlot && room.currentOccupancy < room.capacity) {
    try {
      const response = await fetch(
        "http://localhost/back-end/routes.php?action=reserveRoom",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            user_id: 1, // ID utilisateur (à ajuster dynamiquement)
            course_id: 1, // ID du cours (à ajuster dynamiquement)
            room_id: roomId,
            reservation_date: "2024-09-25", // Date de la réservation (à ajuster dynamiquement)
            start_time: room.selectedTimeSlot.split(" - ")[0],
            end_time: room.selectedTimeSlot.split(" - ")[1],
          }),
        }
      );
      const data = await response.json();
      if (data.message === "Réservation réussie") {
        room.currentOccupancy += 1;
        alert(
          `Vous avez réservé une place dans la ${room.name} pour le créneau ${room.selectedTimeSlot}`
        );
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Erreur lors de la réservation :", error);
    }
  } else {
    alert("Veuillez sélectionner un créneau horaire ou la salle est pleine.");
  }
};

// Fonction pour annuler la réservation avec requête POST
const cancelReservation = async (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  if (room && room.currentOccupancy > 0) {
    try {
      const response = await fetch(
        "http://localhost/back-end/routes.php?action=cancelReservation",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            reservation_id: roomId, // L'ID de la réservation (à ajuster selon la logique backend)
          }),
        }
      );
      const data = await response.json();
      if (data.message === "Annulation réussie") {
        room.currentOccupancy -= 1;
        alert(`Vous avez annulé une place dans la ${room.name}`);
        room.selectedTimeSlot = null; // Réinitialise le créneau horaire après annulation
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Erreur lors de l'annulation :", error);
    }
  } else {
    alert("Aucune place à annuler.");
  }
};
</script>

<template>
  <div class="main-container">
    <Header />
    <section class="booking-page">
      <h2>Réservation de salles</h2>
      <div class="room-grid">
        <div v-for="room in rooms" :key="room.id" class="room-card">
          <div class="room-info">
            <h3>{{ room.name }}</h3>
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
                  room.currentOccupancy >= room.capacity || !room.selectedTimeSlot
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
