<template>
  <v-container>
    <v-card
      class="pb-9"
      color="#E8F5E9"
      light
      tile
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
                  @change="getFieldList"
                  filled
                  color="green"
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
                  v-model="akunSelected"
                  :rules="rule"
                  filled
                  class="ml-1"
                  color="green"
                  :items="akun"
                  item-value="id"
                  item-text="nama"
                  label="Akun"
                ></v-select>
              </v-col>
              <v-col>
                <v-select
                  v-model="tahapSelected"
                  :rules="rule"
                  filled
                  class="ml-1"
                  color="green"
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
                  color="green"
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
              <v-expansion-panel
              class="outlined"
              >
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
                        <div class="mt-3">
                          Pilih Semua
                        </div>
                      </template>
                    </v-checkbox>
                    <v-checkbox
                      v-model="item.value"
                      hide-details
                      color="green"
                      v-for="(item,index) in fieldList"
                      :key="index"
                    >
                      <template v-slot:label>
                        <div class="mt-3">
                          {{item.pertanyaan}}
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
              class="white--text"
            >lihat laporan</v-btn>
            <v-btn
            
            color="green darken-2"
              :disabled="isDownloadDisabled"
              class="ml-1 white--text"
              :loading="downloadLoading"
              @click="getLaporan(true)"
            >
              <v-icon left>
                mdi-file-download
              </v-icon>
              Download laporan
            </v-btn>
          </v-row>
          <v-row>

          </v-row>
        </v-container>
      </v-card-text>
      <v-card class="ml-7 mr-7 ">
        <v-data-table
        
          :headers="headers"
          :items="report"
          :items-per-page="15"
          :loading="isTableLoading"
          loading-text="Memuat... Mohon tunggu"
        ></v-data-table>
      </v-card>
    </v-card>

  </v-container>
</template>

<script>
const FileDownload = require("js-file-download");
var FileSaver = require("file-saver");
import { mapActions, mapState } from "vuex";
import Axios from "axios";
export default {
  created(){
    console.log(this.auth)
  },
  watch: {
    fieldList: {
      deep: true,
      handler: function(v) {
        v.forEach(element => {
          if (!element.value) {
            this.selectAll.value = false;
          }
        });
      }
    },
    validation(v) {
      v ? (this.isDownloadDisabled = false) : (this.isDownloadDisabled = true);
    }
  },
  computed: {
    ...mapState(["auth","report", "url", "fakultas", "isTableLoading", "beasiswa"]),
    fakultasSelected: {
      get:function(){return this.auth.fakultas.id}},
    akun:{
      get: function(){
        return [
          {
            nama:"Fakultas",
            id: "fakultas"
          },
          { nama: this.auth.nama + " (Saya)",
            id: this.auth.id
          }
        ]
      }
    },
    selectUnSelectAllField: {
      // Property description:
      // set value of it's own v-model and select/unselect all corresponding fields
      set: function(v) {
        this.selectAll.value = v;
        // v ? this.selectAll.label = "Uncheck semua pilihan" : this.selectAll.label = "Pilih semua" ;
        this.fieldList.forEach(element => {
          v ? (element.value = true) : (element.value = false);
        });
      },
      get: function() {
        return this.selectAll.value;
      }
    },
    isSpesificBeasiswaSelected: {
      // Property description:
      // set expansion panel of pertanyaan list to disable according to beasiswa is selected or not
      get: function() {
        if (this.beasiswaSelected == "all") {
          return true;
        }
        if (this.beasiswaSelected == null) {
          return true;
        }
        return false;
      }
    },
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
          // console.log(this.fakultas);
          final.push({ id: "all", nama: "Semua" });
          this.fakultas.forEach(element => {
            final.push({ id: element.id, nama: element.nama });
          });
          // console.log(final);
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
          // console.log(this.beasiswa);
          final.push({ id: "all", nama: "Semua" });
          this.beasiswa.forEach(element => {
            final.push({ id: element.id, nama: element.nama });
          });
          // console.log(final);
          return final;
        } else {
          // console.log("hey");
          this.getBeasiswa();
          this.beaLoading = true;
          return [{ id: "", nama: "" }];
        }
      }
    }
  },
  methods: {
    ...mapActions(["getReport", "getFakultas", "getBeasiswa"]),
    getFieldList(item) {
      // Method Descriptiption:
      // list all pertanyaan of the selected beasiswa

      if (item != "all") {
        // check if id beasiswa selected
        var beasiswaDetail = {};
        this.beasiswa.forEach(element => {
          // loop beasiswa to get the desired beasiswa
          if (element.id == item) beasiswaDetail = element;
        });

        this.fieldList = JSON.parse(beasiswaDetail.fields); // set the list
        console.log(this.fieldList);
      }
    },
    async getLaporan(isDownload) {
      await this.$refs.form.validate();
      if (this.validation) {
        var fieldList = [];
        this.fieldList.forEach(element => {
          if (element.value) {
            fieldList.push(element.pertanyaan);
          }
        });
        this.params = {
          beasiswa: this.beasiswaSelected,
          fakultas: this.fakultasSelected,
          tahap: this.tahapSelected,
          status: this.statusSelected,
          field_list: fieldList,
          isVerificator: true
        };
        if(this.akunSelected != "fakultas"){
          this.params['akun'] = this.akunSelected;
        }
        !isDownload
          ? this.getReport(this.params)
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
      Axios.get("/api/beasiswa/download-report", {
        params: data,
        responseType: "blob"
      }).then(response => {
        FileDownload(response.data, "Beasiswa.xlsx");
        this.downloadLoading = false;
      });
    }
  },
  data() {
    return {
      selectAll: {
        label: "Pilih semua",
        value: false
      },
      fieldList: [],
      rule: [v => !!v || "Field ini wajib diisi"],
      isDownloadDisabled: false,
      validation: false,
      fakLoading: false,
      beaLoading: false,
      tableLoading: false,
      downloadLoading: false,
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
      tahapSelected: null,
      akunSelected: null,
      statusSelected: null,
      params: {}
    };
  }
};
</script>

<style>
</style>