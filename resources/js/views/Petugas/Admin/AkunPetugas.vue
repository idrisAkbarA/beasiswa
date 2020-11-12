<template>
  <v-container fluid>
    <v-skeleton-loader
      :loading="isTableLoading"
      type="table"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers.petugas"
        :items="akunPetugas"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="info(item)"
            title="Info"
          >mdi-information-outline
          </v-icon>
          <v-icon
            small
            class="mr-2"
            @click="showChangePass(item)"
            title="Ubah Pasword"
          >mdi-textbox-password
          </v-icon>
          <v-icon
            small
            v-if="item.id != petugas.id"
            title="Hapus Akun"
            @click="deleteItem(item)"
          >mdi-delete
          </v-icon>
        </template>
        <template v-slot:item.role="{ item }">
          <v-chip :color="role[item.role - 1].color">
            {{ role[item.role - 1].role }}
          </v-chip>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>

    <!-- change pass modal -->
    <v-bottom-sheet
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleChangePass"
    >
      <v-card>
        <v-card-title>
          <span>Ubah Password</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="toggleChangePass = false"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="changePass()"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 100px;">
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Password Baru"
                v-model="form.password"
                type="password"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Konfirmasi Password Baru"
                v-model="form.password2"
                type="password"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>

    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleAkunPetugas"
    >
      <v-card>
        <v-card-title> <span>Tambah Petugas</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="toggleAkunPetugas = false"
            text
          >Batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="store()"
          >Tambah Petugas</v-btn>
        </v-card-title>
        <v-card-text>
          <v-row class="ma-5">
            <v-col
              cols="4"
              xs="12"
            >
              <v-text-field
                color="white"
                label="Nama Lengkap"
                v-model="storeItem.nama_lengkap"
              >
              </v-text-field>
            </v-col>
            <v-col
              cols="4"
              xs="12"
            >
              <v-text-field
                color="white"
                label="Username"
                v-model="storeItem.name"
              >
              </v-text-field>
            </v-col>
            <v-col
              cols="4"
              xs="12"
            >
              <v-text-field
                color="white"
                label="Password"
                type="password"
                v-model="storeItem.password"
              >
              </v-text-field>
            </v-col>
            <v-col
              cols="4"
              xs="12"
              class="mx-auto"
            >
              <v-select
                label="Role"
                color="white"
                :items="role"
                item-text="role"
                item-value="id"
                v-model="storeItem.role"
              ></v-select>
            </v-col>
            <v-col v-if="storeItem.role == 5">
              <v-select
                label="Fakultas"
                color="white"
                :items="fakultas"
                item-text="nama"
                item-value="id"
                v-model="storeItem.fakultas_id"
              ></v-select>
            </v-col>
          </v-row>

        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <v-dialog
      width="400"
      overlay-color="#69F0AE"
      v-model="dialogDelete"
    >
      <v-card>
        <v-card-title class="mt-2">
          <v-icon
            color="white"
            class="mr-2"
          >delete</v-icon> Hapus Akun
        </v-card-title>
        <v-card-text class="mt-2 white--text text-center">
          Apakah anda yakin akan menghapus akun ini ?
        </v-card-text>
        <v-card-actions>
          <v-btn
            text
            @click="dialogDelete = false"
            :loading="btnLoading"
          >
            Batal
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            dark
            class="green"
            @click="deleteConfirmed()"
          >
            <v-icon left>mdi-check</v-icon>
            Ya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialogInfo"
      width="500"
    >

      <v-card>
        <v-card-title>
          Info Petugas
          <v-spacer></v-spacer>
          <v-btn
            icon
            dark
            @click="dialogInfo = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text>
          <v-data-table
            :headers="headers.detailPetugas"
            :items="detailPetugas"
            hide-default-header
            hide-default-footer
          >
          </v-data-table>
        </v-card-text>

      </v-card>
    </v-dialog>
    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar.show"
      :timeout="2000"
    >
      {{ snackbar.message }}

      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getFakultas();
    this.getPetugas();
    this.getAkunPetugas();
  },
  computed: {
    ...mapState([
      "nim",
      "isTableLoading",
      "akunPetugas",
      "isOpenBeasiswa",
      "fakultas"
    ]),
    toggleAkunPetugas: {
      get: function() {
        return this.isOpenBeasiswa;
      },
      set: function(data) {
        this.toggleOpenBeasiswa(data);
      }
    }
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions([
      "getFakultas",
      "getAkunPetugas",
      "storeAkunPetugas",
      "editAkunPetugas",
      "deleteAkunPetugas"
    ]),
    getPetugas() {
      this.btnLoading = true;
      axios.get(`/api/user/petugas`).then(response => {
        this.petugas = response.data;
        this.btnLoading = false;
      });
    },
    info(item) {
      //   console.log(item);
      this.dialogInfo = true;
      this.detailPetugas = item;
    },
    showChangePass(item) {
      this.toggleChangePass = true;
      this.form = item;
      this.id = item.id;
    },
    changePass() {
      var data = this.form;
      if (data.password && data.password == data.password2) {
        this.btnLoading = true;
        this.editAkunPetugas(data)
          .then(response => {
            this.btnLoading = false;
            this.toggleChangePass = false;
            this.form = {};
            this.snackbar = {
              show: true,
              color: "blue",
              message: "Ubah password berhasil!"
            };
          })
          .catch(error => {
            this.btnLoading = false;
          });
      } else {
        this.snackbar = {
          show: true,
          color: "red",
          message: "Konfirmasi password harus sama!"
        };
      }
    },
    deleteItem(item) {
      this.dialogDelete = true;
      this.form.id = item.id;
    },
    deleteConfirmed() {
      this.btnLoading = true;
      this.deleteAkunPetugas(this.form)
        .then(response => {
          this.btnLoading = false;
          this.dialogDelete = false;
          this.form = {};
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Hapus akun berhasil!"
          };
        })
        .catch(error => {
          this.btnLoading = false;
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        });
    },
    store() {
      this.btnLoading = true;
      console.log(this.storeItem);
      this.storeAkunPetugas(this.storeItem).then(response => {
        this.storeItem = {};
        this.btnLoading = false;
        this.toggleAkunPetugas = false;
        this.snackbar = {
          show: true,
          color: "blue",
          message: "Tambah akun baru berhasil!"
        };
      });
    }
  },
  watch: {
    dialogInfo: function(val) {
      if (val) {
        const petugas = this.detailPetugas;
        this.detailPetugas = [
          { judul: "Nama", isi: petugas.nama_lengkap },
          { judul: "Username", isi: petugas.name },
          { judul: "Role", isi: petugas.role },
          {
            judul: "Fakultas",
            isi: !!petugas.fakultas ? petugas.fakultas.nama : "-"
          }
        ];
      } else {
        this.detailPetugas = [];
      }
    }
  },
  data() {
    return {
      form: {},
      petugas: {},
      detailPetugas: [],
      msg: "",
      toggleChangePass: false,
      dialogDelete: false,
      dialogInfo: false,
      btnLoading: false,
      snackbar: {
        show: false
      },
      editItem: {
        name: "",
        nama_lengkap: "",
        role: "",
        password: ""
      },
      storeItem: {
        name: "",
        nama_lengkap: "",
        role: "",
        password: ""
      },
      role: [
        { id: 1, role: "Administrator", color: "red" },
        { id: 2, role: "Pewawancara", color: "green" },
        { id: 3, role: "Surveyor", color: "blue darken-1" },
        { id: 4, role: "Petinggi", color: "orange" },
        { id: 5, role: "Verificator", color: "cyan darken-4" }
      ],
      headers: {
        petugas: [
          {
            text: "Username",
            align: "start",
            sortable: false,
            value: "name"
          },
          { text: "Role", value: "role" },
          { text: "Fakultas", value: "fakultas.nama" },
          { text: "Actions", value: "actions", sortable: false }
        ],
        detailPetugas: [
          { text: "Judul", value: "judul" },
          { text: "Isi", value: "isi" }
        ]
      }
    };
  }
};
</script>

<style>
</style>
