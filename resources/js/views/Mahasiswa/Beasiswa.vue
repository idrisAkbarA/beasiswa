<template>
  <v-sheet color="transparent">
    <v-row
      align="center"
      justify="start"
    >
      <v-col cols="4">
        <v-card
          ripple
          class="ma-3 item"
          :height="300"
          :width="200"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/300?random'"
          >
            <!-- <v-img :src="'https://picsum.photos/200/300?grayscale&blur=1&random='+index"> -->
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-row>
          <h1>{{beasiswaSingle.nama}}</h1>
        </v-row>
        <v-row>
          <p>{{beasiswaSingle.deskripsi}}</p>
        </v-row>
        <v-row>
          <p>Batas upload berkas <span v-if="beasiswaSingle.awal_berkas">{{beasiswaSingle.awal_berkas}} sampai </span> {{beasiswaSingle.akhir_berkas}} </p>
        </v-row>
        <v-row v-if="beasiswaSingle.is_interview">
          <p>Waktu wawancara <span v-if="beasiswaSingle.awal_interview">{{beasiswaSingle.awal_interview}} sampai </span> {{beasiswaSingle.akhir_interview}} </p>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-sheet
        width="100%"
        hight="100%"
        class="pa-5"
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
              placeholder="Jawaban Anda"
            ></v-text-field>
            <v-text-field
              prepend-icon="mdi-numeric"
              v-if="field.type == 'Jawaban Angka'"
              dense
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
      //   console.log(response);
      //   console.log(this.fields);
    });
  },
  methods: {
    ...mapActions(["getBeasiswaSingle"]),
    save() {
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
      this.fields.forEach((element,index) => {
        if (element.type == "Upload File") {
          files.push({file:element.value,index});
        }
      });

      // iterate each files in array and upload it
      files.forEach((element,index) => {
     
          var ini = this;
          var data = new FormData();
          data.append("file", element.file);
          data.append("id", beasiswa_id);
          return axios({
            method: "post",
            url: this.url + "/api/pemohon/file",
            onUploadProgress: function(progressEvent) {
              var percentage =
                progressEvent.loaded * (100 / progressEvent.total);
              ini.loading = percentage;
              ini.loadText = "Uploading " + Math.round(ini.loading) + "%";
              console.log(ini.loading);
            },
            data: data
          })
            .then(function(response) {
              // console.log(response.data);
              fileNames.push({
                index: element.index,
                newName: response.data.file_name
              }); //get new names from server

              if(index==files.length-1){
                  fileNames.forEach(item => {
                      console.log(item.index)
                      finalForm = JSON.parse(JSON.stringify(ini.fields))
                      finalForm[item.index].value = item.newName
                      console.log(finalForm)
                      axios.post(`${ini.url}/api/pemohon`,
                        {
                            beasiswa_id,
                            form: finalForm
                        }
                      ).then(response=>{
                          console.log(response.data)
                      }).catch(error=>{
                          console.log(error)
                      })
                  });
              }
            })
            .catch(function(error) {
              console.log(error);
            });
        
      });

    //   axios.all(reqs).then(result=>{
    //       console.log(fileNames)
    //   })
    }
  },
  computed: {
    ...mapState(["beasiswaSingle", "nim", "url"])
  },
  data() {
    return {
      fields: {},
      loading: 0,
      loadText: ""
    };
  }
};
</script>

<style>
</style>