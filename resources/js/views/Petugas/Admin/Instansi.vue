<template>
  <v-container>
    <v-skeleton-loader type="table" :loading="isTableLoading" transition="fade-transition">
      <v-data-table
        :headers="headers"
        :items="instansi"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>
    <v-bottom-sheet scrollable width="60%" inset overlay-color="#69F0AE" v-model="toggleBeasiswa">
      <v-card>
        <v-card-title>
          <span>Tambah Instansi</span>
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
          <v-row dense class="ml-1 mr-1">
            <v-col>
              <v-text-field color="#C8E6C9" label="Nama Beasiswa" v-model="nama"></v-text-field>
            </v-col>
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
                  <v-col cols="7" style="padding-bottom:0 !important;">
                    <v-text-field color="white" dense label="Pertanyaan" v-model="field.pertanyaan"></v-text-field>
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
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
  </v-container>
</template>

<script>
export default {};
</script>

<style>
</style>