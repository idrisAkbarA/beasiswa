<template>
  <v-sheet
    style="margin-bottom:100px"
    color="transparent"
  >
    <v-overlay
      :absolute="false"
      :value="overlay.show"
      color="green"
    >
      <v-card>
        <v-card-text>
          <h5
            v-if="typeof overlay.message != 'string'"
            class="mb-5"
          >Mohon maaf, anda tidak dapat mendaftar pada beasiswa ini</h5>
          <ul v-if="typeof overlay.message != 'string'">
            <li
              v-for="(val, item) in overlay.message"
              :key="item"
            >
              <span>
                {{item}} <i :class="[val ? 'mdi mdi-check' : 'mdi mdi-close-circle']+' ml-2'"></i>
              </span>
            </li>
          </ul>
          <p v-else>
            {{overlay.message}}
          </p>
        </v-card-text>
      </v-card>
    </v-overlay>
    <v-overlay
      :absolute="false"
      :value="editOverlay"
      color="green"
    >
      <v-card>
        <v-card-title>Permohonan anda telah terdaftar!</v-card-title>
        <v-card-subtitle>
          Permohonan anda telah terdaftar, mohon tunggu tahap selanjutnya
        </v-card-subtitle>
        <v-card-text>
          <v-btn
            color="green darken-2"
            @click="editOverlay = false"
            block
          >edit berkas</v-btn>
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
        >
          <!-- <v-img :src="'https://picsum.photos/200/300?grayscale&blur=1&random='+index"> -->
        </v-img>
      </v-col>
      <v-col :class="windowWidth <= 600 ?' ma-5 pa-5':''">
        <v-row>
          <h1>{{beasiswaSingle.nama}}</h1>
        </v-row>
        <v-row>
          <p>{{beasiswaSingle.deskripsi}}</p>
        </v-row>
        <!-- Persyaratan -->
        <v-row>
          <table>
            <tr v-if="beasiswaSingle.total_sks">
              <td>
                <span v-if="beasiswaSingle.total_sks">{{"Minimum "+ beasiswaSingle.total_sks+" SKS"}}</span>
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
          </table>

          <table class="mt-2">
            <tr>
              <td>
                <span>Batas upload berkas <span v-if="beasiswaSingle.awal_berkas"><strong>{{" "+parseDate(beasiswaSingle.awal_berkas)}}</strong> sampai </span> <strong> {{" "+parseDate(beasiswaSingle.akhir_berkas)}}</strong> </span>

              </td>

            </tr>
            <tr>
              <td>
                <span v-if="beasiswaSingle.is_interview">Waktu wawancara <span v-if="beasiswaSingle.awal_interview"><strong>{{parseDate(beasiswaSingle.awal_interview)}} </strong>sampai </span> <strong>{{parseDate(beasiswaSingle.akhir_interview)}}</strong> </span>

              </td>
            </tr>
            <tr>
              <td>
                <span v-if="beasiswaSingle.is_survey"> Waktu survey <span v-if="beasiswaSingle.awal_survey"><strong>{{parseDate(beasiswaSingle.awal_survey)}}</strong> sampai </span> <strong>{{parseDate(beasiswaSingle.akhir_survey)}} </strong> </span>

              </td>
            </tr>
          </table>
          <br>

        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-sheet
        width="100%"
        class="pa-5 "
      >
        <h3>Form Pendaftaran</h3>
        <p>Mohon isi setiap isian dengan teliti. Setiap perubahan akan langsung disimpan oleh aplikasi. <br>
          Klik tombol <strong>DAFTAR</strong> untuk mendaftar pada beasiswa ini. Berkas masih bisa diedit setelah mendaftar, sampai waktu pendaftaran usai.<br>
          Berkas yang tidak lengkap / tidak klik tombol daftar dianggap tidak mendaftar</p>
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
              <p><span v-if="field.required">*</span>{{field.pertanyaan}}</p>
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
                >
                </v-radio>
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
                        accept="image/*, .pdf"
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
                        accept="image/*, .pdf"
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
                    </v-col> -->
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
                      ></v-file-input> -->
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
                        accept="image/*, .pdf"
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
                      ></v-file-input> -->
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
                        accept="image/*, .pdf"
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
                  >
                    Field ini wajib diisi minimal 1
                  </v-alert>
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
              <v-menu
                v-if="field.type == 'Tanggal'"
                @change="updateField(field)"
                v-model="field.date"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    @change="updateField(field)"
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
                  v-model="field.value"
                  @input="menu2 = false"
                ></v-date-picker>
              </v-menu>
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
                      accept="image/*, .pdf"
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
                  accept="image/*, .pdf"
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
          v-if="isSyaratPassed"
          elevation="20"
          :style=" windowWidth<1265?'':'padding-left:256px'"
          color="blue-grey darken-4"
          class="submit-panel"
        >
          <v-container>

            <v-row
              dense
              align="center"
              justify="start"
            >
              <v-col v-if="!isSubmitted">
                <v-btn
                  :disabled="isDisabled"
                  :loading="loadingBtn"
                  @click="checkIsReady()"
                  color="#2E7D32"
                >Daftar</v-btn>
              </v-col>
              <v-col v-if="isSubmitted">
                <v-btn
                  :disabled="isDisabled"
                  :loading="loadingBtn"
                  @click="editOverlay = true"
                  color="#2E7D32"
                >Selesai edit</v-btn>
              </v-col>
            </v-row>
            <v-row
              dense
              v-if="!validation"
            >
              <v-col>
                <v-alert
                  text
                  class="mt-2"
                  border="left"
                  dense
                  type="warning"
                >Masih ada field yang belum diisi</v-alert>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col cols="12">
                <span class="caption">Field dengan tanda * wajib diisi</span>
              </v-col>
            </v-row>
          </v-container>

        </v-sheet>
      </v-sheet>
    </v-row>
    <v-dialog
      width="400px"
      :persistent="loadingSheet.toggle"
      eager
      overlay-color="green darken-4"
      v-model="loadingSheet.toggle"
      inset
    >
      <v-card tile>
        <v-progress-linear
          :value="progress"
          class="my-0"
          :height="5"
        ></v-progress-linear>

        <v-list>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>{{
                this.loadingSheet.message
              }}</v-list-item-title>
              <v-list-item-subtitle>{{
                this.progress + "%"
              }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="isSure"
      overlay-color="green"
      :width="width()"
    >
      <v-card>
        <v-card-title>Peringatan</v-card-title>
        <v-card-text>Seluruh berkas yang dikirim tidak dapat diedit/diperbarui/dihapus kembali, mohon periksa kelengkapan berkas anda.<br><br>Apakah anda yakin untuk mendaftar?</v-card-text>
        <v-card-actions>
          <v-btn
            @click="save()"
            color="green darken-2"
            :disabled="loadingBtn"
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
        >
          tutup
        </v-btn>

      </template>
    </v-snackbar>

  </v-sheet>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  created() {
    axios.get("/api/permohonan/" + this.$route.params.id).then((response) => {
      console.log("test", response.data);
    });
    this.getBeasiswaSingle(this.$route.params.id)
      .then((response) => {
        if (Object.values(response.syarat).includes(false)) {
          this.overlay = {
            show: true,
            message: response.syarat,
          };
        } else {
          this.isSyaratPassed = true;
        }
      })
      .catch((error) => {
        if (error.response.status == 401) {
          this.$router.push({ name: "Landing Page" });
        }
      });
    this.getUserPermohonan();
  },
  methods: {
    ...mapActions(["getBeasiswaSingle"]),
    testRefsWID(id) {
      this.$refs[id][0].loading = true;
      console.log(this.$refs[id]);
    },
    testRefs() {
      console.log(this.$refs);
    },
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    fileChange(file, index) {
      this.fields[index].value = file;
      this.updateFile(this.fields[index]);
      this.getUserPermohonan();
    },
    updateField(item) {
      if (!this.isSyaratPassed) {
        return;
      }
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
      console.log(this.fields, "woi");
      // var form={
      //   beasiswa_id = this.$route.params.id,
      //   form: this.fields
      // }
      axios
        .post(`/api/pemohon`, {
          beasiswa_id: this.$route.params.id,
          form: this.fields,
        })
        .then((response) => {
          console.log(response.data);
          this.overlay.message =
            "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
          this.loadingBtn = false;
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
    },
    async updateFile(item) {
      console.log(item);
      if (item.type == "Multiple Upload") {
        console.log("multi upload yg diupload itu lo", item.multiUpload.items);
        for (let j = 0; j < item.multiUpload.items.length; j++) {
          const tempMulti = item.multiUpload.items[j];
          // console.log(tempMulti);
          // console.log(tempMulti.value);
          // if(tempMulti.value=="File") console.log("huray");
          if (tempMulti.value && tempMulti.value.length != 0) {
            // console.log("lenght",tempMulti.value.length)
            var data = new FormData();
            data.append("file", tempMulti.value);
            data.append("id", this.$route.params.id);
            await this.upload(data, this).then((response) => {
              var formTemp = JSON.parse(JSON.stringify(this.fields));
              var index = this.fields.indexOf(item);
              formTemp[index].multiUpload.items[j]["file_name"] =
                response.data.file_name;
              axios
                .post(`/api/pemohon`, {
                  beasiswa_id: this.$route.params.id,
                  form: formTemp,
                })
                .then((response) => {
                  console.log(response.data);
                  this.getUserPermohonan();
                  this.overlay.message =
                    "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
                  this.loadingBtn = false;
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
      } else {
        console.log("upload yg diupload");

        var data = new FormData();
        data.append("file", item.value);
        data.append("id", this.$route.params.id);
        await this.upload(data, this)
          .then((response) => {
            console.log(response.data);
            var formTemp = JSON.parse(JSON.stringify(this.fields));
            var index = this.fields.indexOf(item);
            console.log(index);
            formTemp[item.index - 1].value = response.data.file_name;
            axios
              .post(`/api/pemohon`, {
                beasiswa_id: this.$route.params.id,
                form: formTemp,
              })
              .then((response) => {
                console.log(response.data);
                this.getUserPermohonan();
                this.overlay.message =
                  "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
                this.loadingBtn = false;
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

    getUserPermohonan() {
      axios
        .get("/api/permohonan/" + this.$route.params.id)
        .then((response) => {
          console.log(response.data);
          if (response.data.length > 0) {
            console.log("aku tidak kosong");

            console.log("cek tanggal", response.data);
            this.fields = JSON.parse(response.data[0].form);
            this.refference = JSON.parse(JSON.stringify(this.fields));
          } else {
            console.log("aku kosong");
            this.getBeasiswaSingle(this.$route.params.id)
              .then((response) => {
                this.defaultFields = JSON.parse(response.fields);
                this.fields = this.defaultFields;
                this.refference = JSON.parse(JSON.stringify(this.fields));
              })
              .then((response) => {
                console.log("response single ", response.data);
              });
          }
          try {
            this.isSubmitted =
              response.data[0].is_submitted == 1 ? true : false;
            this.editOverlay =
              response.data[0].is_submitted == 1 ? true : false;
          } catch (error) {
            this.isSubmitted = false;
          }
          console.log(this.fields, "fields");
        })
        .catch((error) => {
          if (error.response.status == 401) {
            this.$router.push({ name: "Landing Page" });
          }
        });
    },
    goToLandingPage() {
      this.$router.push({ name: "Landing Page" });
    },
    upload: async (data, ini) => {
      ini.progress = 0;
      ini.loadingSheet.toggle = true;
      ini.loadingSheet.message = "Mengupload File...";
      return axios({
        method: "post",
        url: "/api/pemohon/file",
        onUploadProgress: (progressEvent) => {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          ini.progress = percentCompleted;
        },
        data,
      }).finally(() => {
        setTimeout(() => {
          console.log("im supposed to died");
          ini.loadingSheet.toggle = false;
          console.log("loading: ", ini.loadingSheet.toggle);
        }, 1000);
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
        // this.isSure = true;
        this.loadingBtn = true;
        axios
          .post(`/api/pemohon`, {
            beasiswa_id: this.$route.params.id,
            is_submitted: true,
          })
          .then((response) => {
            console.log(response.data);
            this.getUserPermohonan();
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
    ...mapState(["beasiswaSingle", "nim"]),
  },
  watch: {
    beasiswaSingle(val) {
      console.log("beasiswa single", val);
      var now = Date.now();
      // var akhir_berkas = val.
    },
    validation(v) {
      console.log(v);
      v ? (this.isDisabled = false) : (this.isDisabled = true);
    },
  },
  data() {
    return {
      isSyaratPassed: false,
      progress: 0,
      loadingSheet: {},
      editOverlay: false,
      isSubmitted: false,
      snackbar: false,
      msg: {
        color: "green darken-4",
        text: "Perubahan berhasil disimpan!",
        icon: "mdi-content-save",
      },
      isSure: false,
      rule: [(v) => !!v || "Field ini wajib diisi"],
      validation: true,
      isDisabled: false,
      loadingBtn: false,
      //   msg: "",
      //   isAlreadyHas: false,
      overlay: { show: false },
      refference: {},
      fields: {},
      defaultFields: {},
      loading: 0,
      loadText: "",
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
