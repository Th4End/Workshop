<script setup>
import { ref, computed } from "vue";

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
  <section>
    <h2>Inscription</h2>
    <form @submit.prevent="submitForm">
      <div>
        <label for="firstname">Prénom</label>
        <input type="text" id="firstname" v-model="form.firstname" />
      </div>
      <div>
        <label for="lastname">Nom</label>
        <input type="text" id="lastname" v-model="form.lastname" />
      </div>
      <div>
        <label for="email">Email</label>
        <input type="text" id="email" v-model="form.email" />
      </div>
      <div>
        <label for="pswd">Mot de passe</label>
        <input type="password" id="pswd" v-model="form.pswd" />
      </div>
      <p v-if="errorMsg" class="error">{{ errorMsg }}</p>
      <button type="submit">M'inscrire</button>
    </form>
  </section>
</template>

<style lang="css" scoped></style>
