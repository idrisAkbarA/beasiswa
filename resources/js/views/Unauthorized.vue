<template>
  <v-app>
    <v-container fill-height>
      <v-row
        justify="center"
        align="center"
        align-content="center"
        no-gutters
      >
        <v-col cols="12">
          <v-img
            max-width="35vw"
            :src="'/images/noaccess.png'"
            class="mt-n16 mx-auto"
          ></v-img>

        </v-col>
        <v-col cols="12">
          <h1 class="mt-n16 mx-auto text-center center-text">Hmm.. anda tidak punya hak akses kesini. </h1>
        </v-col>
      </v-row>
      <v-row
        justify="center"
        no-gutters
      >
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
export default {};
</script>

<style>
</style>