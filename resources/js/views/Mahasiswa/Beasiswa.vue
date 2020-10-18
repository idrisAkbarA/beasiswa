<template>
  <v-sheet color="transparent">
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
                column
                :mandatory="field.pilihan.required"
                v-model="field.value"
                :rules="isRequired(field.required)"
              >
                <v-radio
                  v-for="(item,index) in field.pilihan.items"
                  :key="index"
                  :value="item.label"
                  color="white"
                  :label="item.label"
                >
                </v-radio>
              </v-radio-group>
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
                    ></v-checkbox>

                  </v-col>
                  <v-col cols="6">
                    <span>{{item.label}}</span>

                  </v-col>
                  <v-col cols="5">

                    <v-file-input
                      v-model="item.value"
                      :disabled="!item.isSelected"
                      filled
                      :label="'Upload '+item.label"
                    ></v-file-input>
                  </v-col>
                </v-row>
                <v-row>
                  <v-alert type="error" v-if="field.error">
                    Field ini wajib diisi minimal 1
                  </v-alert>
                </v-row>

              </v-container>
              <v-text-field
                prepend-icon="mdi-text-short"
                v-if="field.type == 'Jawaban Pendek'"
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
                dense
                v-model="field.value"
                color="white"
                type="number"
                placeholder="Jawaban Anda"
              ></v-text-field>
              <v-menu
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
                  v-model="field.value"
                  @input="menu2 = false"
                ></v-date-picker>
              </v-menu>
              <v-file-input
                v-if="field.type == 'Upload File'"
                dense
                v-model="field.value"
                :rules="isRequired(field.required)"
                color="white"
                placeholder="Upload File"
              ></v-file-input>
              <v-textarea
                :rules="isRequired(field.required)"
                v-model="field.value"
                prepend-icon="mdi-view-headline"
                v-if="field.type == 'Paragraf'"
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
        <v-row>
          <v-col>
            <v-btn
              :disabled="isDisabled"
              :loading="loadingBtn"
              @click="checkIsReady()"
              color="#2E7D32"
            >Daftar</v-btn>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <span class="caption">Field dengan tanda * wajib diisi</span>
          </v-col>
        </v-row>
        <!-- <v-row>
          <v-btn @click="checkMultipleUpload()">test</v-btn>
        </v-row> -->
      </v-sheet>
    </v-row>
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

  </v-sheet>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaSingle(this.$route.params.id).then(response => {
      this.fields = JSON.parse(response.fields);
      if (Object.values(response.syarat).includes(false)) {
        console.log("overlay");
        this.overlay = {
          show: true,
          message: response.syarat
        };
      }
    });
    this.getUserPermohonan();
  },
  methods: {
    ...mapActions(["getBeasiswaSingle"]),
    checkMultipleUpload() {
      this.fields.forEach(element => {
        if (element.required == false) {
          return; // kalau tidak required, berhenti
        }
        if (element.type == "Multiple Upload") {
          var isFilled = false;
          element.multiUpload.items.every(v => {
            if (v.isSelected) {
              console.log("ada yg di ceklis ni");
              console.log(v.value);
              if (!v.value) {
                isFilled = false;
                console.log("op yg di ceklis ga berisi");
                this.validation = false;
              }else{
                console.log("yg di ceklis berisi");
                isFilled = true;
                this.validation = true;
              }
              return false;
            }
            return true;
          });
          console.log(isFilled);
          if (!isFilled) {
            element['error'] = true;
            console.log(element)
            this.validation = false;
          }else{
            element['error'] = false;
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
      axios.get("/api/pemohon/cek-isHas").then(response => {
        console.log(response.data);
        response.data.forEach(element => {
          if (element.beasiswa_id == this.$route.params.id) {
            this.overlay.message =
              "Anda telah mendaftar pada beasiswa ini sebelumnya, lihat status permohonan";
            this.overlay.show = true;
            this.isDisabled = true;
          }
        });
      });
    },


    upload: async (data, url) => {
      return axios({
        method: "post",
        url: url + "/api/pemohon/file",
        onUploadProgress: function(progressEvent) {},
        data
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
        this.isSure = true;
      }
    },
    async save() {
      this.loadingBtn = true;
      var finalForm = [];
      this.fields.forEach(element => {
        finalForm.push(element);
      });
      var beasiswa_id = this.$route.params.id;
      var form = [];
      var files = [];
      var fileNames = [];
      var reqs = [];
      var multiFiles = [];

      // store all file to new array (files)
      this.fields.forEach((element, index) => {
        if (element.type == "Upload File") {
          files.push({ file: element.value, index });
        }
        if (element.type == "Multiple Upload") {
          multiFiles.push({ item: element.multiUpload.items, index });
        }
      });
      for (let i = 0; i < files.length; i++) {
        const element = files[i];
        var data = new FormData();
        data.append("file", element.file);
        data.append("id", beasiswa_id);
        await this.upload(data, this.url).then(response => {
          fileNames.push({
            index: element.index,
            newName: response.data.file_name
          });
        });
      }
      var ini = this;
      finalForm = JSON.parse(JSON.stringify(ini.fields));
      for (let i = 0; i < fileNames.length; i++) {
        const element = fileNames[i];
        // console.log(element.index);
        finalForm[element.index].value = element.newName;
        // console.log(finalForm);
      }
      //multiple upload goes here

      for (let i = 0; i < multiFiles.length; i++) {
        const element = multiFiles[i];
        // console.log(element)
        for (let j = 0; j < element.item.length; j++) {
          const tempMulti = element.item[j];
          // console.log(tempMulti);
          console.log(tempMulti.value);
          if (tempMulti.value) {
            var data = new FormData();
            data.append("file", tempMulti.value);
            data.append("id", 0);
            await this.upload(data, this.url).then(response => {
              tempMulti["file_name"] = response.data.file_name;
              // console.log(tempMulti)
              // console.log(finalForm[tempMulti.index])
              finalForm[element.index].multiUpload.items[j]["file_name"] =
                response.data.file_name;
            });
          }
        }
      }
      console.log(finalForm);
      axios
        .post(`${this.url}/api/pemohon`, {
          beasiswa_id,
          form: finalForm
        })
        .then(response => {
          console.log(response.data);
          this.overlay.message =
            "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
          this.overlay.show = true;
          this.loadingBtn = false;
          this.isDisabled = true;
          this.isSure = false;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  computed: {
    ...mapState(["beasiswaSingle", "nim", "url"])
  },
  watch: {
    validation(v) {
      console.log(v);
      v ? (this.isDisabled = false) : (this.isDisabled = true);
    }
  },
  data() {
    return {
      isSure: false,
      rule: [v => !!v || "Field ini wajib diisi"],
      validation: true,
      isDisabled: false,
      loadingBtn: false,
      //   msg: "",
      //   isAlreadyHas: false,
      overlay: { show: false },
      fields: {},
      loading: 0,
      loadText: ""
    };
  }
};
</script>

<style>
</style>
