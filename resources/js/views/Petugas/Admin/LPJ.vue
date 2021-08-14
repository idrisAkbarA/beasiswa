<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >
      <v-data-table
        :headers="headers.lpj"
        :items="lpj"
        style="background-color: #2e7d323b"
        :items-per-page="10"
        class="elevation-10 mb-10"
      >
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn
            icon
            x-small
            class="mr-2"
            @click="infoLPJ(item)"
            title="Info"
          >
            <v-icon>mdi-information</v-icon>
          </v-btn>
          <v-btn
            icon
            x-small
            class="mr-2"
            @click="edit(item)"
            title="Edit"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-icon
            small
            @click="(dialogDelete = true), (form = item)"
            title="Hapus"
          >mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>
    <!-- Show LPJ -->
    <v-dialog
      v-model="dialogShow"
      height="100%"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      v-if="dialogShow"
    >
      <div class="close-dialog">
        <v-container fluid>
          <v-row class="align-center">
            <v-btn
              icon
              dark
              @click="dialogShow = false"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <span>{{ selectedLPJ.nama }}</span>
          </v-row>
        </v-container>
      </div>
      <vue-scroll :ops="ops">
        <v-card height="100%">
          <v-toolbar
            dark
            color="transparent"
          >
            <v-btn
              icon
              dark
              @click="dialogShow = false"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>{{ selectedLPJ.nama }}</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text class="mt-5">
            <v-tabs
              background-color="#2e7d323b"
              grow
              color="white"
            >
              <v-tab>Laporan</v-tab>
              <v-tab>Info</v-tab>
              <v-tab-item style="height:100vh">
                <v-col
                  style="height:100vh"
                  cols="12"
                >
                  <v-row>
                    <div class="col-lg-10 col-md-12 mb-5">
                      <v-chip
                        color="blue"
                        :outlined="filter != 'permohonan'"
                        class="mr-2 mb-2"
                        @click="filter = 'permohonan'"
                      >Semua ({{
                        undefined !== selectedLPJ.permohonan
                          ? selectedLPJ.permohonan.length
                          : 0
                      }})</v-chip>
                      <v-chip
                        color="purple"
                        :outlined="filter != 'belum_mengisi'"
                        class="mr-2 mb-2"
                        @click="filter = 'belum_mengisi'"
                      >Belum Mengisi ({{
                        undefined !== selectedLPJ.belum_mengisi
                          ? selectedLPJ.belum_mengisi.length
                          : 0
                      }})</v-chip>
                      <v-chip
                        color="orange"
                        :outlined="filter != 'tidak_lengkap'"
                        class="mr-2 mb-2"
                        @click="filter = 'tidak_lengkap'"
                      >Tidak Lengkap ({{
                        undefined !== selectedLPJ.tidak_lengkap
                          ? selectedLPJ.tidak_lengkap.length
                          : 0
                      }})</v-chip>
                      <v-chip
                        color="cyan"
                        :outlined="filter != 'proses'"
                        class="mr-2 mb-2"
                        @click="filter = 'proses'"
                      >Proses ({{
                        undefined !== selectedLPJ.proses
                          ? selectedLPJ.proses.length
                          : 0
                      }})</v-chip>
                      <v-chip
                        color="red"
                        :outlined="filter != 'tidak_lulus'"
                        class="mr-2 mb-2"
                        @click="filter = 'tidak_lulus'"
                      >Tidak Lulus ({{
                        undefined !== selectedLPJ.tidak_lulus
                          ? selectedLPJ.tidak_lulus.length
                          : 0
                      }})</v-chip>
                      <v-chip
                        color="green"
                        :outlined="filter != 'lulus'"
                        class="mr-2 mb-2"
                        @click="filter = 'lulus'"
                      >Lulus ({{
                        undefined !== selectedLPJ.lulus
                          ? selectedLPJ.lulus.length
                          : 0
                      }})</v-chip>
                    </div>
                    <div class="col-lg-2 col-md-12 mb-5">
                      <v-chip
                        light
                        class="float-right"
                        v-if="
                        selectedLPJ.deleted_at &&
                        selectedLPJ.lulus.length < selectedLPJ.quota
                      "
                        @click.stop="drawer = !drawer"
                      >
                        <v-icon class="mr-2">mdi-checkbox-marked-circle-outline</v-icon>Kelulusan
                      </v-chip>
                    </div>
                  </v-row>
                  <v-card-title>
                    <v-btn
                      @click="dialogLulusAll = true"
                      class="text-white mr-6"
                      color="orange darken-2"
                    >Luluskan Semua LPJ</v-btn>

                    <v-text-field
                      v-model="search.permohonan"
                      append-icon="mdi-magnify"
                      label="Search"
                      single-line
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers.permohonan"
                    :items="selectedLPJ[filter]"
                    :items-per-page="10"
                    :search="search.permohonan"
                    :loading="isLoading"
                    style="background-color: #2e7d323b"
                    class="elevation-10 mb-10 row-pointer"
                  >
                    <template v-slot:[`item.is_lulus`]="{ item }">
                      <v-chip
                        dark
                        :color="item.status.color"
                      >
                        <i :class="`mdi ${
                          item.is_lulus ? 'mdi-check' : 'mdi-close'
                        } mr-2`"></i>
                        {{ item.status.text }}
                      </v-chip>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                      <v-btn
                        icon
                        x-small
                        class="mr-2"
                        title="Info"
                        @click="infoPermohonan(item)"
                      >
                        <v-icon>mdi-information</v-icon>
                      </v-btn>
                    </template>
                    <template v-slot:no-data>no data</template>
                  </v-data-table>
                </v-col>
              </v-tab-item>
              <v-tab-item style="height:100vh">
                <v-col
                  style="height:100vh"
                  cols="12"
                >
                  <v-data-table
                    style="height:100%"
                    :headers="headers.detailLPJ"
                    :items="detailLPJ"
                    hide-default-header
                    hide-default-footer
                    class="elevation-1"
                  ></v-data-table>
                  <p class="mt-3">{{ selectedLPJ.deskripsi }}</p>
                </v-col>
              </v-tab-item>
            </v-tabs>
          </v-card-text>
        </v-card>
      </vue-scroll>
      <!-- Show Permohonan -->
    </v-dialog>
    <v-dialog
      overlay-color="green darken-2"
      v-if="drawerShow"
      v-model="drawerShow"
      width="500px"
    >
      <vue-scroll :ops="ops">
        <v-card>
          <v-card-title> Rincian Permohonan LPJ </v-card-title>
          <v-card-subtitle>
            Lihat rincian dan validasi permohonan LPJ
          </v-card-subtitle>
          <v-card-text scrollable>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <strong>{{
                    selectedPermohonan.mahasiswa
                      ? selectedPermohonan.mahasiswa.nama
                      : ""
                  }}</strong>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <div class="col-12">
              <v-row
                no-gutters
                class="ma-5"
                v-for="(field, index) in JSON.parse(selectedPermohonan.form)"
                :key="index"
              >
                <v-col style="padding-bottom: 0 !important">
                  <p>{{ field.pertanyaan }}</p>
                  <v-container v-if="field.type == 'Checkboxes'">
                    <!-- {{'pep'+field.isLulus}} -->
                    <v-row>
                      <v-col cols="9">
                        <v-row
                          align="center"
                          v-for="(item, index) in field.checkboxes.items"
                          :key="index"
                        >
                          <v-checkbox
                            disabled
                            v-model="field.value"
                            :value="item.label"
                            color="white"
                            hide-details
                            class="shrink mr-2 mt-0"
                          ></v-checkbox>
                          <span>{{ item.label }}</span>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-container>
                  <v-container v-if="field.type == 'Multiple Upload'">
                    <v-row>
                      <v-col cols="9">
                        <v-row
                          align="center"
                          v-for="(item, index) in field.multiUpload.items"
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
                              disabled
                            ></v-checkbox>
                          </v-col>
                          <v-col cols="6">
                            <span>{{ item.label }}</span>
                          </v-col>
                          <v-col cols="5">
                            <v-btn
                              v-if="item.file_name"
                              small
                              @click="link(item.file_name)"
                            >lihat file</v-btn>
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-container>
                  <v-row v-if="field.type == 'Pilihan'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{ field.value }}
                      </span>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Pendek'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{ field.value }}
                      </span>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Jawaban Angka'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{ field.value }}
                      </span>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Tanggal'">
                    <v-col>
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{ field.value }}
                      </span>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Upload File'">
                    <v-col cols="9">
                      <v-btn
                        small
                        @click="link(field.value)"
                      >lihat file</v-btn>
                    </v-col>
                  </v-row>
                  <v-row v-if="field.type == 'Paragraf'">
                    <v-col cols="9">
                      <span>
                        <v-icon>mdi-text-short</v-icon>
                        {{ field.value }}
                      </span>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12">
                  <v-divider></v-divider>
                </v-col>
                <v-col cols="12"></v-col>
              </v-row>
            </div>
          </v-card-text>
          <v-card-actions v-if="selectedPermohonan.is_lulus === null || selectedPermohonan.is_lulus === undefined">
            <v-btn
              dark
              text
              :loading="isLoading"
              @click="updatePermohonan(false)"
            >Tidak Lulus</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              class="float-right"
              :loading="isLoading"
              :disabled="selectedPermohonan.form === '[]'"
              @click="updatePermohonan(true)"
            >Lulus</v-btn>
          </v-card-actions>
          <v-card-actions
            class="px-2 py-2 mt-auto"
            v-else
          >
            <small class="mb-0">
              Status :
              <strong :class="[
                  selectedPermohonan.is_lulus ? 'text-success' : 'text-danger',
                ]">{{
                  selectedPermohonan.is_lulus ? "Lulus" : "Tidak Lulus"
                }}</strong>
            </small>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              class="float-right"
              :loading="isLoading"
              @click="selectedPermohonan.is_lulus = null"
            >Ubah</v-btn>
          </v-card-actions>
        </v-card>
      </vue-scroll>
    </v-dialog>
    <!-- Create and Edit -->
    <v-bottom-sheet
      scrollable
      inset
      overlay-color="#69F0AE"
      v-if="bottomSheet"
      v-model="bottomSheet"
    >
      <v-card>
        <v-card-title>
          <span>
            <v-icon class="mr-2">mdi-book-multiple</v-icon>LPJ
          </span>
          <v-spacer></v-spacer>
          <v-btn
            @click="bottomSheet = false"
            class="mr-2"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="isLoading"
            @click="submit"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <v-col cols="12">
            <v-text-field
              color="#C8E6C9"
              label="Nama"
              v-model="form.nama"
            ></v-text-field>
            <v-select
              color="#C8E6C9"
              label="Beasiswa"
              :items="beasiswa"
              item-text="nama"
              item-value="id"
              v-model="form.beasiswa_id"
            ></v-select>
            <v-row>
              <v-col cols="6">
                <v-menu
                  :nudge-right="40"
                  transition="scale-transition"
                  min-width="290px"
                  offset-y
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      label="Tanggal Mulai"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      v-model="form.awal"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    color="green lighten-1"
                    locale="id-ID"
                    v-model="form.awal"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu
                  :nudge-right="40"
                  transition="scale-transition"
                  min-width="290px"
                  offset-y
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      label="Tanggal Akhir"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      v-model="form.akhir"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    color="green lighten-1"
                    locale="id-ID"
                    :allowed-dates="allowedDateAkhir"
                    v-model="form.akhir"
                  ></v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
            <v-textarea
              color="#C8E6C9"
              label="Deskripsi"
              outlined
              rows="3"
              v-model="form.deskripsi"
            ></v-textarea>
          </v-col>
          <v-divider></v-divider>
          <v-subheader>Buat Form</v-subheader>
          <!-- Form -->
          <transition-group name="scale-transition">
            <v-card
              color="#388E3C"
              v-for="field in form.fields"
              :key="field.index"
              elevation="10"
              class="mb-2"
            >
              <v-container>
                <v-row>
                  <v-col cols="7">
                    <v-text-field
                      color="white"
                      dense
                      label="Pertanyaan"
                      v-model="field.pertanyaan"
                      :rules="rules.required"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="5">
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
                  <v-col>
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
                        v-for="(item, index) in field.pilihan.items"
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
                              @click="deleteItem(field, item.label, 'pilihan')"
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
                        @click="addItem(field.index, 'pilihan')"
                      >
                        <v-icon dark>mdi-plus</v-icon>
                      </v-btn>
                    </v-radio-group>
                    <!-- cb -->
                    <v-container v-if="field.type == 'Checkboxes'">
                      <v-row
                        align="center"
                        v-for="(item, index) in field.checkboxes.items"
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
                          @click="deleteItem(field, item.label, 'checkboxes')"
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
                          @click="addItem(field.index, 'checkboxes')"
                        >
                          <v-icon dark>mdi-plus</v-icon>
                        </v-btn>
                      </v-row>
                    </v-container>
                    <v-container v-if="field.type == 'Multiple Upload'">
                      <v-row
                        align="center"
                        v-for="(item, index) in field.multiUpload.items"
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
                        <v-file-input
                          disabled
                          filled
                          label="Upload File"
                        ></v-file-input>
                        <v-btn
                          class="ma-2"
                          icon
                          color="white"
                          @click="deleteItem(field, item.label, 'multiUpload')"
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
                          @click="addItem(field.index, 'multiUpload')"
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
              @click="addField"
            >
              <v-icon dark>mdi-plus</v-icon>
            </v-btn>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <v-dialog
      v-model="dialogDelete"
      width="500"
    >
      <v-card>
        <v-card-title>
          <v-icon class="mr-2">mdi-trash-can</v-icon>Hapus LPJ
        </v-card-title>
        <v-card-text class="text-center">
          Apakah anda yakin ingin menghapus
          <strong>{{ form.nama }}</strong>? Perubahan tidak dapat dikembalikan!
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn
            color="secondary"
            text
            @click="dialogDelete = false"
          >Tidak</v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2E7D32"
            dark
            @click="destroy(form)"
          >Ya</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      overlay-color="green darken-2"
      v-if="selectedLPJ"
      v-model="dialogLulusAll"
      width="500px"
    >
      <v-card>
        <v-card-title> Konfirmasi </v-card-title>
        <v-card-text>
          Apakah anda yakin untuk meluluskan seluruh permohonan LPJ di beasiswa
          <strong>{{ selectedLPJ.beasiswa.nama }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-btn
            @click="dialogLulusAll = false"
            text
          >Batal</v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-2"
            class="text-white"
            @click="lulusAll(selectedLPJ.id)"
          >Luluskan Semua</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar.show"
      :timeout="2000"
    >
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >Close</v-btn>
      </template>
    </v-snackbar>
    <!-- Akhir -->
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  mounted() {
    this.resetForm();
    this.getLPJ();
    this.getBeasiswaSelesai();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa", "mutateLPJ", "mutateLoading"]),
    ...mapActions([
      "getBeasiswaSelesai",
      "getLPJ",
      "showLPJ",
      "editBeasiswa",
      "storeBeasiswa",
    ]),
    allowedDateAkhir(val) {
      return val >= this.form.awal;
    },
    infoLPJ(item) {
      this.selectedLPJ = item;
      this.dialogShow = true;
      this.showLPJ(item.id);
    },
    infoPermohonan(item) {
      this.selectedPermohonan = item;
      this.drawerShow = true;
    },
    createPermohonan() {
      const data = {
        mhs_id: this.selectedPermohonan.mahasiswa.nim,
        lpj_id: this.selectedLPJ.id,
        is_submitted: false,
        is_lulus: false,
        form: [],
      };
      this.mutateLoading(true);
      axios
        .post(`/api/permohonan-lpj`, data)
        .then((response) => {
          this.drawerShow = false;
          this.showLPJ(this.selectedLPJ.id);
          if (response.data.status) {
            const isLulus = response.data.data.is_lulus;
            this.snackbar = {
              show: true,
              color: isLulus ? "blue" : "red",
              message: `Kelulusan beasiswa : ${
                isLulus ? "Lulus" : "Tidak Lulus"
              }`,
            };
          } else {
            this.snackbar = {
              show: true,
              color: "red",
              message: response.data.message,
            };
          }
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        });
    },
    updatePermohonan(val) {
      const id = this.selectedPermohonan.id;
      if (id === undefined) {
        this.createPermohonan();
        return;
      }
      const data = { is_submitted: true, is_lulus: val };
      this.mutateLoading(true);
      axios
        .put(`/api/permohonan-lpj/${id}`, data)
        .then((response) => {
          this.drawerShow = false;
          this.showLPJ(this.selectedLPJ.id);
          if (response.data.status) {
            const isLulus = response.data.data.is_lulus;
            this.snackbar = {
              show: true,
              color: isLulus ? "blue" : "red",
              message: `Kelulusan beasiswa : ${
                isLulus ? "Lulus" : "Tidak Lulus"
              }`,
            };
          } else {
            this.snackbar = {
              show: true,
              color: "red",
              message: response.data.message,
            };
          }
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(this.mutateLoading(false));
    },
    addField() {
      // get the last array then add the order value
      this.form.fields.push({
        type: "Jawaban Pendek",
        pertanyaan: "",
        index: this.form.fields[0]
          ? this.form.fields[this.form.fields.length - 1].index + 1
          : 0,
        value: null,
        date: false,
        checkboxes: {
          items: [{ label: "" }],
        },
        multiUpload: {
          items: [{ label: "", isSelected: false, value: null }],
        },
        pilihan: {
          required: false,
          items: [{ label: "" }],
        },
        required: true,
        isLulus: null,
      });
    },
    deleteField(field) {
      this.form.fields.splice(this.form.fields.indexOf(field), 1);
    },
    addItem(index, type) {
      this.form.fields[index - 1][type].items.push({
        label: "",
      });
    },
    deleteItem(field, label, type) {
      var item = this.form.fields[this.form.fields.indexOf(field)][type].items;
      item.splice(item.indexOf(label), 1);
    },
    lulusAll(id) {
      this.mutateLoading(true);
      axios
        .put(`/api/lpj/lulus/${id}`)
        .then((response) => {
          this.mutateLoading(false);
          this.showLPJ(id);
          this.dialogLulusAll = false;
          this.snackbar = {
            show: true,
            color: "success",
            message: response.data.message,
          };
        })
        .catch((error) => {
          this.mutateLoading(false);
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        });
    },
    edit(item) {
      this.bottomSheet = true;
      this.form = item;
      this.form.fields = JSON.parse(item.fields);
    },
    submit() {
      // TODO: validate !!
      this.form.id ? this.update() : this.save();
    },
    save() {
      var data = this.form;
      data.fields.forEach((element) => {
        if (element.type == "Checkboxes") {
          element.value = [];
        }
      });
      this.mutateLoading(true);
      axios
        .post(`/api/lpj`, data)
        .then((response) => {
          this.bottomSheet = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil menambah LPJ",
          };
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(() => this.mutateLoading(false));
    },
    update() {
      const data = this.form;
      this.mutateLoading(true);
      axios
        .put(`/api/lpj/${data.id}`, data)
        .then((response) => {
          this.bottomSheet = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil mengupdate LPJ",
          };
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(() => this.mutateLoading(false));
    },
    destroy(item) {
      const id = item.id;
      this.mutateLoading(true);
      axios
        .delete(`/api/lpj/${id}`)
        .then((response) => {
          this.dialogDelete = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil menghapus LPJ",
          };
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(() => this.mutateLoading(false));
    },
    showLPJ(id) {
      this.mutateLoading(true);
      axios
        .get(`/api/lpj/${id}`)
        .then((response) => {
          const data = response.data;
          this.selectedLPJ = data;
          this.filterStatuses(data.permohonan);
          this.mutateLoading(false);
        })
        .catch((error) => {
          console.error(error);
          this.mutateLoading(false);
        });
    },
    filterStatuses(data) {
      const statuses = [
        "Belum Mengisi",
        "Tidak Lengkap",
        "Proses",
        "Tidak Lulus",
        "Lulus",
      ];
      statuses.forEach((x) => {
        this.selectedLPJ[x.replace(" ", "_").toLowerCase()] = data.filter(
          (y) => y.status.text == x
        );
      });
    },
    link(url) {
      if (url == null) {
        this.snackbar.message = "Maaf, berkas tidak di upload";
        this.snackbar.color = "red";
        this.snackbar.show = true;
      } else {
        if (url.length > 1) {
          var a = "/" + url;
          var link = a.replace(" ", "%20");
          window.open(link, "_blank");
        } else {
          this.snackbar.message = "Maaf, file yang anda buka corrupt";
          this.snackbar.color = "red";
          this.snackbar.show = true;
        }
      }
    },
    resetForm() {
      this.form = {
        fields: [
          {
            type: "Jawaban Pendek",
            pertanyaan: "",
            index: 1,
            value: "",
            date: false,
            checkboxes: {
              items: [{ label: "" }],
            },
            multiUpload: {
              items: [{ label: "", isSelected: false, value: null }],
            },
            pilihan: {
              required: true,
              items: [{ label: "" }],
            },
            required: true,
            isLulus: null,
          },
        ],
      };
    },
  },
  watch: {
    selectedLPJ: function (val) {
      if (val) {
        this.detailLPJ = [
          { judul: "Nama", isi: val.nama },
          { judul: "Beasiswa", isi: val.beasiswa.nama ?? "-" },
          { judul: "Kuota Beasiswa", isi: val.beasiswa.quota },
          { judul: "Awal Pengisian", isi: val.awal },
          { judul: "Akhir Pengisian", isi: val.akhir },
        ];
      }
    },
    bottomSheet: function (val) {
      if (!val) {
        this.resetForm();
      }
    },
    dialogDelete: function (val) {
      if (!val) {
        this.resetForm();
      }
    },
    dialogShow: function (val) {
      if (!val) {
        this.filter = "permohonan";
      }
    },
  },
  computed: {
    ...mapState([
      "lpj",
      "beasiswa",
      "isOpenBeasiswa",
      "isTableLoading",
      "isLoading",
    ]),
    bottomSheet: {
      get: function () {
        return this.isOpenBeasiswa;
      },
      set: function (data) {
        this.toggleOpenBeasiswa(data);
      },
    },
  },
  data() {
    return {
      ops: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800,
          scrollingX: false,
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true,
        },
      },
      data: null,
      selectedLPJ: null,
      selectedPermohonan: null,
      dialogShow: false,
      drawerShow: false,
      dialogDelete: false,
      dialogLulusAll: false,
      search: "",
      filter: "permohonan",
      form: {},
      snackbar: { show: false },
      rules: {
        required: [(v) => !!v || "Field ini wajib diisi"],
      },
      headers: {
        lpj: [
          { text: "Nama", align: "start", value: "nama" },
          { text: "Beasiswa", value: "beasiswa.nama" },
          { text: "Actions", value: "actions", sortable: false },
        ],
        permohonan: [
          { text: "NIM", align: "start", value: "mhs_id" },
          { text: "Nama", align: "start", value: "mahasiswa.nama" },
          { text: "Jurusan", value: "mahasiswa.jurusan.nama", sortable: true },
          { text: "Status", value: "is_lulus" },
          { text: "Verifikator", value: "verificator" },
          { text: "Actions", value: "actions", sortable: false },
        ],
        detailLPJ: [
          { text: "Judul", value: "judul" },
          { text: "Isi", value: "isi" },
        ],
      },
      itemTypes: [
        "Jawaban Pendek",
        "Jawaban Angka",
        "Paragraf",
        "Upload File",
        "Multiple Upload",
        "Pilihan",
        "Checkboxes",
        "Tanggal",
      ],
    };
  },
};
</script>
<style>
.close-dialog {
  position: fixed;
  z-index: 99;
  width: 100%;
  background-color: #4caf50;
  background-image: url("/images/drawer-bg.jpg");
  background-size: cover;
}
</style>
