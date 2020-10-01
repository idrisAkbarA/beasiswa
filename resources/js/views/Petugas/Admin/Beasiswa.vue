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
            @click="edit(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            @click="deleteBea(item)"
          >
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>

    <!-- btms -->

    <v-bottom-sheet
      scrollable
      :width="width()"
      inset
      overlay-color="#69F0AE"
      v-model="toggleEdit"
    >
      <v-card>
        <v-card-title> <span>Edit Beasiswa</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="batal()"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="sendEdit()"
          >Simpan</v-btn>
        </v-card-title>
        <vue-scroll :ops="ops">
          <v-card-text style="height: 600px;">
            <v-row
              dense
              class="ml-1 mr-1"
            >
              <v-col>

                <v-text-field
                  color="#C8E6C9"
                  label="Nama Beasiswa"
                  v-model="namaEdit"
                ></v-text-field>
              </v-col>

            </v-row>
            <v-row
              dense
              class="ml-1 mr-1"
            >
              <v-col>

                <v-textarea
                  auto-grow
                  color="white"
                  rows="1"
                  label="Deskripsi"
                  v-model="deskripsiEdit"
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
                  v-model="selected_instansiEdit"
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
                  v-model="kuotaEdit"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row align="center">
              <v-col cols="6">
                Tahap upload/pengisian berkas
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menuberkasEdit"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="dateBerkasEdit"
                      label="Batas/Rentang Waktu Upload Berkas"
                      prepend-icon="event"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    range
                    v-model="dateBerkasEdit"
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
                  v-model="is_wawancaraEdit"
                  label="Tahap wawancara"
                  hide-details
                ></v-checkbox>
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menuWawancaraEdit"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :disabled="!is_wawancaraEdit"
                      v-model="dateWawancaraEdit"
                      label="Rentang Waktu Wawancara"
                      prepend-icon="event"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    range
                    v-model="dateWawancaraEdit"
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
                  v-model="is_surveyEdit"
                  label="Tahap Survey"
                  hide-details
                ></v-checkbox>
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menuSurveyEdit"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :disabled="!is_surveyEdit"
                      v-model="dateSurveyEdit"
                      label="Rentang Waktu Wawancara"
                      prepend-icon="event"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    range
                    v-model="dateSurveyEdit"
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
                v-for="field in fieldsEdit"
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
                          @click="addPilihanItemEdit(field.index)"
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
                        auto-grow
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
                      @click="deleteFieldEdit(field)"
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
                @click="addFieldsEdit()"
              >
                <v-icon dark>
                  mdi-plus
                </v-icon>
              </v-btn>
            </v-row>
          </v-card-text>
        </vue-scroll>
      </v-card>
    </v-bottom-sheet>

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
          <v-btn
            @click="batal()"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            v-model="toggleBeasiswa"
            @click="save()"
          >Simpan</v-btn>
        </v-card-title>
        <vue-scroll :ops="ops">
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
                auto-grow
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
                              @click="deletePilihanItemEdit(field,item.label)"
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
                      auto-grow
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
        </vue-scroll>
      </v-card>
    </v-bottom-sheet>
    <v-dialog
      width="400"
      overlay-color="#69F0AE"
      v-model="deleteDialog"
    >
      <v-card>
        <v-card-title class="mt-2">
          Apakah anda yakin ingin menghapus?
          <p style="font-weight:bold">
            <!-- {{itemtoDelete.nama}}? -->
          </p>
        </v-card-title>
        <v-card-actions>

          <v-btn
            text
            @click="dialogDelete = false"
          >
            Batal
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            dark
            class="green"
            @click="finallyDelete"
          >
            <v-icon left>mdi-check</v-icon>
            iya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswa();
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions([
      "getBeasiswa",
      "editBeasiswa",
      "getInstansi",
      "storeBeasiswa",
      "deleteBeasiswa"
    ]),
    deleteBea(item) {
      console.log(item.id);
      this.deleteId = item.id;
      this.deleteDialog = true;
    },
    finallyDelete() {
      console.log(this.deleteId);
      this.deleteBeasiswa(this.deleteId);
      this.deleteDialog = false;
    },
    edit(item) {
      this.idEdit = item.id;
      console.log(item);
      this.kuotaEdit = item.quota;
      this.namaEdit = item.nama;
      this.fieldsEdit = JSON.parse(item.fields);
      this.deskripsiEdit = item.deskripsi;
      this.valueEdit = item.instansi_id;

      this.instansi.forEach(element => {
        if (element.id == item.instansi_id) {
          this.selected_instansiEdit = { id: element.id, name: element.name };
          console.log(this.selected_instansiEdit);
        }
      });

      this.dateWawancaraEdit = [item.awal_interview, item.akhir_interview];
      this.dateSurveyEdit = [item.awal_survey, item.akhir_survey];

      if (item.awal_berkas) {
        this.dateBerkasEdit = [item.awal_berkas, item.akhir_berkas];
      } else {
        if (item.akhir_berkas) {
          this.dateBerkasEdit = [item.akhir_berkas];
        }
      }

      if (item.awal_interview) {
        this.dateWawancaraEdit = [item.awal_interview, item.akhir_interview];
      } else {
        if (item.akhir_interview) {
          this.dateWawancaraEdit = [item.akhir_interview];
        }
      }

      if (item.awal_survey) {
        this.dateSurveyEdit = [item.awal_survey, item.akhir_survey];
      } else {
        if (item.akhir_survey) {
          this.dateSurveyEdit = [item.akhir_survey];
        }
      }
      this.is_wawancaraEdit = item.is_interview ? true : false;
      this.is_surveyEdit = item.is_survey ? true : false;
      this.toggleEdit = true;
    },
    sendEdit() {
      var awal_wawancara = "";
      var akhir_wawancara = "";
      var awal_survey = "";
      var akhir_survey = "";
      var awal_berkas = "";
      var akhir_berkas = "";
      awal_berkas = this.dateBerkasEdit[1] ? this.dateBerkasEdit[0] : null;
      akhir_berkas = this.dateBerkasEdit[1]
        ? this.dateBerkasEdit[1]
        : this.dateBerkasEdit[0];

      if (this.is_wawancaraEdit) {
        awal_wawancara = this.dateWawancaraEdit[1]
          ? this.dateWawancaraEdit[0]
          : null;
        akhir_wawancara = this.dateWawancaraEdit[1]
          ? this.dateWawancaraEdit[1]
          : this.dateWawancaraEdit[0];
      }
      if (this.is_surveyEdit) {
        awal_survey = this.dateSurveyEdit[1] ? this.dateSurveyEdit[0] : null;
        akhir_survey = this.dateSurveyEdit[1]
          ? this.dateSurveyEdit[1]
          : this.dateSurveyEdit[0];
      }
      var data = {
        id: this.idEdit,
        nama: this.namaEdit,
        deskripsi: this.deskripsiEdit,
        kuota: this.kuotaEdit,
        instansi: this.selected_instansiEdit.id,
        fields: this.fieldsEdit,
        is_survey: this.is_surveyEdit,
        is_wawancara: this.is_wawancaraEdit,
        awal_wawancara,
        akhir_wawancara,
        awal_survey,
        akhir_survey,
        awal_berkas,
        akhir_berkas
      };
      console.log(data);
      this.btnLoading = true;
      this.editBeasiswa(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleEdit = false;
          console.log("what");
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
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
      awal_berkas = this.dateBerkas[1] ? this.dateBerkas[0] : null;
      akhir_berkas = this.dateBerkas[1]
        ? this.dateBerkas[1]
        : this.dateBerkas[0];

      if (this.is_wawancara) {
        awal_wawancara = this.dateWawancara[1] ? this.dateWawancara[0] : null;
        akhir_wawancara = this.dateWawancara[1]
          ? this.dateWawancara[1]
          : this.dateWawancara[0];
      }
      if (this.is_survey) {
        awal_survey = this.dateSurvey[1] ? this.dateSurvey[0] : null;
        akhir_survey = this.dateSurvey[1]
          ? this.dateSurvey[1]
          : this.dateSurvey[0];
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
      this.storeBeasiswa(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleBeasiswa = false;
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
    width() {
      // console.log(this.windowWidth)
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "50%";
      }
    },
    batal() {
      this.toggleBeasiswa = false;
    },
    addFieldsEdit() {
      this.fieldsEdit.push({
        type: "Jawaban Pendek",
        pertanyaan: "",
        index: this.fieldsEdit[0]
          ? this.fieldsEdit[this.fieldsEdit.length - 1].index + 1
          : 0,
        value: "",
        date: false,
        pilihan: {
          required: false,
          items: [{ label: "" }]
        },
        required: true
      });
      console.log(this.fieldsEdit);
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
        date: false,
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
    deleteFieldEdit(field) {
      this.fieldsEdit.splice(this.fieldsEdit.indexOf(field), 1);
    },
    addPilihanItem(field_index) {
      this.fields[field_index - 1].pilihan.items.push({ label: "" });
    },
    addPilihanItemEdit(field_index) {
      this.fieldsEdit[field_index - 1].pilihan.items.push({ label: "" });
    },
    deletePilihanItemEdit(field, label) {
      var item = this.fieldsEdit[this.fieldsEdit.indexOf(field)].pilihan.items;
      item.splice(item.indexOf(label), 1);
    },
    deletePilihanItem(field, label) {
      var item = this.fields[this.fields.indexOf(field)].pilihan.items;
      item.splice(item.indexOf(label), 1);
    }
  },
  computed: {
    ...mapState([
      "beasiswa",
      "isOpenBeasiswa",
      "instansi",
      "isTableLoading",
      "isLoading"
    ]),
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
      ops: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true
        }
      },
      idEdit: null,
      deleteDialog: false,
      deleteId: null,
      btnLoadingEdit: false,
      namaEdit: "",
      deskripsiEdit: "",
      selected_instansiEdit: "",
      fieldsEdit: {},
      isBerkasEdit: false,
      is_wawancaraEdit: false,
      is_surveyEdit: false,
      dateBerkasEdit: [new Date().toISOString().substr(0, 10)],
      dateWawancaraEdit: [new Date().toISOString().substr(0, 10)],
      dateSurveyEdit: [new Date().toISOString().substr(0, 10)],
      kuotaEdit: 1,
      toggleEdit: false,

      btnLoading: false,
      nama: "",
      deskripsi: "",
      selected_instansi: "",
      fields: [
        {
          type: "Jawaban Pendek",
          pertanyaan: "",
          index: 1,
          value: "",
          date: false,
          pilihan: {
            required: true,
            items: [{ label: "" }]
          },
          required: true
        }
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
      menuWawancaraEdit: false,
      menuSurveyEdit: false,
      menuberkasEdit: false,
      isBerkas: false,
      is_wawancara: false,
      is_survey: false,
      dateBerkas: [new Date().toISOString().substr(0, 10)],
      dateWawancara: [new Date().toISOString().substr(0, 10)],
      dateSurvey: [new Date().toISOString().substr(0, 10)],
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