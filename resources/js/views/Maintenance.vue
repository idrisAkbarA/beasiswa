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
          v-if="!$vuetify.breakpoint.mobile"
            max-width="25vw"
            :src="'/images/maintenance.png'"
            class="mx-auto "
          ></v-img>
          <v-img
          v-if="$vuetify.breakpoint.mobile"
            max-width="70vw"
            :src="'/images/maintenance.png'"
            class="mx-auto"
          ></v-img>

        </v-col>
        <v-col cols="12">
          <h3 class=" mx-auto text-center center-text font-weight-light">Duh.. maaf, situs sedang dalam maintenance. <br><br>Datang lagi nanti ya... </h3>
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
export default {

}
</script>

<style>

</style>