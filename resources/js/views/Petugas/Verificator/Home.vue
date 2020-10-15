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
                        @click="sheetDetail = true, selectedPermohonan = permohonan, parsedForm = JSON.parse(permohonan.form)"
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
          <v-tabs fixed-tabs>
            <v-tab>
              Permohonan Beasiswa
            </v-tab>
            <v-tab>
              Biodata Mahasiswa
            </v-tab>
            <v-tab-item>
              <v-row
                no-gutters=""
                class="ma-5"
                v-for="(field,index) in parsedForm"
                :key="index"
              >

                <v-col style="padding-bottom:0 !important;">
                  <p>{{field.pertanyaan}}</p>
                  <v-container v-if="field.type == 'Checkboxes'">
                    {{'pep'+field.isLulus}}
                    <v-row>

                      <v-col cols="9">
                        <v-row
                          align="center"
                          v-for="(item,index) in field.checkboxes.items"
                          :key="index"
                        >
                          <v-checkbox
                            disabled
                            v-model="field.value"
                            :value="item.label"
                            color="white"
                            hide-details
                            class="shrink mr-2 mt-0"
                          ></v-checkbox>
                          <span>
                            {{item.label}}
                          </span>

                        </v-row>
                      </v-col>
                      <v-col cols="3">
                        <v-radio-group v-model="field.isLulus">
                          <v-radio
                            label="Lulus"
                            :value="true"
                          ></v-radio>
                          <v-radio
                            label="Tidak Lulus"
                            :value="false"
                          ></v-radio>
                        </v-radio-group>

                      </v-col>
                    </v-row>
                  </v-container>
                  <v-container v-if="field.type == 'Multiple Upload'">
                    <v-row>
                      <v-col cols="9">
                        <v-row
                          align="center"
                          v-for="(item,index) in field.multiUpload.items"
                          :key="index"
                          :value="item.label"
                          no-gutters
                        >
                          <v-col cols="1">
                            <v-checkbox
                              v-model="item.isSelected"
                              color="white"
                              hide-details
                              class="shrink mr-2 mt-0"
                              :value="item.label"
                              disabled
                            ></v-checkbox>

                          </v-col>
                          <v-col cols="6">
                            <span>{{item.label}}</span>

                          </v-col>
                          <v-col cols="5">
                            <v-btn
                              v-if="item.file_name"
                              small
                              @click="link(item.file_name)"
                            >lihat file</v-btn>
                          </v-col>

                        </v-row>

                      </v-col>
                      <v-col cols="3">
                        <v-radio-group v-model="field.isLulus">
                          <v-radio
                            label="Lulus"
                            :value="true"
                          ></v-radio>
                          <v-radio
                            label="Tidak Lulus"
                            :value="false"
                          ></v-radio>
                        </v-radio-group>

                      </v-col>
                    </v-row>

                  </v-container>
                  <v-row v-if="field.type == 'Pilihan'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>{{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>

                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Pendek'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>{{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>

                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Angka'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>{{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>

                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Tanggal'">
                    <v-col>
                      <span>
                        <v-icon>mdi-text-short</v-icon>{{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Upload File'">
                    <v-col cols="9">
                      <v-btn
                        small
                        @click="link(field.value)"
                      >lihat file</v-btn>

                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Paragraf'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>{{field.value}}
                      </span>

                    </v-col>
                    <v-col cols="3">
                      <v-radio-group v-model="field.isLulus">
                        <v-radio
                          label="Lulus"
                          :value="true"
                        ></v-radio>
                        <v-radio
                          label="Tidak Lulus"
                          :value="false"
                        ></v-radio>
                      </v-radio-group>

                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12">
                  <v-divider></v-divider>
                </v-col>
                <v-col cols="12">

                </v-col>
              </v-row>
            </v-tab-item>
            <v-tab-item>
              <v-data-table
                :headers="headers"
                :items="selectedMahasiswa"
                hide-default-header
                hide-default-footer
              >
              </v-data-table>
            </v-tab-item>
          </v-tabs>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn
            color="red"
            :disabled="btnTidakLulus"
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
          <v-card-text class="white--text mt-2 pb-0">
            <strong class="d-block">{{this.selectedPermohonan.mahasiswa.nama}}</strong> akan dinyatakan <strong>{{dialogDelete.value ? 'Lolos' : 'Tidak Lolos'}}</strong> tahap berkas ?
            <v-textarea
              v-model="keterangan"
              class="mt-2"
              hint="Wajib diisi"
              :persistent-hint="true"
              v-if="!dialogDelete.value"
              label="Keterangan"
              rows="1"
              auto-grow
              color="white"
            >

            </v-textarea>
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
              v-if="dialogDelete.value"
              color="#2E7D32"
              dark
              @click="setBerkas(dialogDelete.value)"
            >
              Ya
            </v-btn>
            <v-btn
              v-if="!dialogDelete.value"
              color="#2E7D32"
              :disabled="keterangan? false:true"
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
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    setBerkas(bool) {
      this.btnLoading = true;
      if (bool) {
        this.keterangan = null;
      }
      axios
        .put(`${this.url}/api/pemohon/set-berkas`, {
          id: this.selectedPermohonan.id,
          bool: bool,
          keterangan: this.keterangan,
          form: this.parsedForm
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
    selectedPermohonan: function(val) {
      if (val) {
        this.selectedMahasiswa = [
          { judul: "Nama", isi: val.mahasiswa.nama },
          { judul: "NIM", isi: val.mahasiswa.nim },
          { judul: "Semester", isi: val.mahasiswa.semester },
          { judul: "Jurusan", isi: val.mahasiswa.jurusan.nama },
          { judul: "Fakultas", isi: val.mahasiswa.fakultas.nama },
          { judul: "IPS", isi: val.mahasiswa.ips },
          { judul: "IPK", isi: val.mahasiswa.ipk }
        ];
      }
    },
    parsedForm: {
      deep: true,
      handler(val) {
        for (let i = 0; i < val.length; i++) {
          const element = val[i];
          if (!element.isLulus) {
            this.btnLoading = true;
            this.btnTidakLulus = false;
            break;
          }
          this.btnTidakLulus = true;
          this.btnLoading = false;
        }
      }
    }
  },
  computed: {
    ...mapState(["beasiswa", "url"]),
    resultQuery() {
      if (this.searchQuery) {
        if (typeof this.beasiswa[this.index].berkas == "object") {
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
      keterangan: null,
      parsedForm: {},
      btnTidakLulus: false,
      index: 0,
      searchQuery: "",
      btnLoading: false,
      sheetDetail: false,
      selectedPermohonan: {},
      selectedMahasiswa: {},
      snackbar: { show: false },
      petugas: { fakultas: { nama: "" } },
      dialogDelete: { show: false },
      headers: [
        {
          text: "Judul",
          value: "judul"
        },
        { text: "Isi", value: "isi" }
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

