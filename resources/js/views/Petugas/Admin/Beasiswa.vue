<template>
  <v-container>
    <v-skeleton-loader type="table" :loading="isTableLoading" transition="fade-transition">
      <!-- style="background-color: #2e7d323b" -->
      <v-data-table
        :headers="headers"
        :items="beasiswa"
        style="background-color: #2e7d323b"
        :items-per-page="10"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-btn icon x-small class="mr-2" @click="edit(item)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-icon small @click="deleteBea(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>

    <!-- btms -->

    <v-bottom-sheet scrollable :width="width()" inset overlay-color="#69F0AE" v-model="toggleEdit">
      <v-card>
        <v-card-title>
          <span>Edit Beasiswa</span>
          <v-spacer></v-spacer>
          <v-btn @click="batalEdit()" text>batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            :disabled="isEditDisabled"
            @click="sendEdit()"
          >Simpan</v-btn>
        </v-card-title>
        <vue-scroll :ops="ops">
          <v-card-text style="height: 600px;">
            <v-form ref="editForm" v-model="validationEdit" lazy-validation>
              <v-row dense class="ml-1 mr-1">
                <v-col>
                  <v-text-field
                    color="#C8E6C9"
                    label="Nama Beasiswa"
                    :rules="rules.required"
                    v-model="namaEdit"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row dense class="ml-1 mr-1">
                <v-col>
                  <v-textarea
                    auto-grow
                    color="white"
                    rows="1"
                    label="Deskripsi"
                    :rules="rules.required"
                    v-model="deskripsiEdit"
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row dense class="ml-1 mr-1">
                <v-col cols="4">
                  <v-combobox
                    color="white"
                    label="Instansi"
                    :items="instansi"
                    :rules="rules.required"
                    item-text="name"
                    v-model="selected_instansiEdit"
                  >
                    <template v-slot:item="{ index, item }">{{item.name}}</template>
                  </v-combobox>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    label="Kuota"
                    type="number"
                    :min="0"
                    :rules="rules.required && rules.kuota"
                    v-model="kuotaEdit"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="jenjangEdit"
                    :items="[{i:0, v:'D3'}, {i:1, v:'S1'}, {i:2, v:'S2'}, {i:3, v:'S3'}]"
                    item-text="v"
                    item-value="i"
                    :rules="rules.jenjang"
                    label="Jenjang"
                    color="white"
                    :disabled="isDisabled(dateBerkasEdit)"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row align="center">
                <v-col cols="6">Tahap upload/pengisian berkas</v-col>
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
                      color="green lighten-1"
                      range
                      v-model="dateBerkasEdit"
                      locale="id-ID"
                    ></v-date-picker>
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
                      color="green lighten-1"
                    ></v-date-picker>
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
                      color="green lighten-1"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>

              <v-expansion-panels>
                <v-expansion-panel class="grey darken-3">
                  <v-expansion-panel-header>Syarat lainnya</v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-row>
                      <v-col cols="6">
                        <v-checkbox
                          label="IPK"
                          v-model="lainnya.ipk"
                          :disabled="isDisabled(dateBerkasEdit)"
                        ></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          label="IPK minimal"
                          type="number"
                          ref="ipk"
                          v-model="lainnya.ipk"
                          :disabled="!lainnya.ipk || isDisabled(dateBerkasEdit)"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox
                          label="Semester"
                          v-model="lainnya.semester"
                          :disabled="isDisabled(dateBerkasEdit)"
                        ></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          label="Semester"
                          type="text"
                          ref="semester"
                          v-model="lainnya.semester"
                          :disabled="!lainnya.semester || isDisabled(dateBerkasEdit)"
                        ></v-text-field>
                        <small class="text-muted">berupa angka dipisah oleh koma, cth: 1,3</small>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox
                          label="UKT"
                          v-model="lainnya.ukt"
                          :disabled="isDisabled(dateBerkasEdit)"
                        ></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          label="Batas Gol. UKT"
                          type="number"
                          ref="ukt"
                          v-model="lainnya.ukt"
                          :disabled="!lainnya.ukt || isDisabled(dateBerkasEdit)"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox
                          label="Tidak menerima beasiswa lain"
                          v-model="lainnya.is_first"
                          :disabled="isDisabled(dateBerkasEdit)"
                        ></v-checkbox>
                      </v-col>
                      <v-col cols="6"></v-col>
                    </v-row>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
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
                      <v-col cols="7" style="padding-bottom:0 !important;">
                        <v-text-field
                          color="white"
                          dense
                          :disabled="isDisabled(dateBerkasEdit)"
                          label="Pertanyaan"
                          v-model="field.pertanyaan"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="5" style="padding-bottom:0 !important;">
                        <v-select
                          v-model="field.type"
                          dense
                          :disabled="isDisabled(dateBerkasEdit)"
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
                          :disabled="isDisabled(dateBerkasEdit)"
                          :mandatory="field.pilihan.required"
                        >
                          <v-row align="center">
                            <span class="ml-2 mr-1">Pilihan wajib diisi</span>
                            <v-switch
                              v-model="field.pilihan.required"
                              color="white"
                              :disabled="isDisabled(dateBerkasEdit)"
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
                                  :disabled="isDisabled(dateBerkasEdit)"
                                  v-model="item.label"
                                ></v-text-field>
                                <v-btn
                                  class="ma-2"
                                  icon
                                  color="white"
                                  :disabled="isDisabled(dateBerkasEdit)"
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
                            :disabled="isDisabled(dateBerkasEdit)"
                            @click="addPilihanItemEdit(field.index)"
                          >
                            <v-icon dark>mdi-plus</v-icon>
                          </v-btn>
                        </v-radio-group>

                        <!-- checkme -->
                        <v-container v-if="field.type == 'Multiple Upload'">
                          <v-row
                            align="center"
                            v-for="(item,index) in field.multiUpload.items"
                            :key="index"
                            :value="item.label"
                          >
                            <v-checkbox
                              color="white"
                              hide-details
                              class="shrink mr-2 mt-0"
                              :disabled="isDisabled(dateBerkasEdit)"
                              :value="item.label"
                            ></v-checkbox>
                            <v-text-field
                              v-model="item.label"
                              dense
                              color="white"
                              filled
                              :disabled="isDisabled(dateBerkasEdit)"
                              label="Nama File"
                            ></v-text-field>
                            <v-file-input disabled filled label="Upload File"></v-file-input>
                            <v-btn
                              class="ma-2"
                              icon
                              color="white"
                              :disabled="isDisabled(dateBerkasEdit)"
                              @click="deleteMultiUploadItemEdit(field,item.label)"
                            >
                              <v-icon>mdi-close</v-icon>
                            </v-btn>
                          </v-row>
                          <v-row>
                            <v-btn
                              class="mt-2 grey darken-3"
                              fab
                              dark
                              small
                              :disabled="isDisabled(dateBerkasEdit)"
                              @click="addMultiUploadItemEdit(field.index)"
                            >
                              <v-icon dark>mdi-plus</v-icon>
                            </v-btn>
                          </v-row>
                        </v-container>
                        <v-container v-if="field.type == 'Checkboxes'">
                          <v-row
                            align="center"
                            v-for="(item,index) in field.checkboxes.items"
                            :key="index"
                            :value="item.label"
                          >
                            <v-checkbox
                              color="white"
                              hide-details
                              :disabled="isDisabled(dateBerkasEdit)"
                              class="shrink mr-2 mt-0"
                            ></v-checkbox>
                            <v-text-field
                              v-model="item.label"
                              dense
                              color="white"
                              filled
                              :disabled="isDisabled(dateBerkasEdit)"
                              label="Label"
                            ></v-text-field>
                            <v-btn
                              class="ma-2"
                              icon
                              color="white"
                              :disabled="isDisabled(dateBerkasEdit)"
                              @click="deleteCheckboxesItemEdit(field,item.label)"
                            >
                              <v-icon>mdi-close</v-icon>
                            </v-btn>
                          </v-row>
                          <v-row>
                            <v-btn
                              class="mt-2 grey darken-3"
                              fab
                              dark
                              small
                              :disabled="isDisabled(dateBerkasEdit)"
                              @click="addCheckboxesItemEdit(field.index)"
                            >
                              <v-icon dark>mdi-plus</v-icon>
                            </v-btn>
                          </v-row>
                        </v-container>

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
                    <v-row class="mb-2" align="center" justify="end">
                      <v-btn
                        icon
                        color="white"
                        :disabled="isDisabled(dateBerkasEdit)"
                        @click="deleteFieldEdit(field)"
                      >
                        <v-icon>mdi-trash-can</v-icon>
                      </v-btn>
                      <span class="ml-2 mr-1">Wajib diisi</span>
                      <v-switch
                        v-model="field.required"
                        :disabled="isDisabled(dateBerkasEdit)"
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
                  :disabled="isDisabled(dateBerkasEdit)"
                  @click="addFieldsEdit()"
                >
                  <v-icon dark>mdi-plus</v-icon>
                </v-btn>
              </v-row>
            </v-form>
          </v-card-text>
        </vue-scroll>
      </v-card>
    </v-bottom-sheet>

    <v-bottom-sheet scrollable width="60%" inset overlay-color="#69F0AE" v-model="toggleBeasiswa">
      <v-card>
        <v-card-title>
          <span>Buat Beasiswa</span>
          <v-spacer></v-spacer>
          <v-btn @click="batal()" text>batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            :disabled="isSaveDisabled"
            v-model="toggleBeasiswa"
            @click="save()"
          >Simpan</v-btn>
        </v-card-title>
        <vue-scroll :ops="ops">
          <v-card-text style="height: 600px;">
            <v-form ref="saveForm" v-model="validation" lazy-validation>
              <v-row dense class="ml-1 mr-1">
                <v-col>
                  <v-text-field
                    color="#C8E6C9"
                    label="Nama Beasiswa"
                    v-model="nama"
                    :rules="rules.required"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row dense class="ml-1 mr-1">
                <v-col>
                  <v-textarea
                    auto-grow
                    color="white"
                    rows="1"
                    label="Deskripsi"
                    :rules="rules.required"
                    v-model="deskripsi"
                  ></v-textarea>
                </v-col>
              </v-row>
              <!-- v-model="select" -->
              <!-- :items="items" -->
              <v-row dense class="ml-1 mr-1">
                <v-col cols="4">
                  <v-combobox
                    color="white"
                    label="Instansi"
                    :items="instansi"
                    :rules="rules.required"
                    item-text="name"
                    v-model="selected_instansi"
                  >
                    <template v-slot:item="{ index, item }">{{item.name}}</template>
                  </v-combobox>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    color="white"
                    label="Kuota"
                    type="number"
                    :min="0"
                    :rules="rules.required && rules.kuota"
                    v-model="kuota"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-select
                    multiple
                    v-model="jenjang"
                    :rules="rules.jenjang"
                    :items="[{i:0, v:'D3'}, {i:1, v:'S1'}, {i:2, v:'S2'} , {i:3, v:'S3'}]"
                    item-text="v"
                    item-value="i"
                    label="Jenjang"
                    color="white"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row align="center">
                <v-col cols="6">Tahap upload/pengisian berkas</v-col>
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
                      :min="hariIni()"
                      range
                      v-model="dateBerkas"
                      locale="id-ID"
                      color="green lighten-1"
                    ></v-date-picker>
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
                      color="green lighten-1"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-checkbox color="white" v-model="is_survey" label="Tahap Survey" hide-details></v-checkbox>
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
                      color="green lighten-1"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-expansion-panels>
                <v-expansion-panel class="grey darken-3">
                  <v-expansion-panel-header>Syarat lainnya</v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-row>
                      <v-col cols="6">
                        <v-checkbox label="IPK" v-model="checked.ipk" @></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          label="IPK minimal"
                          type="number"
                          ref="ipk"
                          v-model="form.ipk"
                          :disabled="!checked.ipk"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox label="Semester" v-model="checked.semester"></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          label="Semester"
                          type="text"
                          ref="semester"
                          v-model="form.semester"
                          :disabled="!checked.semester"
                        ></v-text-field>
                        <small>berupa angka dipisah oleh koma, cth: 1,3</small>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox label="UKT" v-model="checked.ukt"></v-checkbox>
                      </v-col>
                      <v-col cols="6">
                        <v-select
                          label="Batas Gol.UKT"
                          :items="[1,2,3,4,5,6,7]"
                          ref="ukt"
                          v-model="form.ukt"
                          :disabled="!checked.ukt"
                        ></v-select>
                      </v-col>
                      <v-col cols="6">
                        <v-checkbox label="Tidak menerima beasiswa lain" v-model="form.is_first"></v-checkbox>
                      </v-col>
                      <v-col cols="6"></v-col>
                    </v-row>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
              <v-row>
                <v-divider></v-divider>
              </v-row>
              <v-row>
                <v-subheader>Buat Form</v-subheader>
              </v-row>

              <!-- Form -->
              <transition-group name="scale-transition">
                <v-card
                  color="#388E3C"
                  v-for="field in fields"
                  :key="field.index"
                  elevation="10"
                  class="mb-2"
                  style="padding-bottom:0 !important;"
                >
                  <v-container style="padding-bottom:0 !important;">
                    <v-row style="padding-bottom:0 !important;">
                      <v-col cols="7" style="padding-bottom:0 !important;">
                        <v-text-field
                          color="white"
                          dense
                          label="Pertanyaan"
                          v-model="field.pertanyaan"
                          :rules="rules.required"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="5" style="padding-bottom:0 !important;">
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
                            <v-switch v-model="field.pilihan.required" color="white"></v-switch>
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
                            <v-icon dark>mdi-plus</v-icon>
                          </v-btn>
                        </v-radio-group>
                        <!-- cb -->
                        <v-container v-if="field.type == 'Checkboxes'">
                          <v-row
                            align="center"
                            v-for="(item,index) in field.checkboxes.items"
                            :key="index"
                            :value="item.label"
                          >
                            <v-checkbox
                              color="white"
                              hide-details
                              class="shrink mr-2 mt-0"
                              :value="item.label"
                            ></v-checkbox>
                            <v-text-field
                              v-model="item.label"
                              dense
                              color="white"
                              filled
                              label="Label"
                            ></v-text-field>
                            <v-btn
                              class="ma-2"
                              icon
                              color="white"
                              @click="deleteCheckboxesItem(field,item.label)"
                            >
                              <v-icon>mdi-close</v-icon>
                            </v-btn>
                          </v-row>
                          <v-row>
                            <v-btn
                              class="mt-2 grey darken-3"
                              fab
                              dark
                              small
                              @click="addCheckboxesItem(field.index)"
                            >
                              <v-icon dark>mdi-plus</v-icon>
                            </v-btn>
                          </v-row>
                        </v-container>
                        <v-container v-if="field.type == 'Multiple Upload'">
                          <v-row
                            align="center"
                            v-for="(item,index) in field.multiUpload.items"
                            :key="index"
                            :value="item.label"
                          >
                            <v-checkbox
                              color="white"
                              hide-details
                              class="shrink mr-2 mt-0"
                              :value="item.label"
                            ></v-checkbox>
                            <v-text-field
                              v-model="item.label"
                              dense
                              color="white"
                              filled
                              label="Nama File"
                            ></v-text-field>
                            <v-file-input disabled filled label="Upload File"></v-file-input>
                            <v-btn
                              class="ma-2"
                              icon
                              color="white"
                              @click="deleteMultiUploadItem(field,item.label)"
                            >
                              <v-icon>mdi-close</v-icon>
                            </v-btn>
                          </v-row>
                          <v-row>
                            <v-btn
                              class="mt-2 grey darken-3"
                              fab
                              dark
                              small
                              @click="addMultiUploadItem(field.index)"
                            >
                              <v-icon dark>mdi-plus</v-icon>
                            </v-btn>
                          </v-row>
                        </v-container>
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
                    <v-row class="mb-2" align="center" justify="end">
                      <v-btn icon color="white" @click="deleteField(field)">
                        <v-icon>mdi-trash-can</v-icon>
                      </v-btn>
                      <span class="ml-2 mr-1">Wajib diisi</span>
                      <v-switch v-model="field.required" color="white"></v-switch>
                    </v-row>
                  </v-container>
                </v-card>
              </transition-group>
              <v-row justify="center">
                <v-btn class="mt-2" fab dark small color="green" @click="addField()">
                  <v-icon dark>mdi-plus</v-icon>
                </v-btn>
              </v-row>
            </v-form>
          </v-card-text>
        </vue-scroll>
      </v-card>
    </v-bottom-sheet>
    <v-dialog width="400" overlay-color="#69F0AE" v-model="deleteDialog">
      <v-card>
        <v-card-title class="mt-2">
          Apakah anda yakin ingin menghapus?
          <p style="font-weight:bold">
            <!-- {{itemtoDelete.nama}}? -->
          </p>
        </v-card-title>
        <v-card-actions>
          <v-btn text @click="dialogDelete = false">Batal</v-btn>
          <v-spacer></v-spacer>
          <v-btn dark class="green" @click="finallyDelete">
            <v-icon left>mdi-check</v-icon>iya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Snackbar -->
    <v-snackbar v-model="snackbar.show" :timeout="2000">
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="snackbar.color" text v-bind="attrs" @click="snackbar.show = false">Close</v-btn>
      </template>
    </v-snackbar>
    <!-- Akhir -->
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswa();
    this.getInstansi();
  },
  watch: {
    validation(v) {
      v ? (this.isSaveDisabled = false) : (this.isSaveDisabled = true);
    },
    validationEdit(v) {
      v ? (this.isEditDisabled = false) : (this.isEditDisabled = true);
    }
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
      this.deleteId = item.id;
      this.deleteDialog = true;
    },
    finallyDelete() {
      this.deleteBeasiswa(this.deleteId);
      this.deleteDialog = false;
    },
    edit(item) {
      this.idEdit = item.id;
      this.kuotaEdit = item.quota;
      this.jenjangEdit = item.jenjang;
      this.namaEdit = item.nama;
      this.fieldsEdit = JSON.parse(item.fields);
      this.deskripsiEdit = item.deskripsi;
      this.valueEdit = item.instansi_id;
      this.lainnya = {
        ipk: item.ipk,
        semester: item.semester,
        ukt: item.ukt,
        is_first: item.is_first
      };

      this.instansi.forEach(element => {
        if (element.id == item.instansi_id) {
          this.selected_instansiEdit = { id: element.id, name: element.name };
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
    async sendEdit() {
      await this.$refs.editForm.validate();

      if (this.validationEdit) {
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
          awal_wawancara = this.dateWawancaraEdit[0];
          akhir_wawancara = this.dateWawancaraEdit[1]
            ? this.dateWawancaraEdit[1]
            : this.dateWawancaraEdit[0];
        }
        if (this.is_surveyEdit) {
          awal_survey = this.dateSurveyEdit[0];
          akhir_survey = this.dateSurveyEdit[1]
            ? this.dateSurveyEdit[1]
            : this.dateSurveyEdit[0];
        }
        var data = {
          id: this.idEdit,
          nama: this.namaEdit,
          deskripsi: this.deskripsiEdit,
          quota: this.kuotaEdit,
          instansi_id: this.selected_instansiEdit.id,
          instansi: this.selected_instansiEdit,
          jenjang: this.jenjangEdit,
          fields: this.fieldsEdit,
          is_survey: this.is_surveyEdit,
          is_interview: this.is_wawancaraEdit,
          awal_interview: awal_wawancara,
          akhir_interview: akhir_wawancara,
          awal_survey,
          akhir_survey,
          awal_berkas,
          akhir_berkas,
          ipk: this.lainnya.ipk ?? null,
          semester: this.lainnya.semester ?? null,
          ukt: this.lainnya.ukt ?? null,
          is_first: this.lainnya.is_first ?? 0
        };
        this.btnLoading = true;
        this.editBeasiswa(data)
          .then(response => {
            this.btnLoading = false;
            this.toggleEdit = false;
          })
          .catch(error => {
            this.btnLoading = false;
            this.snackbar = {
              show: true,
              color: "red",
              message: error
            };
          });
      }
    },
    compareType(a, b) {
      a == b ? true : false;
    },
    isDisabled(date) {
      var date = Date.parse(date[0]);
      var now = Date.now();
      var value = date >= now ? false : true;
      return value;
    },
    async save() {
      await this.$refs.saveForm.validate();
      if (this.validation) {
        var awal_wawancara = "";
        var akhir_wawancara = "";
        var awal_survey = "";
        var akhir_survey = "";
        var awal_berkas = "";
        var akhir_berkas = "";
        this.fields.forEach(element => {
          if (element.type == "Checkboxes") {
            element.value = [];
          }
        });
        var today = new Date();
        var formatedToday =
          today.getFullYear() +
          "-" +
          (today.getMonth() + 1) +
          "-" +
          today.getDate();
        awal_berkas = this.dateBerkas[1] ? this.dateBerkas[0] : formatedToday;
        akhir_berkas = this.dateBerkas[1]
          ? this.dateBerkas[1]
          : this.dateBerkas[0];

        if (this.is_wawancara) {
          awal_wawancara = this.dateWawancara[0];
          akhir_wawancara = this.dateWawancara[1]
            ? this.dateWawancara[1]
            : this.dateWawancara[0];
        }
        if (this.is_survey) {
          awal_survey = this.dateSurvey[0];
          akhir_survey = this.dateSurvey[1]
            ? this.dateSurvey[1]
            : this.dateSurvey[0];
        }
        var data = {
          nama: this.nama,
          deskripsi: this.deskripsi,
          quota: this.kuota,
          instansi_id: this.selected_instansi.id,
          instansi: this.selected_instansi,
          jenjang: this.jenjang,
          fields: this.fields,
          is_survey: this.is_survey,
          is_interview: this.is_wawancara,
          awal_interview: awal_wawancara,
          akhir_interview: akhir_wawancara,
          awal_survey,
          akhir_survey,
          awal_berkas,
          akhir_berkas,
          ipk: this.form.ipk ?? null,
          ukt: this.form.ukt ?? null,
          semester: this.form.semester ?? null,
          is_first: this.form.is_first ?? 0
        };
        this.btnLoading = true;
        this.storeBeasiswa(data)
          .then(response => {
            this.btnLoading = false;
            this.toggleBeasiswa = false;
            this.form = {};
            this.checked = {};
          })
          .catch(error => {
            this.btnLoading = false;
            this.snackbar = {
              show: true,
              color: "red",
              message: error
            };
          });
      }
    },
    width() {
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
    batalEdit() {
      this.toggleEdit = false;
    },
    addFieldsEdit() {
      this.fieldsEdit.push({
        type: "Jawaban Pendek",
        pertanyaan: "",
        index: this.fieldsEdit[0]
          ? this.fieldsEdit[this.fieldsEdit.length - 1].index + 1
          : 0,
        value: null,
        date: false,
        multiUpload: {
          items: [{ label: "", isSelected: false, value: null }]
        },
        checkboxes: {
          items: [{ label: "" }]
        },
        pilihan: {
          required: false,
          items: [{ label: "" }]
        },
        required: true,
        isLulus: null
      });
    },
    addField() {
      // get the last array then add the order value
      this.fields.push({
        type: "Jawaban Pendek",
        pertanyaan: "",
        index: this.fields[0]
          ? this.fields[this.fields.length - 1].index + 1
          : 0,
        value: null,
        date: false,
        checkboxes: {
          items: [{ label: "" }]
        },
        multiUpload: {
          items: [{ label: "", isSelected: false, value: null }]
        },
        pilihan: {
          required: false,
          items: [{ label: "" }]
        },
        required: true,
        isLulus: null
      });
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
    addMultiUploadItem(field_index) {
      this.fields[field_index - 1].multiUpload.items.push({
        label: "",
        isSelected: false,
        value: null
      });
    },
    addCheckboxesItem(field_index) {
      this.fields[field_index - 1].checkboxes.items.push({ label: "" });
    },
    addCheckboxesItemEdit(field_index) {
      this.fieldsEdit[field_index - 1].checkboxes.items.push({ label: "" });
    },
    addPilihanItemEdit(field_index) {
      this.fieldsEdit[field_index - 1].pilihan.items.push({ label: "" });
    },
    addMultiUploadItemEdit(field_index) {
      this.fieldsEdit[field_index - 1].multiUpload.items.push({
        label: "",
        isSelected: false,
        value: null
      });
    },
    deletePilihanItemEdit(field, label) {
      var item = this.fieldsEdit[this.fieldsEdit.indexOf(field)].pilihan.items;
      item.splice(item.indexOf(label), 1);
    },
    deleteMultiUploadItemEdit(field, label) {
      var item = this.fieldsEdit[this.fieldsEdit.indexOf(field)].multiUpload
        .items;
      item.splice(item.indexOf(label), 1);
    },
    deletePilihanItem(field, label) {
      var item = this.fields[this.fields.indexOf(field)].pilihan.items;
      item.splice(item.indexOf(label), 1);
    },
    deleteMultiUploadItem(field, label) {
      var item = this.fields[this.fields.indexOf(field)].multiUpload.items;
      item.splice(item.indexOf(label), 1);
    },
    deleteCheckboxesItem(field, label) {
      var item = this.fields[this.fields.indexOf(field)].checkboxes.items;
      item.splice(item.indexOf(label), 1);
    },
    deleteCheckboxesItemEdit(field, label) {
      var item = this.fieldsEdit[this.fieldsEdit.indexOf(field)].checkboxes
        .items;
      item.splice(item.indexOf(label), 1);
    },
    hariIni() {
      var today = new Date();
      var day = today.getDate() < 10 ? "0" + today.getDate() : today.getDate();
      var formatedToday =
        today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + day;

      return formatedToday;
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
          speed: 800,
          scrollingX: false
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true
        }
      },
      rules: {
        required: [v => !!v || "Field ini wajib diisi"],
        jenjang: [v => (v || "").length > 0 || "Field ini wajib diisi"],
        kuota: [v => v > 0 || "Kuota tidak boleh kurang dari 1"]
      },
      isSaveDisabled: false,
      isEditDisabled: false,
      validation: true,
      validationEdit: true,
      checked: {},
      form: {},
      lainnya: {},
      snackbar: { show: false },
      idEdit: null,
      deleteDialog: false,
      deleteId: null,
      btnLoadingEdit: false,
      namaEdit: "",
      deskripsiEdit: "",
      selected_instansiEdit: "",
      jenjangEdit: "",
      fieldsEdit: {},
      isBerkasEdit: false,
      is_wawancaraEdit: false,
      is_surveyEdit: false,
      dateBerkasEdit: [this.hariIni()],
      dateWawancaraEdit: [this.hariIni()],
      dateSurveyEdit: [this.hariIni()],
      kuotaEdit: 1,
      toggleEdit: false,
      btnLoading: false,
      nama: "",
      deskripsi: "",
      selected_instansi: "",
      jenjang: [1],
      fields: [
        {
          type: "Jawaban Pendek",
          pertanyaan: "",
          index: 1,
          value: "",
          date: false,
          checkboxes: {
            items: [{ label: "" }]
          },
          multiUpload: {
            items: [{ label: "", isSelected: false, value: null }]
          },
          pilihan: {
            required: true,
            items: [{ label: "" }]
          },
          required: true,
          isLulus: null
        }
      ],
      itemTypes: [
        "Jawaban Pendek",
        "Jawaban Angka",
        "Paragraf",
        "Upload File",
        "Multiple Upload",
        "Pilihan",
        "Checkboxes",
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
      dateBerkas: [this.hariIni()],
      dateWawancara: [this.hariIni()],
      dateSurvey: [this.hariIni()],
      kuota: 1,
      headers: [
        {
          text: "Beasiswa",
          align: "start",
          sortable: false,
          value: "nama"
        },
        { text: "Instansi", value: "instansi.name" },
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
