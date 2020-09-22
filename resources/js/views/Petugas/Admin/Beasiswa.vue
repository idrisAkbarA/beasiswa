<template>
  <v-container>

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
          <v-btn text>batal</v-btn>
          <v-btn color="#2E7D32">Simpan</v-btn>
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
              ></v-combobox>

            </v-col>
            <v-col cols="4">
              <v-text-field
                label="Kuota"
                type="number"
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
                v-model="isWawancara"
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
                    :disabled="!isWawancara"
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
                  <v-text-field
                    dense
                    disabled=""
                    filled
                    placeholder="Jawaban Pendek"
                  ></v-text-field>
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
                <!-- v-model="switch1"
                  :label="`Switch 1: ${switch1.toString()}`" -->
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
export default {
  created() {
    this.getBeasiswa();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getBeasiswa"]),
    addField(){
      // get the last array then add the order value
      this.fields.push({
          type: "Jawaban Pendek",
          pertanyaan: "",
          index: (this.fields[0])?this.fields[this.fields.length-1].index+1 : 0,
          required: true
        });
        console.log(this.fields)
    },
    deleteField(field){
      this.fields.splice(this.fields.indexOf(field),1)
    }
  },
  computed: {
    ...mapState(["beasiswa", "isOpenBeasiswa"]),
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
      fields: [
        {
          type: "Jawaban Pendek",
          pertanyaan: "",
          index: 0,
          required: true
        }
      ],
      itemTypes: [
        "Jawaban Pendek",
        "Paragraf",
        "Upload File",
        "Pilihan",
        "Tanggal"
      ],
      sheet: false,
      menuWawancara: false,
      menuberkas: false,
      isBerkas: false,
      isWawancara: false,
      dateBerkas: new Date().toISOString().substr(0, 10),
      dateWawancara: new Date().toISOString().substr(0, 10),
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