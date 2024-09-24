<script setup>
import { ref, computed } from "vue";
// import bcrypt from "bcryptjs";

const form = ref({
  firstname: "",
  lastname: "",
  email: "",
  pswd: "",
});

const errorMsg = ref("");

const hashedPswd = computed(() => {
  return bcrypt.haskSync(form.value.pswd, 10);
});

const submitForm = async () => {
  errorMsg.value = "";

  if (
    !form.value.firstname ||
    !form.value.lastname ||
    !form.value.email ||
    !form.value.pswd
  ) {
    errorMsg.value = "You have to feed all inputs form";
    return;
  }

  const hashedPswd = await bcrypt.hash(form.value.pswd, 10);
  console.log("data form :", form.value);

  // reset form after successful submit
  form.value.firstname = "";
  form.value.lastname = "";
  form.value.email = "";
  form.value.pswd = "";
};
</script>

<template>
  <section class="container">
    <h2>Inscription</h2>
    <form @submit.prevent="submitForm">
      <label for="firstname"
        >Prénom
        <input type="text" id="firstname" v-model="form.firstname" />
      </label>
      <label for="lastname"
        >Nom
        <input type="text" id="lastname" v-model="form.lastname" />
      </label>
      <label for="email"
        >Email
        <input type="text" id="email" v-model="form.email" />
      </label>
      <label for="pswd"
        >Mot de passe
        <input type="password" id="pswd" v-model="form.pswd" />
      </label>
      <p v-if="errorMsg" class="error">{{ errorMsg }}</p>
      <button type="submit">M'inscrire</button>
    </form>
  </section>
</template>

<style scoped>
.container {
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

label {
  display: flex;
  flex-direction: column;
}
</style>
