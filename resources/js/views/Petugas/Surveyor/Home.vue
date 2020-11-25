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
          >Tidak ada peserta survey</p>
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
                    v-if="Object.keys(item.survey).length < 1"
                  >
                    <span class="caption text-muted">
                      Belum ada permohonan masuk

                    </span>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.survey).length > 0"
                  >
                    <v-chip
                      class="text-center"
                      small
                      label
                      dark
                      color="green"
                    >
                      {{Object.keys(item.survey).length}} Permohonan masuk

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
                  <v-subheader>Permohonan Masuk ({{!searchQuery ? Object.keys(item.survey).length : resultQuery.length}})</v-subheader>
                  <v-list-item-group
                    class="bg-white"
                    color="primary"
                  >
                    <template v-for="(permohonan, index) in !searchQuery ? item.survey : resultQuery">
                      <v-list-item
                        :key="permohonan.nama"
                        @click="sheetDetail = true, selectedPermohonan = permohonan; setSurveyList(permohonan)"
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
                        v-if="index < item.survey.length - 1"
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
              <v-row v-if="selectedSurveyList">
                <v-card
                  class="mt-4 mb-4"
                  width="100%"
                  color="green darken-4"
                >
                  <v-card-title>List Survey</v-card-title>
                  <v-card-subtitle>Harap lakukan survey terhadap poin-poin berikut:</v-card-subtitle>
                  <v-card-text>
                    <li
                      v-for="(list,i) in selectedSurveyList"
                      :key="i"
                    >{{list.pertanyaan}}</li>
                  </v-card-text>
                </v-card>
                <v-subheader>Berkas Mahasiswa</v-subheader>
              </v-row>
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
            light
            color="grey"
            @click="dialogDelete = { show : true, value : false}"
          >
            <v-icon>close</v-icon> Tidak Lulus
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2E7D32"
            dark
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
            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i> Kelulusan Survey
          </v-card-title>

          <v-card-text class="white--text text-center mt-2 pb-0">
            <strong class="d-block">{{this.selectedPermohonan.mahasiswa.nama}}</strong> akan dinyatakan <strong>{{dialogDelete.value ? 'Lulus' : 'Tidak Lulus'}}</strong> survey ?
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
              color="#2E7D32"
              dark
              @click="setSurvey(dialogDelete.value)"
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
  },
  methods: {
    setSurveyList(permohonan) {
      console.log(permohonan);
      this.beasiswa.every(beasiswa => {
        if (beasiswa.id == permohonan.beasiswa_id) {
          var temp = JSON.parse(beasiswa.fields_survey);
          temp.length > 0 ? (this.selectedSurveyList = temp) : null;
          // console.log("pertanyaan: " ,this.selectedInterviewList);
          return false;
        }
        return true;
      });
    },
    ...mapActions(["getBeasiswaWithPermohonan"]),
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    setSurvey(bool) {
      this.btnLoading = true;
      axios
        .put(`/api/pemohon/set-survey`, {
          id: this.selectedPermohonan.id,
          bool: bool
        })
        .then(response => {
          this.getBeasiswaWithPermohonan();
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
    ...mapState(["beasiswa"]),
    resultQuery() {
      if (this.searchQuery) {
        if (typeof this.beasiswa[this.index].survey == "object") {
          this.beasiswa[this.index].survey = Object.values(
            this.beasiswa[this.index].survey
          );
        }
        return this.beasiswa[this.index].survey.filter(item => {
          return this.searchQuery
            .toLowerCase()
            .split(" ")
            .every(v => item.mahasiswa.nama.toLowerCase().includes(v));
        });
      } else {
        return this.beasiswa.survey;
      }
    }
  },
  data() {
    return {
      index: 0,
      searchQuery: "",
      selectedSurveyList: null,
      selectedPermohonan: {},
      selectedMahasiswa: {},
      snackbar: { show: false },
      dialogDelete: { show: false },
      btnLoading: false,
      sheetDetail: false,
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

