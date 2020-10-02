<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers.beasiswa"
        :items="table == 'selesai' ? beasiswa.selesai : beasiswa.aktif"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>
              Beasiswa {{table == 'selesai' ? 'selesai' : 'dalam proses'}}
            </v-toolbar-title>
            <v-spacer>
            </v-spacer>
            <v-btn @click="table == 'progress' ? table = 'selesai' : table = 'progress'">
              <small>
                Beasiswa {{table == 'progress' ? 'selesai' : 'sedang di proses'}} <v-icon>mdi-chevron-right</v-icon>
              </small>
            </v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            @click="info(item)"
            title="Lihat detail"
          >
            mdi-information-outline
          </v-icon>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>

    <!-- Dialog Delete -->
    <div class="text-center">
      <v-dialog
        v-model="sheet"
        width="800"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <i class="mdi mdi-account-check mr-2"></i> Penerima Beasiswa
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            <v-card-text>
              <h5>Beasiswa : {{selectedBeasiswa.nama}}</h5>
              <p>Kuota ({{Object.keys(pemohon).length}}/{{selectedBeasiswa.quota}})</p>
            </v-card-text>
            <v-tabs
              fixed-tabs
              dark
              v-model="tab"
            >
              <v-tab
                v-for="item in tabs"
                :key="item"
              >
                {{item}}
              </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tab">
              <v-tab-item
                v-for="item in tabs"
                :key="item"
              >
                <v-data-table
                  :headers="headers.pemohon"
                  :items="selectedBeasiswa[item]"
                  :items-per-page="10"
                  style="background-color: #2e7d323b"
                  class="elevation-10 mb-10"
                >
                  <template v-slot:item.actions="{ item }">
                    <v-icon
                      small
                      @click="info(item)"
                      title="Lihat detail"
                    >
                      mdi-information-outline
                    </v-icon>
                  </template>
                  <template v-slot:no-data>
                    no data
                  </template>
                </v-data-table>
              </v-tab-item>
            </v-tabs-items>
          </v-card-text>
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
      this.sheet = true;
      this.pemohon = item.lulus;
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
      table: "selesai",
      btnLoading: false,
      selectedBeasiswa: "",
      pemohon: [],
      sheet: false,
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
          { text: "Instansi", value: "instansi_id" },
          { text: "Actions", value: "actions", sortable: false }
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
