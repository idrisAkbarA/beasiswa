<template>
  <v-container>
    <v-card
      tile
      color="transparent"
      flat
    >
      <v-card-title>
        <span class="ml-3"> Laporan </span>
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
                  v-model="lpjSelected"
                  :rules="rule"
                  auto-select-first
                  @change="getFieldList"
                  filled
                  color="white"
                  label="LPJ"
                  :loading="lpjLoading"
                  :disabled="lpjLoading"
                  :items="lpjItem"
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
              <!-- <v-col>
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
              </v-col> -->
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
            <v-expansion-panels
              class="mb-7"
              :disabled="isSpesificBeasiswaSelected"
            >
              <v-expansion-panel>
                <!-- <v-progress-linear
                  color="green"
                  indeterminate
                ></v-progress-linear> -->
                <v-expansion-panel-header>
                  Set kolom pertanyaan
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-container
                    class="px-0"
                    fluid
                  >
                    <v-checkbox
                      hide-details
                      color="green"
                      v-model="selectUnSelectAllField"
                    >
                      <template v-slot:label>
                        <div class="mt-3">Pilih Semua</div>
                      </template>
                    </v-checkbox>
                    <v-checkbox
                      v-model="item.value"
                      hide-details
                      color="green"
                      v-for="(item, index) in fieldList"
                      :key="index"
                    >
                      <template v-slot:label>
                        <div class="mt-3">
                          {{ item.pertanyaan }}
                        </div>
                      </template>
                    </v-checkbox>
                  </v-container>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-row>
          <v-row no-gutters>
            <v-btn
              :disabled="isDownloadDisabled"
              color="green darken-2"
              @click="getLaporan(false)"
            >lihat laporan</v-btn>
            <v-btn
              :disabled="isDownloadDisabled"
              class="ml-1"
              :loading="downloadLoading"
              @click="getLaporan(true)"
            >
              <v-icon left> mdi-file-download </v-icon>
              Download laporan
            </v-btn>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
    <v-card class="ml-7 mr-7">
      <v-data-table
        :headers="headers"
        :items="reportLPJ"
        :items-per-page="5"
        :loading="isTableLoading"
        loading-text="Memuat... Mohon tunggu"
        :search="search"
      >
        <template v-slot:top>
          <v-text-field
            v-model="search"
            label="Pencarian"
            color="green"
            class="mx-4"
          ></v-text-field>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
const FileDownload = require("js-file-download");
var FileSaver = require("file-saver");
import { mapActions, mapState } from "vuex";
import Axios from "axios";
export default {
  watch: {
    fieldList: {
      deep: true,
      handler: function (v) {
        v.forEach((element) => {
          if (!element.value) {
            this.selectAll.value = false;
          }
        });
      },
    },
    validation(v) {
      v ? (this.isDownloadDisabled = false) : (this.isDownloadDisabled = true);
    },
  },
  computed: {
    ...mapState(["reportLPJ", "fakultas", "isTableLoading", "lpj"]),
    selectUnSelectAllField: {
      // Property description:
      // set value of it's own v-model and select/unselect all corresponding fields
      set: function (v) {
        this.selectAll.value = v;
        // v ? this.selectAll.label = "Uncheck semua pilihan" : this.selectAll.label = "Pilih semua" ;
        this.fieldList.forEach((element) => {
          v ? (element.value = true) : (element.value = false);
        });
      },
      get: function () {
        return this.selectAll.value;
      },
    },
    isSpesificBeasiswaSelected: {
      // Property description:
      // set expansion panel of pertanyaan list to disable according to beasiswa is selected or not
      get: function () {
        if (this.lpjSelected == "all") {
          return true;
        }
        if (this.lpjSelected == null) {
          return true;
        }
        return false;
      },
    },
    headers: {
      get: function () {
        if (this.reportLPJ.length > 0) {
          var final = [];
          var keys = Object.keys(this.reportLPJ[0]);
          keys.forEach((element) => {
            final.push({
              text: element,
              value: element,
              align: "start",
              sortable: true,
            });
          });
          return final;
        } else {
          return [{ text: "Beasiswa" }, { text: "Nama" }, { text: "NIM" }];
        }
      },
    },
    fakItem: {
      get: function () {
        if (this.fakultas.length > 0) {
          this.fakLoading = false;
          var final = [];
          final.push({ id: "all", nama: "Semua" });
          this.fakultas.forEach((element) => {
            final.push({ id: element.id, nama: element.nama });
          });
          return final;
        } else {
          this.getFakultas();
          this.fakLoading = true;
          return [{ id: "", nama: "" }];
        }
      },
    },

    lpjItem: {
      get: function () {
        if (this.lpj.length > 0) {
          this.lpjLoading = false;
          var final = [];
          final.push({ id: "all", nama: "Semua" });
          this.lpj.forEach((element) => {
            final.push({ id: element.id, nama: element.nama });
          });
          return final;
        } else {
          this.getLPJ();
          this.lpjLoading = true;
          return [{ id: "", nama: "" }];
        }
      },
    },
  },
  methods: {
    ...mapActions(["getReportLPJ", "getFakultas", "getLPJ"]),
    getFieldList(item) {
      // Method Descriptiption:
      // list all pertanyaan of the selected beasiswa
      console.log("item", item);

      if (item != "all") {
        // check if id beasiswa selected
        var lpjDetail = {};
        this.lpj.forEach((element) => {
          // loop beasiswa to get the desired beasiswa
          if (element.id == item) lpjDetail = element;
        });

        this.fieldList = JSON.parse(lpjDetail.fields); // set the list
      }
    },
    async getLaporan(isDownload) {
      await this.$refs.form.validate();
      if (this.validation) {
        var fieldList = [];
        this.fieldList.forEach((element) => {
          if (element.value) {
            fieldList.push(element.pertanyaan);
          }
        });
        this.params = {
          lpj: this.lpjSelected,
          fakultas: this.fakultasSelected,
          // tahap: this.tahapSelected,
          status: this.statusSelected,
          field_list: fieldList,
        };
        !isDownload
          ? this.getReportLPJ(this.params)
          : this.getReportDownload(this.params);
      }
    },
    link(url) {
      var a = url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    getReportDownload(data) {
      this.downloadLoading = true;
      Axios.get("/api/lpj/download-report", {
        params: data,
        responseType: "blob",
      }).then((response) => {
        FileDownload(response.data, "LPJ.xlsx");
        this.downloadLoading = false;
      });
    },
  },
  data() {
    return {
      search: null,
      selectAll: {
        label: "Pilih semua",
        value: false,
      },
      fieldList: [],
      rule: [(v) => !!v || "Field ini wajib diisi"],
      isDownloadDisabled: false,
      validation: false,
      fakLoading: false,
      lpjLoading: false,
      tableLoading: false,
      downloadLoading: false,
      status: [
        { value: "All", text: "Semua" },
        { value: "Menunggu Validasi", text: "Menunggu Validasi" },
        { value: "Lulus", text: "Lulus" },
        { value: "Gagal", text: "Gagal" },
        { value: "Tidak Lengkap", text: "Tidak Lengkap" },
        { value: "Belum Mengisi", text: "Belum Mengisi" },
      ],
      // tahap: [
      //   { value: "berkas", text: "Berkas" },
      //   { value: "wawancara", text: "Wawancara" },
      //   { value: "survey", text: "Survey" },
      //   { value: "seleksi", text: "Seleksi" },
      // ],
      lpjSelected: null,
      fakultasSelected: null,
      // tahapSelected: null,
      statusSelected: null,
      params: {},
    };
  },
};
</script>

<style>
</style>
