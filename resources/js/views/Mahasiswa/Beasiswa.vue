<template>
  <v-sheet color="transparent">
    <v-overlay
      :absolute="false"
      :value="isAlreadyHas"
      color="green"
    >
      <v-card>
        <v-card-text>{{msg}}</v-card-text>
      </v-card>
    </v-overlay>
    <v-row
      align="center"
      justify="start"
    >
      <v-col cols="4">

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
      <v-col>
        <v-row>
          <h1>{{beasiswaSingle.nama}}</h1>
        </v-row>
        <v-row>
          <p>{{beasiswaSingle.deskripsi}}</p>
        </v-row>
        <v-row>
          {{beasiswaSingle.deskripsi}}
          <br><br>
          Batas upload berkas <span v-if="beasiswaSingle.awal_berkas"><strong>{{parseDate(beasiswaSingle.awal_berkas)}}</strong> sampai </span> <strong> {{parseDate(beasiswaSingle.akhir_berkas)}}</strong>
          <br>
          <span v-if="beasiswaSingle.is_interview">Waktu wawancara <span v-if="beasiswaSingle.awal_interview"><strong>{{parseDate(beasiswaSingle.awal_interview)}} </strong>sampai </span> <strong>{{parseDate(beasiswaSingle.akhir_interview)}}</strong> </span>
          <br>
          <span v-if="beasiswaSingle.is_survey">Waktu survey <span v-if="beasiswaSingle.awal_survey"><strong>{{parseDate(beasiswaSingle.awal_interview)}}</strong> sampai </span> <strong>{{parseDate(beasiswaSingle.akhir_interview)}} </strong> </span>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-sheet
        width="100%"
        class="pa-5 "
      >
        <h3>Form Pendaftaran</h3>
        <v-row
          class="ma-5"
          v-for="(field,index) in fields"
          :key="index"
        >

          <v-col style="padding-bottom:0 !important;">
            <p>{{field.pertanyaan}}</p>
            <v-radio-group
              v-if="field.type == 'Pilihan'"
              column
              :mandatory="field.pilihan.required"
              v-model="field.value"
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
            <v-text-field
              prepend-icon="mdi-text-short"
              v-if="field.type == 'Jawaban Pendek'"
              dense
              color="white"
              v-model="field.value"
              placeholder="Jawaban Anda"
            ></v-text-field>
            <v-text-field
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
              color="white"
              placeholder="Upload File"
            ></v-file-input>
            <v-textarea
              v-model="field.value"
              prepend-icon="mdi-view-headline"
              v-if="field.type == 'Paragraf'"
              color="white"
              rows="1"
              dense
              label="Paragraf"
            ></v-textarea>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-btn
              @click="save()"
              color="#2E7D32"
            >Daftar</v-btn>
          </v-col>
        </v-row>
      </v-sheet>
    </v-row>

  </v-sheet>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaSingle(this.$route.params.id).then(response => {
      this.fields = JSON.parse(response.fields);
    });
    this.getUserPermohonan();
  },
  methods: {
    ...mapActions(["getBeasiswaSingle"]),
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    getUserPermohonan() {
      axios.get(this.url + "/api/pemohon/cek-isHas").then(response => {
        console.log(response.data);
        response.data.forEach(element => {
          if (element.beasiswa_id == this.$route.params.id) {
            this.msg =
              "Anda telah mendaftar pada beasiswa ini sebelumnya, lihat status permohonan";
            this.isAlreadyHas = true;
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
    async save() {
      var finalForm = [];
      this.fields.forEach(element => {
        finalForm.push(element);
      });
      var beasiswa_id = this.$route.params.id;
      var form = [];
      var files = [];
      var fileNames = [];
      var reqs = [];

      // store all file to new array (files)
      this.fields.forEach((element, index) => {
        if (element.type == "Upload File") {
          files.push({ file: element.value, index });
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
        console.log(element.index);
        finalForm[element.index].value = element.newName;
        console.log(finalForm);
      }
      axios
        .post(`${this.url}/api/pemohon`, {
          beasiswa_id,
          form: finalForm
        })
        .then(response => {
          console.log(response.data);
          this.msg =
            "Permohonan beasiswa berhasil dikirim, lihat status permohonan beasiswa";
          this.isAlreadyHas = true;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  computed: {
    ...mapState(["beasiswaSingle", "nim", "url"])
  },
  data() {
    return {
      msg: "",
      isAlreadyHas: false,
      fields: {},
      loading: 0,
      loadText: ""
    };
  }
};
</script>

<style>
</style>