<script setup>
import { ref } from "vue";

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

const reserveRoom = (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  if (room && room.selectedTimeSlot && room.currentOccupancy < room.capacity) {
    room.currentOccupancy += 1;
    alert(
      `Vous avez réservé une place dans la ${room.name} pour le créneau ${room.selectedTimeSlot}`
    );
  } else {
    alert("Veuillez sélectionner un créneau horaire ou la salle est pleine.");
  }
};

const cancelReservation = (roomId) => {
  const room = rooms.value.find((r) => r.id === roomId);
  if (room && room.currentOccupancy > 0) {
    room.currentOccupancy -= 1;
    alert(`Vous avez annulé une place dans la ${room.name}`);
    room.selectedTimeSlot = null; // Réinitialise le créneau horaire après annulation
  } else {
    alert("Aucune place à annuler.");
  }
};
</script>

<template>
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
</template>

<style scoped>
.booking-page {
  padding: 20px;
  text-align: center;
}

.room-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 10px;
}

.room-card {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 200px;
}

.button-group {
  margin-top: 10px;
  display: flex;
  justify-content: center;
  gap: 10px;
}

button {
  background-color: #3498db;
  color: white;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background-color: #2980b9;
}
</style>
