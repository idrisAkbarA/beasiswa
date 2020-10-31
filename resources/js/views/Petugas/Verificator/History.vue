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
      <v-subheader>
        <strong class="text-dark">History permohonan</strong>
      </v-subheader>
      <v-row class="pl-8 pr-8">
        <v-text-field prepend-inner-icon="mdi-magnify" clearable label="Pencarian"></v-text-field>
      </v-row>
      <v-row>
        <v-card-text>
          <v-row v-if="loading" justify="center" align-content="center" class="text-center">
            <v-col cols="12">
              <v-progress-circular class="mx-auto" color="green" indeterminate></v-progress-circular>
            </v-col>
            <v-col cols="12">Memuat data</v-col>
          </v-row>
          <p v-if="!beasiswa.length && !loading" class="text-center">Tidak ada berkas</p>
          <v-expansion-panels hover inset>
            <v-expansion-panel v-for="(item,i) in beasiswa" :key="i">
              <v-expansion-panel-header>
                <v-row no-gutters align="center" justify="space-between">
                  <v-col cols="6">
                    <strong>{{item.nama}}</strong>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.permohonan).length < 1"
                  >
                    <span class="caption text-muted">Belum ada permohonan masuk</span>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.permohonan).length > 0"
                  >
                    <v-chip
                      class="text-center"
                      small
                      label
                      dark
                      color="green"
                    >{{Object.keys(item.permohonan).length}} Permohonan diverifikasi</v-chip>
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
                  <v-subheader>Permohonan ({{!searchQuery ? Object.keys(item.permohonan).length : resultQuery.length}})</v-subheader>
                  <v-list-item-group class="bg-white" color="primary">
                    <template
                      v-for="(permohonan, index) in !searchQuery ? item.permohonan : resultQuery"
                    >
                      <v-list-item
                        :key="index"
                        @click="sheetDetail = true, selectedPermohonan = permohonan, parsedForm = JSON.parse(permohonan.form)"
                      >
                        <template>
                          <v-list-item-content>
                            <v-list-item-title v-text="permohonan.mahasiswa.nama"></v-list-item-title>
                            <v-list-item-subtitle
                              v-text="`${permohonan.mahasiswa.jurusan.nama} (${permohonan.mahasiswa.fakultas.nama})`"
                            ></v-list-item-subtitle>
                          </v-list-item-content>
                          <v-list-item-action>
                            <v-icon color="blue" v-if="permohonan.is_berkas_passed">mdi-check</v-icon>
                            <v-icon color="red" v-else>mdi-close</v-icon>
                          </v-list-item-action>
                        </template>
                      </v-list-item>
                      <v-divider
                        v-if="index < Object.keys(item.permohonan).length - 1"
                        :key="'divider '+index"
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
        <v-card-title class="headline white--text" primary-title>
          <i class="mdi mdi-account mr-2"></i>
          {{selectedPermohonan.mahasiswa.nama}}
          <v-spacer></v-spacer>
          <v-icon @click="sheetDetail = false" color="red">mdi-close-box</v-icon>
        </v-card-title>

        <v-card-text class="mt-2 white--text">
          <v-tabs fixed-tabs>
            <v-tab>Permohonan Beasiswa</v-tab>
            <v-tab>Biodata Mahasiswa</v-tab>
            <v-tab-item>
              <v-row no-gutters class="ma-5" v-for="(field,index) in parsedForm" :key="index">
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
                          <span>{{item.label}}</span>
                        </v-row>
                      </v-col>
                      <v-col cols="3">
                        <v-radio-group v-model="field.isLulus">
                          <v-radio label="Lulus" :value="true"></v-radio>
                          <v-radio label="Tidak Lulus" :value="false"></v-radio>
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
                        <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                        <!-- <v-radio-group v-model="field.isLulus">
                          <v-radio label="Lulus" :value="true"></v-radio>
                          <v-radio label="Tidak Lulus" :value="false"></v-radio>
                        </v-radio-group>-->
                      </v-col>
                    </v-row>
                  </v-container>
                  <v-row v-if="field.type == 'Pilihan'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Pendek'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Angka'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Tanggal'">
                    <v-col>
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Upload File'">
                    <v-col cols="9">
                      <v-btn small @click="link(field.value)">lihat file</v-btn>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Paragraf'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{field.value}}
                      </span>
                    </v-col>
                    <v-col cols="3">
                      <v-icon>{{field.isLulus ? 'mdi-check' : 'mdi-close'}}</v-icon>
                      <!-- <v-radio-group v-model="field.isLulus">
                        <v-radio label="Lulus" :value="true"></v-radio>
                        <v-radio label="Tidak Lulus" :value="false"></v-radio>
                      </v-radio-group>-->
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12">
                  <v-divider></v-divider>
                </v-col>
                <v-col cols="12"></v-col>
              </v-row>
            </v-tab-item>
            <v-tab-item>
              <v-data-table
                :headers="headers"
                :items="selectedMahasiswa"
                hide-default-header
                hide-default-footer
              ></v-data-table>
            </v-tab-item>
          </v-tabs>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions v-if="!editMode">
          <v-spacer></v-spacer>
          <v-btn light @click="editMode = true">Edit</v-btn>
        </v-card-actions>
        <v-card-actions v-else>
          <v-btn
            color="red"
            :loading="loading"
            @click="dialogDelete = { show : true, value : false}"
          >
            <v-icon class="mr-2">close</v-icon>Tidak Lulus
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2E7D32"
            dark
            :loading="loading"
            @click="dialogDelete = { show : true, value : true}"
          >
            <v-icon class="mr-2">check</v-icon>Lulus
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <div class="text-center" v-if="dialogDelete.show">
      <v-dialog v-model="dialogDelete.show" width="400" overlay-color="#2E7D32">
        <v-card>
          <v-card-title class="headline white--text" primary-title>
            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i> Varifikasi Berkas
          </v-card-title>
          <v-card-text class="white--text mt-2 pb-0">
            <strong class="d-block">{{this.selectedPermohonan.mahasiswa.nama}}</strong> akan dinyatakan
            <strong>{{dialogDelete.value ? 'Lolos' : 'Tidak Lolos'}}</strong> tahap berkas ?
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
            ></v-textarea>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn @click="dialogDelete = false, keterangan = null" color="white" text>Batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              v-if="dialogDelete.value"
              color="#2E7D32"
              dark
              @click="setBerkas(dialogDelete.value)"
            >Ya</v-btn>
            <v-btn
              v-if="!dialogDelete.value"
              color="#2E7D32"
              :disabled="!keterangan"
              dark
              @click="setBerkas(dialogDelete.value)"
            >Ya</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- Snackbar -->
    <v-snackbar v-model="snackbar.show" :timeout="2000" :color="snackbar.color">
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn small text v-bind="attrs" @click="snackbar.show = false">tutup</v-btn>
      </template>
    </v-snackbar>
  </v-card>
