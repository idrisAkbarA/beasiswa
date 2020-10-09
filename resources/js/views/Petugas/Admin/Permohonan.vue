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
        <v-toolbar dark>
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
      this.selectedBeasiswa.tidak_lulus = item.permohonan.filter(x => {
        return x.is_selection_passed != 1;
      });
    },
    parseDate: function(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
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
