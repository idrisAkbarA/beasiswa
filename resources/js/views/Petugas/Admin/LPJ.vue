<template>
  <v-container>
    <v-skeleton-loader type="table" :loading="isTableLoading" transition="fade-transition">
      <v-data-table
        :headers="headers"
        :items="lpj"
        style="background-color: #2e7d323b"
        :items-per-page="10"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-btn icon x-small class="mr-2" @click="edit(item)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-icon small @click="dialogDelete = true, form = item">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>

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
          <v-btn @click="bottomSheet = false" class="mr-2" text>batal</v-btn>
          <v-btn color="#2E7D32" :loading="isLoading" @click="submit">Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <v-col cols="12">
            <v-text-field color="#C8E6C9" label="Nama" v-model="form.nama"></v-text-field>
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
                <v-menu :nudge-right="40" transition="scale-transition" min-width="290px" offset-y>
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
                  <v-date-picker color="green lighten-1" locale="id-ID" v-model="form.awal"></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu :nudge-right="40" transition="scale-transition" min-width="290px" offset-y>
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
                              @click="deleteItem(field,item.label, 'pilihan')"
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
                        <v-text-field v-model="item.label" dense color="white" filled label="Label"></v-text-field>
                        <v-btn
                          class="ma-2"
                          icon
                          color="white"
                          @click="deleteItem(field,item.label, 'checkboxes')"
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
                          @click="deleteItem(field,item.label, 'multiUpload')"
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
            <v-btn class="mt-2" fab dark small color="green" @click="addField">
              <v-icon dark>mdi-plus</v-icon>
            </v-btn>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <v-dialog v-model="dialogDelete" width="500">
      <v-card>
        <v-card-title>
          <v-icon class="mr-2">mdi-trash-can</v-icon>Hapus LPJ
        </v-card-title>
        <v-card-text class="text-center">
          Apakah anda yakin ingin menghapus
          <strong>{{form.nama}}</strong>? Perubahan tidak dapat dikembalikan!
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn color="secondary" text @click="dialogDelete = false">Tidak</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="#2E7D32" dark @click="destroy(form)">Ya</v-btn>
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
  mounted() {
    this.getLPJ();
    this.getBeasiswaSelesai();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa", "mutateLPJ"]),
    ...mapActions([
      "getBeasiswaSelesai",
      "getLPJ",
      "editBeasiswa",
      "getInstansi",
      "storeBeasiswa",
      "deleteBeasiswa"
    ]),
    allowedDateAkhir(val) {
      return val >= this.form.awal;
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
      this.form.fields.splice(this.form.fields.indexOf(field), 1);
    },
    addItem(index, type) {
      this.form.fields[index - 1][type].items.push({
        label: ""
      });
    },
    deleteItem(field, label, type) {
      var item = this.form.fields[this.form.fields.indexOf(field)][type].items;
      item.splice(item.indexOf(label), 1);
    },
    edit(item) {
      this.form = item;
      this.form.fields = JSON.parse(item.fields);
      this.bottomSheet = true;
    },
    submit() {
      // TODO: validate !!
      this.form.id ? this.update() : this.save();
    },
    save() {
      const data = this.form;
      this.isLoading = true;
      axios
        .post(`/api/lpj`, data)
        .then(response => {
          this.bottomSheet = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil menambah LPJ"
          };
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => (isLoading = false));
    },
    update() {
      const data = this.form;
      this.isLoading = true;
      axios
        .put(`/api/lpj/${data.id}`, data)
        .then(response => {
          this.bottomSheet = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil mengupdate LPJ"
          };
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => (isLoading = false));
    },
    destroy(item) {
      const id = item.id;
      this.isLoading = true;
      axios
        .delete(`/api/lpj/${id}`)
        .then(response => {
          this.dialogDelete = false;
          this.mutateLPJ(response.data);
          this.snackbar = {
            show: true,
            color: "blue",
            message: "Berhasil menghapus LPJ"
          };
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => (isLoading = false));
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
        ]
      };
    }
  },
  watch: {
    bottomSheet: function(val) {
      if (!val) {
        this.resetForm();
      }
    },
    dialogDelete: function(val) {
      if (!val) {
        this.resetForm();
      }
    }
  },
  computed: {
    ...mapState([
      "lpj",
      "beasiswa",
      "isOpenBeasiswa",
      "isTableLoading",
      "isLoading"
    ]),
    bottomSheet: {
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
      data: null,
      dialogDelete: false,
      snackbar: { show: false },
      headers: [
        { text: "Nama", align: "start", value: "nama" },
        { text: "Beasiswa", value: "beasiswa.nama" },
        { text: "Actions", value: "actions", sortable: false }
      ],
      rules: {
        required: [v => !!v || "Field ini wajib diisi"]
      },
      form: {
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
        ]
      },
      itemTypes: [
        "Jawaban Pendek",
        "Jawaban Angka",
        "Paragraf",
        "Upload File",
        "Multiple Upload",
        "Pilihan",
        "Checkboxes",
        "Tanggal"
      ]
    };
  }
};
</script>