</template>

<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["url"]),
    resultQuery() {
      if (this.searchQuery) {
        if (typeof this.beasiswa[this.index].permohonan == "object") {
          this.beasiswa[this.index].permohonan = Object.values(
            this.beasiswa[this.index].permohonan
          );
        }
        return this.beasiswa[this.index].permohonan.filter(item => {
          return this.searchQuery
            .toLowerCase()
            .split(" ")
            .every(v => item.mahasiswa.nama.toLowerCase().includes(v));
        });
      } else {
        return this.beasiswa.permohonan;
      }
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
    }
  },
  created() {
    this.getHistory();
  },
  methods: {
    getHistory() {
      this.loading = true;
      this.beasiswa = [];
      axios
        .get(`${this.url}/api/permohonan/my-history`, {
          params: {
            key: "berkas"
          }
        })
        .then(response => {
          this.beasiswa = response.data;
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => {
          this.loading = false;
        });
    },
    setBerkas(val) {
      this.btnLoading = true;
      this.loading = true;
      if (val) {
        this.keterangan = null;
      }
      axios
        .put(`${this.url}/api/pemohon/set-berkas`, {
          id: this.selectedPermohonan.id,
          bool: val,
          keterangan: this.keterangan,
          form: this.parsedForm
        })
        .then(response => {
          this.getHistory();
          this.sheetDetail = false;
          this.dialogDelete = false;
          this.snackbar = {
            show: true,
            color: response.data.status ? "blue" : "red",
            message: response.data.status
              ? "Berhasil!"
              : "Opps! terjadi kesalahan"
          };
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => {
          this.btnLoading = false;
          this.loading = false;
          this.keterangan = null;
        });
    }
  },
  data() {
    return {
      searchQuery: "",
      keterangan: null,
      editMode: false,
      loading: false,
      sheetDetail: false,
      dialogDelete: false,
      selectedPermohonan: {},
      selectedMahasiswa: {},
      snackbar: { show: false },
      beasiswa: [],
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

<style>
</style>
