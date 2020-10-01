<template>
  <v-app id="inspire">
    <v-main class="bg-pattern">
      <v-container 
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="1"
            md="1"
          >
            <v-progress-circular
              style="margin:auto"
              :size="50"
              color="amber"
              indeterminate
            ></v-progress-circular>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {

}
</script>

<style>

</style>