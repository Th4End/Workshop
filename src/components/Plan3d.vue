<script setup>
import { ref, computed } from "vue";

// Floor data (avec les chemins d'image corrigés)
const floorData = [
  {
    id: 1,
    name: "RDC",
    imageUrl: "/workshop/Plan/Plan_RDC.png", // Utilisation de chemins relatifs valides
  },
  {
    id: 2,
    name: "1er étage",
    imageUrl: "../Plan/Plan_1er_etage.png",
  },
  {
    id: 3,
    name: "2e étage",
    imageUrl: "../Plan/Plan_2em_etage.png",
  },
  {
    id: 4,
    name: "3e étage",
    imageUrl: "../Plan/Plan_3e_etage.png",
  },
];

// Selected floor
const selectedFloor = ref(1);

// Current floor image
const currentFloorImage = computed(() => {
  return floorData.find((floor) => floor.id === selectedFloor.value)?.imageUrl;
});

// Current floor name
const currentFloorName = computed(() => {
  return floorData.find((floor) => floor.id === selectedFloor.value)?.name;
});
</script>

<template>
  <section class="map-container">
    <h2>Plan de l'école</h2>

    <!-- Slider pour choisir l'étage -->
    <label for="floor-slider">Choisissez un étage :</label>
    <input
      id="floor-slider"
      type="range"
      min="1"
      max="4"
      v-model="selectedFloor"
    />

    <p>Étage sélectionné : {{ currentFloorName }}</p>

    <div class="floor-image">
      <img :src="currentFloorImage" :alt="'Carte de ' + currentFloorName" />
    </div>
  </section>
</template>

<style scoped>
.map-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

input[type="range"] {
  width: 300px;
  margin: 1rem 0;
}

.floor-image img {
  max-width: 100%; /* L'image s'adapte à la taille du conteneur */
  height: auto;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-top: 1rem;
}
</style>
