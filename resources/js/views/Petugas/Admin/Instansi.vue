<template>
  <v-container>
    <v-skeleton-loader type="table" :loading="isTableLoading" transition="fade-transition">
      <v-data-table
        :headers="headers"
        :items="instansi"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>
    <v-bottom-sheet scrollable width="60%" inset overlay-color="#69F0AE" v-model="toggleInstansi">
      <v-card>
        <v-card-title>
          <span>Tambah Instansi</span>
          <v-spacer></v-spacer>
          <v-btn @click="batal()" text>batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            v-model="toggleInstansi"
            @click="save()"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px;">
          <v-row dense class="ml-1 mr-1">
            <v-col>
              <v-text-field color="#C8E6C9" label="Nama Instansi" v-model="name"></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getBeasiswa", "getInstansi", "storeInstansi"]),
    save() {
      var data = {
        name: this.name
      };
      console.log(data);
      this.btnLoading = true;
      this.storeInstansi(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleInstansi= false;
        })
        .catch(error => {
          this.btnLoading = false;
        });
    }
  },
  computed: {
    ...mapState([
      "beasiswa",
      "isOpenBeasiswa",
      "instansi",
      "isTableLoading",
      "isLoading"
    ]),
    toggleInstansi: {
      get: function() {
        return this.isOpenBeasiswa;
      },
      set: function(data) {
        this.toggleOpenBeasiswa(data);
      }
    }
  },
  data() {
    return {
      btnLoading: false,
      name: "",
      sheet: false,
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

<style>
</style>