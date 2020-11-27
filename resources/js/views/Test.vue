<template>
  <v-app>
    <v-container>
      <v-card mx-auto>
        <v-card-title>
          Test Upload file
        </v-card-title>
        <v-card-text>
            <v-text-field
            color="green"
            filled
            label="Upload File"
            prepend-icon="mdi-attachment"
            :value="file ? file.name:null"
            readonly
            @click="$refs.fileInput.$refs.input.click()"
            >

            </v-text-field>
          <v-file-input
            hide-input
            ref="fileInput"
            v-model="file"
            class="d-none"
          ></v-file-input>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
export default {
    data() {
        return {
            file:null,
        }
    },
};
</script>

<style>
</style>