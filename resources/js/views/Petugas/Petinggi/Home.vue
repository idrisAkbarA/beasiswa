<template>
  <!-- color="transparent" -->
  <v-card
    elevation="20"
    :width="width()"
    class="fill-height mx-auto"
    style="overflow-y: auto"
  >
    <v-card-text>
      <v-subheader>Permohonan Beasiswa</v-subheader>
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
          >Tidak ada peserta wawancara</p>
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
                    v-if="Object.keys(item.selection).length < 1"
                  >
                    <span class="caption text-muted">
                      Belum ada permohonan masuk

                    </span>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.selection).length > 0"
                  >
                    <v-chip
                      class="text-center"
                      small
                      label
                      dark
                      color="green"
                    >
                      {{Object.keys(item.selection).length}} Permohonan masuk

                    </v-chip>
                  </v-col>
                </v-row>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <span class="text-muted">{{item.deskripsi}}</span>
                <v-row>
                  <v-divider></v-divider>
                </v-row>
                <span>
                  Kuota penerima beasiswa : {{Object.keys(item.lulus).length}} / {{item.quota}} Orang
                </span>
                <v-badge
                  inline
                  content=" Terpenuhi"
                  v-if="Object.keys(item.lulus).length == item.quota"
                ></v-badge>
                <div class="col-12">
                  <v-tabs fixed-tabs>
                    <v-tab>permohonan masuk</v-tab>
                    <v-tab>lulus</v-tab>
                    <v-tab-item class="mt-2">
                      <v-data-table
                        dense
                        show-select
                        v-model="selected"
                        :headers="headers.permohonan"
                        :items="Object.values(item.selection)"
                        @click:row="info"
                      >
                        <template v-slot:top>
                          <v-btn
                            :disabled="!selected.length > 0 || item.lulus.length >= item.quota"
                            class="pa-3 my-3 ml-3"
                            color="green"
                            @click="selectedLulus"
                          >Lulus</v-btn>
                        </template>
                      </v-data-table>
                    </v-tab-item>
                    <v-tab-item>
                      <v-data-table
                        dense
                        show-select
                        :headers="headers.permohonan"
                        :items="Object.values(item.lulus)"
                        @click:row="gaLulus"
                      >

                      </v-data-table>
                    </v-tab-item>
                  </v-tabs>
                </div>
                <v-row justify="end">
                  <v-btn
                    :disabled="btnLoading"
                    :dark="!btnLoading"
                    color="#2E7D32"
                    class="mr-3"
                    @click="tutup(item)"
                  >Tutup Penerimaan beasiswa</v-btn>

                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>

          </v-expansion-panels>
        </v-card-text>
      </v-row>
    </v-card-text>
    <!-- Dialog Detail -->
    <div
      class="text-center"
      v-if="dialogDetail"
    >
      <v-dialog
        v-model="dialogDetail"
        width="600"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <i class="mdi mdi-account mr-2"></i> {{selectedPermohonan.mahasiswa.nama}}
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
                  v-for="(field,index) in JSON.parse(selectedPermohonan.form)"
                  :key="index"
                >

                  <v-col style="padding-bottom:0 !important;">
                    <p>{{field.pertanyaan}}</p>
                    <v-container v-if="field.type == 'Checkboxes'">
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

                    </v-container>
                    <v-container v-if="field.type == 'Multiple Upload'">
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

                    </v-container>
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
              </v-tab-item>
              <v-tab-item>
                <v-data-table
                  :headers="headers.mahasiswa"
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
              @click="dialogDetail = false, selectedPermohonan = {}"
              color="white"
              text
            >batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              dark
              @click="lulus()"
            >
              <v-icon>check</v-icon>Lulus
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- Dialog Delete -->
    <div
      class="text-center"
      v-if="dialogDelete"
    >
      <v-dialog
        v-model="dialogDelete"
        width="400"
        overlay-color="#2E7D32"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i> Tutup Penerimaan Beasiswa
          </v-card-title>

          <v-card-text class="white--text text-center mt-2">
            <strong class="d-block">{{this.selectedBeasiswa.nama}}</strong>
            <small>
              Kuota penerima beasiswa : {{Object.keys(this.selectedBeasiswa.lulus).length}} / {{this.selectedBeasiswa.quota}} Orang
              <v-badge
                inline
                content=" Terpenuhi"
                v-if="Object.keys(this.selectedBeasiswa.lulus).length == this.selectedBeasiswa.quota"
              ></v-badge>
              <v-badge
                inline
                color="red"
                content=" Tdk Terpenuhi"
                v-if="Object.keys(this.selectedBeasiswa.lulus).length < this.selectedBeasiswa.quota"
              ></v-badge>
            </small>
          </v-card-text>
          <v-card-text class="white--text text-center pb-0">
            Apakah anda yakin akan menutup beasiswa ini ?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn
              @click="dialogDelete = false"
              color="white"
              text
            >batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              dark
              @click="deleteSelectedBeasiswa()"
            >
              Selesai
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
  },
  methods: {
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "85%";
      } else {
        return "70%";
      }
    },
    ...mapActions(["getBeasiswaWithPermohonan", "selesaiBeasiswa"]),
    gaLulus(item) {
      this.selectedPermohonan = item;
      this.lulus();
    },
    selectedLulus() {
      const permohonan = this.selected;
      let beasiswa = permohonan[0].beasiswa_id;
      beasiswa = this.beasiswa.find(x => x.id == beasiswa);
      if (beasiswa.lulus.length < beasiswa.quota) {
        permohonan.forEach(item => {
          if (beasiswa.lulus.length < beasiswa.quota) {
            this.selectedPermohonan = item;
            this.lulus();
          } else {
            return;
          }
        });
      } else {
        this.snackbar = {
          show: true,
          color: "red",
          message: "Kuota beasiswa sudah penuh!"
        };
      }
      this.selected = {};
    },
    lulus() {
      this.btnLoading = true;
      axios
        .put(`/api/pemohon/set-selection`, {
          bool: this.selectedPermohonan.is_selection_passed == 1 ? 0 : 1,
          id: this.selectedPermohonan.id
        })
        .then(response => {
          if (!response.data.status) {
            this.snackbar = {
              show: true,
              color: "red",
              message: "Kuota beasiswa sudah penuh!"
            };
          } else {
            this.getBeasiswaWithPermohonan();
          }
          this.dialogDetail = false;
          this.selectedPermohonan = {};
        })
        .catch(error => {
          if (error.response.status == 401) {
            this.$router.push({ name: "Login Petugas" });
          }

          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => {
          this.btnLoading = false;
        });
    },
    info(item) {
      this.dialogDetail = true;
      this.selectedPermohonan = item;
    },
    tutup(item) {
      this.dialogDelete = true;
      this.selectedBeasiswa = item;
    },
    deleteSelectedBeasiswa() {
      this.btnLoading = true;
      this.selesaiBeasiswa(this.selectedBeasiswa.id)
        .then(response => {
          this.btnLoading = false;
          this.dialogDelete = false;
          this.selectedBeasiswa = {};
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Beasiswa telah ditutup!"
          };
        })
        .catch(error => {
          console.error(error);
          this.btnLoading = false;
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        });
    },
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
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
  computed: {
    ...mapState(["beasiswa"])
  },
  data() {
    return {
      selected: [],
      selectedBeasiswa: {},
      selectedPermohonan: {},
      selectedMahasiswa: {},
      dialogDelete: false,
      dialogDetail: false,
      btnLoading: false,
      snackbar: {
        show: false
      },
      headers: {
        permohonan: [
          {
            text: "Nama",
            align: "start",
            sortable: false,
            value: "mahasiswa.nama"
          }
        ],
        mahasiswa: [
          {
            text: "Judul",
            value: "judul"
          },
          { text: "Isi", value: "isi" }
        ]
      }
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
