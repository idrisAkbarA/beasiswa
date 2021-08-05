<template>
  <v-sheet
    elevation="10"
    rounded
    style="margin-bottom:100px"
    color="transparent"
  >
    <v-overlay
      :absolute="false"
      :value="isSubmitted"
      color="green"
    >
      <v-card>
        <v-card-title>Laporan anda telah terdaftar</v-card-title>
        <v-card-subtitle>{{overlay.message}}</v-card-subtitle>
        <v-card-text>
          <v-btn
            color="green darken-2"
            @click="isSubmitted = false"
            block
          >edit laporan</v-btn>
        </v-card-text>
      </v-card>
    </v-overlay>
    <v-row
      align="center"
      justify="start"
    >
      <v-col
        cols="12"
        lg="4"
        md="4"
      >
        <v-img
          class="ma-3 item"
          :max-height="300"
          :min-width="200"
          gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
          :src="'https://picsum.photos/200/300?random'"
        ></v-img>
      </v-col>
      <v-col
        :class="windowWidth <= 600 ?' ma-5 pa-5':''"
        v-if="lpj"
      >
        <v-row>
          <h1>{{lpj.nama}}</h1>
        </v-row>
        <v-row>
          <p>{{lpj.deskripsi}}</p>
        </v-row>
        <!-- Persyaratan -->
        <v-row>
          <!-- <table>
            <tr v-if="beasiswaSingle.total_sks">
              <td>
                <span
                  v-if="beasiswaSingle.total_sks"
                >{{"Minimum "+ beasiswaSingle.total_sks+" SKS"}}</span>
              </td>
            </tr>
            <tr v-if="beasiswaSingle.semester">
              <td>
                <span v-if="beasiswaSingle.semester">{{"Semester "+ beasiswaSingle.semester}}</span>
              </td>
            </tr>
            <tr v-if="beasiswaSingle.is_first">
              <td>
                <span v-if="beasiswaSingle.is_first">Belum pernah mengikuti beasiswa</span>
              </td>
            </tr>
            <tr v-if="beasiswaSingle.ukt">
              <td>
                <span v-if="beasiswaSingle.ukt">Golongan UKT {{beasiswaSingle.ukt}} kebawah</span>
              </td>
            </tr>
          </table>-->

          <!-- <table class="mt-2">
            <tr>
              <td>
                <span>
                  Batas upload berkas
                  <span v-if="beasiswaSingle.awal_berkas">
                    <strong>{{" "+parseDate(beasiswaSingle.awal_berkas)}}</strong> sampai
                  </span>
                  <strong>{{" "+parseDate(beasiswaSingle.akhir_berkas)}}</strong>
                </span>
              </td>
            </tr>
            <tr>
              <td>
                <span v-if="beasiswaSingle.is_interview">
                  Waktu wawancara
                  <span v-if="beasiswaSingle.awal_interview">
                    <strong>{{parseDate(beasiswaSingle.awal_interview)}}</strong>sampai
                  </span>
                  <strong>{{parseDate(beasiswaSingle.akhir_interview)}}</strong>
                </span>
              </td>
            </tr>
            <tr>
              <td>
                <span v-if="beasiswaSingle.is_survey">
                  Waktu survey
                  <span v-if="beasiswaSingle.awal_survey">
                    <strong>{{parseDate(beasiswaSingle.awal_survey)}}</strong> sampai
                  </span>
                  <strong>{{parseDate(beasiswaSingle.akhir_survey)}}</strong>
                </span>
              </td>
            </tr>
          </table>-->
          <br />
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-sheet
        width="100%"
        class="pa-5"
      >
        <h3>Form Laporan Pertanggungjawaban</h3>
        <p>
          Mohon isi setiap isian dengan teliti. Setiap perubahan akan langsung disimpan oleh aplikasi.
          <br />Klik tombol
          <strong>DAFTAR</strong> untuk submit berkas LPJ ini. Berkas masih bisa diedit setelah mendaftar, sampai waktu pendaftaran usai.
          <br />Berkas yang tidak lengkap / tidak klik tombol daftar dianggap tidak mendaftar
        </p>
        <v-form
          ref="form"
          v-model="validation"
          lazy-validation
        >
          <v-row
            class="ma-5"
            v-for="(field,index) in fields"
            :key="index"
          >
            <v-col style="padding-bottom:0 !important;">
              <p>
                <span v-if="field.required">*</span>
                {{field.pertanyaan}}
              </p>
              <v-container v-if="field.type == 'Checkboxes'">
                <v-row
                  align="center"
                  v-for="(item,index) in field.checkboxes.items"
                  :key="index"
                >
                  <v-checkbox
                    @change="updateField(field)"
                    v-model="field.value"
                    :value="item.label"
                    color="white"
                    hide-details
                    class="shrink mr-2 mt-0"
                  ></v-checkbox>

                  <span>{{item.label}}</span>
                </v-row>
              </v-container>
              <v-radio-group
                v-if="field.type == 'Pilihan'"
                @change="updateField(field)"
                column
                :mandatory="field.pilihan.required"
                v-model="field.value"
                :rules="isRequired(field.required)"
              >
                <v-radio
                  @change="updateField(field)"
                  v-for="(item,index) in field.pilihan.items"
                  :key="index"
                  :value="item.label"
                  color="white"
                  :label="item.label"
                ></v-radio>
              </v-radio-group>
              <v-container v-if="field.type == 'Multiple Upload'">
                <template v-if="windowWidth>600">
                  <v-row
                    align-self="center"
                    align="center"
                    v-for="(item,i) in field.multiUpload.items"
                    :key="i"
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
                        @change="checkMultipleUpload()"
                      ></v-checkbox>
                    </v-col>
                    <v-col cols="4">
                      <span>{{item.label}}</span>
                    </v-col>
                    <v-col
                      cols="7"
                      v-if="!item.file_name"
                    >
                      <v-text-field
                        color="green"
                        :disabled="!item.isSelected"
                        filled
                        prepend-icon="mdi-attachment"
                        :value="item.value ? item.value.name:null"
                        :label="'Upload '+item.label"
                        @click="$refs[`fileInput_${index}_${i}`][0].$refs.input.click()"
                      ></v-text-field>
                      <!-- v-model="item.value" -->
                      <v-file-input
                        @change="updateField(field)"
                        hide-input
                        :ref="`fileInput_${index}_${i}`"
                        v-model="item.value"
                        class="d-none"
                      ></v-file-input>
                    </v-col>
                    <v-col
                      cols="4"
                      v-if="item.file_name"
                    >
                      <v-text-field
                        color="green"
                        :disabled="!item.isSelected"
                        prepend-icon="mdi-attachment"
                        :value="item.value ? item.value.name:null"
                        :label="'Upload '+item.label"
                        @click="$refs[`fileInput_${index}_${i}`][0].$refs.input.click()"
                      ></v-text-field>
                      <!-- v-model="item.value" -->
                      <v-file-input
                        @change="updateField(field)"
                        hide-input
                        :ref="`fileInput_${index}_${i}`"
                        v-model="item.value"
                        class="d-none"
                      ></v-file-input>
                    </v-col>
                    <v-col
                      cols="3"
                      v-if="item.file_name"
                      class="pl-2"
                    >
                      <v-btn
                        block
                        class="mx-auto"
                        @click="link(item.file_name)"
                        color="grey darken-3"
                      >Lihat File</v-btn>
                    </v-col>
                  </v-row>
                </template>

                <template v-if="windowWidth<=600">
                  <v-row
                    class="mt-6"
                    align-self="center"
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
                        @change="checkMultipleUpload()"
                      ></v-checkbox>
                    </v-col>
                    <!-- <v-col cols="6">
                      <span>{{item.label}}</span>
                    </v-col>-->
                    <v-col
                      cols="11"
                      v-if="!item.file_name"
                    >
                      <!-- <v-file-input
                        @change="updateField(field)"
                        v-model="item.value"
                        :disabled="!item.isSelected"
                        filled
                        :label="'Upload '+item.label"
                      ></v-file-input>-->
                      <v-text-field
                        hide-details="auto"
                        color="green"
                        :disabled="!item.isSelected"
                        filled
                        prepend-icon="mdi-attachment"
                        :value="item.value ? item.value.name:null"
                        :label="'Upload '+item.label"
                        @click="$refs[`fileInput_${index}_${i}`][0].$refs.input.click()"
                      ></v-text-field>
                      <!-- v-model="item.value" -->
                      <v-file-input
                        @change="updateField(field)"
                        hide-input
                        :ref="`fileInput_${index}_${i}`"
                        v-model="item.value"
                        class="d-none"
                      ></v-file-input>
                    </v-col>
                    <v-col
                      cols="6"
                      v-if="item.file_name"
                    >
                      <!-- <v-file-input
                        hide-details="auto"
                        @change="updateField(field)"
                        v-model="item.value"
                        :disabled="!item.isSelected"
                        :label="'Ganti File '+item.label"
                      ></v-file-input>-->
                      <v-text-field
                        hide-details="auto"
                        color="green"
                        :disabled="!item.isSelected"
                        prepend-icon="mdi-attachment"
                        :value="item.value ? item.value.name:null"
                        :label="'Ganti File '+item.label"
                        @click="$refs[`fileInput_${index}_${i}`][0].$refs.input.click()"
                      ></v-text-field>
                      <!-- v-model="item.value" -->
                      <v-file-input
                        @change="updateField(field)"
                        hide-input
                        :ref="`fileInput_${index}_${i}`"
                        v-model="item.value"
                        class="d-none"
                      ></v-file-input>
                    </v-col>
                    <v-col
                      cols="5"
                      v-if="item.file_name"
                      class="pl-2"
                    >
                      <v-btn
                        block
                        class="mx-auto"
                        @click="link(item.file_name)"
                        color="grey darken-3"
                      >Lihat File</v-btn>
                    </v-col>
                  </v-row>
                </template>
                <v-row>
                  <v-alert
                    class="mt-2"
                    outlined
                    width="100%"
                    type="error"
                    v-if="field.error"
                  >Field ini wajib diisi minimal 1</v-alert>
                </v-row>
              </v-container>
              <v-text-field
                prepend-icon="mdi-text-short"
                v-if="field.type == 'Jawaban Pendek'"
                @change="updateField(field)"
                dense
                color="white"
                v-model="field.value"
                placeholder="Jawaban Anda"
                :rules="isRequired(field.required)"
                :required="field.required"
              ></v-text-field>
              <v-text-field
                :rules="isRequired(field.required)"
                prepend-icon="mdi-numeric"
                v-if="field.type == 'Jawaban Angka'"
                @change="updateField(field)"
                dense
                v-model="field.value"
                color="white"
                type="number"
                placeholder="Jawaban Anda"
              ></v-text-field>
              <!-- <v-menu
                v-if="field.type == 'Tanggal'"
                v-model="field.date"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="field.value"
                    label="Tanggal"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    color="white"
                    v-on="on"
                    :rules="isRequired(field.required)"
                    :clearable="!field.required"
                  ></v-text-field>
                </template>
                <v-date-picker
                  @change="updateField(field);menu2 = false"
                  v-model="field.value"
                ></v-date-picker>
              </v-menu> -->

              <v-dialog
                v-if="field.type == 'Tanggal'"
                ref="dialog"
                v-model="modal"
                :return-value.sync="field.value"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="field.value"
                    label="Tanggal"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    :rules="isRequired(field.required)"
                    :clearable="!field.required"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="field.value"
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="modal = false"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog[index-1].save(field.value);updateField(field);"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-dialog>
              <template v-if="field.type == 'Upload File'">
                <v-row
                  align="center"
                  v-if="field.value"
                >
                  <v-col>
                    <v-text-field
                      prepend-icon="mdi-attachment"
                      dense
                      label="Ganti File"
                      @click="$refs[`fileInput_${index}`][0].$refs.input.click()"
                      color="white"
                    ></v-text-field>
                    <!-- :loading="true" -->
                    <v-file-input
                      @change="fileChange($event,index)"
                      hide-input
                      :ref="`fileInput_${index}`"
                      class="d-none"
                    ></v-file-input>
                  </v-col>
                  <v-col>
                    <v-btn
                      block
                      color="grey darken-3"
                      @click="link(field.value)"
                    >lihat file anda</v-btn>
                  </v-col>
                </v-row>
                <v-text-field
                  prepend-icon="mdi-attachment"
                  v-if="!field.value"
                  dense
                  :value="field.value ? field.value.name:null"
                  :rules="isRequired(field.required)"
                  @click="$refs[`fileInput_${index}`][0].$refs.input.click()"
                  color="white"
                  placeholder="Upload File"
                ></v-text-field>
                <!-- :loading="true" -->
                <v-file-input
                  @change="updateField(field)"
                  hide-input
                  :ref="`fileInput_${index}`"
                  v-model="field.value"
                  class="d-none"
                ></v-file-input>
              </template>
              <v-textarea
                :rules="isRequired(field.required)"
                v-model="field.value"
                prepend-icon="mdi-view-headline"
                v-if="field.type == 'Paragraf'"
                @change="updateField(field)"
                color="white"
                rows="1"
                dense
                label="Paragraf"
              ></v-textarea>
            </v-col>
            <v-col cols="12">
              <v-divider></v-divider>
            </v-col>
          </v-row>
        </v-form>
        <v-sheet
          elevation="20"
          :style=" windowWidth<1265?'':'padding-left:256px'"
          color="blue-grey darken-4"
          class="submit-panel"
        >
          <v-container fluid>
            <v-row
              dense
              align="center"
              justify="start"
            >
              <v-col
                cols="12"
                v-if="lpj"
              >
                <v-btn
                  :disabled="isDisabled"
                  :loading="isLoading"
                  @click="checkIsReady"
                  color="#2E7D32"
                >Simpan</v-btn>
              </v-col>
            </v-row>
            <!-- <v-row v-if="!validation">
          <v-col>
            <span>Masih ada field yang belum diisi</span>
          </v-col>
        </v-row> -->
            <v-row dense>
              <v-col
                cols="12"
                v-if="!validation"
              >
                <v-alert
                  text
                  class="mt-2"
                  border="left"
                  dense
                  type="warning"
                >Masih ada field yang belum diisi</v-alert>
              </v-col>
              <v-col>

                <span class="caption">Field dengan tanda * wajib diisi</span>
              </v-col>
            </v-row>
          </v-container>
        </v-sheet>
      </v-sheet>
    </v-row>

    <v-dialog
      v-model="isSure"
      overlay-color="green"
      :width="width()"
    >
      <v-card>
        <v-card-title>Peringatan</v-card-title>
        <v-card-text>
          Seluruh berkas yang dikirim tidak dapat diedit/diperbarui/dihapus kembali, mohon periksa kelengkapan berkas anda.
          <br />
          <br />Apakah anda yakin untuk mendaftar?
        </v-card-text>
        <v-card-actions>
          <v-btn
            @click="save()"
            color="green darken-2"
            :disabled="isLoading"
          >Upload Berkas</v-btn>
          <v-btn
            @click="isSure=false"
            text
          >batal</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      v-model="snackbar"
      :color="msg.color"
    >
      <v-icon>{{msg.icon}}</v-icon>
      {{ msg.text }}
      <template v-slot:action="{ attrs }">
        <v-btn
          color="white"
          text
          v-bind="attrs"
          @click="snackbar = false"
          small
        >tutup</v-btn>
      </template>
    </v-snackbar>
  </v-sheet>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  created() {
    this.daftarLPJ();
  },
  mounted() {
    // console.log("drawer:", this.$el);
  },
  methods: {
    ...mapActions([""]),
    ...mapMutations(["mutateLoading"]),
    daftarLPJ() {
      axios
        .get(`/api/lpj/daftar/${this.$route.params.id}`)
        .then((response) => {
          if (!response.data.status) {
            this.overlay = {
              show: true,
              message: response.data.message,
            };
          }
          this.lpj = response.data.data;
          this.fields = JSON.parse(response.data.data.fields);
          this.isSubmitted =
            response.data.data.is_submitted == 0 ||
            response.data.data.is_submitted == null
              ? false
              : true;
          console.log(this.isSubmitted);
        })
        .catch((error) => console.error(error));
    },
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    fileChange(file, index) {
      this.fields[index].value = file;
      console.log("debug change:", this.fields[index]);
      this.updateFile(this.fields[index]);
      //   this.getUserPermohonan();
      this.daftarLPJ();
    },
    updateField(item) {
      console.log("debug item:", item);
      // if(item.type == "Tanggal"){
      //   console.log("tanggal")
      // }
      if (item.type != "Multiple Upload" && item.type != "Upload File") {
        console.log("huy non upload");
        if (this.isSubmitted) {
          if (item.value == null || item.value.length < 1 || item.value == "") {
            console.log("aku ada isi?", item.value);
            item.value = this.refference[item.index - 1].value;
          } else {
            console.log("real call non file");
            this.updateNonFile();
          }
        } else {
          console.log("real call non file");
          this.updateNonFile();
        }
      } else {
        console.log("im an upload");
        this.updateFile(item);
      }
    },
    updateNonFile() {
      const lpj_id = this.$route.params.id;
      this.mutateLoading(true);
      axios
        .post(`/api/permohonan-lpj`, {
          lpj_id: lpj_id,
          form: this.fields,
        })
        .then((response) => {
          console.log(response.data);
          this.overlay.message =
            "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
          this.snackbar = true;
          this.isSure = false;
          this.mutateLoading(false);
        })
        .catch((error) => {
          console.log(error);
          this.msg.color = "red";
          this.msg.text =
            "Maaf ada kendala ketika ingin menyimpan, coba lagi nanti..";
          this.snackbar = true;
          this.mutateLoading(false);
        });
    },
    async updateFile(item) {
      console.log(item);
      if (item.type == "Multiple Upload") {
        console.log("multi upload yg diupload itu lo", item.multiUpload.items);
        this.mutateLoading(true);
        for (let j = 0; j < item.multiUpload.items.length; j++) {
          const tempMulti = item.multiUpload.items[j];
          if (tempMulti.value && tempMulti.value.length != 0) {
            var data = new FormData();
            data.append("file", tempMulti.value);
            data.append("id", this.$route.params.id);
            await this.upload(data).then((response) => {
              var formTemp = JSON.parse(JSON.stringify(this.fields));
              var index = this.fields.indexOf(item);
              formTemp[index].multiUpload.items[j]["file_name"] =
                response.data.file_name;
              axios
                .post(`/api/permohonan-lpj`, {
                  lpj_id: this.$route.params.id,
                  form: formTemp,
                })
                .then((response) => {
                  console.log(response.data);
                  //   this.getUserPermohonan();
                  this.daftarLPJ();
                  this.overlay.message =
                    "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
                  //   this.loadingBtn = false;
                  // this.isDisabled = true;
                  this.snackbar = true;
                  this.isSure = false;
                })
                .catch((error) => {
                  this.msg.color = "red";
                  this.msg.text =
                    "Maaf ada kendala ketika ingin menyimpan, coba lagi nanti..";
                  this.snackbar = true;
                  console.log(error);
                });
            });
          }
        }
        this.mutateLoading(false);
      } else {
        console.log("upload yg diupload");
        var data = new FormData();
        data.append("file", item.value);
        data.append("id", this.$route.params.id);
        await this.upload(data)
          .then((response) => {
            console.log(response.data);
            var formTemp = JSON.parse(JSON.stringify(this.fields));
            var index = this.fields.indexOf(item);
            console.log(index);
            formTemp[item.index - 1].value = response.data.file_name;
            axios
              .post(`/api/permohonan-lpj`, {
                lpj_id: this.$route.params.id,
                form: formTemp,
              })
              .then((response) => {
                console.log(response.data);
                // this.getUserPermohonan();
                this.daftarLPJ();
                this.overlay.message =
                  "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
                // this.loadingBtn = false;
                // this.isDisabled = true;
                this.snackbar = true;
                this.isSure = false;
              })
              .catch((error) => {
                this.msg.color = "red";
                this.msg.text =
                  "Maaf ada kendala ketika ingin menyimpan, coba lagi nanti..";
                this.snackbar = true;
                console.log(error);
              });
          })
          .catch((error) => {
            if (error.response.status == 401) {
              this.goToLandingPage();
            }
          });
      }
    },
    checkMultipleUpload() {
      this.fields.forEach((element) => {
        if (element.required == false) {
          return; // kalau tidak required, berhenti
        }
        if (element.type == "Multiple Upload") {
          var isFilled = false;
          element.multiUpload.items.every((v) => {
            if (v.isSelected) {
              this.updateNonFile();
              console.log("ada yg di ceklis ni");
              console.log(v.value);
              if (!v.value) {
                isFilled = false;
                console.log("op yg di ceklis ga berisi");
                this.validation = false;
              } else {
                console.log("yg di ceklis berisi");
                isFilled = true;
                this.validation = true;
              }
              return false;
            } else {
              this.updateNonFile();
            }
            return true;
          });
          console.log(isFilled);
          if (!isFilled) {
            element["error"] = true;
            console.log(element);
            this.validation = false;
          } else {
            element["error"] = false;
            console.log(element);
          }
        }
      });
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    width() {
      // console.log(this.windowWidth)
      if (this.windowWidth <= 600) {
        return "70%";
      } else if (this.windowWidth <= 960) {
        return "30%";
      } else {
        return "20%";
      }
    },
    goToLandingPage() {
      this.$router.push({ name: "Landing Page" });
    },
    upload: async (data) => {
      var ini = this;
      return axios({
        method: "post",
        url: "/api/permohonan-lpj/file",
        onUploadProgress: function (progressEvent) {},
        data,
      });
    },
    isRequired(bool) {
      if (bool) {
        return this.rule;
      } else {
        return [];
      }
    },
    async checkIsReady() {
      await this.$refs.form.validate();
      await this.checkMultipleUpload();
      if (this.validation) {
        this.loadingBtn = true;
        axios
          .post(`/api/permohonan-lpj`, {
            lpj_id: this.$route.params.id,
            is_submitted: true,
          })
          .then((response) => {
            console.log(response.data);
            // this.getUserPermohonan();
            this.daftarLPJ();
            this.overlay.message =
              "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
            this.loadingBtn = false;
            // this.isDisabled = true;
            this.snackbar = true;
            this.isSure = false;
          })
          .catch((error) => {
            if (error.response.status == 401) {
              this.$router.push({ name: "Landing Page" });
            }
            this.msg.color = "red";
            this.msg.text =
              "Maaf ada kendala ketika ingin menyimpan, coba lagi nanti..";
            this.snackbar = true;
            console.log(error);
          });
      }
    },
    textChanged(v) {
      if (isSubmitted) {
        if (!v) {
        }
      }
    },
    save() {
      // this.loadingBtn = true;
      // var finalForm = [];
      // this.fields.forEach(element => {
      //   finalForm.push(element);
      // });
    },
  },
  computed: {
    ...mapState(["isLoading"]),
    // fields: {
    //   set: function(v) {
    //     console.log("woi")
    //     this.lpj.fields = JSON.stringify(v)
    //   },
    //   get: function() {
    //     console.log("hey")
    //     if (this.lpj) {
    //       return JSON.parse(this.lpj.fields);
    //     }
    //   }
    // }
  },
  watch: {
    lpj: {
      deep: true,
      handler: function (v) {
        console.log("LPJ:", JSON.parse(v.fields));
      },
    },
    validation(v) {
      console.log(v);
      v ? (this.isDisabled = false) : (this.isDisabled = true);
    },
  },
  data() {
    return {
      fields: {},
      modal: false,
      lpj: null,
      editOverlay: false,
      isSubmitted: false,
      snackbar: false,
      isSure: false,
      validation: true,
      isDisabled: false,
      loadingBtn: false,
      overlay: { show: false },
      refference: {},
      defaultFields: {},
      loading: 0,
      loadText: "",
      rule: [(v) => !!v || "Field ini wajib diisi"],
      msg: {
        color: "green darken-4",
        text: "Perubahan berhasil disimpan!",
        icon: "mdi-content-save",
      },
    };
  },
};
</script>

<style>
.submit-panel {
  position: fixed;
  right: 0;
  left: 0;
  width: 100%;
  bottom: 0;
  z-index: 3;
}
</style>
