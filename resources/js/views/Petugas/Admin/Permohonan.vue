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
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
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
                <div class="mb-5">
                  <v-chip
                    color="blue"
                    :outlined="filter != 'permohonan'"
                    class="mr-2"
                    @click="filter = 'permohonan'"
                  >
                    Semua ({{undefined !== selectedBeasiswa.permohonan ? selectedBeasiswa.permohonan.length : 0}})
                  </v-chip>
                  <v-chip
                    color="orange"
                    :outlined="filter != 'on_progress'"
                    class="mr-2"
                    @click="filter = 'on_progress'"
                  >
                    Proses ({{undefined !== selectedBeasiswa.on_progress ? selectedBeasiswa.on_progress.length : 0}})
                  </v-chip>
                  <v-chip
                    color="red"
                    :outlined="filter != 'tidak_lulus'"
                    class="mr-2"
                    @click="filter = 'tidak_lulus'"
                  >
                    Tidak Lulus ({{undefined !== selectedBeasiswa.tidak_lulus ? selectedBeasiswa.tidak_lulus.length : 0}})
                  </v-chip>
                  <v-chip
                    color="green"
                    :outlined="filter != 'lulus'"
                    class="mr-2"
                    @click="filter = 'lulus'"
                  >
                    Lulus ({{undefined !== selectedBeasiswa.lulus ? selectedBeasiswa.lulus.length : 0}})
                  </v-chip>
                </div>
                <v-data-table
                  :headers="headers.pemohon"
                  :items="selectedBeasiswa[filter]"
                  :items-per-page="10"
                  @click:row="getUserPermohonan"
                  style="background-color: #2e7d323b"
                  class="elevation-10 mb-10"
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
                <!-- <p>Instansi : {{selectedBeasiswa.instansi}}</p> -->
                <p>Batas pengumpulan berkas : {{parseDate(selectedBeasiswa.awal_berkas)}} - {{parseDate(selectedBeasiswa.akhir_berkas)}}</p>
                <p v-if="selectedBeasiswa.is_interview">Waktu wawancara : {{parseDate(selectedBeasiswa.awal_interview)}} - {{parseDate(selectedBeasiswa.akhir_interview)}}</p>
                <p v-if="selectedBeasiswa.is_survey">Waktu survey : {{parseDate(selectedBeasiswa.awal_survey)}} - {{parseDate(selectedBeasiswa.akhir_survey)}}</p>
                <p>Kuota ({{Object.keys(lulus).length}}/{{selectedBeasiswa.quota}})
                  <v-badge
                    inline
                    content=" Terpenuhi"
                    v-if="Object.keys(lulus).length == selectedBeasiswa.quota"
                  ></v-badge>
                  <v-badge
                    inline
                    color="red"
                    content=" Tdk Terpenuhi"
                    v-if="Object.keys(lulus).length < this.selectedBeasiswa.quota"
                  ></v-badge>
                </p>
                <p class="">{{selectedBeasiswa.deskripsi}}</p>
              </v-col>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
      </v-card>
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
    info(item) {
      this.selectedBeasiswa = item;
      this.dialog = true;
      this.lulus = item.lulus;
      this.selectedBeasiswa.tidak_lulus = [];
      this.selectedBeasiswa.on_progress = [];
      item.permohonan.forEach(x => {
        if (
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
      // axios
      //   .get("/api/pemohon/cek-isHas-admin", {
      //     params: {
      //       nim: item.mhs_id
      //     }
      //   })
      //   .then(response => {
      //     console.log(response.data);
      //   });
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

      console.log(this.permohonans);
    }
  },
  watch: {
    filter: function(val) {
      if (val) {
        this.selectedBeasiswa;
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
      permohonans: null,
      beasiswa: null,
      item: null,
      dialogMHS: false,
      btnLoading: false,
      selectedBeasiswa: "",
      pemohon: [],
      lulus: [],
      dialog: false,
      filter: "permohonan",
      tab: null,
      tabs: ["permohonan", "lulus"],
      headers: {
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
