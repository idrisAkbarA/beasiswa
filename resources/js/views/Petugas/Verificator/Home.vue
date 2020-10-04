<template>
  <v-card
    color="#E8F5E9"
    light
    elevation="20"
    width="70%"
    class="fill-height mx-auto"
    style="overflow-y: auto"
  >
    <v-card-text>
      <v-subheader><strong class="text-dark">Permohonan Beasiswa Fakultas {{petugas.fakultas.nama}}</strong></v-subheader>
      <v-row class="pl-8 pr-8">
        <v-text-field
          prepend-inner-icon="mdi-magnify"
          clearable
          label="Pencarian"
        ></v-text-field>
      </v-row>
      <v-row>
        <v-card-text>
          <p
            v-if="!beasiswa"
            class="text-center"
          >Tidak ada berkas</p>
          <v-expansion-panels
            hover
            inset
          >
            <v-expansion-panel
              v-for="(item,i) in beasiswa"
              :key="i"
            >
              <v-expansion-panel-header>
                <v-row
                  no-gutters
                  align="center"
                  justify="space-between"
                >
                  <v-col cols="6"><strong>{{item.nama}}</strong></v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.berkas).length < 1"
                  >
                    <span class="caption text-muted">
                      Belum ada permohonan masuk

                    </span>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.berkas).length > 0"
                  >
                    <v-chip
                      class="text-center"
                      small
                      label
                      dark
                      color="green"
                    >
                      {{Object.keys(item.berkas).length}} Permohonan masuk

                    </v-chip>
                  </v-col>
                </v-row>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <span class="text-muted">{{item.deskripsi}}</span>
                <v-row>
                  <v-divider class="mb-0"></v-divider>
                </v-row>
                <v-list>
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    v-model="searchQuery"
                    @focus="index = i"
                  ></v-text-field>
                  <v-subheader>Permohonan Masuk ({{!searchQuery ? Object.keys(item.berkas).length : resultQuery.length}})</v-subheader>
                  <v-list-item-group
                    class="bg-white"
                    color="primary"
                  >
                    <template v-for="(permohonan, index) in !searchQuery ? item.berkas : resultQuery">
                      <v-list-item
                        :key="index"
                        @click="sheetDetail = true, selectedPermohonan = permohonan"
                      >
                        <template>
                          <v-list-item-content>
                            <v-list-item-title v-text="permohonan.mahasiswa.nama"></v-list-item-title>
                            <v-list-item-subtitle v-text="`${permohonan.mahasiswa.jurusan.nama} (${permohonan.mahasiswa.fakultas.nama})`"></v-list-item-subtitle>
                          </v-list-item-content>
                          <v-list-item-action>
                            <v-icon>mdi-chevron-right</v-icon>
                          </v-list-item-action>
                        </template>
                      </v-list-item>
                      <v-divider
                        v-if="index < Object.keys(item.berkas).length - 1"
                        :key="index"
                        class="my-0"
                      ></v-divider>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-row>
    </v-card-text>
    <!-- Sheet Detail -->
    <v-bottom-sheet
      width="60%"
      inset
      scrollable
      overlay-color="#69F0AE"
      v-if="sheetDetail"
      v-model="sheetDetail"
    >
      <v-card>
        <v-card-title
          class="headline white--text"
          primary-title
        >
          <i class="mdi mdi-account mr-2"></i> {{selectedPermohonan.mahasiswa.nama}}
          <v-spacer></v-spacer>
          <v-icon
            @click="sheetDetail = false"
            color="red"
          >mdi-close-box</v-icon>
        </v-card-title>

        <v-card-text class="mt-2 white--text">
          Persyaratan Permohonan Beasiswa
          <v-row
            no-gutters=""
            class="ma-5"
            v-for="(field,index) in JSON.parse(selectedPermohonan.form)"
            :key="index"
          >

            <v-col style="padding-bottom:0 !important;">
              <p>{{field.pertanyaan}}</p>
              <p v-if="field.type == 'Pilihan'"><span>
                  <v-icon>mdi-text-short</v-icon>{{field.value}}
                </span></p>
              <p v-if="field.type == 'Jawaban Pendek'"><span>
                  <v-icon>mdi-text-short</v-icon>{{field.value}}
                </span></p>
              <p v-if="field.type == 'Jawaban Angka'"><span>
                  <v-icon>mdi-text-short</v-icon>{{field.value}}
                </span></p>
              <p v-if="field.type == 'Tanggal'"><span>
                  <v-icon>mdi-text-short</v-icon>{{field.value}}
                </span></p>
              <v-btn
                v-if="field.type == 'Upload File'"
                small
                @click="link(field.value)"
              >lihat file</v-btn>
              <p v-if="field.type == 'Paragraf'"><span>
                  <v-icon>mdi-text-short</v-icon>{{field.value}}
                </span></p>
            </v-col>
            <v-col cols="12">
              <v-divider></v-divider>
            </v-col>
            <v-col cols="12">

            </v-col>
          </v-row>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn
            light
            color="grey"
            @click="dialogDelete = { show : true, value : false}"
          >
            <v-icon>close</v-icon> Tidak Lulus
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2E7D32"
            dark
            :disabled="btnLoading"
            @click="dialogDelete = { show : true, value : true}"
          >
            <v-icon>check</v-icon> Lulus
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <div
      class="text-center"
      v-if="dialogDelete.show"
    >
      <v-dialog
        v-model="dialogDelete.show"
        width="400"
        overlay-color="#2E7D32"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i> Varifikasi Berkas
          </v-card-title>
          <v-card-text class="white--text text-center mt-2 pb-0">
            <strong class="d-block">{{this.selectedPermohonan.mahasiswa.nama}}</strong> akan dinyatakan <strong>{{dialogDelete.value ? 'Lolos' : 'Tidak Lolos'}}</strong> tahap berkas ?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn
              @click="dialogDelete = false"
              color="white"
              text
            >Batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              dark
              @click="setBerkas(dialogDelete.value)"
            >
              Ya
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
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
  </v-card>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaWithPermohonan();
    this.getPetugas();
  },
  methods: {
    ...mapActions(["getBeasiswaWithPermohonan"]),
    getPetugas() {
      axios.get(`${this.url}/api/user/petugas`).then(response => {
        this.petugas = response.data;
        this.btnLoading = false;
      });
    },
    link(url) {
      var a = this.url + "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    setBerkas(bool) {
      this.btnLoading = true;
      axios
        .put(`${this.url}/api/pemohon/set-berkas`, {
          id: this.selectedPermohonan.id,
          bool: bool
        })
        .then(response => {
          this.getBeasiswaWithPermohonan();
          this.sheetDetail = false;
          this.dialogDelete = false;
          this.btnLoading = false;
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil!"
          };
        });
    }
  },
  watch: {
    //
  },
  computed: {
    ...mapState(["beasiswa", "url"]),
    resultQuery() {
      if (this.searchQuery) {
        if (typeof this.beasiswa[this.index].berkas == "object") {
          console.log("object");
          this.beasiswa[this.index].berkas = Object.values(
            this.beasiswa[this.index].berkas
          );
        }
        return this.beasiswa[this.index].berkas.filter(item => {
          return this.searchQuery
            .toLowerCase()
            .split(" ")
            .every(v => item.mahasiswa.nama.toLowerCase().includes(v));
        });
      } else {
        return this.beasiswa.berkas;
      }
    }
  },
  data() {
    return {
      index: 0,
      searchQuery: "",
      btnLoading: false,
      sheetDetail: false,
      selectedPermohonan: {},
      snackbar: { show: false },
      petugas: { fakultas: { nama: "" } },
      dialogDelete: { show: false },
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
a {
  text-decoration: none !important;
}
.area {
  width: 70%;
  margin: auto;
  position: absolute;
  height: 100%;
  background: white;
}
</style>

