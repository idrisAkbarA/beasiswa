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
    <!-- bottom sheet tambah -->
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
    <!-- bottom sheet edit -->
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleInstansiEdit"
    >
      <v-card>
        <v-card-title>
          <span>Edit Instansi</span>
          <v-spacer></v-spacer>
          <v-btn @click="batalEdit()" text>batal</v-btn>
          <v-btn color="#2E7D32" :loading="btnLoading" @click="saveEdit()">Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px;">
          <v-row dense class="ml-1 mr-1">
            <v-col>
              <v-text-field color="#C8E6C9" label="Nama Instansi" v-model="nameEdit"></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <div class="text-center">
      <v-dialog v-model="dialogDelete" width="400">
        <v-card>
          <v-card-title class="headline white--text" primary-title>
            <v-icon color="white" class="mr-2">delete</v-icon>Hapus Instansi
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            Apakah anda yakin akan menghapus Instansi
            <span class="font-weight-bold"></span>?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn @click="batalDelete()" color="white" text>batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="red" dark @click="dialogDelete = false,deleteConfirmed()">
              <v-icon>delete</v-icon>Hapus
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- Akhir -->
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
    ...mapActions([
      "getBeasiswa",
      "getInstansi",
      "storeInstansi",
      "deleteInstansi",
      "editInstansi"
    ]),
    save() {
      var data = {
        name: this.name
      };
      console.log(data);
      this.btnLoading = true;
      this.storeInstansi(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleInstansi = false;
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
    batal() {
      this.toggleInstansi = false;
    },
    batalDelete() {
      this.dialogDelete = false;
    },
    deleteItem(item) {
      this.dialogDelete = true;
      this.id = item.id;
    },
    deleteConfirmed() {
      this.btnLoading = true;
      this.deleteInstansi(this.id)
        .then(response => {
          this.btnLoading = false;
          this.toggleInstansi = false;
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
    editItem(item) {
      this.toggleInstansiEdit = true;
      this.nameEdit = item.name;
      this.idEdit = item.id;
    },
    batalEdit() {
      this.toggleInstansiEdit = false;
    },
    saveEdit() {
        var data={
            id:this.idEdit,
            name:this.nameEdit
        }
      this.btnLoading = true;
      this.editInstansi(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleInstansi = false;
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
      id: "",
      name: "",
      nameEdit: "",
      idEdit: "",
      sheet: false,
      toggleInstansiEdit: false,
      dialogDelete: false,
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