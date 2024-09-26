<script setup>
import { ref, computed } from "vue";

// Floor data avec des chemins d'images stockés dans le dossier `public`
const floorData = [
  {
    id: 1,
    name: "RDC",
    imageUrl: "/Plan/Plan_RDC.webp", // Les images doivent être dans `public/Plan`
  },
  {
    id: 2,
    name: "1er étage",
    imageUrl: "/Plan/Plan_1er_etage.webp",
  },
  {
    id: 3,
    name: "2e étage",
    imageUrl: "/Plan/Plan_2em_etage.webp",
  },
  {
    id: 4,
    name: "3e étage",
    imageUrl: "/Plan/Plan_3e_etage.webp",
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
  max-width: 100%;
  height: auto;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-top: 1rem;
}
</style>
