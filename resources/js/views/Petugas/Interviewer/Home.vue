<template>
  <v-card
    light
    elevation="20"
    width="70%"
    class="fill-height mx-auto"
    style="overflow-y: auto"
  >
    <v-card-text>
      <v-subheader>Permohonan Beasiswa</v-subheader>
      <v-row>
        <v-expansion-panels>
            <v-expansion-panel
            v-for="(item,i) in 5"
            :key="i"
            >
            <v-expansion-panel-header>
                Item
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>

      </v-row>

    </v-card-text>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      headers: [
        {
          text: "Nama Instansi",
          align: "start",
          sortable: false,
          value: "name"
        },
        { text: "Actions", value: "actions", sortable: false }
      ]
    };
  }
};
</script>

<style scoped>
.area {
  width: 70%;
  margin: auto;
  position: absolute;
  height: 100%;
  background: white;
}
</style>