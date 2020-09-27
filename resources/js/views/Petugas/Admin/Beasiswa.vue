<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers"
        :items="beasiswa"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editItem(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleBeasiswa"
    >
      <v-card>
        <v-card-title> <span>Buat Beasiswa</span>
          <v-spacer></v-spacer>
          <v-btn @click="batal()" text>batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            v-model="toggleBeasiswa"
            @click="save()"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px;">
          <v-row
            dense
            class="ml-1 mr-1"
          >
            <v-col>

              <v-text-field
                color="#C8E6C9"
                label="Nama Beasiswa"
                v-model="nama"
              ></v-text-field>
            </v-col>

          </v-row>
          <v-row
            dense
            class="ml-1 mr-1"
          >
            <v-col>

              <v-textarea
                color="white"
                rows="1"
                label="Deskripsi"
                v-model="deskripsi"
              ></v-textarea>
            </v-col>

          </v-row>
          <!-- v-model="select" -->
          <!-- :items="items" -->
          <v-row
            dense
            class="ml-1 mr-1"
          >
            <v-col cols="8">
              <v-combobox
                color="white"
                label="Instansi"
                :items="instansi"
                item-text="name"
                v-model="selected_instansi"
              >
                <template v-slot:item="{ index, item }">
                  {{item.name}}
                </template>
                <!-- <template v-slot:selection="{ item,selected }">
                  {{item.name}}
                </template> -->
              </v-combobox>

            </v-col>
            <v-col cols="4">
              <v-text-field
                label="Kuota"
                type="number"
                v-model="kuota"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row align="center">
            <v-col cols="6">
              Tahap upload/pengisian berkas
            </v-col>
            <v-col cols="6">
              <v-menu
                v-model="menuberkas"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="dateBerkas"
                    label="Batas/Rentang Waktu Upload Berkas"
                    prepend-icon="event"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  range
                  v-model="dateBerkas"
                  locale="id-ID"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6">
              <v-checkbox
                color="white"
                v-model="is_wawancara"
                label="Tahap wawancara"
                hide-details
              ></v-checkbox>
            </v-col>
            <v-col cols="6">
              <v-menu
                v-model="menuWawancara"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :disabled="!is_wawancara"
                    v-model="dateWawancara"
                    label="Rentang Waktu Wawancara"
                    prepend-icon="event"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  range
                  v-model="dateWawancara"
                  locale="id-ID"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6">
              <v-checkbox
                color="white"
                v-model="is_survey"
                label="Tahap Survey"
                hide-details
              ></v-checkbox>
            </v-col>
            <v-col cols="6">
              <v-menu
                v-model="menuSurvey"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :disabled="!is_survey"
                    v-model="dateSurvey"
                    label="Rentang Waktu Wawancara"
                    prepend-icon="event"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  range
                  v-model="dateSurvey"
                  locale="id-ID"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row>
            <v-divider></v-divider>
          </v-row>
          <v-row>
            <v-subheader>Buat Form</v-subheader>
          </v-row>

          <!-- Form -->
          <transition-group name="scale-transition">

            <v-card
              v-for="field in fields"
              :key="field.index"
              elevation="10"
              color="#388E3C"
              class="mb-2"
              style="padding-bottom:0 !important;"
            >

              <v-container style="padding-bottom:0 !important;">
                <v-row style="padding-bottom:0 !important;">
                  <v-col
                    cols="7"
                    style="padding-bottom:0 !important;"
                  >
                    <v-text-field
                      color="white"
                      dense
                      label="Pertanyaan"
                      v-model="field.pertanyaan"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="5"
                    style="padding-bottom:0 !important;"
                  >
                    <v-select
                      v-model="field.type"
                      dense
                      :items="itemTypes"
                      label="Tipe isian"
                      color="white"
                      outlined
                    ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col style="padding-bottom:0 !important;">
                    <v-radio-group
                      v-if="field.type == 'Pilihan'"
                      column
                      :mandatory="field.pilihan.required"
                    >

                      <v-row align="center">
                        <span class="ml-2 mr-1">Pilihan wajib diisi</span>
                        <v-switch
                          v-model="field.pilihan.required"
                          color="white"
                        ></v-switch>

                      </v-row>

                      <v-radio
                        v-for="(item,index) in field.pilihan.items"
                        :key="index"
                        :value="item.label"
                        color="white"
                      >
                        <template v-slot:label>
                          <v-row align="center">
                            <v-text-field
                              class="ma-2"
                              color="white"
                              dense
                              filled
                              label="Label"
                              v-model="item.label"
                            ></v-text-field>
                            <v-btn
                              class="ma-2"
                              icon
                              color="white"
                              @click="deletePilihanItem(field,item.label)"
                            >
                              <v-icon>mdi-close</v-icon>
                            </v-btn>
                          </v-row>
                        </template>
                      </v-radio>
                      <v-btn
                        class="mt-2 grey darken-3"
                        fab
                        dark
                        small
                        @click="addPilihanItem(field.index)"
                      >
                        <v-icon dark>
                          mdi-plus
                        </v-icon>
                      </v-btn>
                    </v-radio-group>
                    <v-text-field
                      prepend-icon="mdi-text-short"
                      v-if="field.type == 'Jawaban Pendek'"
                      dense
                      disabled
                      filled
                      placeholder="Jawaban Pendek"
                    ></v-text-field>
                    <v-text-field
                      prepend-icon="mdi-numeric"
                      v-if="field.type == 'Jawaban Angka'"
                      dense
                      disabled
                      filled
                      type="number"
                      placeholder="Jawaban Angka"
                    ></v-text-field>
                    <v-text-field
                      prepend-icon="mdi-calendar"
                      v-if="field.type == 'Tanggal'"
                      dense
                      disabled
                      filled
                      label="Tanggal"
                    ></v-text-field>
                    <v-file-input
                      v-if="field.type == 'Upload File'"
                      dense
                      disabled
                      filled
                      placeholder="Upload File"
                    ></v-file-input>
                    <v-textarea
                      prepend-icon="mdi-view-headline"
                      v-if="field.type == 'Paragraf'"
                      color="white"
                      rows="1"
                      disabled
                      filled
                      dense
                      label="Paragraf"
                    ></v-textarea>
                  </v-col>
                </v-row>
                <v-row
                  class="mb-2"
                  align="center"
                  justify="end"
                >

                  <v-btn
                    icon
                    color="white"
                    @click="deleteField(field)"
                  >
                    <v-icon>mdi-trash-can</v-icon>
                  </v-btn>
                  <span class="ml-2 mr-1">Wajib diisi</span>
                  <v-switch
                    v-model="field.required"
                    color="white"
                  ></v-switch>

                </v-row>

              </v-container>
            </v-card>
          </transition-group>
          <v-row justify="center">
            <v-btn
              class="mt-2"
              fab
              dark
              small
              color="green"
              @click="addField()"
            >
              <v-icon dark>
                mdi-plus
              </v-icon>
            </v-btn>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>

  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
import Axios from "axios";
export default {
  created() {
    this.getBeasiswa();
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getBeasiswa", "getInstansi", "storeBeasiswa"]),
    compareType(a, b) {
      a == b ? true : false;
    },
    save() {
      var awal_wawancara = "";
      var akhir_wawancara = "";
      var awal_survey = "";
      var akhir_survey = "";
      var awal_berkas = "";
      var akhir_berkas = "";
      if (typeof this.dateBerkas == "object") {
        console.log("sama");
      }
      console.log(typeof this.dateBerkas);
      awal_berkas =
        typeof this.dateBerkas == "object" ? this.dateBerkas[0] : null;
      akhir_berkas =
        typeof this.dateBerkas == "object"
          ? this.dateBerkas[1]
          : this.dateBerkas;

      if (this.is_wawancara) {
        awal_wawancara =
          typeof this.dateWawancara == "object" ? this.dateWawancara[0] : null;
        akhir_wawancara =
          typeof this.dateWawancara == "object"
            ? this.dateWawancara[1]
            : this.dateWawancara;
      }
      if (this.is_survey) {
        awal_survey =
          typeof this.dateSurvey == "object" ? this.dateSurvey[0] : null;
        akhir_survey =
          typeof this.dateSurvey == "object"
            ? this.dateSurvey[1]
            : this.dateSurvey;
      }
      var data = {
        nama: this.nama,
        deskripsi: this.deskripsi,
        kuota: this.kuota,
        instansi: this.selected_instansi.id,
        fields: this.fields,
        is_survey: this.is_survey,
        is_wawancara: this.is_wawancara,
        awal_wawancara,
        akhir_wawancara,
        awal_survey,
        akhir_survey,
        awal_berkas,
        akhir_berkas
      };
      console.log(data);
      this.btnLoading = true;
      this.storeBeasiswa(data).then(response=>{
        this.btnLoading = false;
        this.toggleBeasiswa = false
        
      }).catch(error=>{
        this.btnLoading = false;
        
      });
    },
    batal(){
      this.toggleBeasiswa = false;
    },
    addField() {
      // get the last array then add the order value
      this.fields.push({
        type: "Jawaban Pendek",
        pertanyaan: "",
        index: this.fields[0]
          ? this.fields[this.fields.length - 1].index + 1
          : 0,
        value: "",
        date:false,
        pilihan: {
            required: false,
            items: [{ label: "" }]
          },
        required: true
      });
      console.log(this.fields);
    },
    deleteField(field) {
      this.fields.splice(this.fields.indexOf(field), 1);
    },
    addPilihanItem(field_index) {
      this.fields[field_index].pilihan.items.push({ label: "" });
    },
    deletePilihanItem(field, label) {
      var item = this.fields[this.fields.indexOf(field)].pilihan.items;
      item.splice(item.indexOf(label), 1);
    }
  },
  computed: {
    ...mapState(["beasiswa", "isOpenBeasiswa", "instansi","isTableLoading","isLoading"]),
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
      btnLoading:false,
      nama: "",
      deskripsi: "",
      selected_instansi: "",
      fields: [
        {
         type: "Jawaban Pendek",
          pertanyaan: "",
          index: 1,
          value: "",
          date:false,
          pilihan: {
            required: true,
            items: [{ label: "" }]
          },
          required: true
        },
      ],
      itemTypes: [
        "Jawaban Pendek",
        "Jawaban Angka",
        "Paragraf",
        "Upload File",
        "Pilihan",
        "Tanggal"
      ],
      sheet: false,
      menuWawancara: false,
      menuSurvey: false,
      menuberkas: false,
      isBerkas: false,
      is_wawancara: false,
      is_survey: false,
      dateBerkas: new Date().toISOString().substr(0, 10),
      dateWawancara: new Date().toISOString().substr(0, 10),
      dateSurvey: new Date().toISOString().substr(0, 10),
      kuota: 1,
      headers: [
        {
          text: "Beasiswa",
          align: "start",
          sortable: false,
          value: "nama"
        },
        { text: "Deskripsi", value: "deskripsi" },
        { text: "Actions", value: "actions", sortable: false }
      ]
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