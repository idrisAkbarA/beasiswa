<template>
  <v-container>
    <v-card
      tile
      color="transparent"
      flat
    >
      <v-card-title>
        <span class="ml-3">

          Laporan
        </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row no-gutters>
            <span class="caption mb-5">
              Filter Laporan | Isi field untuk melihat laporan
            </span>
          </v-row>
          <v-form
            ref="form"
            v-model="validation"
            lazy-validation
          >
            <v-row no-gutters>

              <v-col>
                <v-autocomplete
                  v-model="beasiswaSelected"
                  :rules="rule"
                  auto-select-first
                  filled
                  color="white"
                  label="Beasiswa"
                  :loading="beaLoading"
                  :disabled="beaLoading"
                  :items="beaItem"
                  item-value="id"
                  item-text="nama"
                ></v-autocomplete>
              </v-col>
              <v-col>
                <v-select
                  v-model="fakultasSelected"
                  :rules="rule"
                  :loading="fakLoading"
                  :disabled="fakLoading"
                  filled
                  class="ml-1"
                  color="white"
                  :items="fakItem"
                  item-value="id"
                  item-text="nama"
                  label="Fakultas"
                ></v-select>
              </v-col>
              <v-col>
                <v-select
                  v-model="tahapSelected"
                  :rules="rule"
                  filled
                  class="ml-1"
                  color="white"
                  :items="tahap"
                  item-text="text"
                  item-value="value"
                  label="Tahap"
                ></v-select>
              </v-col>
              <v-col>
                <v-select
                  v-model="statusSelected"
                  :rules="rule"
                  filled
                  class="ml-1"
                  color="white"
                  :items="status"
                  item-text="text"
                  item-value="value"
                  label="Status"
                ></v-select>
              </v-col>
            </v-row>
          </v-form>
          <v-row no-gutters>
            <v-btn
              :disabled="isDownloadDisabled"
              color="green darken-2"
              @click="getLaporan(false)"
            >lihat laporan</v-btn>
            <v-btn
              :disabled="isDownloadDisabled"
              class="ml-1"
              @click="getLaporan(true)"
            >
              <v-icon left>
                mdi-file-download
              </v-icon>
              Download laporan
            </v-btn>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
    <v-card class="ml-7 mr-7">
      <v-data-table
        :headers="headers"
        :items="report"
        hide-default-footer
        :loading="isTableLoading"
        loading-text="Memuat... Mohon tunggu"
      ></v-data-table>
    </v-card>
  </v-container>
</template>

<script>
// const FileDownload = require("js-file-download");
import { mapActions, mapState } from "vuex";
import Axios from "axios";
export default {
  watch: {
    validation(v) {
      v ? (this.isDownloadDisabled = false) : (this.isDownloadDisabled = true);
    }
  },
  computed: {
    ...mapState(["report", "url", "fakultas", "isTableLoading", "beasiswa"]),
    headers: {
      get: function() {
        if (this.report.length > 0) {
          var final = [];
          var keys = Object.keys(this.report[0]);
          keys.forEach(element => {
            final.push({
              text: element,
              value: element,
              align: "start",
              sortable: true
            });
          });
          return final;
        } else {
          return [{ text: "Beasiswa" }, { text: "Nama" }, { text: "NIM" }];
        }
      }
    },
    fakItem: {
      get: function() {
        if (this.fakultas.length > 0) {
          this.fakLoading = false;
          var final = [];
          console.log(this.fakultas);
          final.push({ id: "all", nama: "Semua" });
          this.fakultas.forEach(element => {
            final.push({ id: element.id, nama: element.nama });
          });
          console.log(final);
          return final;
        } else {
          console.log("hey");
          this.getFakultas();
          this.fakLoading = true;
          return [{ id: "", nama: "" }];
        }
      }
    },

    beaItem: {
      get: function() {
        if (this.beasiswa.length > 0) {
          this.beaLoading = false;
          var final = [];
          console.log(this.beasiswa);
          final.push({ id: "all", nama: "Semua" });
          this.beasiswa.forEach(element => {
            final.push({ id: element.id, nama: element.nama });
          });
          console.log(final);
          return final;
        } else {
          console.log("hey");
          this.getBeasiswa();
          this.beaLoading = true;
          return [{ id: "", nama: "" }];
        }
      }
    }
  },
  methods: {
    ...mapActions(["getReport", "getFakultas", "getBeasiswa"]),
    async getLaporan(isDownload) {
      await this.$refs.form.validate();
      if (this.validation) {
        this.params = {
          beasiswa: this.beasiswaSelected,
          fakultas: this.fakultasSelected,
          tahap: this.tahapSelected,
          status: this.statusSelected
        };
        console.log(this.params);
        !isDownload
          ? this.getReport(this.params)
          : this.link(`/api/beasiswa/download-report?beasiswa=${this.beasiswaSelected}
                                                    &fakultas=${this.fakultasSelected}
                                                    &tahap=${this.tahapSelected}
                                                    &status=${this.statusSelected}
          `);
        // !isDownload
        //   ? this.getReport(this.params)
        //   : axios
        //       .get("/api/beasiswa/download-report", { params: this.params })
        //       .then(response => {
        //         FileDownload(response.data, "report.xlsx");
        //       });
      }
    },
    link(url) {
      var a =  url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    }
  },
  data() {
    return {
      rule: [v => !!v || "Field ini wajib diisi"],
      isDownloadDisabled: false,
      validation: false,
      fakLoading: false,
      beaLoading: false,
      tableLoading: false,
      status: [
        { value: "all", text: "Semua" },
        { value: "lulus", text: "Lulus" },
        { value: "gagal", text: "Gagal" },
        { value: "seleksi", text: "Dalam Tahap Seleksi" }
      ],
      tahap: [
        { value: "berkas", text: "Berkas" },
        { value: "wawancara", text: "Wawancara" },
        { value: "survey", text: "Survey" },
        { value: "seleksi", text: "Seleksi" }
      ],
      beasiswaSelected: null,
      fakultasSelected: null,
      tahapSelected: null,
      statusSelected: null,
      params: {}
    };
  }
};
</script>

<style>
</style>