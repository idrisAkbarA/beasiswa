<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers.beasiswa"
        :items="beasiswaProgress.selesai"
        :items-per-page="10"
        @click:row="info"
        style="background-color: #2e7d323b; cursor: hand;"
        class="elevation-10 mb-10 row-pointer"
      >
        <template v-slot:item.status="{ item }">
          <v-chip :color="item.status == 'Selesai' ? 'green' : 'orange'">
            {{item.status}}
          </v-chip>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>

    <v-dialog
      v-model="dialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar
          dark
          color="green"
        >
          <v-btn
            icon
            dark
            @click="dialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{selectedBeasiswa.nama}}</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text class="mt-5">
          <v-tabs fixed-tabs>
            <v-tab>Laporan</v-tab>
            <v-tab>Info</v-tab>
            <v-tab-item>
              <v-col cols="12">
                <v-row>
                  <div class="col-lg-6 col-md-12 mb-5">
                    <v-chip
                      color="blue"
                      :outlined="filter != 'permohonan'"
                      class="mr-2 mb-2"
                      @click="filter = 'permohonan'"
                    >
                      Semua ({{undefined !== selectedBeasiswa.permohonan ? selectedBeasiswa.permohonan.length : 0}})
                    </v-chip>
                    <v-chip
                      color="orange"
                      :outlined="filter != 'on_progress'"
                      class="mr-2 mb-2"
                      @click="filter = 'on_progress'"
                    >
                      Proses ({{undefined !== selectedBeasiswa.on_progress ? selectedBeasiswa.on_progress.length : 0}})
                    </v-chip>
                    <v-chip
                      color="red"
                      :outlined="filter != 'tidak_lulus'"
                      class="mr-2 mb-2"
                      @click="filter = 'tidak_lulus'"
                    >
                      Tidak Lulus ({{undefined !== selectedBeasiswa.tidak_lulus ? selectedBeasiswa.tidak_lulus.length : 0}})
                    </v-chip>
                    <v-chip
                      color="green"
                      :outlined="filter != 'lulus'"
                      class="mr-2 mb-2"
                      @click="filter = 'lulus'"
                    >
                      Lulus ({{undefined !== selectedBeasiswa.lulus ? selectedBeasiswa.lulus.length : 0}})
                    </v-chip>
                  </div>
                  <div class="col-lg-6 col-md-12 mb-5">
                    <v-spacer></v-spacer>
                    <v-chip
                      light
                      class="float-right"
                      v-if="selectedBeasiswa.deleted_at && selectedBeasiswa.lulus.length < selectedBeasiswa.quota"
                      @click.stop="drawer = !drawer"
                    >
                      <v-icon class="mr-2">
                        mdi-checkbox-marked-circle-outline
                      </v-icon>
                      Kelulusan
                    </v-chip>
                  </div>
                </v-row>
                <v-card-title>
                  <v-text-field
                    v-model="search.permohonan"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                  ></v-text-field>
                </v-card-title>
                <v-data-table
                  :headers="headers.pemohon"
                  :items="selectedBeasiswa[filter]"
                  :items-per-page="10"
                  :search="search.permohonan"
                  @click:row="getUserPermohonan"
                  style="background-color: #2e7d323b"
                  class="elevation-10 mb-10 row-pointer"
                >
                  <template v-slot:item.verificator="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_berkas_passed != null"
                      :color="item.is_berkas_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${item.is_berkas_passed ? 'mdi-check' : 'mdi-close'} mr-2`"></i>
                      {{item.verificator}}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.interviewer="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_interview_passed != null"
                      :color="item.is_interview_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${item.is_interview_passed ? 'mdi-check' : 'mdi-close'} mr-2`"></i>
                      {{item.interviewer}}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.surveyor="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_survey_passed != null"
                      :color="item.is_survey_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${item.is_survey_passed ? 'mdi-check' : 'mdi-close'} mr-2`"></i>
                      {{item.surveyor}}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.selector="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_selection_passed != null"
                      :color="item.is_selection_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${item.is_selection_passed ? 'mdi-check' : 'mdi-close'} mr-2`"></i>
                      {{item.selector}}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:no-data>
                    no data
                  </template>
                </v-data-table>
              </v-col>
            </v-tab-item>
            <v-tab-item>
              <v-col cols="12">
                <v-data-table
                  :headers="headers.detailBeasiswa"
                  :items="detailBeasiswa"
                  hide-default-header
                  hide-default-footer
                  class="elevation-1"
                >
                </v-data-table>
                <p class="mt-3">{{selectedBeasiswa.deskripsi}}</p>
              </v-col>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
      </v-card>

      <!-- right sheet -->
      <v-navigation-drawer
        v-if="drawer"
        v-model="drawer"
        absolute
        temporary
        right
        height="100%"
        width="50vw"
      >
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title><strong>{{selectedBeasiswa.nama}}</strong></v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <div class="col-12">
          <v-row>
            <v-timeline
              align-top
              dense
            >
              <v-timeline-item
                color="blue"
                small
              >
                <div>
                  <div class="font-weight-normal">
                    <strong>Download file .xlx</strong>
                  </div>
                  <div>
                    <p>
                      Download file template excel
                    </p>
                    <v-btn
                      color="#2E7D32"
                      :disabled="btnLoading"
                      @click="downloadTemplate"
                    ><i class="mdi mdi-download mr-2"></i> Download template excel kosong</v-btn>
                  </div>
                </div>
              </v-timeline-item>
              <v-timeline-item
                color="blue"
                small
              >
                <div>
                  <div class="font-weight-normal">
                    <strong>Tambahkan info mahasiswa dalam template excel.</strong>
                  </div>
                  <div class="pr-5">
                    <p>
                      Kolom yang wajib diisi adalah nim
                    </p>
                    <v-simple-table
                      light
                      dense
                    >
                      <thead>
                        <tr>
                          <th>A</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><strong>NIM</strong></td>
                        </tr>
                        <tr>
                          <td>11750115076</td>
                        </tr>
                      </tbody>
                    </v-simple-table>
                  </div>
                </div>
              </v-timeline-item>
              <v-timeline-item
                color="blue"
                small
              >
                <div>
                  <div class="font-weight-normal mb-3">
                    <strong>Upload file .xlx</strong>
                  </div>
                  <div>
                    <div v-if="file">
                      <v-chip
                        close
                        small
                        @click:close="file = ''"
                        class="my-2"
                      >
                        {{file.name}}
                      </v-chip>
                    </div>
                    <v-btn
                      color="#2E7D32"
                      :disabled="btnLoading"
                      @click="$refs.fileInput.$refs.input.click()"
                    ><i class="mdi mdi-attachment mr-2"></i> Lampirkan file excel</v-btn>
                    <v-file-input
                      hide-input
                      ref="fileInput"
                      v-model="file"
                      class="d-none"
                    ></v-file-input>
                  </div>
                </div>
              </v-timeline-item>
            </v-timeline>
          </v-row>
        </div>
        <template v-slot:append>
          <div class="px-2 py-2">
            <v-btn
              color="#2E7D32"
              class="float-right"
              :loading="btnLoading"
              @click="importPermohonan"
            >
              Save
            </v-btn>
          </div>
        </template>
      </v-navigation-drawer>
    </v-dialog>
    <v-dialog
      scrollable
      width="500"
      overlay-color="green"
      v-model="dialogMHS"
    >
      <v-card>
        <v-card-title>
          Detail Permohonan
        </v-card-title>
        <v-card-text>
          <v-col
            cols="12"
            v-if="permohonans"
          >
            <v-timeline
              dense
              v-if="isShowTimeline(permohonans)"
            >
              <v-slide-x-reverse-transition
                group
                hide-on-leave
              >
                <v-timeline-item
                  v-for="(time,index) in permohonans.timeline"
                  :key="index"
                  small
                  fill-dot
                  :icon="time.is_done? 'mdi-check' :'mdi-calendar-clock'"
                  :color="time.is_done? 'green darken-2' :'grey darken-3'"
                >
                  <v-row>
                    <v-col
                      cols="12"
                      xl="4"
                    >{{
                        time.awal_tgl && time.akhir_tgl?
                        parseDate(time.awal_tgl)  + " - " + parseDate(time.akhir_tgl)
                        : "-"
                        }}</v-col>
                    <v-col
                      cols="12"
                      xl="8"
                    >
                      <v-alert :class="time.is_done? 'green darken-2' :'transparent'">
                        {{time.msg}}
                      </v-alert>
                    </v-col>
                  </v-row>

                </v-timeline-item>
              </v-slide-x-reverse-transition>
            </v-timeline>
          </v-col>
          <v-col
            cols="12"
            v-if="permohonans"
          >
            <span>Keterangan</span>
            <br>
            <p>
              {{permohonans.keterangan}}
            </p>
          </v-col>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- Akhir -->
    <!-- bottom sheet create -->
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleBeasiswa"
    >
      <v-card>
        <v-card-title>
          <span>Tambah Beasiswa Selesai</span>
          <v-spacer></v-spacer>
          <v-btn
            text
            class="mr-2"
            @click="toggleBeasiswa = false"
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="store"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px;">
          <v-row
            dense
            class="ml-1 mr-1"
          >
            <v-col cols="12">
              <v-text-field
                color="#C8E6C9"
                label="Nama Beasiswa"
                v-model="form.nama"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                color="#C8E6C9"
                label="Deskripsi Beasiswa"
                v-model="form.deskripsi"
              ></v-text-field>
            </v-col>
            <v-col cols="10">
              <v-combobox
                color="white"
                label="Instansi"
                :items="instansi"
                item-text="name"
                item-value="id"
                v-model="form.instansi_id"
              >

              </v-combobox>
            </v-col>
            <v-col cols="2">
              <v-text-field
                type="number"
                label="Kuota"
                min="0"
                v-model="form.quota"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
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
    this.getBeasiswaSelesai();
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getBeasiswaSelesai", "getInstansi", "storeBeasiswa"]),
    compareType(a, b) {
      a == b ? true : false;
    },
    getPermohonan(id) {
      axios
        .get(`/api/permohonan/${id}/beasiswa`, {
          params: {
            tahap: "lulus"
          }
        })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.error(error);
        });
    },
    store() {
      let form = _.clone(this.form);
      form.selesai = true;
      if (form.instansi_id && typeof form.instansi_id == "object") {
        form.instansi_id = form.instansi_id.id;
      }
      this.storeBeasiswa(form)
        .then(() => {
          this.toggleBeasiswa = false;
          this.getBeasiswaSelesai();
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        });
    },
    importPermohonan() {
      var formData = new FormData();
      var file = this.file;
      formData.append("file", file);
      this.btnLoading = true;
      axios
        .post("/api/permohonan/import/" + this.selectedBeasiswa.id, formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          if (response.data.status) {
            this.drawer = false;
            this.selectedBeasiswa = response.data.data;
            this.form = {};
            this.file = "";
            this.snackbar = {
              show: true,
              color: "blue",
              message: "Success!"
            };
          } else {
            this.snackbar = {
              show: true,
              color: "red",
              message: response.data.message
            };
          }
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
        });
    },
    info(item) {
      this.selectedBeasiswa = item;
      this.dialog = true;
      this.lulus = item.lulus;
      this.selectedBeasiswa.tidak_lulus = [];
      this.selectedBeasiswa.on_progress = [];
      item.permohonan.forEach(x => {
        if (
          (x.is_selection_passed == null && item.deleted_at != null) ||
          x.is_berkas_passed == 0 ||
          x.is_interview_passed == 0 ||
          x.is_survey_passed == 0 ||
          x.is_selection_passed == 0
        ) {
          this.selectedBeasiswa.tidak_lulus.push(x);
        } else if (x.is_selection_passed == null) {
          this.selectedBeasiswa.on_progress.push(x);
        }
      });
    },
    parseDate: function(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    isShowTimeline(item) {
      if (item.is_selection_passed == 0) {
        return false;
      }
      if (item.is_selection_passed == 1) {
        return true;
      }
      if (item.is_berkas_passed == 0) {
        return false;
      }
      if (item.is_interview_passed == 0) {
        return false;
      }
      if (item.is_survey_passed == 0) {
        return false;
      }
      return true;
    },
    checkStatus(item) {
      if (item.is_selection_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_selection_passed == 1) {
        return "Selamat permohonan anda lulus.";
      }
      if (item.is_berkas_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_interview_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_survey_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_survey_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }

      if (item.is_survey_passed == 1) {
        return "Menunggu seleksi pimpinan.";
      }
      if (item.is_interview_passed == 1) {
        return "Team surveyor segera melakukan survey ke lokasi anda, harap menunggu.";
      }
      if (item.is_berkas_passed == 1) {
        return "Segera lakukan wawancara pada tanggal yang ditentukan.";
      }
      return "Permohonan anda sedang di proses";
    },
    checkColor(item) {
      if (item.is_berkas_passed == 0) {
        return "ditolak";
      }
      if (item.is_interview_passed == 0) {
        return "ditolak";
      }
      if (item.is_survey_passed == 0) {
        return "ditolak";
      }
      if (item.is_survey_passed == 0) {
        return "ditolak";
      }
      if (item.is_selection_passed == 0) {
        return "ditolak";
      }
      if (item.is_selection_passed == 1) {
        return "diterima";
      }
      return "#2e7d323b";
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    getUserPermohonan(item) {
      console.log(item);
      this.dialogMHS = true;
      this.permohonans = item;
      this.permohonans["beasiswa"] = this.selectedBeasiswa;
      this.addTimeline();
    },
    addTimeline() {
      console.log(this.permohonans);
      var timeline = [];

      timeline.push({
        kegiatan: "Pengisian Berkas",
        awal_tgl: this.permohonans.beasiswa.awal_berkas,
        akhir_tgl: this.permohonans.beasiswa.akhir_berkas,
        msg: "Pengisian berkas",
        is_done: true
      });
      if (this.permohonans.beasiswa.is_interview == 1) {
        var is_done = false;
        if (this.permohonans.is_interview_passed == 1) {
          is_done = true;
        }
        timeline.push({
          kegiatan: "Wawancara",
          awal_tgl: this.permohonans.beasiswa.awal_interview,
          akhir_tgl: this.permohonans.beasiswa.akhir_interview,
          is_done,
          msg: `Lakukan wawancara pada tanggal
            ${
              this.permohonans.beasiswa.awal_interview
                ? this.parseDate(this.permohonans.beasiswa.awal_interview) +
                  " sampai "
                : ""
            }  ${this.parseDate(this.permohonans.beasiswa.akhir_interview)}`
        });
      }
      if (this.permohonans.beasiswa.is_survey == 1) {
        var is_done = false;
        if (this.permohonans.is_survey_passed == 1) {
          is_done = true;
        }
        timeline.push({
          kegiatan: "Wawancara",
          awal_tgl: this.permohonans.beasiswa.awal_survey,
          akhir_tgl: this.permohonans.beasiswa.akhir_survey,
          is_done,
          msg: `Team survey akan melakukan survey pada
            ${
              this.permohonans.beasiswa.awal_survey
                ? this.parseDate(this.permohonans.beasiswa.awal_survey) +
                  " sampai "
                : ""
            }  ${this.parseDate(
            this.permohonans.beasiswa.akhir_survey
          )}, harap menunggu.`
        });
      }
      var is_done = false;
      if (this.permohonans.is_selection_passed) {
        is_done = true;
      }
      timeline.push({
        kegiatan: "Seleksi pimpinan",
        awal_tgl: null,
        akhir_tgl: null,
        is_done,
        msg: "Harap menunggu hasil seleksi pimpinan."
      });
      this.permohonans["timeline"] = timeline;
      timeline = [];
    },
    cekTahap(beasiswa, tahap) {
      if (tahap == "berkas" && beasiswa.awal_berkas) {
        return `${this.parseDate(beasiswa.awal_berkas)} - ${this.parseDate(
          beasiswa.akhir_berkas
        )}`;
      } else if (beasiswa[`is_${tahap}`]) {
        return `${this.parseDate(beasiswa[`awal_${tahap}`])} - ${this.parseDate(
          beasiswa[`akhir_${tahap}`]
        )}`;
      } else {
        return "-";
      }
    },
    downloadTemplate() {
      const link = "/template/template_kelulusan.xlsx";
      window.open(link, "_blank");
    }
  },
  watch: {
    filter: function(val) {
      if (val) {
        this.selectedBeasiswa;
      }
    },
    selectedBeasiswa: function(val) {
      if (val) {
        console.log("asds");
        this.getPermohonan(val.id);
        this.detailBeasiswa = [
          { judul: "Nama", isi: val.nama },
          { judul: "Instansi", isi: val.instansi.name ?? "-" },
          { judul: "Tahap Berkas", isi: this.cekTahap(val, "berkas") },
          { judul: "Tahap Interview", isi: this.cekTahap(val, "interview") },
          { judul: "Tahap Survey", isi: this.cekTahap(val, "survey") },
          { judul: "Kuota", isi: `${val.lulus.length}/${val.quota}` },
          { judul: "Status", isi: val.status }
        ];
      }
    }
  },
  computed: {
    ...mapState([
      "beasiswaProgress",
      "isOpenBeasiswa",
      "instansi",
      "isTableLoading",
      "isLoading"
    ]),
    toggleBeasiswa: {
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
      drawer: false,
      permohonans: null,
      beasiswa: null,
      item: null,
      dialogMHS: false,
      btnLoading: false,
      selectedBeasiswa: "",
      file: "",
      pemohon: [],
      lulus: [],
      form: {},
      dialog: false,
      filter: "permohonan",
      tab: null,
      tabs: ["permohonan", "lulus"],
      detailBeasiswa: {},
      snackbar: { show: false },
      search: {
        beasiswa: "",
        permohonan: ""
      },
      headers: {
        detailBeasiswa: [
          { text: "Judul", value: "judul" },
          { text: "Isi", value: "isi" }
        ],
        beasiswa: [
          {
            text: "Beasiswa",
            align: "start",
            sortable: false,
            value: "nama"
          },
          { text: "Instansi", value: "instansi.name" },
          { text: "Status", value: "status" }
        ],
        pemohon: [
          {
            text: "Nama",
            align: "start",
            sortable: false,
            value: "mahasiswa.nama"
          },
          { text: "NIM", value: "mhs_id" },
          { text: "Berkas", value: "verificator" },
          { text: "Interview", value: "interviewer" },
          { text: "Survey", value: "surveyor" },
          { text: "Lulus", value: "selector" }
        ]
      }
    };
  }
};
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
.fab {
  color: #2e7d323b;
  position: fixed;
  bottom: 20px;
  right: 20px;
}
.floating {
  width: 100%;
  position: fixed;
  bottom: 20px;
  z-index: 10;
}
</style>
